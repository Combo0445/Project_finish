<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareInstruction;
use Carbon\Carbon;
use Auth;

class CIController extends Controller
{
    public function ShowStaffCI()
    {
        $careInstructions = CareInstruction::where('Name_Staff', Auth::user()->Name_User)->with('elderly')->get();
        return view('staff.CI.Staff-ShowCI', compact('careInstructions'));
    }

    public function ShowUnconfirmCI()
    {
        $careInstructions = CareInstruction::where('Name_Staff', Auth::user()->Name_User)->with('elderly')->get();
        return view('staff.CI.unconfirmCI', compact('careInstructions'));
    }

    // Method to confirm the CI
    public function confirmCI($id)
    {
        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update(['Confirm' => 'ยืนยัน']);

        return redirect()->route('staff.ci.index')->with('success', 'คำแนะนำการดูแลยืนยันเรียบร้อยแล้ว');
    }

    // Method to unconfirm the CI
    public function unconfirmCI($id)
    {
        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update(['Confirm' => null]);

        return redirect()->route('staff.ci.unconfirm')->with('success', 'คำแนะนำการดูแลไม่ได้รับการยืนยันสำเร็จ');
    }

    public function ReportCIConfirm()
    {
        $careInstructions = CareInstruction::whereNotNull('Confirm')->get();
        return view('staff.Report.report-ci-confirm', compact('careInstructions'));
    }

    public function generateSingleReport($id)
{
    $careInstruction = CareInstruction::with('elderly')->findOrFail($id);
    return view('Staff.Report.report-ci-single', compact('careInstruction'));
}

}
