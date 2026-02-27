<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareInstruction;
use App\Models\Elderly;
use App\Models\CareGiver;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Prescription;
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
        $query = CareInstruction::with(['elderly', 'prescriptions.medicine']);

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
        } elseif ($user->Type_Personnel == 'Pharmacist') {
            // Pharmacists see all instructions to prepare medicine, but usually only look at unconfirmed
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

        $medicines = Medicine::select('id', 'name', 'stock', 'type')->get();

        return view('care_instructions.create', compact('elderly', 'reporter', 'staffMembers', 'history', 'medicines'));
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

        if ($request->has('prescriptions')) {
            foreach ($request->prescriptions as $prescriptionData) {
                if (!empty($prescriptionData['medicine_id']) && !empty($prescriptionData['amount'])) {
                    Prescription::create([
                        'care_instruction_id' => $ci->ID_CI,
                        'medicine_id' => $prescriptionData['medicine_id'],
                        'amount' => $prescriptionData['amount'],
                        'dosage' => $prescriptionData['dosage'] ?? null,
                    ]);
                }
            }
        }

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

        $medicines = Medicine::select('id', 'name', 'stock', 'type')->get();

        return view('care_instructions.edit', compact('careInstruction', 'elderly', 'reporter', 'staffMembers', 'history', 'medicines'));
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

        // Delete all old prescriptions and recreate them
        $careInstruction->prescriptions()->delete();
        if ($request->has('prescriptions')) {
            foreach ($request->prescriptions as $prescriptionData) {
                if (!empty($prescriptionData['medicine_id']) && !empty($prescriptionData['amount'])) {
                    Prescription::create([
                        'care_instruction_id' => $careInstruction->ID_CI,
                        'medicine_id' => $prescriptionData['medicine_id'],
                        'amount' => $prescriptionData['amount'],
                        'dosage' => $prescriptionData['dosage'] ?? null,
                    ]);
                }
            }
        }

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

    // Role Methods for Staff Only
    public function confirm($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Staff') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::with('prescriptions.medicine')->findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($careInstruction) {
                // Validation for stock with locks
                foreach ($careInstruction->prescriptions as $prescription) {
                    if ($prescription->medicine_id) {
                        // Lock the medicine row
                        $medicine = \App\Models\Medicine::where('id', $prescription->medicine_id)->lockForUpdate()->first();

                        if ($medicine && $prescription->amount > $medicine->stock) {
                            throw new \Exception('สต็อกยา ' . $medicine->name . ' ไม่เพียงพอ (ต้องการ: ' . $prescription->amount . ', มีอยู่: ' . $medicine->stock . ')');
                        }
                    }
                }

                // Dispense logic (FIFO)
                foreach ($careInstruction->prescriptions as $prescription) {
                    if (!$prescription->medicine_id)
                        continue;

                    $medicine = \App\Models\Medicine::where('id', $prescription->medicine_id)->lockForUpdate()->first();
                    if (!$medicine)
                        continue;

                    $amountToDispense = $prescription->amount;

                    // Get lots that have stock, ordered by expiry date (FIFO) and lock them
                    $lots = \App\Models\MedicineLot::where('medicine_id', $prescription->medicine_id)
                        ->where('stock', '>', 0)
                        ->orderByRaw('exp_date IS NULL, exp_date ASC') // nulls last
                        ->orderBy('mfd_date', 'asc')
                        ->lockForUpdate()
                        ->get();

                    foreach ($lots as $lot) {
                        if ($amountToDispense <= 0)
                            break;

                        if ($lot->stock >= $amountToDispense) {
                            $lot->decrement('stock', $amountToDispense);
                            $amountToDispense = 0;
                        } else {
                            $amountToDispense -= $lot->stock;
                            $lot->update(['stock' => 0]);
                        }
                    }

                    // Deduct total stock from medicine
                    $medicine->decrement('stock', $prescription->amount);

                    // Mark prescription as dispensed
                    $prescription->update(['dispensed' => true]);
                }

                $careInstruction->update(['Confirm' => 'ยืนยัน']);
            });

            return redirect()->back()->with('success', 'คำแนะนำการดูแลยืนยันและตัดสต็อกยาเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function unconfirm($id)
    {
        $role = session('impersonate_role', Auth::user()->Type_Personnel);
        if ($role !== 'Staff') {
            abort(403, 'Unauthorized action.');
        }

        $careInstruction = CareInstruction::with('prescriptions.medicine')->findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($careInstruction) {
                // Restore stock
                foreach ($careInstruction->prescriptions as $prescription) {
                    if ($prescription->dispensed && $prescription->medicine_id) {

                        $medicine = \App\Models\Medicine::where('id', $prescription->medicine_id)->lockForUpdate()->first();

                        if ($medicine) {
                            $medicine->increment('stock', $prescription->amount);

                            // Try to find the latest lot to return the stock to, locking it
                            $latestLot = \App\Models\MedicineLot::where('medicine_id', $prescription->medicine_id)
                                ->orderBy('created_at', 'desc')
                                ->lockForUpdate()
                                ->first();

                            if ($latestLot) {
                                $latestLot->increment('stock', $prescription->amount);
                            } else {
                                // Fallback if no lot exists
                                \App\Models\MedicineLot::create([
                                    'medicine_id' => $prescription->medicine_id,
                                    'lot_number' => 'RETURN-' . date('Ymd_His'),
                                    'stock' => $prescription->amount
                                ]);
                            }
                            $prescription->update(['dispensed' => false]);
                        }
                    }
                }

                $careInstruction->update(['Confirm' => null]);
            });

            return redirect()->back()->with('success', 'ยกเลิกการยืนยันและคืนสต็อกยาแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการคืนสต็อก: ' . $e->getMessage());
        }
    }
}
