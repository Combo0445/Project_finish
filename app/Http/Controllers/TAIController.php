<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreTAI;
use App\Models\Elderly;
use App\Models\CareGiver;
use Illuminate\Support\Facades\Auth;
use App\Exports\Tai;
use Maatwebsite\Excel\Facades\Excel;

class TAIController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = ScoreTAI::with(['elderly', 'user']);

        if ($search) {
            $query->whereHas('elderly', fn($q) => $q->where('Name_Elderly', 'LIKE', "%{$search}%"));
        }

        if ($dateFrom || $dateTo) {
            if ($dateFrom) {
                $query->whereDate('updated_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('updated_at', '<=', $dateTo);
            }
        } else {
            $query->whereDate('updated_at', now()->toDateString());
        }

        $tai = $query->paginate(20);
        $tai->appends($request->all());
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
        $tai->mobility = $request->mobility;
        $tai->confuse = $request->confuse;
        $tai->feed = $request->feed;
        $tai->toilet = $request->toilet;
        $tai->group = $request->group;
        $tai->ID_User = Auth::id();
        $tai->save();

        $elderly = Elderly::findOrFail($tai->ID_Elderly);

        // Update or create CareGiver record
        $cg = CareGiver::updateOrCreate(
            ['ID_ADL' => $tai->ID_ADL, 'ID_Elderly' => $tai->ID_Elderly],
            [
                'Name_Elderly' => $elderly->Name_Elderly,
                'Birthday' => $elderly->Birthday,
                'Address' => $elderly->Address,
                'Group_ADL' => $request->group,
                'Date_CG' => now()->toDateString(),
            ]
        );

        return redirect()->route('tai.index')->with('success', 'บันทึกการประเมิน TAI เรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        $tai = ScoreTAI::findOrFail($id);
        $tai->delete();

        return redirect()->route('tai.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function ExportTAI()
    {
        return Excel::download(new Tai, 'Tai.xlsx');
    }
}
