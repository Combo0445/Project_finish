<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreTAI;
use App\Models\Elderly;
use Illuminate\Support\Facades\Auth;

class TAIController extends Controller
{
    public function index()
    {
        $tai = ScoreTAI::with(['elderly', 'user'])->get();
        return view('staff.TAI.ShowTAI', compact('tai'));
    }

    public function edit($id)
    {
        $tai = ScoreTAI::findOrFail($id);
        $elderly = Elderly::find($tai->ID_Elderly);
        $user = Auth::user();

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

        $tai = ScoreTAI::findOrFail($id);
        $tai->mobility = $request->input('mobility');
        $tai->confuse = $request->input('confuse');
        $tai->feed = $request->input('feed');
        $tai->toilet = $request->input('toilet');
        $tai->group = $request->input('group');
        $tai->ID_User = Auth::user()->ID_User; // อัปเดต user ล่าสุดที่แก้ไข
        $tai->save();

        return redirect()->route('tai.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy($id)
{
    $tai = ScoreTAI::findOrFail($id);
    $tai->delete();

    return redirect()->route('tai.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
}
}
