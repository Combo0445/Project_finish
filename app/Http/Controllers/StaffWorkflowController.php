<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Models\CareInstruction;
use Illuminate\Http\Request;

class StaffWorkflowController extends Controller
{
    /**
     * Start the daily workflow for Staff by finding the highest priority pending task.
     */
    public function start()
    {
        // 1. Check for Unconfirmed Care Instructions
        $unconfirmedInstructions = CareInstruction::whereNull('Confirm')->count();
        if ($unconfirmedInstructions > 0) {
            return redirect()->route('care_instructions.index')
                ->with('warning', 'มีคำแนะนำการดูแลที่ต้องรอการยืนยัน (' . $unconfirmedInstructions . ' รายการ)');
        }

        // 2. Check for Elders without ADL assessment
        $missingAdl = Elderly::doesntHave('barthel_adl')->first();
        if ($missingAdl) {
            // Found someone missing ADL, send staff there. Typically the staff goes to elderly profile or ADL index,
            // Let's redirect to ADL index for now as that's where they usually search.
            return redirect()->route('adl.index')
                ->with('info', 'มีผู้สูงอายุที่ยังไม่ได้ประเมิน ADL (กรุณาประเมินคลิกปุ่มรายชื่อด้านล่าง)');
        }

        // 3. Check for Elders without TAI assessment
        // TAI is assessed alongside ADL. We check if an elderly lacks TAI.
        $missingTai = Elderly::doesntHave('score_tai')->first();
        if ($missingTai) {
            return redirect()->route('tai.index')
                ->with('info', 'มีผู้สูงอายุที่ยังไม่ได้ประเมิน TAI (กรุณาประเมินคลิกปุ่มรายชื่อด้านล่าง)');
        }

        // 4. Check for Elders without CG assessment
        $missingCg = Elderly::doesntHave('care_giver')->first();
        if ($missingCg) {
            return redirect()->route('cg.index')
                ->with('info', 'มีผู้สูงอายุที่ยังไม่ได้บันทึกผลการปฏิบัติงาน CG (กรุณาบันทึกคลิดปุ่มรายชื่อด้านล่าง)');
        }

        // If all tasks are clear
        return redirect()->route('dashboard')
            ->with('success', 'เยี่ยมมาก! งานสำคัญของคุณสำหรับวันนี้เสร็จสิ้นแล้ว');
    }
}
