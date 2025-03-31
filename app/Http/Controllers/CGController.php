<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use Illuminate\Http\Request;
use App\Models\BarthelAdl;
use App\Models\ActivityCaregiver;
use App\Models\CareGiver;
use Carbon\Carbon;

class CGController extends Controller
{
    public function index(Request $request)
    {
        $query = CareGiver::query();

        if ($request->has('search')) {
            $query->where('Name_Elderly', 'like', '%' . $request->search . '%')
                ->orWhere('Name_CG', 'like', '%' . $request->search . '%');
        }

        $careGivers = $query->get();
        return view('staff.CG.ShowCG', compact('careGivers'));
    }

    public function create()
    {
        $elderlys = BarthelAdl::with('elderly')->get();
        return view('staff.CG.AddCG', compact('elderlys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name_CG' => 'required|string',
            'Related' => 'required|string',
            'Phone_CG' => 'required|string',
            'ID_Elderly' => 'required|exists:barthel_adls,ID_ADL',
            'Name_Elderly' => 'required|string',
            'Address' => 'required|string',
            'Weight' => 'required|numeric',
            'Height' => 'required|numeric',
            'Waist' => 'required|numeric',
            'Group_ADL' => 'required|string',
            'Disease' => 'nullable|string',
            'Disability' => 'nullable|string',
            'Rights' => 'nullable|string',
            'Date' => 'required|date',
            'Consciousness' => 'required|string',
            'Vital_signs' => 'required|string',
            'Bedsores' => 'required|string',
            'Bedsores_details' => 'nullable|string',
            'Pain' => 'required|string',
            'Pain_details' => 'nullable|string',
            'Swelling' => 'required|string',
            'Swelling_details' => 'nullable|string',
            'Itchy_rash' => 'required|string',
            'Itchy_rash_details' => 'nullable|string',
            'Stiff_joints' => 'required|string',
            'Stiff_joints_details' => 'nullable|string',
            'Malnutrition' => 'required|string',
            'Malnutrition_details' => 'nullable|string',
            'Eating' => 'required|string',
            'Swallowing' => 'required|string',
            'Defecation' => 'required|string',
            'Urinary_excretion' => 'required|string',
            'Taking_medicine' => 'required|string',
            'Emotional_state' => 'required|string',
            'Economic_problems' => 'required|string',
            'Economic_problems_details' => 'nullable|string',
            'Social_problems' => 'required|string',
            'Social_problems_details' => 'nullable|string',
            'Doctor_FU' => 'required|string',
            'Doctor_FU_details' => 'nullable|string',
            'Other_problems' => 'nullable|string',
            'Assistance' => 'nullable|string',
            'Reporter' => 'required|string',
            'Picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $careGiverData = $request->only([
            'Name_CG', 'Related', 'Phone_CG','Name_Elderly', 'Address', 'Weight', 'Height',
            'Waist', 'Group_ADL', 'Disease', 'Disability', 'Rights', 'Date', 'Consciousness', 'Vital_signs',
            'Bedsores', 'Bedsores_details', 'Pain', 'Pain_details', 'Swelling',
            'Swelling_details', 'Itchy_rash', 'Itchy_rash_details', 'Stiff_joints',
            'Stiff_joints_details', 'Malnutrition', 'Malnutrition_details', 'Eating',
            'Swallowing', 'Defecation', 'Urinary_excretion', 'Taking_medicine',
            'Emotional_state', 'Economic_problems', 'Economic_problems_details',
            'Social_problems', 'Social_problems_details', 'Doctor_FU', 'Doctor_FU_details',
            'Other_problems', 'Assistance', 'Reporter', 'Picture'
        ]);

        $id_elderly = BarthelAdl::findOrFail($request->ID_Elderly);
        $elderly = Elderly::findOrFail($id_elderly->ID_Elderly);

        $careGiverData['Bedsores'] = $request->Bedsores . ($request->Bedsores_details ? '-' . $request->Bedsores_details : '');
        $careGiverData['Pain'] = $request->Pain . ($request->Pain_details ? '-' . $request->Pain_details : '');
        $careGiverData['Swelling'] = $request->Swelling . ($request->Swelling_details ? '-' . $request->Swelling_details : '');
        $careGiverData['Itchy_rash'] = $request->Itchy_rash . ($request->Itchy_rash_details ? '-' . $request->Itchy_rash_details : '');
        $careGiverData['Stiff_joints'] = $request->Stiff_joints . ($request->Stiff_joints_details ? '-' . $request->Stiff_joints_details : '');
        $careGiverData['Malnutrition'] = $request->Malnutrition . ($request->Malnutrition_details ? '-' . $request->Malnutrition_details : '');
        $careGiverData['Economic_problems'] = $request->Economic_problems . ($request->Economic_problems_details ? '-' . $request->Economic_problems_details : '');
        $careGiverData['Social_problems'] = $request->Social_problems . ($request->Social_problems_details ? '-' . $request->Social_problems_details : '');
        $careGiverData['Doctor_FU'] = $request->Doctor_FU . ($request->Doctor_FU_details ? '-' . $request->Doctor_FU_details : '');
        $careGiverData['Date_CG'] = $request->Date;
        $careGiverData['Birthday'] = $elderly->Birthday;
        $careGiverData['ID_Elderly'] = $id_elderly->ID_Elderly;

        $adl = BarthelAdl::where('ID_Elderly', $id_elderly->ID_Elderly)->first();
        if ($adl) {
            $careGiverData['ID_ADL'] = $adl->ID_ADL;
        } else {
            return redirect()->back()->withErrors(['ID_ADL' => 'ไม่พบข้อมูล ADL สำหรับผู้สูงอายุที่เลือก']);
        }

        $cg = new CareGiver();
        $cg->fill($careGiverData);
        $cg->save();

        return redirect()->route('cg.create')->with('success', 'เพิ่ม Care Giver สำเร็จแล้ว!');
    }

    public function edit($id)
    {
        $caregiver = CareGiver::findOrFail($id);
        $elderly = Elderly::findOrFail($caregiver->ID_Elderly);

        $birthDate = $elderly->Birthday;
        $age = Carbon::parse($birthDate)->age;

        return view('staff.CG.EditCG', compact('caregiver', 'elderly', 'age'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Name_CG' => 'required|string',
            'Related' => 'required|string',
            'Phone_CG' => 'required|string',
            'ID_Elderly' => 'required|string',
            'Name_Elderly' => 'required|string',
            'Address' => 'required|string',
            'Weight' => 'required|numeric',
            'Height' => 'required|numeric',
            'Waist' => 'required|numeric',
            'Group_ADL' => 'required|string',
            'Disease' => 'nullable|string',
            'Disability' => 'nullable|string',
            'Rights' => 'nullable|string',
            'Date' => 'required|date',
            'Consciousness' => 'required|string',
            'Vital_signs' => 'required|string',
            'Bedsores' => 'required|string',
            'Pain' => 'required|string',
            'Swelling' => 'required|string',
            'Itchy_rash' => 'required|string',
            'Stiff_joints' => 'required|string',
            'Malnutrition' => 'required|string',
            'Eating' => 'required|string',
            'Swallowing' => 'required|string',
            'Defecation' => 'required|string',
            'Urinary_excretion' => 'required|string',
            'Taking_medicine' => 'required|string',
            'Emotional_state' => 'required|string',
            'Economic_problems' => 'required|string',
            'Social_problems' => 'required|string',
            'Doctor_FU' => 'required|string',
            'Other_problems' => 'nullable|string',
            'Assistance' => 'nullable|string',
            'Reporter' => 'required|string',
            'Picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $careGiverData = $request->only([
            'Name_CG', 'Related', 'Phone_CG','ID_Elderly', 'Name_Elderly', 'Address', 'Weight', 'Height',
            'Waist', 'Group_ADL', 'Disease', 'Disability', 'Rights', 'Date', 'Consciousness', 'Vital_signs',
            'Bedsores', 'Pain', 'Swelling', 'Itchy_rash', 'Stiff_joints',
            'Malnutrition', 'Eating', 'Swallowing', 'Defecation', 'Urinary_excretion',
            'Taking_medicine', 'Emotional_state', 'Economic_problems', 'Social_problems',
            'Doctor_FU', 'Other_problems', 'Assistance', 'Reporter', 'Picture'
        ]);

        $careGiver = CareGiver::findOrFail($id);
        $careGiver->update($careGiverData);

        return redirect()->route('cg.index')->with('success', 'อัปเดตข้อมูล Care Giver สำเร็จแล้ว!');
    }

    public function destroy($id)
    {
        $careGiver = CareGiver::findOrFail($id);
        $careGiver->delete();

        return redirect()->route('cg.index')->with('success', 'ลบ Care Giver เรียบร้อยแล้ว!');
    }

    public function showACG(Request $request)
    {
        $query = ActivityCaregiver::query();

        if ($request->has('search')) {
            $query->whereHas('caregiver', function ($q) use ($request) {
                $q->where('Name_Elderly', 'like', '%' . $request->search . '%');
            });
        }

        $activities = $query->get();
        return view('staff.ACG.ShowACG', compact('activities'));
    }

    public function editActivity($id)
    {
        $activity = ActivityCaregiver::findOrFail($id);
        return view('staff.ACG.EditACG', compact('activity'));
    }

    public function updateActivity(Request $request, $id)
    {
        $request->validate([
            'activity_date' => 'required|date',
            'evaluate' => 'nullable|string',
            'dress_the_wound' => 'nullable|string',
            'rehabilitate' => 'nullable|string',
            'clean_body' => 'nullable|string',
            'take_care_medicine' => 'nullable|string',
            'take_care_feeding' => 'nullable|string',
            'environmental' => 'nullable|string',
            'take_exercise' => 'nullable|string',
            'give_advice_consult' => 'nullable|string',
            'take_to_see_a_doctor' => 'nullable|string',
            'other_specified' => 'nullable|string',
            'take_to_make_merit' => 'nullable|string',
            'take_to_market' => 'nullable|string',
            'take_to_meet_friends' => 'nullable|string',
            'take_to_allowance' => 'nullable|string',
            'talk_as_friends' => 'nullable|string',
            'other_social_specified' => 'nullable|string',

        ]);

        $activityData = [
            'Date_ACG' => $request->activity_date,
            'Evaluate' => $request->evaluate,
            'Dress_the_wound' => $request->dress_the_wound,
            'Rehabilitate' => $request->rehabilitate,
            'Clean_body' => $request->clean_body,
            'Take_care_medicine' => $request->take_care_medicine,
            'Take_care_feeding' => $request->take_care_feeding,
            'Environmental' => $request->environmental,
            'Take_exercise' => $request->take_exercise,
            'Give_advice_consult' => $request->give_advice_consult,
            'Take_to_see_a_doctor' => $request->take_to_see_a_doctor,
            'Other' => $request->other_specified,
            'Take_to_make_merit' => $request->take_to_make_merit,
            'Take_to_market' => $request->take_to_market,
            'Take_to_meet_friends' => $request->take_to_meet_friends,
            'Take_to_allowance' => $request->take_to_allowance,
            'Talk_as_friends' => $request->talk_as_friends,
            'Other_specified' => $request->other_social_specified,

        ];

        $activity = ActivityCaregiver::findOrFail($id);
        $activity->update($activityData);

        return redirect()->route('acg.index')->with('success', 'อัปเดตกิจกรรมสำเร็จแล้ว!');
    }

    public function destroyActivity($id)
    {
        $activity = ActivityCaregiver::findOrFail($id);
        $activity->delete();

        return redirect()->route('acg.index')->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว!');
    }

    public function createActivity()
    {
        $elderlys = Elderly::has('care_giver')->with('care_giver')->get();
        return view('staff.ACG.AddACG', compact('elderlys'));
    }

    public function storeActivity(Request $request)
    {
        $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'activity_date' => 'required|date',
            'evaluate' => 'nullable|string',
            'dress_the_wound' => 'nullable|string',
            'rehabilitate' => 'nullable|string',
            'clean_body' => 'nullable|string',
            'take_care_medicine' => 'nullable|string',
            'take_care_feeding' => 'nullable|string',
            'environmental' => 'nullable|string',
            'take_exercise' => 'nullable|string',
            'give_advice_consult' => 'nullable|string',
            'take_to_see_a_doctor' => 'nullable|string',
            'other_specified' => 'nullable|string',
            'take_to_make_merit' => 'nullable|string',
            'take_to_market' => 'nullable|string',
            'take_to_meet_friends' => 'nullable|string',
            'take_to_allowance' => 'nullable|string',
            'talk_as_friends' => 'nullable|string',
            'other_social_specified' => 'nullable|string',

        ]);

        $careGiverId = $this->getLatestCareGiverId($request->ID_Elderly, $request->activity_date);

        if (!$careGiverId) {
            return redirect()->back()->withErrors(['ID_CG' => 'ไม่พบข้อมูล Care Giver สำหรับผู้สูงอายุที่เลือก']);
        }

        $activityData = [
            'ID_CG' => $careGiverId,
            'Date_ACG' => $request->activity_date,
            'Evaluate' => $request->evaluate,
            'Dress_the_wound' => $request->dress_the_wound,
            'Rehabilitate' => $request->rehabilitate,
            'Clean_body' => $request->clean_body,
            'Take_care_medicine' => $request->take_care_medicine,
            'Take_care_feeding' => $request->take_care_feeding,
            'Environmental' => $request->environmental,
            'Take_exercise' => $request->take_exercise,
            'Give_advice_consult' => $request->give_advice_consult,
            'Take_to_see_a_doctor' => $request->take_to_see_a_doctor,
            'Other' => $request->other_specified,
            'Take_to_make_merit' => $request->take_to_make_merit,
            'Take_to_market' => $request->take_to_market,
            'Take_to_meet_friends' => $request->take_to_meet_friends,
            'Take_to_allowance' => $request->take_to_allowance,
            'Talk_as_friends' => $request->talk_as_friends,
            'Other_specified' => $request->other_social_specified,

        ];

        $activity = new ActivityCaregiver();
        $activity->fill($activityData);
        $activity->save();

        return redirect()->route('activities.create')->with('success', 'เพิ่มกิจกรรมเรียบร้อยแล้ว!');
    }

    private function getLatestCareGiverId($idElderly, $currentDate)
    {
        $latestCareGiver = CareGiver::where('ID_Elderly', $idElderly)
            ->where('Date_CG', '<=', $currentDate)
            ->orderBy('Date_CG', 'desc')
            ->first();

        return $latestCareGiver ? $latestCareGiver->ID_CG : null;
    }

    public function getElderlyDetails($elderlyId)
    {
        $adl = BarthelAdl::find($elderlyId);
        if ($adl) {
            $elderly = Elderly::find($adl->ID_Elderly);
            if ($elderly) {
                $age = Carbon::parse($elderly->Birthday)->age;
                return response()->json([
                    'Age' => $age,
                    'Address' => $elderly->Address,
                    'Group_ADL' => $adl->Group_ADL,
                ]);
            }
        }
        return response()->json([
            'Age' => 'ไม่พบข้อมูล',
            'Address' => 'ไม่พบข้อมูล',
            'Group_ADL' => 'ไม่พบข้อมูล',
        ]);
    }

    public function ReportCGAll()
    {
        $cgs = CareGiver::all();

        return view('staff.Report.report-cg-all', compact('cgs'));
    }

    public function ReportCG($id)
    {
        $cg = CareGiver::with('elderly')->findOrFail($id);

        return view('staff.Report.report-cg', compact('cg'));
    }

    public function ReportACGAll()
    {
        $activities = ActivityCaregiver::with('caregiver')->get();
        return view('staff.Report.report-acg-all', compact('activities'));
    }

    public function ReportACG($id)
    {
        $activity = ActivityCaregiver::with('caregiver')->findOrFail($id);
        return view('staff.Report.report-acg', compact('activity'));
    }
}
