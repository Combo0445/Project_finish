<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Models\AddressElderly;
use App\Models\BarthelAdl;
use App\Http\Requests\ElderlyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CareGiver;
use App\Models\ActivityCaregiver;

class ElderlyController extends Controller
{
    public function Addelderly()
    {
        return view('staff.elderly.addelderly');
    }

    public function Storeelderly(ElderlyRequest $request)
    {

        DB::transaction(function () use ($request) {
            $elderly = new Elderly();
            $elderly->fill($request->only(['Name_Elderly', 'Gender', 'Birthday', 'Address', 'Phone_Elderly']));
            $imageService = app(\App\Services\ImageUploadService::class);
            $elderly->Image_Elderly = $imageService->handleSingleUpload($request->file('Image_Elderly'), 'elderly_images');

            $elderly->save();

            $addressElderly = new AddressElderly();
            $addressElderly->ID_Elderly = $elderly->ID_Elderly;
            $addressElderly->Name_Elderly = $elderly->Name_Elderly;
            $addressElderly->Latitude_position = $request->input('Latitude_position');
            $addressElderly->Longitude_position = $request->input('Longitude_position');
            $addressElderly->save();
        });

        return redirect()->route('dashboard')->with('success', 'เพิ่มข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
    }



    public function Editelderly($id)
    {
        $elderly = Elderly::findOrFail($id);
        $addressElderly = AddressElderly::where('ID_Elderly', $id)->first();
        return view('staff.elderly.editelderly', compact('elderly', 'addressElderly'));
    }

    public function Updateelderly(ElderlyRequest $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $elderly = Elderly::findOrFail($id);
            $elderly->fill($request->only(['Name_Elderly', 'Gender', 'Birthday', 'Address', 'Phone_Elderly']));
            $imageService = app(\App\Services\ImageUploadService::class);
            $elderly->Image_Elderly = $imageService->handleSingleUpload(
                $request->file('Image_Elderly'),
                'elderly_images',
                $elderly->Image_Elderly
            );

            $elderly->save();

            $addressElderly = AddressElderly::where('ID_Elderly', $id)->first();
            if (!$addressElderly) {
                $addressElderly = new AddressElderly();
                $addressElderly->ID_Elderly = $id;
            }
            $addressElderly->Latitude_position = $request->input('Latitude_position');
            $addressElderly->Longitude_position = $request->input('Longitude_position');
            $addressElderly->save();
        });

        return redirect()->route('dashboard')->with('success', 'อัปเดตข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
    }

    public function Deleteelderly($id)
    {
        $elderly = Elderly::findOrFail($id);

        // Soft deletes the record without wiping the physical image, so it can be restored.
        $elderly->delete();

        return redirect()->route('dashboard')->with('success', 'ลบข้อมูลผู้สูงอายุเรียบร้อยแล้ว');
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
        $elderlies = Elderly::with(['barthel_adl', 'care_giver'])->paginate(20);

        $ageGroupsData = Elderly::selectRaw('
            SUM(TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 60 AND 69) as age_60_69,
            SUM(TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 70 AND 79) as age_70_79,
            SUM(TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 80 AND 89) as age_80_89,
            SUM(TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) >= 90) as age_90_plus
        ')->first();

        $ageGroups = [
            'ช่วงอายุ 60-69' => (int) ($ageGroupsData->age_60_69 ?? 0),
            'ช่วงอายุ 70-79' => (int) ($ageGroupsData->age_70_79 ?? 0),
            'ช่วงอายุ 80-89' => (int) ($ageGroupsData->age_80_89 ?? 0),
            'ช่วงอายุ 90+' => (int) ($ageGroupsData->age_90_plus ?? 0),
        ];
        // ดึงข้อมูล ADL group counts
        $adlGroups = [
            'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
            'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
            'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
        ];

        return view('staff.Report.report-elderly', compact('elderlies', 'ageGroups', 'adlGroups'));
    }

    public function showProfile($id)
    {
        $elderly = Elderly::with([
            'addressElderly',
            'barthel_adl',
            'care_giver',
            'score_tai',
            'adl_history',
            'cg_history',
            'tai_history',
            'care_instructions'
        ])->findOrFail($id);

        return view('staff.elderly.elderly-profile', compact('elderly'));
    }

    public function checkAssessmentToday($id, Request $request)
    {
        $today = $request->query('date', now()->toDateString());

        $adlDone = BarthelAdl::where('ID_Elderly', $id)
            ->whereDate('created_at', $today)
            ->exists();

        $cgDone = CareGiver::where('ID_Elderly', $id)
            ->where('Date_CG', $today)
            ->exists();

        $acgDone = ActivityCaregiver::where('Date_ACG', $today)
            ->whereHas('caregiver', function ($query) use ($id) {
                $query->where('ID_Elderly', $id);
            })
            ->exists();

        return response()->json([
            'adl_done' => $adlDone,
            'cg_done' => $cgDone,
            'acg_done' => $acgDone,
        ]);
    }
}
