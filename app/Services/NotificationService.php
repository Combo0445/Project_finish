<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a LINE Notify message to a specific user.
     *
     * @param User $user
     * @param string $message
     * @return bool
     */
    public function sendLineNotification(User $user, string $message): bool
    {
        if (!$user->line_token) {
            return false;
        }

        // Global toggle - can be controlled via .env (NOTIFICATION_ENABLED)
        if (!config('notifications.enabled', true)) {
            Log::info("LINE Notify disabled globally. Message for User {$user->ID_User}: {$message}");
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $user->line_token,
            ])->asForm()->post('https://notify-api.line.me/api/notify', [
                        'message' => $message,
                    ]);

            if (!$response->successful()) {
                Log::warning("LINE Notify failed for User {$user->ID_User}: " . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("LINE Notify Error for User {$user->ID_User}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast a message to all users of specific roles.
     *
     * @param array $roles
     * @param string $message
     * @return int Number of successfully sent notifications
     */
    public function broadcastToRoles(array $roles, string $message): int
    {
        $users = User::whereIn('Type_Personnel', $roles)
            ->whereNotNull('line_token')
            ->get();

        $successCount = 0;
        foreach ($users as $user) {
            if ($this->sendLineNotification($user, $message)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Template for Care Instruction notification.
     */
    public function notifyCareInstruction(User $staff, $elderlyName, $doctorName, $instructions, $isUpdate = false)
    {
        $prefix = $isUpdate ? "📝 มีการแก้ไขคำแนะนำการดูแลจากแพทย์" : "🔔 มีผลการประเมินและคำแนะนำใหม่จากแพทย์";

        $message = "\n{$prefix}\n";
        $message .= "ผู้สูงอายุ: " . $elderlyName . "\n";
        $message .= "แพทย์: " . $doctorName . "\n";
        $message .= "คำแนะนำ: " . mb_substr($instructions, 0, 100) . (mb_strlen($instructions) > 100 ? "..." : "");

        return $this->sendLineNotification($staff, $message);
    }

    /**
     * Template for ADL Drop notification.
     */
    public function notifyAdlDrop($elderlyName, $prevGroup, $currGroup, $assessorName)
    {
        $message = "\n⚠️ แจ้งเตือน: สุขภาพผู้สูงอายุแย่ลง (ADL Drop)\n";
        $message .= "ผู้สูงอายุ: " . $elderlyName . "\n";
        $message .= "เดิม: " . $prevGroup . "\n";
        $message .= "ปัจจุบัน: " . $currGroup . "\n";
        $message .= "ผู้ประเมิน: " . $assessorName;

        return $this->broadcastToRoles(['Admin', 'Doctor'], $message);
    }
}
