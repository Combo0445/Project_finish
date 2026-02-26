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
        $query = CareInstruction::with('elderly');

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
            // Staff see only instructions assigned to them
            $query->where('Name_Staff', $user->Name_User);
        }

        // Feature Toggle: Filter Unconfirmed Only via Query Parameter
        if ($request->has('unconfirmed') && $request->unconfirmed == 'true') {
            $query->whereNull('Confirm');
        }

        $careInstructions = $query->orderBy('Date_CI', 'desc')->paginate(20);

        return view('care_instructions.index', compact('careInstructions'));
    }

    public function create(Request $request)
    {
        if (Auth::user()->Type_Personnel !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $elderly = Elderly::findOrFail($request->elderly_id);
        $careGiver = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->first();
        $reporter = $careGiver ? $careGiver->Reporter : 'Unknown';

        return view('care_instructions.create', compact('elderly', 'reporter'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->Type_Personnel !== 'Doctor') {
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

        return redirect()->route('care_instructions.index')->with('success', 'คำแนะนำถูกบันทึกเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        if (Auth::user()->Type_Personnel !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);

        $elderly = Elderly::findOrFail($careInstruction->ID_Elderly);
        $careGiver = CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->first();
        $reporter = $careGiver ? $careGiver->Reporter : 'Unknown';

        return view('care_instructions.edit', compact('careInstruction', 'elderly', 'reporter'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->Type_Personnel !== 'Doctor') {
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

        return redirect()->route('care_instructions.index')->with('success', 'คำแนะนำถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if (Auth::user()->Type_Personnel !== 'Doctor') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->delete();

        return redirect()->route('care_instructions.index')->with('success', 'ลบคำแนะนำการดูแลเรียบร้อยแล้ว');
    }

    // Role Methods for Staff Only
    public function confirm($id)
    {
        if (Auth::user()->Type_Personnel !== 'Staff') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update(['Confirm' => 'ยืนยัน']);

        return redirect()->back()->with('success', 'คำแนะนำการดูแลยืนยันเรียบร้อยแล้ว');
    }

    public function unconfirm($id)
    {
        if (Auth::user()->Type_Personnel !== 'Staff') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::findOrFail($id);
        $careInstruction->update(['Confirm' => null]);

        return redirect()->back()->with('success', 'ยกเลิกการยืนยันคำแนะนำการดูแลแล้ว');
    }
}
