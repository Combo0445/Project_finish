@extends('layouts.app')

@section('title', 'Add Care Giver')

@push('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .assessment-container {
            max-width: 900px;
            margin: auto;
            margin-top: 20px;
        }

        h4 {
            text-align: center;
            color: #333;
        }

        form div.form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
            /* Make inputs easier to tap */
        }

        .question-block {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #355e3b;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .question-block h5 {
            color: #355e3b;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        /* Wizard UI Styles */
        .wizard-step {
            display: none;
        }

        .wizard-step.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            color: #6c757d;
            transition: all 0.3s;
        }

        .step-dot.active {
            background: #355e3b;
            color: white;
            transform: scale(1.1);
        }

        .step-dot.completed {
            background: #28a745;
            color: white;
        }

        .hidden {
            display: none;
        }
    </style>
    <script>
        // แยกค่าสัญญาณชีพจากข้อความเดิม (เช่น "BP 120/80 - PR 80 - RR 20 - BT 36.5") มาใส่ในช่อง input
        function populateVitalSignsFromText(vitalSignsText) {
            const bpMatch = vitalSignsText.match(/BP (\d+)\/(\d+)/);
            const prMatch = vitalSignsText.match(/PR (\d+)/);
            const rrMatch = vitalSignsText.match(/RR (\d+)/);
            const btMatch = vitalSignsText.match(/BT ([\d.]+)/);

            if (bpMatch) {
                document.getElementById('BP_systolic').value = bpMatch[1];
                document.getElementById('BP_diastolic').value = bpMatch[2];
            }
            if (prMatch) document.getElementById('PR').value = prMatch[1];
            if (rrMatch) document.getElementById('RR').value = rrMatch[1];
            if (btMatch) document.getElementById('BT').value = btMatch[1];
        }

        // เก็บผลลัพธ์ล่าสุดที่ดึงมาไว้ ให้ปุ่ม "ดึงข้อมูลครั้งล่าสุด" ใช้ได้โดยไม่ต้องยิง
        // request ซ้ำ — ไม่เติมใส่ในช่องน้ำหนัก/ส่วนสูง/ความดันให้อัตโนมัติ เพราะบางวัน
        // เจ้าหน้าที่อาจวัดค่าจริงมาแล้วและต้องการกรอกเองบางช่อง
        let latestElderlyDetails = null;

        function fetchElderlyDetails() {
            var elderlyId = document.getElementById('ID_Elderly').value;
            if (elderlyId) {
                fetch(`{{ route('get-elderly-details', ':elderlyId') }}`.replace(':elderlyId', elderlyId))
                    .then(response => response.json())
                    .then(data => {
                        latestElderlyDetails = data;
                        document.getElementById('Age').value = data.Age;
                        document.getElementById('Address').value = data.Address;
                        document.getElementById('Group_ADL').value = data.Group_ADL;
                        document.getElementById('ID_ADL').value = elderlyId;
                        document.getElementById('Name_Elderly').value = document.getElementById('ID_Elderly').options[
                            document.getElementById('ID_Elderly').selectedIndex].text;
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // เรียกเมื่อกดปุ่ม "ดึงข้อมูลครั้งล่าสุด" เท่านั้น เติมน้ำหนัก/ส่วนสูง/รอบเอว/ความดัน
        // จากการประเมินครั้งก่อนหน้าของผู้สูงอายุคนนี้
        function fillLatestVitals() {
            if (!document.getElementById('ID_Elderly').value) {
                alert('กรุณาเลือกผู้สูงอายุก่อน');
                return;
            }
            if (!latestElderlyDetails || !latestElderlyDetails.Latest) {
                alert('ไม่พบข้อมูลการประเมินครั้งก่อนหน้าของผู้สูงอายุคนนี้');
                return;
            }
            const latest = latestElderlyDetails.Latest;
            if (latest.Weight) document.getElementById('Weight').value = latest.Weight;
            if (latest.Height) document.getElementById('Height').value = latest.Height;
            if (latest.Waist) document.getElementById('Waist').value = latest.Waist;
            if (latest.Vital_signs) populateVitalSignsFromText(latest.Vital_signs);
        }
    </script>
@endpush

@section('content')
    <div class="assessment-container px-3">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">เพิ่มรายงานผลการปฏิบัติงานผู้ดูแลผู้สูงอายุ (CG)</h4>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success text-white">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger text-white">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Wizard Step Indicators -->
                    <div class="step-indicator">
                        <div class="step-dot active" id="dot-1">1</div>
                        <div class="step-dot" id="dot-2">2</div>
                        <div class="step-dot" id="dot-3">3</div>
                    </div>

                    @php
                        // ก่อน submit ฝั่ง JS จะรวมค่า select ("มี"/"ไม่มี") กับรายละเอียดเป็นสตริงเดียว
                        // เช่น "มี (แผลที่ส้นเท้า)" แล้วส่งเป็นค่าของ select นั้นเอง เมื่อ redisplay
                        // เพราะ validate ไม่ผ่านที่ช่องอื่น จึงต้องแยกกลับมาเป็น select + รายละเอียด
                        // เพื่อคืนค่าที่กรอกไว้แล้ว ไม่ให้ต้องกรอกใหม่ทั้งหมด
                        $toggleFields = [
                            'Bedsores', 'Pain', 'Swelling', 'Itchy_rash',
                            'Stiff_joints', 'Malnutrition',
                            'Economic_problems', 'Social_problems', 'Doctor_FU',
                        ];
                        $oldToggle = [];
                        foreach ($toggleFields as $tf) {
                            $raw = old($tf);
                            $detail = '';
                            if ($raw && preg_match('/^มี \((.*)\)$/u', $raw, $m)) {
                                $detail = $m[1];
                            }
                            $oldToggle[$tf] = ['hasValue' => $raw !== null, 'isYes' => $raw && $raw !== 'ไม่มี', 'detail' => $detail];
                        }
                    @endphp

                    <!-- SINGLE FORM FOR EVERYTHING -->
                    <form id="assessment-form" action="{{ route('cg.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- STEP 1: Care Giver & Elderly Data -->
                        <div class="wizard-step active" id="step-1">
                            <h5 class="text-center text-primary mb-4">ส่วนที่ 1: ข้อมูลผู้สูงอายุและผู้ดูแล</h5>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Name_CG">ชื่อผู้ดูแลผู้สูงอายุ</label>
                                    <input type="text" id="Name_CG" name="Name_CG" class="form-control form-control-lg"
                                        required value="{{ old('Name_CG') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Related">เกี่ยวข้องเป็น</label>
                                    <input type="text" id="Related" name="Related" class="form-control form-control-lg"
                                        required value="{{ old('Related') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Phone_CG">เบอร์ติดต่อ</label>
                                    <input type="number" id="Phone_CG" name="Phone_CG"
                                        class="form-control form-control-lg" required value="{{ old('Phone_CG') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ID_Elderly">ชื่อ-สกุลผู้สูงอายุ</label>
                                    <select id="ID_Elderly" name="ID_Elderly" class="form-control form-control-lg"
                                        onchange="fetchElderlyDetails()" required>
                                        <option value="">เลือกผู้สูงอายุ</option>
                                        @foreach ($elderlys as $elderly)
                                            <option value="{{ $elderly->ID_ADL }}"
                                                {{ old('ID_Elderly', $selected_elderly_id ?? null) == $elderly->ID_ADL ? 'selected' : '' }}>
                                                {{ $elderly->elderly->Name_Elderly }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(old('ID_Elderly', $selected_elderly_id ?? null))
                                        <script>
                                            window.onload = function() {
                                                fetchElderlyDetails();
                                            };
                                        </script>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Age">อายุ</label>
                                    <input type="number" id="Age" name="Age"
                                        class="form-control form-control-lg bg-light" required readonly>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label for="Address">ที่อยู่</label>
                                    <input type="text" id="Address" name="Address"
                                        class="form-control form-control-lg bg-light" required readonly>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillLatestVitals()">
                                    <i class="fas fa-history me-1"></i> ดึงข้อมูลครั้งล่าสุด (น้ำหนัก/ส่วนสูง/รอบเอว/ความดัน)
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Weight">น้ำหนักตัว (กก.)</label>
                                    <input type="number" id="Weight" name="Weight" class="form-control form-control-lg"
                                        step="0.1" required value="{{ old('Weight') }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Height">ส่วนสูง (ซม.)</label>
                                    <input type="number" id="Height" name="Height" class="form-control form-control-lg"
                                        step="0.1" required value="{{ old('Height') }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Waist">รอบเอว (ซม.)</label>
                                    <input type="number" id="Waist" name="Waist" class="form-control form-control-lg"
                                        step="0.1" required value="{{ old('Waist') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Group_ADL">กลุ่มประเภทผู้สูงอายุ</label>
                                    <input type="text" id="Group_ADL" name="Group_ADL"
                                        class="form-control form-control-lg bg-light" readonly required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Rights">สิทธิการรักษา</label>
                                    <select id="Rights" name="Rights" class="form-control form-control-lg" required>
                                        <option value="" disabled {{ old('Rights') ? '' : 'selected' }}>-- เลือกสิทธิการรักษา --</option>
                                        <option value="สิทธิข้าราชการ" {{ old('Rights') == 'สิทธิข้าราชการ' ? 'selected' : '' }}>สิทธิข้าราชการ</option>
                                        <option value="บัตรผู้พิการ" {{ old('Rights') == 'บัตรผู้พิการ' ? 'selected' : '' }}>บัตรผู้พิการ</option>
                                        <option value="บัตรทอง" {{ old('Rights') == 'บัตรทอง' ? 'selected' : '' }}>บัตรทอง</option>
                                        <option value="ประกันสุขภาพ" {{ old('Rights') == 'ประกันสุขภาพ' ? 'selected' : '' }}>ประกันสุขภาพ</option>
                                        <option value="อปท." {{ old('Rights') == 'อปท.' ? 'selected' : '' }}>อปท.</option>
                                        <option value="ผู้สูงอายุ" {{ old('Rights') == 'ผู้สูงอายุ' ? 'selected' : '' }}>ผู้สูงอายุ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Disease">โรคประจำตัว</label>
                                    <input type="text" id="Disease" name="Disease" class="form-control form-control-lg" value="{{ old('Disease') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Disability">ความพิการ</label>
                                    <input type="text" id="Disability" name="Disability"
                                        class="form-control form-control-lg" value="{{ old('Disability') }}">
                                </div>
                            </div>

                            <input type="hidden" id="ID_ADL" name="ID_ADL">
                            <input type="hidden" id="Name_Elderly" name="Name_Elderly">
                        </div>

                        <!-- STEP 2: Vital Signs & Conditions -->
                        <div class="wizard-step" id="step-2">
                            <h5 class="text-center text-primary mb-4">ส่วนที่ 2: สัญญาณชีพและอาการเบื้องต้น</h5>

                            <div class="question-block">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="Date">ลงเวลารายงานผลการปฏิบัติงาน</label>
                                        <input type="date" id="Date" name="Date" class="form-control form-control-lg"
                                            required value="{{ old('Date') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="Consciousness">ความรู้สึกตัว</label>
                                        <select id="Consciousness" name="Consciousness"
                                            class="form-control form-control-lg" required>
                                            <option value="รู้สึกดี" {{ old('Consciousness', 'รู้สึกดี') == 'รู้สึกดี' ? 'selected' : '' }}>รู้สึกดี</option>
                                            <option value="ไม่รู้สึกตัว" {{ old('Consciousness') == 'ไม่รู้สึกตัว' ? 'selected' : '' }}>ไม่รู้สึกตัว</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="question-block">
                                <h5>สัญญาณชีพ (Vital Signs)</h5>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <span>BP ก่อน</span>
                                        <input type="number" id="BP_systolic"
                                            class="form-control form-control-lg text-center" placeholder="120"
                                            style="width: 100px;">
                                    </div>

                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <span>BP หลัง</span>
                                        <input type="number" id="BP_diastolic"
                                            class="form-control form-control-lg text-center" placeholder="80"
                                            style="width: 100px;">
                                    </div>

                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <span>PR</span>
                                        <input type="number" id="PR" class="form-control form-control-lg text-center"
                                            placeholder="80" style="width: 100px;">
                                    </div>

                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <span>RR</span>
                                        <input type="number" id="RR" class="form-control form-control-lg text-center"
                                            placeholder="20" style="width: 100px;">
                                    </div>

                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <span>BT (°C)</span>
                                        <input type="number" id="BT" class="form-control form-control-lg text-center"
                                            placeholder="36.5" step="0.01" style="width: 100px;">
                                    </div>
                                </div>
                                <input type="hidden" id="Vital_signs" name="Vital_signs">
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Bedsores">แผลกดทับ</label>
                                    <select id="Bedsores" name="Bedsores" class="form-control form-control-lg" required>
                                        <option value="ไม่มี" {{ !$oldToggle['Bedsores']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Bedsores']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Bedsores_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Bedsores']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Pain">อาการปวด</label>
                                    <select id="Pain" name="Pain" class="form-control form-control-lg" required>
                                        <option value="ไม่มี" {{ !$oldToggle['Pain']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Pain']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Pain_details" class="form-control mt-2"
                                        placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Pain']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Swelling">อาการบวม</label>
                                    <select id="Swelling" name="Swelling" class="form-control form-control-lg" required>
                                        <option value="ไม่มี" {{ !$oldToggle['Swelling']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Swelling']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Swelling_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Swelling']['detail'] }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Itchy_rash">ผื่นคัน</label>
                                    <select id="Itchy_rash" name="Itchy_rash" class="form-control form-control-lg"
                                        required>
                                        <option value="ไม่มี" {{ !$oldToggle['Itchy_rash']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Itchy_rash']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Itchy_rash_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Itchy_rash']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Stiff_joints">ข้อติดแข็ง</label>
                                    <select id="Stiff_joints" name="Stiff_joints" class="form-control form-control-lg"
                                        required>
                                        <option value="ไม่มี" {{ !$oldToggle['Stiff_joints']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Stiff_joints']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Stiff_joints_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Stiff_joints']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Malnutrition">ทุพโภชนาการ</label>
                                    <select id="Malnutrition" name="Malnutrition" class="form-control form-control-lg"
                                        required>
                                        <option value="ไม่มี" {{ !$oldToggle['Malnutrition']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Malnutrition']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Malnutrition_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Malnutrition']['detail'] }}">
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: Assessment & Problems (Continued) -->
                        <div class="wizard-step" id="step-3">
                            <h5 class="text-center text-primary mb-4">ส่วนที่ 3: การประเมินและปัญหาอื่นๆ</h5>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Eating">การรับประทานอาหาร</label>
                                    <select id="Eating" name="Eating" class="form-control form-control-lg" required>
                                        <option value="ตักกินเองได้" {{ old('Eating', 'ตักกินเองได้') == 'ตักกินเองได้' ? 'selected' : '' }}>ตักกินเองได้</option>
                                        <option value="กินเองไม่ได้" {{ old('Eating') == 'กินเองไม่ได้' ? 'selected' : '' }}>กินเองไม่ได้</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Swallowing">การกลืน</label>
                                    <select id="Swallowing" name="Swallowing" class="form-control form-control-lg"
                                        required>
                                        <option value="กลืนได้ปกติ" {{ old('Swallowing', 'กลืนได้ปกติ') == 'กลืนได้ปกติ' ? 'selected' : '' }}>กลืนได้ปกติ</option>
                                        <option value="สำลัก" {{ old('Swallowing') == 'สำลัก' ? 'selected' : '' }}>สำลัก</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Defecation">การขับถ่ายอุจจาระ</label>
                                    <select id="Defecation" name="Defecation" class="form-control form-control-lg"
                                        required>
                                        <option value="กลั้นได้" {{ old('Defecation', 'กลั้นได้') == 'กลั้นได้' ? 'selected' : '' }}>กลั้นได้</option>
                                        <option value="กลั้นไม่ได้" {{ old('Defecation') == 'กลั้นไม่ได้' ? 'selected' : '' }}>กลั้นไม่ได้</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Urinary_excretion">การขับถ่ายปัสสาวะ</label>
                                    <select id="Urinary_excretion" name="Urinary_excretion"
                                        class="form-control form-control-lg" required>
                                        <option value="กลั้นได้" {{ old('Urinary_excretion', 'กลั้นได้') == 'กลั้นได้' ? 'selected' : '' }}>กลั้นได้</option>
                                        <option value="กลั้นไม่ได้" {{ old('Urinary_excretion') == 'กลั้นไม่ได้' ? 'selected' : '' }}>กลั้นไม่ได้</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="Taking_medicine">การรับประทานยา</label>
                                    <select id="Taking_medicine" name="Taking_medicine"
                                        class="form-control form-control-lg" required>
                                        <option value="กินสม่ำเสมอ" {{ old('Taking_medicine', 'กินสม่ำเสมอ') == 'กินสม่ำเสมอ' ? 'selected' : '' }}>กินสม่ำเสมอ</option>
                                        <option value="ขาดยา" {{ old('Taking_medicine') == 'ขาดยา' ? 'selected' : '' }}>ขาดยา</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Emotional_state">สภาพอารมณ์</label>
                                    <select id="Emotional_state" name="Emotional_state"
                                        class="form-control form-control-lg" required>
                                        <option value="ปกติ" {{ old('Emotional_state', 'ปกติ') == 'ปกติ' ? 'selected' : '' }}>ปกติ</option>
                                        <option value="ผิดปกติ" {{ old('Emotional_state') == 'ผิดปกติ' ? 'selected' : '' }}>ผิดปกติ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="Economic_problems">ปัญหาเศรษฐกิจ</label>
                                    <select id="Economic_problems" name="Economic_problems"
                                        class="form-control form-control-lg" required>
                                        <option value="ไม่มี" {{ !$oldToggle['Economic_problems']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Economic_problems']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Economic_problems_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Economic_problems']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Social_problems">ปัญหาสังคม</label>
                                    <select id="Social_problems" name="Social_problems"
                                        class="form-control form-control-lg" required>
                                        <option value="ไม่มี" {{ !$oldToggle['Social_problems']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Social_problems']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Social_problems_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Social_problems']['detail'] }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="Doctor_FU">แพทย์นัด F/U</label>
                                    <select id="Doctor_FU" name="Doctor_FU" class="form-control form-control-lg"
                                        required>
                                        <option value="ไม่มี" {{ !$oldToggle['Doctor_FU']['isYes'] ? 'selected' : '' }}>ไม่มี</option>
                                        <option value="มี" {{ $oldToggle['Doctor_FU']['isYes'] ? 'selected' : '' }}>มี</option>
                                    </select>
                                    <input style="display:none;" type="text" id="Doctor_FU_details"
                                        class="form-control mt-2" placeholder="รายละเอียดถ้ามี" value="{{ $oldToggle['Doctor_FU']['detail'] }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 form-group">
                                    <label for="Other_problems">ปัญหาอื่น ๆ</label>
                                    <input type="text" id="Other_problems" name="Other_problems"
                                        class="form-control form-control-lg" value="{{ old('Other_problems') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="Assistance">การช่วยเหลือ</label>
                                    <input type="text" id="Assistance" name="Assistance"
                                        class="form-control form-control-lg" value="{{ old('Assistance') }}">
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="Picture">อัปโหลดรูปภาพ (ไม่เกิน 4 รูป)</label>
                                <input type="file" name="Picture[]" id="Picture" class="form-control form-control-lg"
                                    multiple accept="image/*" onchange="validateAndPreviewImages(this)">
                                <small class="form-text text-muted">สามารถอัปโหลดได้สูงสุด 4 รูป</small>
                                <div id="imagePreview"
                                    style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="Reporter">ผู้รายงาน</label>
                                <input type="text" id="Reporter" name="Reporter"
                                    class="form-control form-control-lg bg-light" value="{{ Auth::user()->Name_User }}"
                                    readonly required>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <button type="button" class="btn btn-secondary btn-lg" id="btn-prev" style="display: none;">
                                <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                            </button>

                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('cg.index') }}" class="btn btn-outline-danger btn-lg"
                                    id="btn-cancel">ยกเลิก</a>
                                <button type="button" class="btn btn-primary btn-lg" id="btn-next">
                                    ถัดไป <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="btn-submit"
                                    style="display: none;">
                                    <i class="fas fa-save me-2"></i> บันทึกรายงาน
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Wizard step navigation (previously missing entirely — the form had no way
        // to reach step 2/3 or reveal the submit button, so it could not be saved at all)
        document.addEventListener('DOMContentLoaded', function () {
            let currentStep = 1;
            const totalSteps = 3;

            const updateUI = () => {
                document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
                document.getElementById(`step-${currentStep}`).classList.add('active');

                document.querySelectorAll('.step-dot').forEach((el, index) => {
                    if (index + 1 < currentStep) {
                        el.classList.add('completed');
                        el.classList.remove('active');
                    } else if (index + 1 === currentStep) {
                        el.classList.add('active');
                        el.classList.remove('completed');
                    } else {
                        el.classList.remove('active', 'completed');
                    }
                });

                document.getElementById('btn-prev').style.display = currentStep > 1 ? 'block' : 'none';

                if (currentStep === totalSteps) {
                    document.getElementById('btn-next').style.display = 'none';
                    document.getElementById('btn-submit').style.display = 'block';
                } else {
                    document.getElementById('btn-next').style.display = 'block';
                    document.getElementById('btn-submit').style.display = 'none';
                }
            };

            const validateStep = (step) => {
                const stepEl = document.getElementById(`step-${step}`);
                const invalid = stepEl.querySelector(':invalid');
                if (invalid) {
                    invalid.reportValidity();
                    return false;
                }
                return true;
            };

            // จุดข้ามระหว่างช่วง: ตรวจทุกขั้นตอน (ไม่ใช่แค่ขั้นที่กำลังแสดงอยู่) แล้วเด้ง
            // ไปยังช่องแรกที่ยังไม่ได้กรอก — ฟิลด์ required ที่ถูกซ่อนด้วย display:none จะไม่ถูก
            // ตรวจสอบโดยเบราว์เซอร์ตอน submit ตามปกติ จึงต้องเช็คเองก่อนปล่อยให้ฟอร์ม submit จริง
            const jumpToFirstInvalid = () => {
                for (let s = 1; s <= totalSteps; s++) {
                    const invalid = document.getElementById(`step-${s}`).querySelector(':invalid');
                    if (invalid) {
                        currentStep = s;
                        updateUI();
                        window.scrollTo(0, 0);
                        invalid.reportValidity();
                        return true;
                    }
                }
                return false;
            };

            document.getElementById('btn-next').addEventListener('click', () => {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    currentStep++;
                    updateUI();
                    window.scrollTo(0, 0);
                }
            });

            document.getElementById('btn-prev').addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                    window.scrollTo(0, 0);
                }
            });

            @if ($errors->any())
                // Redisplayed after a server-side validation error: restore the vital-signs
                // fields too (they're plain JS-only inputs, not bound via old() like the rest),
                // then jump to the first step/field that still needs data, same as a blocked
                // client-side submit
                @if (old('Vital_signs'))
                    populateVitalSignsFromText(@json(old('Vital_signs')));
                @endif
                jumpToFirstInvalid();
            @endif

            updateUI();

            // ตัวกันสุดท้ายก่อน submit จริง: ต้องลงทะเบียนก่อน listener อื่น ๆ ของฟอร์มนี้
            // (ที่จะ disable ปุ่มและรวมค่าสัญญาณชีพ) จึง stopImmediatePropagation เพื่อกันไม่ให้
            // listener เหล่านั้นทำงานเมื่อข้อมูลยังไม่ครบ
            document.getElementById('assessment-form').addEventListener('submit', function (event) {
                if (jumpToFirstInvalid()) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            });
        });
    </script>

    <script>
        function validateAndPreviewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = ''; // ล้างรูปเดิมก่อน

            if (input.files.length > 4) {
                alert('คุณสามารถอัปโหลดรูปภาพได้ไม่เกิน 4 รูป');
                input.value = ''; // รีเซ็ต input
                return;
            }

            Array.from(input.files).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '120px';
                    img.style.maxHeight = '120px';
                    img.style.objectFit = 'cover';
                    img.style.border = '1px solid #ccc';
                    img.style.padding = '2px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fields = [
                'Bedsores', 'Pain', 'Swelling', 'Itchy_rash',
                'Stiff_joints', 'Malnutrition',
                'Economic_problems', 'Social_problems', 'Doctor_FU'
            ];

            fields.forEach(field => {
                const sel = document.getElementById(field);
                const input = document.getElementById(field + '_details');

                // ฟังก์ชัน toggle
                function toggleDetail() {
                    if (sel.value === 'มี') {
                        input.style.display = 'block';
                        input.required = true;
                    } else {
                        input.style.display = 'none';
                        input.required = false;
                        input.value = '';
                    }
                }

                // listener & initial state
                sel.addEventListener('change', toggleDetail);
                toggleDetail();
            });

            // ก่อน submit: ผนวกรายละเอียดเข้า select
            const form = document.getElementById('assessment-form');
            form.addEventListener('submit', function () {
                fields.forEach(field => {
                    const sel = document.getElementById(field);
                    const input = document.getElementById(field + '_details');
                    const details = input.value.trim();
                    if (sel.value === 'มี' && details) {
                        sel.value = `มี (${details})`;
                    }
                });
            });
        });
    </script>
    <script>
        function concatenateVitalSigns() {
            const bpSys = document.getElementById('BP_systolic').value;
            const bpDia = document.getElementById('BP_diastolic').value;
            const pr = document.getElementById('PR').value;
            const rr = document.getElementById('RR').value;
            const bt = document.getElementById('BT').value;

            // ตรวจสอบว่าใส่ค่าอะไรบ้างแล้วสร้าง string แบบยืดหยุ่น
            let vitalSigns = '';
            if (bpSys && bpDia) vitalSigns += `BP ${bpSys}/${bpDia}`;
            if (pr) vitalSigns += (vitalSigns ? ' - ' : '') + `PR ${pr}`;
            if (rr) vitalSigns += (vitalSigns ? ' - ' : '') + `RR ${rr}`;
            if (bt) vitalSigns += (vitalSigns ? ' - ' : '') + `BT ${bt}`;

            // เซ็ตค่าลง hidden input
            document.getElementById('Vital_signs').value = vitalSigns;
        }

        // ต้องลงทะเบียนหลัง DOMContentLoaded เช่นเดียวกับ listener อื่น ๆ ของฟอร์มนี้ เพื่อให้
        // ตัวกันข้อมูลไม่ครบ (registered ก่อนหน้า) มีโอกาส stopImmediatePropagation ได้ทัน
        // ก่อนที่ปุ่มจะถูก disable โดยไม่ได้บันทึกจริง
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('assessment-form').addEventListener('submit', function (event) {
                concatenateVitalSigns();
                const submitBtn = document.getElementById('btn-submit');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> กำลังบันทึก...';
            });
        });
    </script>
@endpush