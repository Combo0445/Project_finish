<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineLot;

class MedicineLotController extends Controller
{
    public function create(Medicine $medicine)
    {
        return view('medicines.lots.create', compact('medicine'));
    }

    public function store(Request $request, Medicine $medicine)
    {
        $request->validate([
            'lot_number' => 'required|string|max:255',
            'mfd_date' => 'nullable|date',
            'exp_date' => 'nullable|date|after_or_equal:mfd_date',
            'stock' => 'required|integer|min:1',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $lot = new MedicineLot($request->all());
        $medicine->lots()->save($lot);

        $medicine->increment('stock', $lot->stock);

        return redirect()->route('medicines.index')->with('success', 'เพิ่มล็อตยาเข้าคลังเรียบร้อยแล้ว');
    }
}
