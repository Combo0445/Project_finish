<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarthelAdl;
use App\Models\CareGiver;
use App\Models\Elderly;
use App\Models\CareInstruction;
use Carbon\Carbon;
use Auth;

class DoctorController extends Controller
{
    public function ShowDataElderly()
    {

        $query = Elderly::with('barthel_adl');



        $elderlys = $query->get();
        return view('doctor.dashboard-doctor', compact('elderlys'));
    }

    public function CreateCI(Request $request)
    {
        $elderly = Elderly::findOrFail($request->elderly_id);
        $careGiver = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->first();
        $reporter = $careGiver ? $careGiver->Reporter : 'Unknown';

        return view('doctor.CI.AddCI', compact('elderly', 'reporter'));
    }

    public function storeCI(Request $request)
    {
        $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'Date_CI' => 'required|date',
            'Name_Elderly' => 'required|string',
            'Name_Doctor' => 'required|string',
            'Name_Staff' => 'required|string',
            'Care_instructions' => 'required|string',
        ]);

        CareInstruction::create($request->all());

        return redirect()->route('doctor.dashboard')->with('success', 'คำแนะนำถูกบันทึกเรียบร้อยแล้ว');
    }

    public function ShowCI()
    {
        $user = Auth::user();
        $query = CareInstruction::query();

        if ($user->Type_Personnel == 'Doctor') {
            $typeDoctor = $user->Type_Doctor;
            $query->whereHas('elderly.barthel_adl', function ($q) use ($typeDoctor) {
                if ($typeDoctor == 'ติดสังคม') {
                    $q->where('Group_ADL', 'ติดสังคม');
                } elseif ($typeDoctor == 'ติดบ้าน') {
                    $q->where('Group_ADL', 'ติดบ้าน');
                } elseif ($typeDoctor == 'ติดเตียง') {
                    $q->where('Group_ADL', 'ติดเตียง');
                }
            });
        }

        $careInstructions = $query->get();
        return view('doctor.CI.ShowCI', compact('careInstructions'));
    }

    public function DestroyCI($id)
    {
        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->delete();
        return redirect()->route('ci.index')->with('success', 'ลบคำแนะนำการดูแลเรียบร้อยแล้ว');
    }

    public function editCI($id)
    {
        $careInstruction = CareInstruction::findOrFail($id);
        return view('doctor.CI.EditCI', compact('careInstruction'));
    }

    public function updateCI(Request $request, $id)
    {
        $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'Date_CI' => 'required|date',
            'Name_Elderly' => 'required|string',
            'Name_Doctor' => 'required|string',
            'Name_Staff' => 'required|string',
            'Care_instructions' => 'required|string',
        ]);

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update($request->all());

        return redirect()->route('ci.index')->with('success', 'คำแนะนำถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function ReportCI()
    {
        $careInstructions = CareInstruction::whereNull('Confirm')->get();

        return view('doctor.Report.report-ci', compact('careInstructions'));
    }
}
