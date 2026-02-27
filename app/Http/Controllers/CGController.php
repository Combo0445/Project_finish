<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Http\Requests\CareGiverRequest;
use App\Http\Requests\ActivityCaregiverRequest;
use Illuminate\Http\Request;
use App\Models\BarthelAdl;
use App\Models\ActivityCaregiver;
use App\Models\CareGiver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CGController extends Controller
{
    public function index(Request $request)
    {
        $query = CareGiver::with(['elderly']);

        if ($request->has('search')) {
            $query->where('Name_Elderly', 'like', '%' . $request->search . '%')
                ->orWhere('Name_CG', 'like', '%' . $request->search . '%');
        }

        $careGivers = $query->paginate(20);
        return view('staff.CG.ShowCG', compact('careGivers'));
    }

    public function create()
    {
        $elderlys = BarthelAdl::with('elderly')->get();
        return view('staff.CG.AddCG', compact('elderlys'));
    }

    public function store(CareGiverRequest $request)
    {
        try {
            $careGiverData = $request->only([
                'Name_CG',
                'Related',
                'Phone_CG',
                'Name_Elderly',
                'Address',
                'Weight',
                'Height',
                'Waist',
                'Group_ADL',
                'Disease',
                'Disability',
                'Rights',
                'Date',
                'Consciousness',
                'Vital_signs',
                'Bedsores',
                'Bedsores_details',
                'Pain',
                'Pain_details',
                'Swelling',
                'Swelling_details',
                'Itchy_rash',
                'Itchy_rash_details',
                'Stiff_joints',
                'Stiff_joints_details',
                'Malnutrition',
                'Malnutrition_details',
                'Eating',
                'Swallowing',
                'Defecation',
                'Urinary_excretion',
                'Taking_medicine',
                'Emotional_state',
                'Economic_problems',
                'Economic_problems_details',
                'Social_problems',
                'Social_problems_details',
                'Doctor_FU',
                'Doctor_FU_details',
                'Other_problems',
                'Assistance',
                'Reporter',
            ]);

            $id_elderly = BarthelAdl::findOrFail($request->ID_Elderly);
            $elderly = Elderly::findOrFail($id_elderly->ID_Elderly);

            // ป้องกันการประเมินซ้ำในวันเดียวกัน
            $alreadyToday = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                ->where('Date_CG', now()->toDateString())
                ->exists();

            if ($alreadyToday) {
                return redirect()->back()->with('error', 'ผู้สูงอายุคนนี้ได้รับการประเมิน CG ในวันนี้แล้ว ไม่สามารถประเมินซ้ำได้');
            }

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

            $picturePaths = [];

            if ($request->hasFile('Picture')) {
                foreach ($request->file('Picture') as $picture) {
                    $path = $picture->store('pictures', 'public');
                    $picturePaths[] = $path;
                }
            }

            $careGiverData['Picture'] = json_encode($picturePaths);

            $cg = new CareGiver();
            $cg->fill($careGiverData);
            $cg->save();

            return redirect()->route('cg.create')->with('success', 'เพิ่ม Care Giver สำเร็จแล้ว!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $caregiver = CareGiver::findOrFail($id);
        $elderly = Elderly::findOrFail($caregiver->ID_Elderly);

        $birthDate = $elderly->Birthday;
        $age = Carbon::parse($birthDate)->age;

        return view('staff.CG.EditCG', compact('caregiver', 'elderly', 'age'));
    }

    public function update(CareGiverRequest $request, $id)
    {
        try {
            $careGiverData = $request->only([
                'Name_CG',
                'Related',
                'Phone_CG',
                'ID_Elderly',
                'Name_Elderly',
                'Address',
                'Weight',
                'Height',
                'Waist',
                'Group_ADL',
                'Disease',
                'Disability',
                'Rights',
                'Date_CG',
                'Consciousness',
                'Vital_signs',
                'Bedsores',
                'Pain',
                'Swelling',
                'Itchy_rash',
                'Stiff_joints',
                'Malnutrition',
                'Eating',
                'Swallowing',
                'Defecation',
                'Urinary_excretion',
                'Taking_medicine',
                'Emotional_state',
                'Economic_problems',
                'Social_problems',
                'Doctor_FU',
                'Other_problems',
                'Assistance',
                'Reporter',
            ]);

            $careGiver = CareGiver::findOrFail($id);

            // บันทึกรูปใหม่
            if ($request->hasFile('Picture')) {
                // ลบรูปเก่าออกจาก storage เฉพาะเมื่อมีการอัปโหลดใหม่
                if ($careGiver->Picture) {
                    $oldImages = json_decode($careGiver->Picture, true);
                    if (is_array($oldImages)) {
                        foreach ($oldImages as $oldPath) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }

                $picturePaths = [];
                foreach ($request->file('Picture') as $picture) {
                    $path = $picture->store('pictures', 'public');
                    $picturePaths[] = $path;
                }
                $careGiverData['Picture'] = json_encode($picturePaths);
            }
            // ถ้าไม่มีรูปใหม่ ไม่ต้องเซตเป็น null เพื่อรักษาของเดิมไว้

            $careGiver->update($careGiverData);

            ActivityCaregiver::create([
                'ID_CG' => $careGiver->ID_CG,
                'Date_ACG' => Carbon::now()->toDateString(), // วันที่ปัจจุบัน
                'Evaluate' => null,
                'Dress_the_wound' => null,
                'Rehabilitate' => null,
                'Clean_body' => null,
                'Take_care_medicine' => null,
                'Take_care_feeding' => null,
                'Environmental' => null,
                'Take_exercise' => null,
                'Give_advice_consult' => null,
                'Take_to_see_a_doctor' => null,
                'Other' => null,
                'Take_to_make_merit' => null,
                'Take_to_market' => null,
                'Take_to_meet_friends' => null,
                'Take_to_allowance' => null,
                'Talk_as_friends' => null,
                'Other_specified' => null,
                'Problem' => null,
                'Solution' => null,
            ]);

            return redirect()->route('cg.index')->with('success', 'อัปเดตข้อมูล Care Giver สำเร็จแล้ว!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $careGiver = CareGiver::findOrFail($id);

        if ($careGiver->Picture) {
            $images = json_decode($careGiver->Picture, true);
            if (is_array($images)) {
                foreach ($images as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

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

        // // เพิ่มการเรียงลำดับจากใหม่ไปเก่า
        // $activities = $query->orderBy('ID_CG', 'desc')->get();

        $activities = $query->get();
        return view('staff.ACG.ShowACG', compact('activities'));
    }

    public function editActivity($id)
    {
        $activity = ActivityCaregiver::findOrFail($id);
        return view('staff.ACG.EditACG', compact('activity'));
    }

    public function updateActivity(ActivityCaregiverRequest $request, $id)
    {

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
            'Problem' => $request->problem,
            'Solution' => $request->solution,
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

    public function storeActivity(ActivityCaregiverRequest $request)
    {

        $careGiverId = $this->getLatestCareGiverId($request->ID_Elderly, $request->activity_date);

        if (!$careGiverId) {
            return redirect()->back()->withErrors(['ID_CG' => 'ไม่พบข้อมูล Care Giver สำหรับผู้สูงอายุที่เลือก']);
        }

        // ป้องกันการประเมินซ้ำในวันเดียวกัน
        $alreadyExists = ActivityCaregiver::where('ID_CG', $careGiverId)
            ->where('Date_ACG', $request->activity_date)
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()->with('error', 'กิจกรรม ACG สำหรับวันนี้ได้รับบันทึกแล้ว ไม่สามารถบันทึกซ้ำได้');
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
            'Problem' => $request->problem,
            'Solution' => $request->solution,
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
                    'ID_Elderly' => $elderly->ID_Elderly,
                ]);
            }
        }
        return response()->json([
            'Age' => 'ไม่พบข้อมูล',
            'Address' => 'ไม่พบข้อมูล',
            'Group_ADL' => 'ไม่พบข้อมูล',
        ]);
    }


}
