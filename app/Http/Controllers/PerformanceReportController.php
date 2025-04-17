<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceReport;
use App\Models\Elderly;
use App\Models\BarthelAdl;
use App\Models\ScoreTAI;
use App\Models\CareGiver;
use App\Models\User;
use Carbon\Carbon;

class PerformanceReportController extends Controller
{
    public function index()
    {
        // โหลดข้อมูลพร้อมความสัมพันธ์ที่ต้องการใช้งาน
        $performanceReports = PerformanceReport::with('elderly', 'adl', 'tai', 'caregiver', 'user')->get();
        return view('staff.PerformanceReport.ShowPerformanceReport', compact('performanceReports'));
    }


    public function create()
    {
        // แค่โหลด list ผู้สูงอายุ
        $elderlys = Elderly::all();
        return view('staff.PerformanceReport.AddPerformanceReport', compact('elderlys'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'ID_Adl'     => 'required|exists:barthel_adls,ID_ADL',
            'ID_Tai'     => 'required|exists:score_t_a_i_s,id',
            'ID_CG'      => 'required|exists:care_givers,ID_CG',    // ← เพิ่มตรงนี้
            'Date'       => 'required|date_format:Y-m-d\TH:i',
            'State'      => 'required|string|max:255',
            'Activity'   => 'required|string|max:255',
            'Problems'   => 'nullable|string|max:255',
            'Relative'   => 'nullable|string|max:255',
            'Note'       => 'nullable|string|max:255',
        ]);

        PerformanceReport::create([
            'ID_Elderly' => $validatedData['ID_Elderly'],
            'ID_ADL'     => $validatedData['ID_Adl'],
            'ID_TAI'     => $validatedData['ID_Tai'],
            'ID_CG'      => $validatedData['ID_CG'], // ← เพิ่มตรงนี้
            'ID_User'    => auth()->user()->ID_User,
            'Date'       => Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['Date']),
            'State'      => $validatedData['State'],
            'Activity'   => $validatedData['Activity'],
            'Problems'   => $validatedData['Problems'],
            'Relative'   => $validatedData['Relative'],
            'Note'       => $validatedData['Note'],
        ])->save();

        return redirect()
            ->route('performanceReport.index')
            ->with('success', 'Performance Report created successfully.');
    }
    public function show($id)
    {
        $performanceReport = PerformanceReport::findOrFail($id);
        return view('performanceReport.show', compact('performanceReport'));
    }
    public function edit($id)
    {
        // โหลด list ผู้สูงอายุ สำหรับ dropdown
        $elderlys = Elderly::all();
        $performanceReport = PerformanceReport::with('adl', 'tai', 'caregiver')->findOrFail($id);
        return view('staff.PerformanceReport.EditPerformanceReport', compact('elderlys', 'performanceReport'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'ID_Adl'     => 'required|exists:barthel_adls,ID_ADL',
            'ID_Tai'     => 'required|exists:score_t_a_i_s,id',
            'ID_CG'      => 'required|exists:care_givers,ID_CG',
            'Date'       => 'required|date',
            'State'      => 'required|string|max:255',
            'Activity'   => 'required|string|max:255',
            'Problems'   => 'nullable|string|max:255',
            'Relative'   => 'nullable|string|max:255',
            'Note'       => 'nullable|string|max:255',
        ]);

        $report = PerformanceReport::findOrFail($id);
        $report->update([
            'ID_Elderly' => $validatedData['ID_Elderly'],
            'ID_ADL'     => $validatedData['ID_Adl'],
            'ID_TAI'     => $validatedData['ID_Tai'],
            'ID_CG'      => $validatedData['ID_CG'],
            'Date'       => $validatedData['Date'],
            'State'      => $validatedData['State'],
            'Activity'   => $validatedData['Activity'],
            'Problems'   => $validatedData['Problems'],
            'Relative'   => $validatedData['Relative'],
            'Note'       => $validatedData['Note'],
        ]);

        return redirect()->route('performanceReport.index')
            ->with('success', 'Performance Report updated successfully.');
    }
    public function destroy($id)
    {
        // หาเรคอร์ด ถ้าไม่เจอจะโยน 404 ให้ทันที
        $report = PerformanceReport::findOrFail($id);

        // ลบ
        $report->delete();

        // รีไดเรกท์กลับมาพร้อมข้อความสำเร็จ
        return redirect()
            ->route('performanceReport.index')
            ->with('success', 'ลบ Performance Report สำเร็จแล้ว');
    }

    public function getPerformanceData($elderlyId)
    {
        $latestAdl = BarthelAdl::where('ID_Elderly', $elderlyId)
            ->orderBy('created_at', 'desc')->first();
        $latestTai = ScoreTAI::where('ID_Elderly', $elderlyId)
            ->orderBy('created_at', 'desc')->first();
        $latestCg  = CareGiver::where('ID_Elderly', $elderlyId)
            ->orderBy('Date_CG', 'desc')->first();

        return response()->json([
            'adl' => $latestAdl ? [
                'id'    => $latestAdl->ID_ADL,
                'label' => "ADL #{$latestAdl->ID_ADL} (Score: {$latestAdl->Score_ADL})"
            ] : null,
            'tai' => $latestTai ? [
                'id'    => $latestTai->id,
                'label' => "TAI #{$latestTai->id}"
            ] : null,
            'cg'  => $latestCg  ? [
                'id'    => $latestCg->ID_CG,
                'label' => $latestCg->Name_CG
            ] : null,
        ]);
    }
}
