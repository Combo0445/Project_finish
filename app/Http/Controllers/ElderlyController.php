<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Models\AddressElderly;
use App\Models\BarthelAdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ElderlyController extends Controller
{
    public function Addelderly()
    {
        return view('staff.elderly.addelderly');
    }

    public function Storeelderly(Request $request)
    {
        $request->validate([
            'Name_Elderly' => 'required|string|max:255',
            'Birthday' => 'required|date',
            'Address' => 'required|string',
            'Phone_Elderly' => 'required|string',
            'Image_Elderly' => 'nullable|image'
        ]);

        $elderly = new Elderly();
        $elderly->fill($request->only(['Name_Elderly', 'Birthday', 'Address', 'Phone_Elderly']));

        if ($request->hasFile('Image_Elderly')) {
            $imagePath = $request->file('Image_Elderly')->store('elderly_images', 'public');
            $elderly->Image_Elderly = $imagePath;
        }

        $elderly->save();

        $addressElderly = new AddressElderly();
        $addressElderly->ID_Elderly = $elderly->ID_Elderly;
        $addressElderly->Name_Elderly = $elderly->Name_Elderly;
        $addressElderly->Latitude_position = $request->input('Latitude_position');
        $addressElderly->Longitude_position = $request->input('Longitude_position');
        $addressElderly->save();

        return redirect()->back()->with('success', 'เพิ่มข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
    }

    public function Showelderly(Request $request)
    {
        // 1. โหลด Elderly พร้อมที่อยู่และ ADL group
        $elderlies = Elderly::with(['addressElderly','barthel_adl','care_giver'])->get();

        // 2. คำนวณช่วงอายุ
        $ageGroups = [
            'ช่วงอายุ 60-69' => 0,
            'ช่วงอายุ 70-79' => 0,
            'ช่วงอายุ 80-89' => 0,
            'ช่วงอายุ 90+'   => 0,
        ];
        foreach ($elderlies as $e) {
            $age = Carbon::parse($e->Birthday)->age;
            if ($age >= 60 && $age <= 69) {
                $ageGroups['ช่วงอายุ 60-69']++;
            } elseif ($age >= 70 && $age <= 79) {
                $ageGroups['ช่วงอายุ 70-79']++;
            } elseif ($age >= 80 && $age <= 89) {
                $ageGroups['ช่วงอายุ 80-89']++;
            } elseif ($age >= 90) {
                $ageGroups['ช่วงอายุ 90+']++;
            }
        }

        // 3. คำนวณสัดส่วน ADL
        $adlGroups = [
            'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL','กลุ่มติดสังคม')->count(),
            'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL','กลุ่มติดบ้าน')->count(),
            'กลุ่มติดเตียง'=> BarthelAdl::where('Group_ADL','กลุ่มติดเตียง')->count(),
        ];

        // 4. เตรียม JSON สำหรับ Marker บนแผนที่
        $elderlyLocations = [];
        foreach ($elderlies as $e) {
            if ($e->addressElderly
                && $e->addressElderly->Latitude_position
                && $e->addressElderly->Longitude_position
            ) {
                $adlGroup = optional($e->barthel_adl)->Group_ADL ?: 'ยังไม่ได้ประเมิน';
                $elderlyLocations[] = [
                    'latitude'  => $e->addressElderly->Latitude_position,
                    'longitude' => $e->addressElderly->Longitude_position,
                    'name'      => $e->Name_Elderly,
                    'address'   => $e->Address,
                    'adlGroup'  => $adlGroup,
                ];
            }
        }

        // 5. ส่งข้อมูลไปยัง View
        return view('staff.dashboard-staff', compact(
            'elderlies','ageGroups','adlGroups','elderlyLocations'
        ));
    }


    public function Editelderly($id)
    {
        $elderly = Elderly::findOrFail($id);
        $addressElderly = AddressElderly::where('ID_Elderly', $id)->first();
        return view('staff.elderly.editelderly', compact('elderly', 'addressElderly'));
    }

    public function Updateelderly(Request $request, $id)
    {
        $elderly = Elderly::findOrFail($id);

        $request->validate([
            'Name_Elderly' => 'required|string|max:255',
            'Birthday' => 'required|date',
            'Address' => 'required|string',
            'Phone_Elderly' => 'required|string',
            'Image_Elderly' => 'nullable|image'
        ]);

        $elderly->fill($request->only(['Name_Elderly', 'Birthday', 'Address', 'Phone_Elderly']));

        if ($request->hasFile('Image_Elderly')) {
            // Delete old image
            if ($elderly->Image_Elderly) {
                Storage::disk('public')->delete($elderly->Image_Elderly);
            }

            $imagePath = $request->file('Image_Elderly')->store('elderly_images', 'public');
            $elderly->Image_Elderly = $imagePath;
        }

        $elderly->save();

        $addressElderly = AddressElderly::where('ID_Elderly', $id)->first();
        if (!$addressElderly) {
            $addressElderly = new AddressElderly();
            $addressElderly->ID_Elderly = $id;
        }
        $addressElderly->Latitude_position = $request->input('Latitude_position');
        $addressElderly->Longitude_position = $request->input('Longitude_position');
        $addressElderly->save();

        return redirect()->route('staff-dashboard')->with('success', 'อัปเดตข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
    }

    public function Deleteelderly($id)
    {
        $elderly = Elderly::findOrFail($id);

        // Delete image
        if ($elderly->Image_Elderly) {
            Storage::disk('public')->delete($elderly->Image_Elderly);
        }

        $elderly->delete();

        return redirect()->route('staff-dashboard')->with('success', 'ลบข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
    }

    public function searchLocation($id)
    {
        $addressElderly = AddressElderly::where('ID_Elderly', $id)->firstOrFail();
        $latitude = $addressElderly->Latitude_position;
        $longitude = $addressElderly->Longitude_position;

        return redirect()->away("https://www.google.com/maps/search/?api=1&query=$latitude,$longitude");
    }

    public function showReport()
    {
        $elderlies = Elderly::all();

        $ageGroups = [
            'ช่วงอายุ 60-69' => 0,
            'ช่วงอายุ 70-79' => 0,
            'ช่วงอายุ 80-89' => 0,
            'ช่วงอายุ 90+' => 0,
        ];

        foreach ($elderlies as $elderly) {
            $age = \Carbon\Carbon::parse($elderly->Birthday)->age;
            if ($age >= 60 && $age <= 69) {
                $ageGroups['ช่วงอายุ 60-69']++;
            } elseif ($age >= 70 && $age <= 79) {
                $ageGroups['ช่วงอายุ 70-79']++;
            } elseif ($age >= 80 && $age <= 89) {
                $ageGroups['ช่วงอายุ 80-89']++;
            } elseif ($age >= 90) {
                $ageGroups['ช่วงอายุ 90+']++;
            }
        }
        // ดึงข้อมูล ADL group counts
        $adlGroups = [
            'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
            'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
            'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
        ];

        return view('staff.Report.report-elderly', compact('elderlies', 'ageGroups','adlGroups'));
    }
}
