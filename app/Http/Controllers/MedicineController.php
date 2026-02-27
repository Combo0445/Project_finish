<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::select('id', 'name', 'type', 'description', 'stock')->orderBy('id', 'desc')->get();
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'เพิ่มข้อมูลยาสำเร็จ');
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'อัปเดตข้อมูลยาสำเร็จ');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'ลบข้อมูลยาสำเร็จ');
    }
}
