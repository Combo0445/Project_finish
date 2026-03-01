<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareInstruction;
use App\Models\Elderly;
use App\Models\CareGiver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CareInstructionController extends Controller
{
    protected $notificationService;

    public function __construct(\App\Services\NotificationService $notificationService = null)
    {
        // Allow backwards compatibility if notification server isn't injected tightly
        if (class_exists('\App\Services\NotificationService')) {
            $this->notificationService = $notificationService ?? new \App\Services\NotificationService();
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = CareInstruction::with(['elderly']);

        // Role-Based Filtering
        if ($user->Type_Personnel == 'Doctor') {
            $typeDoctor = $user->Type_Doctor;
            // Doctors see only Care Instructions linked to the ADL Group they manage
            $query->whereHas('elderly.barthel_adl', function ($q) use ($typeDoctor) {
                if ($typeDoctor == 'ติดสังคม')
                    $q->where('Group_ADL', 'ติดสังคม');
                elseif ($typeDoctor == 'ติดบ้าน')
                    $q->where('Group_ADL', 'ติดบ้าน');
                elseif ($typeDoctor == 'ติดเตียง')
                    $q->where('Group_ADL', 'ติดเตียง');
            });
        } elseif ($user->Type_Personnel == 'Staff') {
            // Staff can see all instructions, so no additional filter is applied.
        }

        // Feature Toggle: Filter Unconfirmed Only via Query Parameter
        if ($request->has('unconfirmed') && $request->unconfirmed == 'true') {
            $query->whereNull('Confirm');
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Name_Elderly', 'LIKE', "%$search%")
                    ->orWhere('Care_instructions', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('elderly_id')) {
            $query->where('ID_Elderly', $request->elderly_id);
        }

        $careInstructions = $query->orderBy('Date_CI', 'desc')->paginate(20);

        return view('care_instructions.index', compact('careInstructions'));
    }

    public function create(Request $request)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $elderly = Elderly::findOrFail($request->elderly_id);
        $careGiver = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->first();
        $reporter = $careGiver ? $careGiver->Reporter : 'Unknown';

        // Fetch all staff members for the dropdown
        $staffMembers = User::where('Type_Personnel', 'Staff')->get();

        // Fetch historical care instructions for this patient
        $history = CareInstruction::where('ID_Elderly', $elderly->ID_Elderly)
            ->orderBy('Date_CI', 'desc')
            ->take(5)
            ->get();

        return view('care_instructions.create', compact('elderly', 'reporter', 'staffMembers', 'history'));
    }

    public function store(Request $request)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'Date_CI' => 'required|date',
            'Name_Elderly' => 'required|string',
            'Name_Doctor' => 'required|string',
            'Name_Staff' => 'required|string',
            'Care_instructions' => 'required|string',
        ]);

        $ci = CareInstruction::create($request->only([
            'ID_Elderly',
            'Date_CI',
            'Name_Elderly',
            'Name_Doctor',
            'Name_Staff',
            'Care_instructions',
        ]));

        $staff = User::where('Name_User', $request->Name_Staff)->first();
        if ($staff && $this->notificationService) {
            $this->notificationService->notifyCareInstruction(
                $staff,
                $request->Name_Elderly,
                $request->Name_Doctor,
                $request->Care_instructions
            );
        }

        return redirect()->route('dashboard')->with('success', 'คำแนะนำถูกบันทึกเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);

        $elderly = Elderly::findOrFail($careInstruction->ID_Elderly);
        $careGiver = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->first();
        $reporter = $careGiver ? $careGiver->Reporter : 'Unknown';

        // Fetch all staff members for the dropdown
        $staffMembers = User::where('Type_Personnel', 'Staff')->get();

        // Fetch historical care instructions for this patient (excluding current one)
        $history = CareInstruction::where('ID_Elderly', $elderly->ID_Elderly)
            ->where('ID_CI', '!=', $id)
            ->orderBy('Date_CI', 'desc')
            ->take(5)
            ->get();

        return view('care_instructions.edit', compact('careInstruction', 'elderly', 'reporter', 'staffMembers', 'history'));
    }

    public function update(Request $request, $id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'ID_Elderly' => 'required|exists:elderlys,ID_Elderly',
            'Date_CI' => 'required|date',
            'Name_Elderly' => 'required|string',
            'Name_Doctor' => 'required|string',
            'Name_Staff' => 'required|string',
            'Care_instructions' => 'required|string',
        ]);

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update($request->only([
            'ID_Elderly',
            'Date_CI',
            'Name_Elderly',
            'Name_Doctor',
            'Name_Staff',
            'Care_instructions',
        ]));

        $staff = User::where('Name_User', $request->Name_Staff)->first();
        if ($staff && $this->notificationService) {
            $this->notificationService->notifyCareInstruction(
                $staff,
                $request->Name_Elderly,
                Auth::user()->Name_User,
                $request->Care_instructions,
                true
            );
        }

        return redirect()->route('dashboard')->with('success', 'คำแนะนำถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->delete();

        return redirect()->route('care_instructions.index')->with('success', 'ลบคำแนะนำการดูแลเรียบร้อยแล้ว');
    }

    // Acknowledge Care Plan - Staff Only
    public function confirm($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Staff' && $role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);

        $careInstruction->update(['Confirm' => 'ยืนยัน']);

        return redirect()->back()->with('success', 'ยืนยันรับทราบแผนการดูแลเรียบร้อยแล้ว');
    }

    public function unconfirm($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Staff' && $role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);

        try {
            $careInstruction->update(['Confirm' => null]);
            return redirect()->back()->with('success', 'ยกเลิกการยืนยันเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            \Log::error('CareInstruction Void Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการยกเลิก กรุณาลองใหม่อีกครั้ง');
        }
    }
}
