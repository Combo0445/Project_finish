@props(['elderlys'])

<style>
    .modal {
        z-index: 10000 !important;
        overflow-y: auto !important;
    }
    .modal-backdrop {
        z-index: 9999 !important;
    }
    .modal-content {
        box-shadow: 0 15px 35px rgba(50,50,93,.2), 0 5px 15px rgba(0,0,0,.17) !important;
        border: 1px solid rgba(0,0,0,0.1) !important;
        background-color: #fff !important;
    }
    .modal-header .close {
        padding: 1rem;
        margin: -1rem -1rem -1rem auto;
    }
    .modal-title {
        width: 90%;
        line-height: 1.5;
        font-weight: 700;
        color: #32325d;
    }
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
    }
    /* Fix text overflow in tables */
    .table td, .table th {
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-all;
        vertical-align: middle !important;
    }
</style>

<!-- ADL Modal -->
@foreach ($elderlys as $elderly)
    <div class="modal fade" id="adlModal-{{ $elderly->ID_Elderly }}" tabindex="-1" aria-labelledby="adlModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adlModalLabel-{{ $elderly->ID_Elderly }}">ข้อมูลประเมินความสามารถในการดำเนินกิจวัตรประจำวันของคุณ {{ $elderly->Name_Elderly }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($elderly->barthel_adl)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                @php
                                    $adlFields = [
                                        'Feeding' => 'การรับประทานอาหาร',
                                        'Grooming' => 'การดูแลร่างกาย',
                                        'Transfer' => 'การย้ายตัว',
                                        'Toilet_use' => 'การใช้ห้องน้ำ',
                                        'Mobility' => 'การเคลื่อนที่ภายในห้องหรือบ้าน',
                                        'Dressing' => 'การสวมใส่เสื้อผ้า',
                                        'Stairs' => 'การขึ้นลงบันได 1 ชั้น',
                                        'Bathing' => 'การอาบน้ำ',
                                        'Bowels' => 'การกลั้นการถ่ายอุจจาระในระยะ 1 สัปดาห์ที่ผ่านมา',
                                        'Bladder' => 'การกลั้นปัสสาวะในระยะ 1 สัปดาห์ที่ผ่านมา',
                                    ];
                                @endphp
                                @foreach ($adlFields as $field => $label)
                                    <tr>
                                        <th>{{ $label }}</th>
                                        <td>{{ \App\Models\BarthelAdl::getAdlDescription(strtolower($field), $elderly->barthel_adl->$field) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <p>ไม่พบข้อมูล ADL</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- CG Dates Modal -->
@foreach ($elderlys as $elderly)
    <div class="modal fade" id="cgDatesModal-{{ $elderly->ID_Elderly }}" tabindex="-1" aria-labelledby="cgDatesModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cgDatesModalLabel-{{ $elderly->ID_Elderly }}">เลือกวันที่สำหรับข้อมูลรายงานผลการปฏิบัติงานผู้ดูแลคุณ {{ $elderly->Name_Elderly }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->orderBy('Date_CG', 'desc')->get();
                    @endphp
                    @forelse ($caregivers as $caregiver)
                        <button class="btn btn-outline-primary w-100 mb-2" data-toggle="modal" data-target="#cgDetailsModal-{{ $caregiver->ID_CG }}">{{ $caregiver->Date_CG }}</button>
                    @empty
                        <p>ไม่พบข้อมูล CG</p>
                    @endforelse
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- CG Details Modal -->
@foreach ($elderlys as $elderly)
    @php
        $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->orderBy('Date_CG', 'desc')->get();
    @endphp
    @foreach ($caregivers as $caregiver)
        <div class="modal fade" id="cgDetailsModal-{{ $caregiver->ID_CG }}" tabindex="-1" aria-labelledby="cgDetailsModalLabel-{{ $caregiver->ID_CG }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cgDetailsModalLabel-{{ $caregiver->ID_CG }}">รายงานผลการปฏิบัติงานผู้ดูแลของคุณ {{ $elderly->Name_Elderly }} ({{ $caregiver->Date_CG }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                @php
                                    $cgFields = [
                                        'Name_CG' => 'ชื่อผู้ดูแลผู้สูงอายุ',
                                        'Name_Elderly' => 'ชื่อผู้สูงอายุ',
                                        'Birthday' => 'อายุ',
                                        'Address' => 'ที่อยู่',
                                        'Weight' => 'น้ำหนักตัว',
                                        'Height' => 'ส่วนสูง',
                                        'Waist' => 'รอบเอว',
                                        'Group_ADL' => 'กลุ่ม ADL',
                                        'Disease' => 'โรคประจำตัว',
                                        'Disability' => 'ความพิการ',
                                        'Rights' => 'สิทธิการรักษา',
                                        'Caretaker' => 'ชื่อผู้ดูแล',
                                        'Related' => 'เกี่ยวข้องเป็น',
                                        'Phone_Caretaker' => 'เบอร์ติดต่อ',
                                        'Consciousness' => 'ความรู้สึกตัว',
                                        'Vital_signs' => 'สัญญาณชีพ',
                                        'Bedsores' => 'แผลกดทับ',
                                        'Pain' => 'อาการปวด',
                                        'Swelling' => 'อาการบวม',
                                        'Itchy_rash' => 'ผื่นคัน',
                                        'Stiff_joints' => 'ข้อติดแข็ง',
                                        'Malnutrition' => 'ทุพโภชนาการ',
                                        'Eating' => 'การรับประทานอาหาร',
                                        'Swallowing' => 'การกลืน',
                                        'Defecation' => 'การขับถ่ายอุจจาระ',
                                        'Urinary_excretion' => 'การขับถ่ายปัสสาวะ',
                                        'Taking_medicine' => 'การรับประทานยา',
                                        'Emotional_state' => 'สภาพอารมณ์',
                                        'Economic_problems' => 'ปัญหาเศรษฐกิจ',
                                        'Social_problems' => 'ปัญหาสังคม',
                                        'Doctor_FU' => 'แพทย์นัด F/U',
                                        'Other_problems' => 'ปัญหาอื่น ๆ',
                                        'Assistance' => 'การช่วยเหลือ',
                                        'Reporter' => 'ผู้รายงาน',
                                    ];
                                @endphp
                                @foreach ($cgFields as $field => $label)
                                    <tr>
                                        <th>{{ $label }}</th>
                                        <td>
                                            @if($field === 'Birthday')
                                                {{ \Carbon\Carbon::parse($caregiver->$field)->age }} ปี
                                            @else
                                                {{ $caregiver->$field }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<!-- ACG Dates Modal -->
@foreach ($elderlys as $elderly)
    <div class="modal fade" id="acgDatesModal-{{ $elderly->ID_Elderly }}" tabindex="-1" aria-labelledby="acgDatesModalLabel-{{ $elderly->ID_Elderly }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acgDatesModalLabel-{{ $elderly->ID_Elderly }}">เลือกวันที่สำหรับข้อมูลรายงานผลการปฏิบัติงานผู้ดูแลคุณ {{ $elderly->Name_Elderly }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->orderBy('Date_CG', 'desc')->get();
                    @endphp
                    @forelse ($caregivers as $caregiver)
                        <button class="btn btn-outline-primary w-100 mb-2" data-toggle="modal" data-target="#cgForACGModal-{{ $caregiver->ID_CG }}">{{ $caregiver->Date_CG }}</button>
                    @empty
                        <p>ไม่พบข้อมูล CG</p>
                    @endforelse
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- ACG Selection Modals and Details Modals... (omitted for brevity but should be included in full refactor) -->
@foreach ($elderlys as $elderly)
    @php
        $caregivers = \App\Models\CareGiver::where('ID_Elderly', $elderly->ID_Elderly)->orderBy('Date_CG', 'desc')->get();
    @endphp
    @foreach ($caregivers as $caregiver)
        <div class="modal fade" id="cgForACGModal-{{ $caregiver->ID_CG }}" tabindex="-1" aria-labelledby="cgForACGModalLabel-{{ $caregiver->ID_CG }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cgForACGModalLabel-{{ $caregiver->ID_CG }}">เลือกวันที่สำหรับกิจกรรมที่ให้การช่วยเหลือของคุณ {{ $elderly->Name_Elderly }} ({{ $caregiver->Date_CG }})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $activities = \App\Models\ActivityCaregiver::where('ID_CG', $caregiver->ID_CG)->orderBy('Date_ACG', 'desc')->get();
                        @endphp
                        @forelse ($activities as $activity)
                            <button class="btn btn-outline-info w-100 mb-2" data-toggle="modal" data-target="#acgDetailsModal-{{ $activity->ID_ACG }}">{{ $activity->Date_ACG }}</button>
                        @empty
                            <p>ไม่พบข้อมูล ACG</p>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $activities = \App\Models\ActivityCaregiver::where('ID_CG', $caregiver->ID_CG)->orderBy('Date_ACG', 'desc')->get();
        @endphp
        @foreach ($activities as $activity)
            <div class="modal fade" id="acgDetailsModal-{{ $activity->ID_ACG }}" tabindex="-1" aria-labelledby="acgDetailsModalLabel-{{ $activity->ID_ACG }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="acgDetailsModalLabel-{{ $activity->ID_ACG }}">ข้อมูลกิจกรรมที่ให้การช่วยเหลือของคุณ {{ $elderly->Name_Elderly }} ({{ $activity->Date_ACG }})</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    @php
                                        $acgFields = [
                                            'Date_ACG' => 'วันที่ทำกิจกรรม',
                                            'Evaluate' => 'การประเมิน',
                                            'Dress_the_wound' => 'การแต่งแผล',
                                            'Rehabilitate' => 'การฟื้นฟู',
                                            'Clean_body' => 'การทำความสะอาดร่างกาย',
                                            'Take_care_medicine' => 'การดูแลการใช้ยา',
                                            'Take_care_feeding' => 'การดูแลการให้อาหาร',
                                            'Environmental' => 'สภาพแวดล้อม',
                                            'Take_exercise' => 'การออกกำลังกาย',
                                            'Give_advice_consult' => 'ให้คำปรึกษาและแนะนำ',
                                            'Take_to_see_a_doctor' => 'พาไปพบแพทย์',
                                            'Other' => 'กิจกรรมอื่นๆ',
                                            'Take_to_make_merit' => 'พาไปทำบุญ',
                                            'Take_to_market' => 'พาไปตลาด',
                                            'Take_to_meet_friends' => 'พาไปพบเพื่อน',
                                            'Take_to_allowance' => 'พาไปเบิกเบี้ยยังชีพ',
                                            'Talk_as_friends' => 'พูดคุยเป็นเพื่อน',
                                            'Other_specified' => 'กิจกรรมสังคมอื่นๆ',
                                            'Problem' => 'ปัญหาที่พบ',
                                            'Troubleshoot' => 'วิธีการแก้ไข',
                                        ];
                                    @endphp
                                    @foreach ($acgFields as $field => $label)
                                        <tr>
                                            <th>{{ $label }}</th>
                                            <td>{{ $activity->$field }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endforeach
