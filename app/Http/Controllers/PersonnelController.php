<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\User;

class PersonnelController extends Controller
{

    public function addPersonnelTypes()
    {
        $types = ['Admin', 'Staff', 'Doctor'];

        foreach ($types as $type) {
            $personnel = new Personnel();
            $personnel->Type_Personnel = $type;
            $personnel->save();
        }

        return response()->json([
            'message' => 'ประเภทของ Personnel ถูกเพิ่มเรียบร้อยแล้ว'
        ]);
    }

    public function showPersonnel()
    {
        // ดึงข้อมูลจำนวนบุคลากรตามประเภท
        $adminCount = User::where('Type_Personnel', 'Admin')->count();
        $doctorCount = User::where('Type_Personnel', 'Doctor')->count();
        $staffCount = User::where('Type_Personnel', 'Staff')->count();

        // ส่งข้อมูลไปยังหน้า view
        return view('layout.personnel', [
            'adminCount' => $adminCount,
            'doctorCount' => $doctorCount,
            'staffCount' => $staffCount,
        ]);
    }
}
