<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Models\User;
use App\Models\BarthelAdl;
use App\Models\CareGiver;
use App\Models\CareInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user)
            return redirect()->route('login');
        switch ($user->Type_Personnel) {
            case 'Admin':
                return $this->adminDashboard();
            case 'Staff':
                return $this->staffDashboard($request);
            case 'Doctor':
                return $this->doctorDashboard($request);
            case 'Pharmacist':
                return redirect()->route('medicines.index');
            default:
                return redirect()->route('welcome');
        }
    }

    private function adminDashboard()
    {
        $users = User::orderBy('ID_User', 'desc')->get();
        return view('dashboard', compact('users'));
    }

    private function staffDashboard(Request $request)
    {
        $search = $request->input('search');
        $groupFilter = $request->input('adl_group');
        $gender = $request->input('gender');
        $ageRange = $request->input('age_range');

        $query = Elderly::with(['addressElderly', 'barthel_adl', 'care_giver', 'score_tai', 'care_instructions']);

        if ($search) {
            $query->where('Name_Elderly', 'LIKE', "%{$search}%");
        }
        if ($groupFilter) {
            $query->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', $groupFilter));
        }
        if ($gender) {
            $query->where('Gender', $gender);
        }
        if ($ageRange) {
            $this->applyAgeFilter($query, $ageRange);
        }

        $elderlies = $query->paginate(20);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $elderlies */
        $elderlies->withQueryString();

        // Stats & Charts
        $adlGroups = [
            'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
            'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
            'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
        ];

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

        $elderlyLocations = $this->getElderlyLocations($elderlies);

        return view('dashboard', compact('elderlies', 'ageGroups', 'adlGroups', 'elderlyLocations'));
    }

    private function doctorDashboard(Request $request)
    {
        $user = Auth::user();
        $query = Elderly::with(['barthel_adl', 'care_giver', 'care_instructions']);

        // Doctor's assigned group filter
        $typeDoctor = $user->Type_Doctor;
        if ($typeDoctor) {
            $query->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', $typeDoctor));
        }

        if ($request->filled('adl_group')) {
            $query->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', $request->adl_group));
        }
        if ($request->filled('age_range')) {
            $this->applyAgeFilter($query, $request->age_range);
        }

        $elderlys = $query->paginate(20);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $elderlys */
        $elderlys->withQueryString();

        $stats = [
            'total_patients' => $elderlys->total(),
            'pending_ci' => CareInstruction::whereNull('Confirm')->count(),
            'today_assessed' => Elderly::whereHas('care_giver', fn($q) => $q->where('Date_CG', now()->toDateString()))->count()
        ];

        $chartData = [
            'adl_distribution' => [
                'ติดสังคม' => (clone $query)->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', 'กลุ่มติดสังคม'))->count(),
                'ติดบ้าน' => (clone $query)->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', 'กลุ่มติดบ้าน'))->count(),
                'ติดเตียง' => (clone $query)->whereHas('barthel_adl', fn($q) => $q->where('Group_ADL', 'กลุ่มติดเตียง'))->count(),
            ],
            'age_distribution' => [
                '60-69' => (clone $query)->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 60 AND 69')->count(),
                '70-79' => (clone $query)->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 70 AND 79')->count(),
                '80-89' => (clone $query)->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 80 AND 89')->count(),
                '90+' => (clone $query)->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) >= 90')->count(),
            ]
        ];

        return view('dashboard', compact('elderlys', 'stats', 'chartData'));
    }

    private function applyAgeFilter($query, $range)
    {
        switch ($range) {
            case '60-69':
                $query->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 60 AND 69');
                break;
            case '70-79':
                $query->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 70 AND 79');
                break;
            case '80-89':
                $query->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) BETWEEN 80 AND 89');
                break;
            case '90+':
                $query->whereRaw('TIMESTAMPDIFF(YEAR, Birthday, CURDATE()) >= 90');
                break;
        }
    }

    private function getElderlyLocations($elderlies)
    {
        $locations = [];
        foreach ($elderlies as $e) {
            if ($e->addressElderly && $e->addressElderly->Latitude_position && $e->addressElderly->Longitude_position) {
                $locations[] = [
                    'latitude' => $e->addressElderly->Latitude_position,
                    'longitude' => $e->addressElderly->Longitude_position,
                    'name' => $e->Name_Elderly,
                    'address' => $e->Address,
                    'adlGroup' => optional($e->barthel_adl)->Group_ADL ?: 'ยังไม่ได้ประเมิน',
                ];
            }
        }
        return $locations;
    }
}
