<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarthelAdl;
use App\Models\Elderly;
use App\Models\ScoreTAI;
use Illuminate\Support\Facades\Auth;

class ADLController extends Controller
{
    protected $notificationService;

    public function __construct(\App\Services\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = BarthelAdl::with('elderly');

        if ($search) {
            $query->where('Name_Elderly', 'LIKE', "%{$search}%");
        }

        if ($dateFrom || $dateTo) {
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        $adls = $query->paginate(20);
        $adls->appends($request->all());

        return view('staff.ADL.ShowADL', compact('adls'));
    }

    public function create(Request $request)
    {
        $elderlies = Elderly::select('ID_Elderly', 'Name_Elderly')->get();
        $selected_elderly_id = $request->query('elderly_id');
        return view('staff.ADL.ADL', compact('elderlies', 'selected_elderly_id'));
    }

    public function submitADL(Request $request)
    {
        // Validate the input
        $request->validate([
            'elderly_id' => 'required|integer',
            'feeding' => 'required|integer',
            'grooming' => 'required|integer',
            'transfer' => 'required|integer',
            'toilet_use' => 'required|integer',
            'mobility' => 'required|integer',
            'dressing' => 'required|integer',
            'stairs' => 'required|integer',
            'bathing' => 'required|integer',
            'bowels' => 'required|integer',
            'bladder' => 'required|integer',
        ]);

        // ป้องกันการประเมินซ้ำในวันเดียวกัน
        $alreadyToday = BarthelAdl::where('ID_Elderly', $request->elderly_id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($alreadyToday) {
            return redirect()->back()->with('error', 'ผู้สูงอายุคนนี้ได้รับการประเมิน ADL ในวันนี้แล้ว ไม่สามารถประเมินซ้ำได้');
        }

        // Calculate the total score
        $totalScore = $request->feeding + $request->grooming + $request->transfer + $request->toilet_use + $request->mobility + $request->dressing + $request->stairs + $request->bathing + $request->bowels + $request->bladder;

        // Determine the group based on the total score
        if ($totalScore >= 0 && $totalScore <= 4) {
            $group = 'กลุ่มติดเตียง';
        } elseif ($totalScore >= 5 && $totalScore <= 11) {
            $group = 'กลุ่มติดบ้าน';
        } else {
            $group = 'กลุ่มติดสังคม';
        }

        // Get elderly and user information
        $elderly = Elderly::find($request->elderly_id);
        $user = Auth::user();

        // Get previous ADL for comparison
        $previousAdl = BarthelAdl::where('ID_Elderly', $elderly->ID_Elderly)
            ->where('ID_ADL', '<', 999999999) // Placeholder for latest
            ->orderBy('ID_ADL', 'desc')
            ->first();

        // Create a new ADL record
        $adl = BarthelAdl::create([
            'ID_Elderly' => $elderly->ID_Elderly,
            'Name_Elderly' => $elderly->Name_Elderly,
            'ID_User' => $user->ID_User,
            'Name_User' => $user->Name_User,
            'Score_ADL' => $totalScore,
            'Group_ADL' => $group,
            'Feeding' => $request->feeding,
            'Grooming' => $request->grooming,
            'Transfer' => $request->transfer,
            'Toilet_use' => $request->toilet_use,
            'Mobility' => $request->mobility,
            'Dressing' => $request->dressing,
            'Stairs' => $request->stairs,
            'Bathing' => $request->bathing,
            'Bowels' => $request->bowels,
            'Bladder' => $request->bladder,
        ]);

        // Check for health deterioration
        if ($previousAdl && $previousAdl->Group_ADL !== $group) {
            $levels = ['กลุ่มติดสังคม' => 3, 'กลุ่มติดบ้าน' => 2, 'กลุ่มติดเตียง' => 1];
            $prevLevel = $levels[$previousAdl->Group_ADL] ?? 0;
            $currLevel = $levels[$group] ?? 0;

            if ($currLevel < $prevLevel) {
                // Status deteriorated - Notify using centralized Service
                $this->notificationService->notifyAdlDrop(
                    $elderly->Name_Elderly,
                    $previousAdl->Group_ADL,
                    $group,
                    $user->Name_User
                );
            }
        }

        $tai = ScoreTAI::where('ID_ADL', $adl->ID_ADL)->first();
        if ($tai) {
            return redirect()->route('tai.edit', $tai->id)->with('success', 'ส่งการประเมิน ADL สำเร็จแล้ว! กรุณาประเมิน TAI ต่อ');
        }

        return redirect()->back()->with('success', 'ส่งการประเมิน ADL สำเร็จแล้ว!');
    }

    public function edit($id)
    {
        $adl = BarthelAdl::findOrFail($id);
        $elderlies = Elderly::select('ID_Elderly', 'Name_Elderly')->get();
        return view('staff.ADL.EditADL', compact('adl', 'elderlies'));
    }

    public function update(Request $request, $id)
    {
        $adl = BarthelAdl::findOrFail($id);

        $request->validate([
            'elderly_id' => 'required|integer',
            'feeding' => 'required|integer',
            'grooming' => 'required|integer',
            'transfer' => 'required|integer',
            'toilet_use' => 'required|integer',
            'mobility' => 'required|integer',
            'dressing' => 'required|integer',
            'stairs' => 'required|integer',
            'bathing' => 'required|integer',
            'bowels' => 'required|integer',
            'bladder' => 'required|integer',
        ]);

        $totalScore = $request->feeding + $request->grooming + $request->transfer + $request->toilet_use + $request->mobility + $request->dressing + $request->stairs + $request->bathing + $request->bowels + $request->bladder;

        if ($totalScore >= 0 && $totalScore <= 4) {
            $group = 'กลุ่มติดเตียง';
        } elseif ($totalScore >= 5 && $totalScore <= 11) {
            $group = 'กลุ่มติดบ้าน';
        } else {
            $group = 'กลุ่มติดสังคม';
        }

        $adl->update([
            'ID_Elderly' => $request->elderly_id,
            'Name_Elderly' => Elderly::find($request->elderly_id)->Name_Elderly,
            'Score_ADL' => $totalScore,
            'Group_ADL' => $group,
            'Feeding' => $request->feeding,
            'Grooming' => $request->grooming,
            'Transfer' => $request->transfer,
            'Toilet_use' => $request->toilet_use,
            'Mobility' => $request->mobility,
            'Dressing' => $request->dressing,
            'Stairs' => $request->stairs,
            'Bathing' => $request->bathing,
            'Bowels' => $request->bowels,
            'Bladder' => $request->bladder,
            'updated_at' => now(),
            'created_at' => $adl->created_at,
        ]);

        $tai = ScoreTAI::where('ID_ADL', $adl->ID_ADL)->first();
        if ($tai) {
            return redirect()->route('tai.edit', $tai->id)->with('success', 'อัปเดตการประเมิน ADL สำเร็จแล้ว! กรุณาตรวจสอบการประเมิน TAI');
        }

        return redirect()->route('adl.index')->with('success', 'อัปเดตการประเมิน ADL สำเร็จแล้ว!');
    }

    public function destroy($id)
    {
        $adl = BarthelAdl::findOrFail($id);
        $adl->delete();

        return redirect()->route('adl.index')->with('success', 'ลบการประเมิน ADL สำเร็จแล้ว!');
    }

    public function ReportADLAll()
    {
        $adls = BarthelAdl::with('elderly')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get();

        // Use ReportController logic style for consistency//
        return app(\App\Http\Controllers\ReportController::class)->ReportADLAll(request());
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 18,
            'default_font' => 'sarabun',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'shrink_tables_to_fit' => 1,
        ]);

        $html = view('staff.Report.report-adl-all', [
            'adls' => $adls,
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/Logo.png')))
        ])->render();

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ADL_Report_All.pdf"',
        ]);
    }

    public function ReportADL($id)
    {
        $adl = BarthelAdl::with('elderly')->findOrFail($id);
        return view('staff.Report.report-adl', compact('adl'));
    }
}
