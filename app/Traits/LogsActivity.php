<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $model->logActivity('updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getOriginal(), null);
        });
    }

    protected function logActivity($action, $oldValues = null, $newValues = null)
    {
        // Don't log if there are no changes on update
        if ($action === 'updated' && empty($newValues)) {
            return;
        }

        AuditLog::create([
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'action' => $action,
            'user_id' => Auth::check() ? Auth::id() : null,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip()
        ]);
    }
}
