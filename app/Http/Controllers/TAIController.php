<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreTAI;
use App\Models\Elderly;
use App\Models\CareGiver;
use Illuminate\Support\Facades\Auth;

class TAIController extends Controller
{
    public function index()
    {
        $tai = ScoreTAI::with(['elderly', 'user'])->get();
        // dd($tai); // ตรวจสอบข้อมูลก่อนส่งไป View
        return view('staff.TAI.ShowTAI', compact('tai'));
    }

    public function edit($id)
    {
        $tai = ScoreTAI::findOrFail($id);
        $elderly = Elderly::find($tai->ID_Elderly);
        $user = Auth::user();
        // dd($user);

        return view('staff.TAI.EditTAI', compact('tai', 'elderly', 'user'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'mobility' => 'required|integer|min:0|max:5',
        'confuse' => 'required|integer|min:0|max:5',
        'feed' => 'required|integer|min:0|max:5',
        'toilet' => 'required|integer|min:0|max:5',
        'group' => 'required',
    ]);

    // ค้นหา ScoreTAI record
    $tai = ScoreTAI::findOrFail($id);

    // อัปเดตข้อมูลใน ScoreTAI
    $tai->mobility = $request->mobility;
    $tai->confuse = $request->confuse;
    $tai->feed = $request->feed;
    $tai->toilet = $request->toilet;
    $tai->group = $request->group;
    $tai->ID_User = Auth::id();  // ผู้ใช้ที่ล็อกอิน
    $tai->save();

    // ดึงข้อมูลที่ต้องใช้สำหรับ CareGiver
    $elderly = Elderly::findOrFail($tai->ID_Elderly);

    // สร้างข้อมูลใหม่ในตาราง care_givers
    CareGiver::create([
        'ID_ADL' => $tai->ID_ADL,
        'ID_Elderly' => $tai->ID_Elderly,
        'Name_CG' => null,
        'Related' => null,
        'Phone_CG' => null,
        'Name_Elderly' => $elderly->Name_Elderly,
        'Birthday' => $elderly->Birthday,
        'Weight' => null,
        'Height' => null,
        'Waist' => null,
        'Address' => $elderly->Address,
        'Group_ADL' => $request->group,
        'Disease' => null,
        'Disability' => null,
        'Rights' => null,
        'Date_CG' => now()->toDateString(),
        'Consciousness' => null,
        'Vital_signs' => null,
        'Bedsores' => null,
        'Pain' => null,
        'Swelling' => null,
        'Itchy_rash' => null,
        'Stiff_joints' => null,
        'Malnutrition' => null,
        'Eating' => null,
        'Swallowing' => null,
        'Defecation' => null,
        'Urinary_excretion' => null,
        'Taking_medicine' => null,
        'Emotional_state' => null,
        'Economic_problems' => null,
        'Social_problems' => null,
        'Doctor_FU' => null,
        'Other_problems' => null,
        'Assistance' => null,
        'Reporter' => null,
        'Picture' => null,
    ]);

    return redirect()->route('tai.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว และบันทึก Care Giver สำเร็จ');
}


    public function destroy($id)
    {
        $tai = ScoreTAI::findOrFail($id);
        $tai->delete();

        return redirect()->route('tai.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
