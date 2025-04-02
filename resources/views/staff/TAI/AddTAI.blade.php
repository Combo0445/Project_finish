<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Care Giver</title>
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
        function fetchElderlyDetails() {
            var elderlyId = document.getElementById('ID_Elderly').value;
            if (elderlyId) {
                fetch(`{{ route('get-elderly-details', ':elderlyId') }}`.replace(':elderlyId', elderlyId))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('Age').value = data.Age;
                        document.getElementById('Address').value = data.Address;
                        document.getElementById('Group_ADL').value = data.Group_ADL;
                        document.getElementById('Name_Elderly').value = document.getElementById('ID_Elderly').options[
                            document.getElementById('ID_Elderly').selectedIndex].text;
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function showAssessmentForm() {
            document.getElementById('caregiver-form').classList.add('hidden');
            document.getElementById('assessment-form').classList.remove('hidden');
        }

        function showCareGiverForm() {
            document.getElementById('assessment-form').classList.add('hidden');
            document.getElementById('caregiver-form').classList.remove('hidden');
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
                <h4>รายงานผลการปฏิบัติงานผู้ดูแลผู้สูงอายุ (CG)</h4>
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
                        <div class="form-group">
                            <label for="Name_CG">ชื่อผู้ดูแลผู้สูงอายุ</label>
                            <input type="text" id="Name_CG" name="Name_CG" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Related">เกี่ยวข้องเป็น</label>
                            <input type="text" id="Related" name="Related" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Phone_CG">เบอร์ติดต่อ</label>
                            <input type="number" id="Phone_CG" name="Phone_CG" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="ID_Elderly">ชื่อ-สกุลผู้สูงอายุ</label>
                            <select id="ID_Elderly" name="ID_Elderly" class="form-control"
                                onchange="fetchElderlyDetails()" required>
                                <option value="">เลือกผู้สูงอายุ</option>
                                @foreach ($elderlys as $elderly)
                                    <option value="{{ $elderly->ID_ADL }}">{{ $elderly->elderly->Name_Elderly }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Age">อายุ</label>
                            <input type="number" id="Age" name="Age" class="form-control" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="Address">ที่อยู่</label>
                            <input type="text" id="Address" name="Address" class="form-control" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="Weight">น้ำหนักตัว</label>
                            <input type="number" id="Weight" name="Weight" class="form-control" step="0.1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="Height">ส่วนสูง</label>
                            <input type="number" id="Height" name="Height" class="form-control" step="0.1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="Waist">รอบเอว</label>
                            <input type="number" id="Waist" name="Waist" class="form-control" step="0.1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="Group_ADL">กลุ่มประเภทผู้สูงอายุ</label>
                            <input type="text" id="Group_ADL" name="Group_ADL" class="form-control" readonly
                                required>
                        </div>
                        <div class="form-group">
                            <label for="Disease">โรคประจำตัว</label>
                            <input type="text" id="Disease" name="Disease" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Disability">ความพิการ</label>
                            <input type="text" id="Disability" name="Disability" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Rights">สิทธิการรักษา</label>
                            <input type="text" id="Rights" name="Rights" class="form-control">
                        </div>
                        <input type="hidden" id="ID_ADL" name="ID_ADL">
                        <input type="hidden" id="Name_Elderly" name="Name_Elderly">

                        <button class="btn btn-success" type="button"
                            onclick="transferValues(); showAssessmentForm();">ถัดไป</button>
                        <a href="{{ route('cg.index') }}" class="btn btn-danger">ยกเลิก</a>
                    </form>

                    <!-- Assessment Form -->
                    <form id="assessment-form" class="hidden" action="{{ route('cg.store') }}" method="POST">
                        @csrf
                        <!-- Hidden fields to pass caregiver form data -->
                        <input type="hidden" id="Name_CG_hidden" name="Name_CG">
                        <input type="hidden" id="Related_hidden" name="Related">
                        <input type="hidden" id="Phone_CG_hidden" name="Phone_CG">
                        <input type="hidden" id="ID_Elderly_hidden" name="ID_Elderly">
                        <input type="hidden" id="Age_hidden" name="Age">
                        <input type="hidden" id="Address_hidden" name="Address">
                        <input type="hidden" id="Weight_hidden" name="Weight">
                        <input type="hidden" id="Height_hidden" name="Height">
                        <input type="hidden" id="Waist_hidden" name="Waist">
                        <input type="hidden" id="Group_ADL_hidden" name="Group_ADL">
                        <input type="hidden" id="Disease_hidden" name="Disease">
                        <input type="hidden" id="Disability_hidden" name="Disability">
                        <input type="hidden" id="Rights_hidden" name="Rights">
                        <input type="hidden" id="Name_Elderly_hidden" name="Name_Elderly">
                        <!-- End hidden fields -->
                        <div class="form-group">
                            <label for="Date">ลงเวลารายงานผลการปฏิบัติงาน</label>
                            <input type="date" id="Date" name="Date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Consciousness">ความรู้สึกตัว</label>
                            <select id="Consciousness" name="Consciousness" class="form-control" required>
                                <option value="รู้สึกดี">รู้สึกดี</option>
                                <option value="ไม่รู้สึกตัว">ไม่รู้สึกตัว</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Vital_signs">สัญญาณชีพ</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="number" id="BP" name="Vital_signs_BP" class="form-control" required placeholder="BP..." style="width: 80px;">
                                <input type="number" id="PR" name="Vital_signs_PR" class="form-control" required placeholder="PR..." style="width: 80px;">
                                <input type="number" id="RR" name="Vital_signs_RR" class="form-control" required placeholder="RR..." style="width: 80px;">
                                <input type="number" id="BT" name="Vital_signs_BT" class="form-control" required placeholder="BT..." style="width: 80px;">
                            </div>
                            <input type="hidden" id="Vital_signs" name="Vital_signs"> <!-- Hidden input to store the concatenated value -->
                        </div>
                        <div class="form-group">
                            <label for="Bedsores">แผลกดทับ</label>
                            <select id="Bedsores" name="Bedsores" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Bedsores_details" name="Bedsores_details" class="form-control"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Pain">อาการปวด</label>
                            <select id="Pain" name="Pain" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Pain_details" name="Pain_details" class="form-control"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Swelling">อาการบวม</label>
                            <select id="Swelling" name="Swelling" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Swelling_details" name="Swelling_details" class="form-control"
                                placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Itchy_rash">ผื่นคัน</label>
                            <select id="Itchy_rash" name="Itchy_rash" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Itchy_rash_details" name="Itchy_rash_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Stiff_joints">ข้อติดแข็ง</label>
                            <select id="Stiff_joints" name="Stiff_joints" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Stiff_joints_details" name="Stiff_joints_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Malnutrition">ทุพโภชนาการ</label>
                            <select id="Malnutrition" name="Malnutrition" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Malnutrition_details" name="Malnutrition_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Eating">การรับประทานอาหาร</label>
                            <select id="Eating" name="Eating" class="form-control" required>
                                <option value="ตักกินเองได้">ตักกินเองได้</option>
                                <option value="กินเองไม่ได้">กินเองไม่ได้</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Swallowing">การกลืน</label>
                            <select id="Swallowing" name="Swallowing" class="form-control" required>
                                <option value="กลืนได้ปกติ">กลืนได้ปกติ</option>
                                <option value="สำลัก">สำลัก</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Defecation">การขับถ่ายอุจจาระ</label>
                            <select id="Defecation" name="Defecation" class="form-control" required>
                                <option value="กลั้นได้">กลั้นได้</option>
                                <option value="กลั้นไม่ได้">กลั้นไม่ได้</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Urinary_excretion">การขับถ่ายปัสสาวะ</label>
                            <select id="Urinary_excretion" name="Urinary_excretion" class="form-control" required>
                                <option value="กลั้นได้">กลั้นได้</option>
                                <option value="กลั้นไม่ได้">กลั้นไม่ได้</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Taking_medicine">การรับประทานยา</label>
                            <select id="Taking_medicine" name="Taking_medicine" class="form-control" required>
                                <option value="กินสม่ำเสมอ">กินสม่ำเสมอ</option>
                                <option value="ขาดยา">ขาดยา</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Emotional_state">สภาพอารมณ์</label>
                            <select id="Emotional_state" name="Emotional_state" class="form-control" required>
                                <option value="ปกติ">ปกติ</option>
                                <option value="ผิดปกติ">ผิดปกติ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Economic_problems">ปัญหาเศรษฐกิจ</label>
                            <select id="Economic_problems" name="Economic_problems" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Economic_problems_details" name="Economic_problems_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Social_problems">ปัญหาสังคม</label>
                            <select id="Social_problems" name="Social_problems" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Social_problems_details" name="Social_problems_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Doctor_FU">แพทย์นัด F/U</label>
                            <select id="Doctor_FU" name="Doctor_FU" class="form-control" required>
                                <option value="ไม่มี">ไม่มี</option>
                                <option value="มี">มี</option>
                            </select>
                            <input type="text" id="Doctor_FU_details" name="Doctor_FU_details"
                                class="form-control" placeholder="รายละเอียดถ้ามี">
                        </div>
                        <div class="form-group">
                            <label for="Other_problems">ปัญหาอื่น ๆ</label>
                            <input type="text" id="Other_problems" name="Other_problems" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Assistance">การช่วยเหลือ</label>
                            <input type="text" id="Assistance" name="Assistance" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="Reporter">ผู้รายงาน</label>
                            <input type="text" id="Reporter" name="Reporter" class="form-control"
                                value="{{ Auth::user()->Name_User }}" readonly required>
                        </div>
                        <button class="btn btn-success" type="submit">บันทึก</button>
                        <button class="btn btn-danger" type="button" onclick="showCareGiverForm()">กลับ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function concatenateVitalSigns() {
            const bp = document.getElementById('BP').value;
            const pr = document.getElementById('PR').value;
            const rr = document.getElementById('RR').value;
            const bt = document.getElementById('BT').value;

            // Concatenate the values into the desired format
            const vitalSigns = `BP ${bp} - PR ${pr} - RR ${rr} - BT ${bt}`;

            // Set the concatenated value to the hidden field
            document.getElementById('Vital_signs').value = vitalSigns;
        }

        // Attach the function to the form's submit event
        document.getElementById('assessment-form').addEventListener('submit', function(event) {
            concatenateVitalSigns();
        });
    </script>
</body>

</html>
