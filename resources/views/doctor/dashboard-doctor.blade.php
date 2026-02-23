<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำแนะนํา</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-xl {
            max-width: 75% !important;
        }

        .align-items-center .btn-adl {
            background-color: #17a2b8; /* สีฟ้าอ่อน */
            border-color: #17a2b8;
            color: white;
        }

        .align-items-center .btn-cg {
            background-color: rgb(65, 220, 255); /* สีเขียว */
            border-color: rgb(65, 220, 255);
            color: white;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }


        @media only screen and (max-width: 767px) {
            .container {
                padding: 10px; /* ปรับ padding ให้น้อยลงสำหรับมือถือ */
            }

            .table th,
            .table td {
                font-size: 12px; /* ปรับขนาดตัวอักษรในตาราง */
            }

            .modal-xl {
                max-width: 95% !important; /* ปรับขนาด modal ให้เหมาะกับมือถือ */
            }

            .modal-lg {
                max-width: 90% !important;
            }

            .btn-adl, .btn-cg, .btn-info {
                width: 100%; /* ทำให้ปุ่มเต็มความกว้างบนหน้าจอมือถือ */
                margin-bottom: 5px;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 1024px) {
            .container {
                padding: 20px; /* ปรับ padding สำหรับแท็บเล็ต */
            }

            .table th,
            .table td {
                font-size: 14px; /* ปรับขนาดตัวอักษรสำหรับแท็บเล็ต */
            }

            .modal-xl {
                max-width: 85% !important; /* ปรับขนาด modal ให้เหมาะกับแท็บเล็ต */
            }

            .modal-lg {
                max-width: 80% !important;
            }

            .btn-adl, .btn-cg, .btn-info {
                width: 48%; /* ทำให้ปุ่มครึ่งหนึ่งของความกว้าง */
                margin-bottom: 10px;
            }
        }

    </style>
</head>

<body>
    @include('layout.nav')

    <main class="main-content position-relative h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4>ข้อมูลผู้สูงอายุ</h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                        <table id="doctorTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>รูป</th>
                                    <th>ชื่อ</th>
                                    <th>อายุ</th>
                                    <th>ที่อยู่</th>
                                    <th>เบอร์โทร</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elderlys as $elderly)
                                    @php
                                        $latestCaregiverDate =
                                            \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                                                ->orderBy('Date_CG', 'desc')
                                                ->first()->Date_CG ?? null;
                                        $latestActivityDate =
                                            \App\Models\ActivityCaregiver::whereIn('ID_CG', function ($query) use ($elderly) {
                                                $query
                                                    ->select('ID_CG')
                                                    ->from('care_givers')
                                                    ->where('ID_Elderly', $elderly->ID_Elderly);
                                            })
                                                ->orderBy('Date_ACG', 'desc')
                                                ->first()->Date_ACG ?? null;
                                        $latestDateCI =
                                            \App\Models\CareInstruction::where('ID_Elderly', $elderly->ID_Elderly)
                                                ->orderBy('Date_CI', 'desc')
                                                ->first()->Date_CI ?? null;

                                        $showElderly = false;

                                        if (auth()->user()->Type_Personnel == 'Doctor' && $elderly->barthel_adl) {
                                            $typeDoctor = auth()->user()->Type_Doctor;
                                            if (($typeDoctor == 'กลุ่มติดสังคม' && $elderly->barthel_adl->Group_ADL == 'กลุ่มติดสังคม') ||
                                                ($typeDoctor == 'กลุ่มติดบ้าน' && $elderly->barthel_adl->Group_ADL == 'กลุ่มติดบ้าน') ||
                                                ($typeDoctor == 'กลุ่มติดเตียง' && $elderly->barthel_adl->Group_ADL == 'กลุ่มติดเตียง')) {
                                                $showElderly = true;
                                            }
                                        }
                                    @endphp

                                    @if ($showElderly && ($latestCaregiverDate > $latestDateCI || $latestActivityDate > $latestDateCI))
                                        <tr>
                                            <td>
                                                @if ($elderly->Image_Elderly)
                                                    <img src="{{ url('storage/' . $elderly->Image_Elderly) }}" alt="Elderly Image" width="50">
                                                @else
                                                    <img src="{{ url('storage/default.png') }}" alt="Elderly Image" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $elderly->Name_Elderly }}</td>
                                            <td>{{ \Carbon\Carbon::parse($elderly->Birthday)->age }} ปี</td>
                                            <td>{{ $elderly->Address }}</td>
                                            <td>{{ $elderly->Phone_Elderly }}</td>
                                            <td>
                                                <button class="btn btn-adl btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#adlModal-{{ $elderly->ID_Elderly }}">ข้อมูล ADL</button>
                                                <button class="btn btn-cg btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#cgDatesModal-{{ $elderly->ID_Elderly }}">ข้อมูล CG</button>
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#acgDatesModal-{{ $elderly->ID_Elderly }}">ข้อมูล ACG</button>
                                                <a href="{{ route('ci.create', ['elderly_id' => $elderly->ID_Elderly]) }}" class="btn btn-success btn-sm">
                                                    <i class="fas fa-plus"></i> คำแนะนำ
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>


    <!-- ADL Modal -->
    @foreach ($elderlys as $elderly)
        <div class="modal fade" id="adlModal-{{ $elderly->ID_Elderly }}" tabindex="-1"
            aria-labelledby="adlModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adlModalLabel-{{ $elderly->ID_Elderly }}">ข้อมูลประเมินความสามารถในการดำเนินกิจวัตรประจำวันของคุณ
                            {{ $elderly->Name_Elderly }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($elderly->barthel_adl)
                            <table class="table table-bordered">
                                <tr>
                                    <th>การรับประทานอาหาร</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('feeding', $elderly->barthel_adl->Feeding) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การดูแลร่างกาย</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('grooming', $elderly->barthel_adl->Grooming) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การย้ายตัว</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('transfer', $elderly->barthel_adl->Transfer) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การใช้ห้องน้ำ</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('toilet_use', $elderly->barthel_adl->Toilet_use) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การเคลื่อนที่ภายในห้องหรือบ้าน</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('mobility', $elderly->barthel_adl->Mobility) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การสวมใส่เสื้อผ้า</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('dressing', $elderly->barthel_adl->Dressing) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การขึ้นลงบันได 1 ชั้น</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('stairs', $elderly->barthel_adl->Stairs) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การอาบน้ำ</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bathing', $elderly->barthel_adl->Bathing) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การกลั้นการถ่ายอุจจาระในระยะ 1 สัปดาห์ที่ผ่านมา</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bowels', $elderly->barthel_adl->Bowels) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>การกลั้นปัสสาวะในระยะ 1 สัปดาห์ที่ผ่านมา</th>
                                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bladder', $elderly->barthel_adl->Bladder) }}
                                    </td>
                                </tr>
                            </table>
                        @else
                            <p>ไม่พบข้อมูล ADL</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- CG Dates Modal -->
    @foreach ($elderlys as $elderly)
        <div class="modal fade" id="cgDatesModal-{{ $elderly->ID_Elderly }}" tabindex="-1"
            aria-labelledby="cgDatesModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cgDatesModalLabel-{{ $elderly->ID_Elderly }}">เลือกวันที่สำหรับข้อมูลรายงานผลการปฏิบัติงานผู้ดูแลคุณ {{ $elderly->Name_Elderly }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                                ->orderBy('Date_CG', 'desc')
                                ->get();
                        @endphp
                        @if ($caregivers->isEmpty())
                            <p>ไม่พบข้อมูล CG</p>
                        @else
                            @foreach ($caregivers as $caregiver)
                                <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#cgDetailsModal-{{ $caregiver->ID_CG }}">{{ $caregiver->Date_CG }}</button>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- CG Details Modal -->
    @foreach ($elderlys as $elderly)
        @php
            $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                ->orderBy('Date_CG', 'desc')
                ->get();
        @endphp
        @foreach ($caregivers as $caregiver)
            <div class="modal fade" id="cgDetailsModal-{{ $caregiver->ID_CG }}" tabindex="-1"
                aria-labelledby="cgDetailsModalLabel-{{ $caregiver->ID_CG }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cgDetailsModalLabel-{{ $caregiver->ID_CG }}">รายงานผลการปฏิบัติงานผู้ดูแลของคุณ
                                {{ $elderly->Name_Elderly }} ({{ $caregiver->Date_CG }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                                    <td>{{ $caregiver->Name_CG }}</td>
                                </tr>
                                <tr>
                                    <th>ชื่อผู้สูงอายุ</th>
                                    <td>{{ $caregiver->Name_Elderly }}</td>
                                </tr>
                                <tr>
                                    <th>อายุ</th>
                                    <td>{{ \Carbon\Carbon::parse($caregiver->Birthday)->age }} ปี</td>
                                </tr>
                                <tr>
                                    <th>ที่อยู่</th>
                                    <td>{{ $caregiver->Address }}</td>
                                </tr>
                                <tr>
                                    <th>น้ำหนักตัว</th>
                                    <td>{{ $caregiver->Weight }}</td>
                                </tr>
                                <tr>
                                    <th>ส่วนสูง</th>
                                    <td>{{ $caregiver->Height }}</td>
                                </tr>
                                <tr>
                                    <th>รอบเอว</th>
                                    <td>{{ $caregiver->Waist }}</td>
                                </tr>
                                <tr>
                                    <th>กลุ่ม ADL</th>
                                    <td>{{ $caregiver->Group_ADL }}</td>
                                </tr>
                                <tr>
                                    <th>โรคประจำตัว</th>
                                    <td>{{ $caregiver->Disease }}</td>
                                </tr>
                                <tr>
                                    <th>ความพิการ</th>
                                    <td>{{ $caregiver->Disability }}</td>
                                </tr>
                                <tr>
                                    <th>สิทธิการรักษา</th>
                                    <td>{{ $caregiver->Rights }}</td>
                                </tr>
                                <tr>
                                    <th>ชื่อผู้ดูแล</th>
                                    <td>{{ $caregiver->Caretaker }}</td>
                                </tr>
                                <tr>
                                    <th>เกี่ยวข้องเป็น</th>
                                    <td>{{ $caregiver->Related }}</td>
                                </tr>
                                <tr>
                                    <th>เบอร์ติดต่อ</th>
                                    <td>{{ $caregiver->Phone_Caretaker }}</td>
                                </tr>
                                <tr>
                                    <th>ความรู้สึกตัว</th>
                                    <td>{{ $caregiver->Consciousness }}</td>
                                </tr>
                                <tr>
                                    <th>สัญญาณชีพ</th>
                                    <td>{{ $caregiver->Vital_signs }}</td>
                                </tr>
                                <tr>
                                    <th>แผลกดทับ</th>
                                    <td>{{ $caregiver->Bedsores }}</td>
                                </tr>
                                <tr>
                                    <th>อาการปวด</th>
                                    <td>{{ $caregiver->Pain }}</td>
                                </tr>
                                <tr>
                                    <th>อาการบวม</th>
                                    <td>{{ $caregiver->Swelling }}</td>
                                </tr>
                                <tr>
                                    <th>ผื่นคัน</th>
                                    <td>{{ $caregiver->Itchy_rash }}</td>
                                </tr>
                                <tr>
                                    <th>ข้อติดแข็ง</th>
                                    <td>{{ $caregiver->Stiff_joints }}</td>
                                </tr>
                                <tr>
                                    <th>ทุพโภชนาการ</th>
                                    <td>{{ $caregiver->Malnutrition }}</td>
                                </tr>
                                <tr>
                                    <th>การรับประทานอาหาร</th>
                                    <td>{{ $caregiver->Eating }}</td>
                                </tr>
                                <tr>
                                    <th>การกลืน</th>
                                    <td>{{ $caregiver->Swallowing }}</td>
                                </tr>
                                <tr>
                                    <th>การขับถ่ายอุจจาระ</th>
                                    <td>{{ $caregiver->Defecation }}</td>
                                </tr>
                                <tr>
                                    <th>การขับถ่ายปัสสาวะ</th>
                                    <td>{{ $caregiver->Urinary_excretion }}</td>
                                </tr>
                                <tr>
                                    <th>การรับประทานยา</th>
                                    <td>{{ $caregiver->Taking_medicine }}</td>
                                </tr>
                                <tr>
                                    <th>สภาพอารมณ์</th>
                                    <td>{{ $caregiver->Emotional_state }}</td>
                                </tr>
                                <tr>
                                    <th>ปัญหาเศรษฐกิจ</th>
                                    <td>{{ $caregiver->Economic_problems }}</td>
                                </tr>
                                <tr>
                                    <th>ปัญหาสังคม</th>
                                    <td>{{ $caregiver->Social_problems }}</td>
                                </tr>
                                <tr>
                                    <th>แพทย์นัด F/U</th>
                                    <td>{{ $caregiver->Doctor_FU }}</td>
                                </tr>
                                <tr>
                                    <th>ปัญหาอื่น ๆ</th>
                                    <td>{{ $caregiver->Other_problems }}</td>
                                </tr>
                                <tr>
                                    <th>การช่วยเหลือ</th>
                                    <td>{{ $caregiver->Assistance }}</td>
                                </tr>
                                <tr>
                                    <th>ผู้รายงาน</th>
                                    <td>{{ $caregiver->Reporter }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <!-- ACG Dates Modal -->
    @foreach ($elderlys as $elderly)
        <div class="modal fade" id="acgDatesModal-{{ $elderly->ID_Elderly }}" tabindex="-1"
            aria-labelledby="acgDatesModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="acgDatesModalLabel-{{ $elderly->ID_Elderly }}">เลือกวันที่สำหรับข้อมูลรายงานผลการปฏิบัติงานผู้ดูแลคุณ {{ $elderly->Name_Elderly }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                                ->orderBy('Date_CG', 'desc')
                                ->get();
                        @endphp
                        @if ($caregivers->isEmpty())
                            <p>ไม่พบข้อมูล CG</p>
                        @else
                            @foreach ($caregivers as $caregiver)
                                <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#cgForACGModal-{{ $caregiver->ID_CG }}">{{ $caregiver->Date_CG }}</button>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- CG Date Selection Modal (Second Level) -->
    @foreach ($elderlys as $elderly)
        @php
            $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                ->orderBy('Date_CG', 'desc')
                ->get();
        @endphp
        @foreach ($caregivers as $caregiver)
            <div class="modal fade" id="cgForACGModal-{{ $caregiver->ID_CG }}" tabindex="-1"
                aria-labelledby="cgForACGModalLabel-{{ $caregiver->ID_CG }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cgForACGModalLabel-{{ $caregiver->ID_CG }}">เลือกวันที่สำหรับกิจกรรมที่ให้การช่วยเหลือของคุณ {{ $elderly->Name_Elderly }} ({{ $caregiver->Date_CG }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $activities = \App\Models\ActivityCaregiver::where('ID_CG', $caregiver->ID_CG)
                                    ->orderBy('Date_ACG', 'desc')
                                    ->get();
                            @endphp
                            @if ($activities->isEmpty())
                                <p>ไม่พบข้อมูล ACG</p>
                            @else
                                @foreach ($activities as $activity)
                                    <button class="btn btn-outline-info w-100 mb-2" data-bs-toggle="modal"
                                        data-bs-target="#acgDetailsModal-{{ $activity->ID_ACG }}">{{ $activity->Date_ACG }}</button>
                                @endforeach
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    <!-- ACG Details Modal (Third Level) -->
    @foreach ($elderlys as $elderly)
        @php
            $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)
                ->orderBy('Date_CG', 'desc')
                ->get();
        @endphp
        @foreach ($caregivers as $caregiver)
            @php
                $activities = \App\Models\ActivityCaregiver::where('ID_CG', $caregiver->ID_CG)
                    ->orderBy('Date_ACG', 'desc')
                    ->get();
            @endphp
            @foreach ($activities as $activity)
                <div class="modal fade" id="acgDetailsModal-{{ $activity->ID_ACG }}" tabindex="-1"
                    aria-labelledby="acgDetailsModalLabel-{{ $activity->ID_ACG }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="acgDetailsModalLabel-{{ $activity->ID_ACG }}">ข้อมูลกิจกรรมที่ให้การช่วยเหลือของคุณ {{ $elderly->Name_Elderly }} ({{ $activity->Date_ACG }})</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>วันที่ทำกิจกรรม</th>
                                        <td>{{ $activity->Date_ACG }}</td>
                                    </tr>
                                    <tr>
                                        <th>การประเมิน</th>
                                        <td>{{ $activity->Evaluate }}</td>
                                    </tr>
                                    <tr>
                                        <th>การแต่งแผล</th>
                                        <td>{{ $activity->Dress_the_wound }}</td>
                                    </tr>
                                    <tr>
                                        <th>การฟื้นฟู</th>
                                        <td>{{ $activity->Rehabilitate }}</td>
                                    </tr>
                                    <tr>
                                        <th>การทำความสะอาดร่างกาย</th>
                                        <td>{{ $activity->Clean_body }}</td>
                                    </tr>
                                    <tr>
                                        <th>การดูแลการใช้ยา</th>
                                        <td>{{ $activity->Take_care_medicine }}</td>
                                    </tr>
                                    <tr>
                                        <th>การดูแลการให้อาหาร</th>
                                        <td>{{ $activity->Take_care_feeding }}</td>
                                    </tr>
                                    <tr>
                                        <th>สภาพแวดล้อม</th>
                                        <td>{{ $activity->Environmental }}</td>
                                    </tr>
                                    <tr>
                                        <th>การออกกำลังกาย</th>
                                        <td>{{ $activity->Take_exercise }}</td>
                                    </tr>
                                    <tr>
                                        <th>ให้คำปรึกษาและแนะนำ</th>
                                        <td>{{ $activity->Give_advice_consult }}</td>
                                    </tr>
                                    <tr>
                                        <th>พาไปพบแพทย์</th>
                                        <td>{{ $activity->Take_to_see_a_doctor }}</td>
                                    </tr>
                                    <tr>
                                        <th>กิจกรรมอื่นๆ</th>
                                        <td>{{ $activity->Other }}</td>
                                    </tr>
                                    <tr>
                                        <th>พาไปทำบุญ</th>
                                        <td>{{ $activity->Take_to_make_merit }}</td>
                                    </tr>
                                    <tr>
                                        <th>พาไปตลาด</th>
                                        <td>{{ $activity->Take_to_market }}</td>
                                    </tr>
                                    <tr>
                                        <th>พาไปพบเพื่อน</th>
                                        <td>{{ $activity->Take_to_meet_friends }}</td>
                                    </tr>
                                    <tr>
                                        <th>พาไปเบิกเบี้ยยังชีพ</th>
                                        <td>{{ $activity->Take_to_allowance }}</td>
                                    </tr>
                                    <tr>
                                        <th>พูดคุยเป็นเพื่อน</th>
                                        <td>{{ $activity->Talk_as_friends }}</td>
                                    </tr>
                                    <tr>
                                        <th>กิจกรรมสังคมอื่นๆ</th>
                                        <td>{{ $activity->Other_specified }}</td>
                                    </tr>
                                    <tr>
                                        <th>ปัญหาที่พบ</th>
                                        <td>{{ $activity->Problem }}</td>
                                    </tr>
                                    <tr>
                                        <th>วิธีการแก้ไข</th>
                                        <td>{{ $activity->Troubleshoot }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#doctorTable').DataTable({
                "language": {
                    "paginate": {
                        "previous": "ก่อนหน้า",
                        "next": "ถัดไป"
                    },
                    "search": "ค้นหา : ",
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "กำลังแสดงรายการ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                    "infoEmpty": "ไม่พบข้อมูล",
                    "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)"
                },
                "dom": '<"row"<"col-sm-12 col-md-12"l><"col-sm-12 col-md-12"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-3 d-flex justify-content-center"p>>'
            });
        });

    </script>
</body>

</html>
