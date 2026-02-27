<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มแบบประเมิน TAI</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
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

        input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
            /* Make radio buttons easier to tap */
        }

        .total-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-weight: bold;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .total-group span {
            font-size: 18px;
            color: #355e3b;
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
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="assessment-container px-3">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">เพิ่มแบบประเมินผู้สูงอายุ (TAI)</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success text-white">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Wizard Step Indicators -->
                <div class="step-indicator">
                    <div class="step-dot active" id="dot-1">1</div>
                    <div class="step-dot" id="dot-2">2</div>
                    <div class="step-dot" id="dot-3">3</div>
                </div>

                <form method="POST" action="{{ route('tai.update', $tai->id) }}" id="taiForm">
                    @csrf
                    @method('PUT')

                    <!-- STEP 1: Basic Info -->
                    <div class="wizard-step active" id="step-1">
                        <h5 class="text-center text-primary mb-4">ส่วนที่ 1: ข้อมูลผู้สูงอายุ</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="elderly_name">ชื่อผู้สูงอายุ:</label>
                                <input type="text" class="form-control form-control-lg" id="elderly_name"
                                    value="{{ $elderly->Name_Elderly }}" readonly>
                                <input type="hidden" name="elderly_id" value="{{ $elderly->ID_Elderly }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>เจ้าหน้าที่ผู้รับผิดชอบ:</label>
                                <input type="text" class="form-control form-control-lg" value="{{ $user->Name_User }}"
                                    readonly disabled>
                                <input type="hidden" name="user_id" value="{{ $user->ID_User }}">
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Assessment -->
                    <div class="wizard-step" id="step-2">
                        <h5 class="text-center text-primary mb-4">ส่วนที่ 2: การประเมินคะแนน</h5>

                        <div class="question-block">
                            <h5>1. การเคลื่อนที่ (Mobility):</h5>
                            <div class="form-group d-flex flex-wrap gap-3">
                                @for ($i = 0; $i <= 5; $i++)
                                    <label class="me-3 mb-2 px-2 py-1 border rounded" style="cursor: pointer;">
                                        <input type="radio" name="mobility" value="{{ $i }}" {{ $tai->mobility == $i ? 'checked' : '' }} required> {{ $i }} คะแนน
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="question-block">
                            <h5>2. ความสับสน (Confuse):</h5>
                            <div class="form-group d-flex flex-wrap gap-3">
                                @for ($i = 0; $i <= 5; $i++)
                                    <label class="me-3 mb-2 px-2 py-1 border rounded" style="cursor: pointer;">
                                        <input type="radio" name="confuse" value="{{ $i }}" {{ $tai->confuse == $i ? 'checked' : '' }} required> {{ $i }} คะแนน
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="question-block">
                            <h5>3. การรับประทานอาหาร (Feeding):</h5>
                            <div class="form-group d-flex flex-wrap gap-3">
                                @for ($i = 0; $i <= 5; $i++)
                                    <label class="me-3 mb-2 px-2 py-1 border rounded" style="cursor: pointer;">
                                        <input type="radio" name="feed" value="{{ $i }}" {{ $tai->feed == $i ? 'checked' : '' }} required> {{ $i }} คะแนน
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="question-block">
                            <h5>4. การใช้ห้องน้ำ (Toilet):</h5>
                            <div class="form-group d-flex flex-wrap gap-3">
                                @for ($i = 0; $i <= 5; $i++)
                                    <label class="me-3 mb-2 px-2 py-1 border rounded" style="cursor: pointer;">
                                        <input type="radio" name="toilet" value="{{ $i }}" {{ $tai->toilet == $i ? 'checked' : '' }} required> {{ $i }} คะแนน
                                    </label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Summary -->
                    <div class="wizard-step" id="step-3">
                        <h5 class="text-center text-primary mb-4">ส่วนที่ 3: สรุปผลการประเมิน</h5>

                        <div class="total-group mt-4 flex-column flex-md-row gap-3">
                            <div class="text-center text-md-start">
                                <h5>คะแนนรวมทั้งหมด</h5>
                                <span id="total_score" class="h3 d-block mt-2">0</span>
                            </div>
                            <div class="text-center text-md-start border-start-md ps-md-4">
                                <h5>ประเภทกลุ่มสุขภาพ</h5>
                                <span id="group" class="h4 d-block mt-2 badge bg-info p-2">N/A</span>
                            </div>
                            <div class="text-center text-md-start border-start-md ps-md-4">
                                <h5>รหัสกลุ่ม (Group Code)</h5>
                                <span id="group_code" class="h4 d-block mt-2 badge bg-secondary p-2">N/A</span>
                            </div>
                        </div>

                        <input type="hidden" name="group" id="group_input">
                        <input type="hidden" name="group_code" id="group_code_input">
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <!-- Left: Back Button -->
                        <button type="button" class="btn btn-secondary btn-lg" id="btn-prev" style="display: none;">
                            <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                        </button>

                        <!-- Right: Action Buttons Group -->
                        <div class="ms-auto d-flex gap-2">
                            <a href="{{ route('tai.index') }}" class="btn btn-outline-danger btn-lg"
                                id="btn-cancel">ยกเลิก</a>
                            <button type="button" class="btn btn-primary btn-lg" id="btn-next">
                                ถัดไป <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="btn-submit" style="display: none;">
                                <i class="fas fa-save me-2"></i> บันทึกผลการประเมิน
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentStep = 1;
            const totalSteps = 3;

            const updateUI = () => {
                // Hide all steps, show current
                document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
                document.getElementById(`step-${currentStep}`).classList.add('active');

                // Update Dots
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

                // Update Buttons
                document.getElementById('btn-prev').style.display = currentStep > 1 ? 'block' : 'none';
                
                if (currentStep === totalSteps) {
                    document.getElementById('btn-next').style.display = 'none';
                    document.getElementById('btn-submit').style.display = 'block';
                } else {
                    document.getElementById('btn-next').style.display = 'block';
                    document.getElementById('btn-submit').style.display = 'none';
                }
            };

            // Basic Validation before next step
            const validateStep = (step) => {
                if(step === 1) {
                    if(!document.getElementById('elderly_id') || !document.getElementById('elderly_id').value) {
                        return false;
                    }
                }
                if(step === 2) {
                    const requiredNames = ['mobility', 'confuse', 'feed', 'toilet'];
                    for(let name of requiredNames) {
                        if(!document.querySelector(`input[name="${name}"]:checked`)) {
                            alert('กรุณาตอบคำถามให้ครบทุกข้อในหน้านี้');
                            return false;
                        }
                    }
                }
                return true;
            };

            document.getElementById('btn-next').addEventListener('click', () => {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    currentStep++;
                    updateUI();
                    window.scrollTo(0,0);
                }
            });

            document.getElementById('btn-prev').addEventListener('click', () => {
                if (currentStep > 1) {
                     currentStep--;
                    updateUI();
                    window.scrollTo(0,0);
                }
            });

            // TAI Specific Calculation Logic
            function calculateTotalScore() {
                let score = 0;
                const radios = document.querySelectorAll('input[type="radio"]:checked');

                let mobility = null;
                let confuse = null;
                let feed = null;
                let toilet = null;

                radios.forEach(radio => {
                    score += parseInt(radio.value);
                    if (radio.name === 'mobility') { mobility = radio.value; }
                    if (radio.name === 'confuse') { confuse = radio.value; }
                    if (radio.name === 'feed') { feed = radio.value; }
                    if (radio.name === 'toilet') { toilet = radio.value; }
                });

                document.getElementById('total_score').innerText = score;

                let groupText = 'N/A';
                let groupCode = 'N/A';
                let fgClass = 'bg-secondary';

                // ถ้ามีข้อมูลครบทั้ง 4 ช่อง
                if (mobility !== null && confuse !== null && feed !== null && toilet !== null) {
                    if (mobility === '5' && confuse === '5' && feed === '5' && toilet === '5') {
                        groupText = 'กลุ่มปกติ (B5)';
                        groupCode = 'B5';
                        fgClass = 'bg-success';
                    } else if (mobility >= '3' && confuse >= '4' && feed >= '4' && toilet >= '4') {
                        groupText = 'กลุ่มปกติ (B4)';
                        groupCode = 'B4';
                        fgClass = 'bg-success';
                    } else if (mobility >= '3' && confuse >= '4' && feed <= '3' && toilet <= '3') {
                        groupText = 'กลุ่มปกติ (B3)';
                        groupCode = 'B3';
                        fgClass = 'bg-success';
                    } else if (mobility >= '3' && confuse <= '3' && feed >= '4' && toilet >= '4') {
                        groupText = 'กลุ่มติดบ้าน (C4)';
                        groupCode = 'C4';
                        fgClass = 'bg-warning text-dark';
                    } else if (mobility >= '3' && confuse <= '3' && feed === '3' && toilet === '4') {
                        groupText = 'กลุ่มติดบ้าน (C3)';
                        groupCode = 'C3';
                        fgClass = 'bg-warning text-dark';
                    } else if (mobility >= '3' && confuse <= '3' && feed === '4' && toilet === '3') {
                        groupText = 'กลุ่มติดบ้าน (C3)';
                        groupCode = 'C3';
                        fgClass = 'bg-warning text-dark';
                    } else if (mobility >= '3' && confuse <= '3' && feed <= '3' && toilet <= '3') {
                        groupText = 'กลุ่มติดบ้าน (C2)';
                        groupCode = 'C2';
                        fgClass = 'bg-warning text-dark';
                    } else if (mobility <= '2' && feed >= '4') {
                        groupText = 'กลุ่มติดเตียง (I3)';
                        groupCode = 'I3';
                        fgClass = 'bg-danger';
                    } else if (mobility <= '2' && feed === '3') {
                        groupText = 'กลุ่มติดเตียง (I2)';
                        groupCode = 'I2';
                        fgClass = 'bg-danger';
                    } else if (mobility <= '2' && feed <= '2') {
                        groupText = 'กลุ่มติดเตียง (I1)';
                        groupCode = 'I1';
                        fgClass = 'bg-danger';
                    } else {
                        groupText = 'ไม่พบคะแนน';
                        groupCode = '';
                    }
                }

                document.getElementById('group').innerText = groupText;
                document.getElementById('group').className = `h4 d-block mt-2 badge p-2 ${fgClass}`;
                document.getElementById('group_code').innerText = groupCode;
                
                document.getElementById('group_input').value = groupText;
                document.getElementById('group_code_input').value = groupCode;
            }

            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });

            calculateTotalScore();
        });
    </script>
</body>

</html>