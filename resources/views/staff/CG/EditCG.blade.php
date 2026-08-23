<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Care Giver</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button,
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #3498db;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #007BFF;
        }

        .btn-secondary:hover {
            background: #0056b3;
        }

        .hidden {
            display: none;
        }
    </style>
    <script>


        function showAssessmentForm() {
            document.getElementById('caregiver-form').classList.add('hidden');
            document.getElementById('assessment-form').classList.remove('hidden');
        }

        // ก่อนสลับไปหน้าถัดไป ต้องเช็คว่ากรอกครบ (เช่น น้ำหนัก/ส่วนสูง/รอบเอว) ก่อน — เดิมปุ่ม
        // "ถัดไป" เป็น type="button" จึงไม่เคยทริกเกอร์ HTML5 validation เลย ทำให้ข้ามช่องที่ยังไม่ได้
        // กรอกไปได้โดยไม่มีการเตือน
        function goToAssessmentForm() {
            const form = document.getElementById('caregiver-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            transferValues();
            showAssessmentForm();
        }

        function showCareGiverForm() {
            document.getElementById('assessment-form').classList.add('hidden');
            document.getElementById('caregiver-form').classList.remove('hidden');
        }

        // ดึงน้ำหนัก/ส่วนสูง/รอบเอว/ความดัน จากรายงาน CG ครั้งก่อนหน้าของผู้สูงอายุคนนี้
        // (ไม่นับรายงานที่กำลังแก้ไขอยู่นี้เอง) มาใส่แทนค่าปัจจุบันในฟอร์ม
        function fillLatestVitals() {
            const url = `{{ route('get-elderly-details', $caregiver->ID_ADL) }}?exclude={{ $caregiver->ID_CG }}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (!data.Latest) {
                        alert('ไม่พบข้อมูลการประเมินครั้งก่อนหน้าของผู้สูงอายุคนนี้');
                        return;
                    }
                    if (data.Latest.Weight) document.getElementById('Weight').value = data.Latest.Weight;
                    if (data.Latest.Height) document.getElementById('Height').value = data.Latest.Height;
                    if (data.Latest.Waist) document.getElementById('Waist').value = data.Latest.Waist;
                    if (data.Latest.Vital_signs) populateVitalSignsFromText(data.Latest.Vital_signs);
                })
                .catch(error => console.error('Error:', error));
        }

        function transferValues() {
            document.getElementById('Name_CG_hidden').value = document.getElementById('Name_CG').value;
            document.getElementById('Related_hidden').value = document.getElementById('Related').value;
            document.getElementById('Phone_CG_hidden').value = document.getElementById('Phone_CG').value;
            document.getElementById('ID_Elderly_hidden').value = document.getElementById('ID_Elderly').value;
            document.getElementById('Age_hidden').value = document.getElementById('Age').value;
            document.getElementById('Address_hidden').value = document.getElementById('Address').value;
            document.getElementById('Weight_hidden').value = document.getElementById('Weight').value;
            document.getElementById('Height_hidden').value = document.getElementById('Height').value;
            document.getElementById('Waist_hidden').value = document.getElementById('Waist').value;
            document.getElementById('Group_ADL_hidden').value = document.getElementById('Group_ADL').value;
            document.getElementById('Disease_hidden').value = document.getElementById('Disease').value;
            document.getElementById('Disability_hidden').value = document.getElementById('Disability').value;
            document.getElementById('Rights_hidden').value = document.getElementById('Rights').value;
            document.getElementById('Name_Elderly_hidden').value = document.getElementById('Name_Elderly').value;
        }
    </script>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>แก้ไขรายงานผลการปฏิบัติงานผู้ดูแลผู้สูงอายุ (CG)</h4>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Care Giver Form -->
                    <form id="caregiver-form" action="javascript:void(0);" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="Name_CG">ชื่อ - สกุลผู้ดูแลผู้สูงอายุ</label>
                            <input type="text" id="Name_CG" name="Name_CG" class="form-control"
                                value="{{ old('Name_CG', $caregiver->Name_CG) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Related">เกี่ยวข้องเป็น</label>
                            <input type="text" id="Related" name="Related" class="form-control"
                                value="{{ old('Related', $caregiver->Related) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Phone_CG">เบอร์ติดต่อ</label>
                            <input type="number" id="Phone_CG" name="Phone_CG" class="form-control"
                                value="{{ old('Phone_CG', $caregiver->Phone_CG) }}" required>
                        <div class="form-group">
                            <label for="Name_Elderly">ชื่อ-สกุลผู้สูงอายุ</label>
                            <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                value="{{ $caregiver->Name_Elderly }}" readonly>
                            <input type="hidden" id="ID_Elderly" name="ID_Elderly"
                                value="{{ $caregiver->ID_Elderly }}">
                        </div>
                        <div class="form-group">
                            <label for="Age">อายุ</label>
                            <input type="number" id="Age" name="Age" class="form-control"
                                value="{{ $age }}" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="Address">ที่อยู่</label>
                            <input type="text" id="Address" name="Address" class="form-control"
                                value="{{ $caregiver->Address }}" required readonly>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillLatestVitals()">
                                ดึงข้อมูลครั้งก่อนหน้า (น้ำหนัก/ส่วนสูง/รอบเอว/ความดัน)
                            </button>
                        </div>
                        <div class="form-group">
                            <label for="Weight">น้ำหนักตัว</label>
                            <input type="number" id="Weight" name="Weight" class="form-control" step="0.1"
                                value="{{ old('Weight', $caregiver->Weight) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Height">ส่วนสูง</label>
                            <input type="number" id="Height" name="Height" class="form-control" step="0.1"
                                value="{{ old('Height', $caregiver->Height) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Waist">รอบเอว</label>
                            <input type="number" id="Waist" name="Waist" class="form-control" step="0.1"
                                value="{{ old('Waist', $caregiver->Waist) }}" required>
                        </div>
                        @php
                            $group = $caregiver->Group_ADL;
                            if ($group === 'B3') {
                                $displayText = 'ติดบ้าน กลุ่มที่ 1';
                            } elseif (in_array($group, ['C2', 'C3', 'C4'])) {
                                $displayText = 'ติดบ้าน กลุ่มที่ 2';
                            } elseif ($group === 'I3') {
                                $displayText = 'ติดเตียง กลุ่มที่ 1';
                            } elseif (in_array($group, ['I1', 'I2'])) {
                                $displayText = 'ติดเตียง กลุ่มที่ 2';
                            } else {
                                $displayText = 'ยังไม่ได้ประเมิน';
                            }
                        @endphp

                        <div class="form-group">
                            <label for="Group_ADL">กลุ่มประเภทผู้สูงอายุ</label>
                            {{-- ถ้าต้องการเก็บโค้ดจริงด้วย ให้ใช้ hidden field --}}
                            <input type="hidden" id="Group_ADL" name="Group_ADL" value="{{ $group }}">

                            {{-- แสดงข้อความที่อ่านเข้าใจง่าย --}}
                            <input type="text" name="Group_ADL_Display" class="form-control" value="{{ $displayText }}" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="Disease">โรคประจำตัว</label>
                            <input type="text" id="Disease" name="Disease" class="form-control"
                                value="{{ old('Disease', $caregiver->Disease) }}">
                        </div>
                        <div class="form-group">
                            <label for="Disability">ความพิการ</label>
                            <input type="text" id="Disability" name="Disability" class="form-control"
                                value="{{ old('Disability', $caregiver->Disability) }}">
                        </div>
                        @php $oldRights = old('Rights', $caregiver->Rights) @endphp
                        <div class="form-group">
                            <label for="Rights">สิทธิการรักษา</label>
                            <select id="Rights" name="Rights" class="form-control" required>
                                <option value="" disabled {{ $oldRights ? '' : 'selected' }}>-- เลือกสิทธิการรักษา --</option>
                                <option value="สิทธิข้าราชการ" {{ $oldRights == 'สิทธิข้าราชการ' ? 'selected' : '' }}>สิทธิข้าราชการ</option>
                                <option value="บัตรผู้พิการ" {{ $oldRights == 'บัตรผู้พิการ' ? 'selected' : '' }}>บัตรผู้พิการ</option>
                                <option value="บัตรทอง" {{ $oldRights == 'บัตรทอง' ? 'selected' : '' }}>บัตรทอง</option>
                                <option value="ประกันสุขภาพ" {{ $oldRights == 'ประกันสุขภาพ' ? 'selected' : '' }}>ประกันสุขภาพ</option>
                                <option value="อปท." {{ $oldRights == 'อปท.' ? 'selected' : '' }}>อปท.</option>
                                <option value="ผู้สูงอายุ" {{ $oldRights == 'ผู้สูงอายุ' ? 'selected' : '' }}>ผู้สูงอายุ</option>
                            </select>
                        </div>
                        </div>
                        <input type="hidden" id="ID_ADL" name="ID_ADL" value="{{ $caregiver->ID_ADL }}">
                        <input type="hidden" id="Name_Elderly_hidden" name="Name_Elderly"
                            value="{{ $caregiver->Name_Elderly }}">

                        <button class="btn btn-success" type="button"
                            onclick="goToAssessmentForm();">ถัดไป</button>
                        <a href="{{ route('cg.index') }}" class="btn btn-danger">ยกเลิก</a>
                    </form>

                    @php
                        // ฟิลด์เหล่านี้ submit ค่าเป็น "ไม่มี" หรือข้อความรายละเอียดดิบๆ โดยตรง (ไม่ใช่ "มี")
                        // เพราะ JS เปลี่ยนชื่อ input ตอน submit ให้ input รายละเอียดใช้ name เดียวกับ select
                        // เมื่อเลือก "มี" ดังนั้น old() ของฟิลด์เหล่านี้จะเป็นข้อความรายละเอียดโดยตรงอยู่แล้ว
                        $toggleFields = [
                            'Bedsores', 'Pain', 'Swelling', 'Itchy_rash',
                            'Stiff_joints', 'Malnutrition',
                            'Economic_problems', 'Social_problems', 'Doctor_FU',
                        ];
                        $oldToggle = [];
                        foreach ($toggleFields as $tf) {
                            $oldToggle[$tf] = old($tf, $caregiver->$tf);
                        }
                    @endphp

                    <!-- Assessment Form -->
                    <form id="assessment-form" class="hidden" action="{{ route('cg.update', $caregiver->ID_CG) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Hidden fields to pass caregiver form data -->
                        <input type="hidden" id="Name_CG_hidden" name="Name_CG" value="{{ old('Name_CG', $caregiver->Name_CG) }}">
                        <input type="hidden" id="Related_hidden" name="Related" value="{{ old('Related', $caregiver->Related) }}">
                        <input type="hidden" id="Phone_CG_hidden" name="Phone_CG"
                            value="{{ old('Phone_CG', $caregiver->Phone_CG) }}">
                        <input type="hidden" id="ID_Elderly_hidden" name="ID_Elderly"
                            value="{{ $caregiver->ID_Elderly }}">
                        <input type="hidden" id="Age_hidden" name="Age" value="{{ $caregiver->Age }}">
                        <input type="hidden" id="Address_hidden" name="Address" value="{{ $caregiver->Address }}">
                        <input type="hidden" id="Weight_hidden" name="Weight" value="{{ old('Weight', $caregiver->Weight) }}">
                        <input type="hidden" id="Height_hidden" name="Height" value="{{ old('Height', $caregiver->Height) }}">
                        <input type="hidden" id="Waist_hidden" name="Waist" value="{{ old('Waist', $caregiver->Waist) }}">
                        <input type="hidden" id="Group_ADL_hidden" name="Group_ADL"
                            value="{{ $caregiver->Group_ADL }}">
                        <input type="hidden" id="Disease_hidden" name="Disease" value="{{ old('Disease', $caregiver->Disease) }}">
                        <input type="hidden" id="Disability_hidden" name="Disability"
                            value="{{ old('Disability', $caregiver->Disability) }}">
                        <input type="hidden" id="Rights_hidden" name="Rights" value="{{ $oldRights }}">
                        <input type="hidden" id="Name_Elderly_hidden" name="Name_Elderly"
                            value="{{ $caregiver->Name_Elderly }}">
                        <!-- End hidden fields -->
                        <div class="form-group">
                            <label for="Date">ว/ด/ป</label>
                            <input type="date" id="Date_CG" name="Date_CG" … value="{{ old('Date_CG', $caregiver->Date_CG) }}">
                        </div>
                        <div class="form-group">
                            <label for="Consciousness">ความรู้สึกตัว</label>
                            @php $oldConsciousness = old('Consciousness', $caregiver->Consciousness) @endphp
                            <select id="Consciousness" name="Consciousness" class="form-control" required>
                                <option value="รู้สึกดี"
                                    {{ $oldConsciousness == 'รู้สึกดี' ? 'selected' : '' }}>รู้สึกดี</option>
                                <option value="ไม่รู้สึกตัว"
                                    {{ $oldConsciousness == 'ไม่รู้สึกตัว' ? 'selected' : '' }}>ไม่รู้สึกตัว
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Vital_signs">สัญญาณชีพ</label>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span>BP ก่อน</span>
                                    <input type="number" id="BP_systolic" class="form-control" placeholder="BP ก่อน" style="width: 80px;">
                                </div>
                        
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span>BP หลัง</span>
                                    <input type="number" id="BP_diastolic" class="form-control" placeholder="BP หลัง" style="width: 80px;">
                                </div>
                        
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span>&nbsp;</span>
                                    <input type="number" id="PR" class="form-control" placeholder="PR..." style="width: 80px;">
                                </div>
                        
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span>&nbsp;</span>
                                    <input type="number" id="RR" class="form-control" placeholder="RR..." style="width: 80px;">
                                </div>
                        
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span>&nbsp;</span>
                                    <input type="number" id="BT" class="form-control" placeholder="BT..." step="0.01" style="width: 80px;">
                                </div>
                            </div>
                        
                            <input type="hidden" id="Vital_signs" name="Vital_signs" value="{{ old('Vital_signs', $caregiver->Vital_signs) }}">
                        </div>
                        <div class="form-group">
                            <label for="Bedsores">แผลกดทับ</label>
                            <select id="Bedsores" name="Bedsores" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Bedsores'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี
                                </option>
                                <option value="มี" {{ $oldToggle['Bedsores'] != 'ไม่มี' && $oldToggle['Bedsores'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Bedsores_details" name="Bedsores_details" class="form-control"
                                value="{{ $oldToggle['Bedsores'] }}" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Pain">อาการปวด</label>
                            <select id="Pain" name="Pain" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Pain'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี
                                </option>
                                <option value="มี" {{ $oldToggle['Pain'] != 'ไม่มี' && $oldToggle['Pain'] != null ? 'selected' : '' }}>มี</option>
                            </select>
                            <input style="display:none;" type="text" id="Pain_details" name="Pain_details" class="form-control"
                                value="{{ $oldToggle['Pain'] }}" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Swelling">อาการบวม</label>
                            <select id="Swelling" name="Swelling" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Swelling'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี
                                </option>
                                <option value="มี" {{ $oldToggle['Swelling'] != 'ไม่มี' && $oldToggle['Swelling'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Swelling_details" name="Swelling_details" class="form-control"
                                value="{{ $oldToggle['Swelling'] }}" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Itchy_rash">ผื่นคัน</label>
                            <select id="Itchy_rash" name="Itchy_rash" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Itchy_rash'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี
                                </option>
                                <option value="มี" {{ $oldToggle['Itchy_rash'] != 'ไม่มี' && $oldToggle['Itchy_rash'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Itchy_rash_details" name="Itchy_rash_details"
                                class="form-control" value="{{ $oldToggle['Itchy_rash'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Stiff_joints">ข้อติดแข็ง</label>
                            <select id="Stiff_joints" name="Stiff_joints" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Stiff_joints'] == 'ไม่มี' ? 'selected' : '' }}>
                                    ไม่มี</option>
                                <option value="มี" {{ $oldToggle['Stiff_joints'] != 'ไม่มี' && $oldToggle['Stiff_joints'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Stiff_joints_details" name="Stiff_joints_details"
                                class="form-control" value="{{ $oldToggle['Stiff_joints'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Malnutrition">ทุพโภชนาการ</label>
                            <select id="Malnutrition" name="Malnutrition" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Malnutrition'] == 'ไม่มี' ? 'selected' : '' }}>
                                    ไม่มี</option>
                                <option value="มี" {{ $oldToggle['Malnutrition'] != 'ไม่มี' && $oldToggle['Malnutrition'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Malnutrition_details" name="Malnutrition_details"
                                class="form-control" value="{{ $oldToggle['Malnutrition'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Eating">การรับประทานอาหาร</label>
                            <select id="Eating" name="Eating" class="form-control" required>
                                <option value="ตักกินเองได้"
                                    {{ old('Eating', $caregiver->Eating) == 'ตักกินเองได้' ? 'selected' : '' }}>ตักกินเองได้</option>
                                <option value="กินเองไม่ได้"
                                    {{ old('Eating', $caregiver->Eating) == 'กินเองไม่ได้' ? 'selected' : '' }}>กินเองไม่ได้</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Swallowing">การกลืน</label>
                            <select id="Swallowing" name="Swallowing" class="form-control" required>
                                <option value="กลืนได้ปกติ"
                                    {{ old('Swallowing', $caregiver->Swallowing) == 'กลืนได้ปกติ' ? 'selected' : '' }}>กลืนได้ปกติ
                                </option>
                                <option value="สำลัก" {{ old('Swallowing', $caregiver->Swallowing) == 'สำลัก' ? 'selected' : '' }}>สำลัก
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Defecation">การขับถ่ายอุจจาระ</label>
                            <select id="Defecation" name="Defecation" class="form-control" required>
                                <option value="กลั้นได้" {{ old('Defecation', $caregiver->Defecation) == 'กลั้นได้' ? 'selected' : '' }}>
                                    กลั้นได้</option>
                                <option value="กลั้นไม่ได้"
                                    {{ old('Defecation', $caregiver->Defecation) == 'กลั้นไม่ได้' ? 'selected' : '' }}>กลั้นไม่ได้
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Urinary_excretion">การขับถ่ายปัสสาวะ</label>
                            <select id="Urinary_excretion" name="Urinary_excretion" class="form-control" required>
                                <option value="กลั้นได้"
                                    {{ old('Urinary_excretion', $caregiver->Urinary_excretion) == 'กลั้นได้' ? 'selected' : '' }}>กลั้นได้
                                </option>
                                <option value="กลั้นไม่ได้"
                                    {{ old('Urinary_excretion', $caregiver->Urinary_excretion) == 'กลั้นไม่ได้' ? 'selected' : '' }}>กลั้นไม่ได้
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Taking_medicine">การรับประทานยา</label>
                            <select id="Taking_medicine" name="Taking_medicine" class="form-control" required>
                                <option value="กินสม่ำเสมอ"
                                    {{ old('Taking_medicine', $caregiver->Taking_medicine) == 'กินสม่ำเสมอ' ? 'selected' : '' }}>กินสม่ำเสมอ
                                </option>
                                <option value="ขาดยา" {{ old('Taking_medicine', $caregiver->Taking_medicine) == 'ขาดยา' ? 'selected' : '' }}>
                                    ขาดยา</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Emotional_state">สภาพอารมณ์</label>
                            <select id="Emotional_state" name="Emotional_state" class="form-control" required>
                                <option value="ปกติ" {{ old('Emotional_state', $caregiver->Emotional_state) == 'ปกติ' ? 'selected' : '' }}>
                                    ปกติ</option>
                                <option value="ผิดปกติ"
                                    {{ old('Emotional_state', $caregiver->Emotional_state) == 'ผิดปกติ' ? 'selected' : '' }}>ผิดปกติ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Economic_problems">ปัญหาเศรษฐกิจ</label>
                            <select id="Economic_problems" name="Economic_problems" class="form-control" required>
                                <option value="ไม่มี"
                                    {{ $oldToggle['Economic_problems'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี</option>
                                <option value="มี" {{ $oldToggle['Economic_problems'] != 'ไม่มี' && $oldToggle['Economic_problems'] != null ? 'selected' : '' }}>
                                    มี</option>
                            </select>
                            <input style="display:none;" type="text" id="Economic_problems_details" name="Economic_problems_details"
                                class="form-control" value="{{ $oldToggle['Economic_problems'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Social_problems">ปัญหาสังคม</label>
                            <select id="Social_problems" name="Social_problems" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Social_problems'] == 'ไม่มี' ? 'selected' : '' }}>
                                    ไม่มี</option>
                                <option value="มี" {{ $oldToggle['Social_problems'] != 'ไม่มี' && $oldToggle['Social_problems'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Social_problems_details" name="Social_problems_details"
                                class="form-control" value="{{ $oldToggle['Social_problems'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Doctor_FU">แพทย์นัด F/U</label>
                            <select id="Doctor_FU" name="Doctor_FU" class="form-control" required>
                                <option value="ไม่มี" {{ $oldToggle['Doctor_FU'] == 'ไม่มี' ? 'selected' : '' }}>ไม่มี
                                </option>
                                <option value="มี" {{ $oldToggle['Doctor_FU'] != 'ไม่มี' && $oldToggle['Doctor_FU'] != null ? 'selected' : '' }}>มี
                                </option>
                            </select>
                            <input style="display:none;" type="text" id="Doctor_FU_details" name="Doctor_FU_details"
                                class="form-control" value="{{ $oldToggle['Doctor_FU'] }}"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Other_problems">ปัญหาอื่น ๆ</label>
                            <input type="text" id="Other_problems" name="Other_problems" class="form-control"
                                value="{{ old('Other_problems', $caregiver->Other_problems) }}">
                        </div>
                        <div class="form-group">
                            <label for="Assistance">การช่วยเหลือ</label>
                            <input type="text" id="Assistance" name="Assistance" class="form-control"
                                value="{{ old('Assistance', $caregiver->Assistance) }}">
                        </div>
                        <div class="form-group">
                            <label for="Reporter">ผู้รายงาน</label>
                            <input type="text" id="Reporter" name="Reporter" class="form-control"
                                value="{{ Auth::user()->Name_User }}" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="Picture">อัปโหลดรูปภาพ (ไม่เกิน 4 รูป)</label>
                            <input type="file" name="Picture[]" id="Picture" class="form-control" multiple accept="image/*" onchange="validateAndPreviewImages(this)">
                            <small class="form-text text-muted">สามารถอัปโหลดได้สูงสุด 4 รูป</small>
                        
                            <!-- พื้นที่แสดงรูป preview -->
                            <div id="imagePreview" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;">
                                @if($caregiver->Picture)
                                    @foreach(json_decode($caregiver->Picture, true) as $img)
                                        <div style="position: relative;">
                                            <img src="{{ asset('storage/' . $img) }}"
                                                 style="max-width: 120px; max-height: 120px; object-fit: cover; border: 1px solid #ccc; padding: 2px;">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <script>
                            function validateAndPreviewImages(input) {
                                const preview = document.getElementById('imagePreview');
                                preview.innerHTML = ''; // ล้างรูป preview เดิมออกทั้งหมด
                        
                                if (input.files.length > 4) {
                                    alert('คุณสามารถอัปโหลดรูปภาพได้ไม่เกิน 4 รูป');
                                    input.value = ''; // รีเซ็ต input
                                    return;
                                }
                        
                                Array.from(input.files).forEach(file => {
                                    if (!file.type.startsWith('image/')) return;
                        
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
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

                        <button class="btn btn-success" type="submit">บันทึก</button>
                        <button class="btn btn-danger" type="button" onclick="showCareGiverForm()">กลับ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      // รายชื่อฟิลด์ที่ต้องใช้ pattern เดียวกัน
      const fields = [
        'Economic_problems',
        'Social_problems',
        'Doctor_FU',
        'Bedsores',
        'Pain',
        'Swelling',
        'Itchy_rash',
        'Stiff_joints',
        'Malnutrition'
      ];
  
      fields.forEach(field => {
        const sel   = document.getElementById(field);
        const input = document.getElementById(field + '_details');
  
        // ฟังก์ชันแสดง/ซ่อน input
        function toggleDetail() {
          if (sel.value === 'มี') {
            input.style.display = 'block';
            input.required      = true;
          } else {
            input.style.display = 'none';
            input.required      = false;
            input.value         = '';
          }
        }
  
        // ตั้ง listener เมื่อเปลี่ยนค่า
        sel.addEventListener('change', toggleDetail);
        // เรียกครั้งแรกตอน load page
        toggleDetail();
      });
  
      // ก่อน submit ให้เปลี่ยน name ของฟิลด์
      const form = document.getElementById('assessment-form');
      form.addEventListener('submit', function() {
        fields.forEach(field => {
          const sel   = document.getElementById(field);
          const input = document.getElementById(field + '_details');
  
          if (sel.value === 'มี') {
            // ให้ input ส่งเป็น field เดียวกับชื่อ select
            input.name = field;
            // เอา name ของ select ออก (หรือเปลี่ยนเป็นชื่ออื่น)
            sel.removeAttribute('name');
          } else {
            // กรณีไม่มี ยังคงส่ง select ธรรมดา
            sel.name = field;
            // ปรับ input ไปส่งชื่อ dummy ไม่ Validate
            input.name = field + '_details_dummy';
          }
        });
      });
    });
  </script>
  
<script>
    // ฟังก์ชันแยกค่าสัญญาณชีพเดิมมาใส่ในช่อง input
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

    // ฟังก์ชันรวมค่าใหม่ไว้ใน hidden input
    function concatenateVitalSigns() {
        const bpSys = document.getElementById('BP_systolic').value;
        const bpDia = document.getElementById('BP_diastolic').value;
        const pr = document.getElementById('PR').value;
        const rr = document.getElementById('RR').value;
        const bt = document.getElementById('BT').value;

        let vitalSigns = '';
        if (bpSys && bpDia) vitalSigns += `BP ${bpSys}/${bpDia}`;
        if (pr) vitalSigns += (vitalSigns ? ' - ' : '') + `PR ${pr}`;
        if (rr) vitalSigns += (vitalSigns ? ' - ' : '') + `RR ${rr}`;
        if (bt) vitalSigns += (vitalSigns ? ' - ' : '') + `BT ${bt}`;

        document.getElementById('Vital_signs').value = vitalSigns;
    }

    // โหลดข้อมูลเมื่อหน้าโหลดเสร็จ
    document.addEventListener('DOMContentLoaded', function () {
        const vitalText = document.getElementById('Vital_signs').value;
        populateVitalSignsFromText(vitalText);

        @if ($errors->any())
            @php
                $caregiverFormFields = ['Name_CG', 'Related', 'Phone_CG', 'Weight', 'Height', 'Waist', 'Group_ADL', 'Rights'];
                $hasCaregiverFormError = collect($caregiverFormFields)->contains(fn($f) => $errors->has($f));
            @endphp
            // หน้านี้ redisplay เพราะ validate ไม่ผ่าน -- ถ้าช่องที่ error ไม่ได้อยู่ในส่วนที่ 1
            // (ข้อมูลผู้ดูแล/ผู้สูงอายุ) ให้เปิดส่วนที่ 2 (แบบประเมิน) ที่ผู้ใช้กำลังกรอกอยู่ให้เลย
            // แทนที่จะย้อนกลับไปหน้าแรกโดยไม่มีสาเหตุ
            @if (!$hasCaregiverFormError)
                showAssessmentForm();
            @endif
        @endif
    });

    // เพิ่มการรวมค่าก่อน submit form
    document.getElementById('assessment-form').addEventListener('submit', function (event) {
        concatenateVitalSigns();
    });
</script>


</html>
