@extends('layouts.app')

@section('title', 'ADL Assessment')

@push('styles')
    <style>
        .assessment-container {
            max-width: 900px;
            margin: auto;
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
@endpush

@section('content')
    <div class="assessment-container">
        <x-card>
            <x-slot name="header">
                <h4 class="mb-0">แบบฟอร์มประเมินการดำเนินชีวิตประจำวัน (ADL)</h4>
            </x-slot>

            @if(session('success'))
                <div class="alert alert-success text-white">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>กรุณาตรวจสอบข้อมูลที่กรอก:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Wizard Step Indicators -->
            <div class="step-indicator">
                <div class="step-dot active" id="dot-1">1</div>
                <div class="step-dot" id="dot-2">2</div>
                <div class="step-dot" id="dot-3">3</div>
            </div>

            <form method="POST" action="{{ route('adl.submit') }}" id="adlForm">
                @csrf

                <!-- STEP 1: Basic Info -->
                <div class="wizard-step active" id="step-1">
                    <h5 class="text-center text-primary mb-4">ส่วนที่ 1: ข้อมูลผู้สูงอายุ</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="elderly_id">เลือกรายชื่อผู้สูงอายุที่ต้องการประเมิน:</label>
                                <select name="elderly_id" id="elderly_id" class="form-control form-control-lg" required>
                                    <option value="" disabled selected>-- กรุณาเลือก --</option>
                                    @foreach($elderlies as $elderly)
                                        <option value="{{ $elderly->ID_Elderly }}" {{ old('elderly_id', $selected_elderly_id ?? null) == $elderly->ID_Elderly ? 'selected' : '' }}>
                                            {{ $elderly->Name_Elderly }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>เจ้าหน้าที่ผู้ประเมิน:</label>
                                <input type="text" class="form-control form-control-lg"
                                    value="{{ Auth::user()->Name_User }}" readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Questions 1-5 -->
                <div class="wizard-step" id="step-2">
                    <h5 class="text-center text-primary mb-4">ส่วนที่ 2: การประเมินข้อ 1 - 5</h5>
                    <!-- Q1 -->
                    <div class="question-block">
                        <h5>1. การรับประทานอาหาร:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="feeding" value="0" {{ old("feeding") == "0" ? "checked" : "" }} required>
                                ไม่สามารถตักอาหารเข้าปากได้ต้องมีคนป้อนให้</label>
                            <label class="d-block mb-3"><input type="radio" name="feeding" value="1" {{ old("feeding") == "1" ? "checked" : "" }}>
                                ตักอาหารเองได้แต่ต้องมีคนช่วย</label>
                            <label class="d-block mb-3"><input type="radio" name="feeding" value="2" {{ old("feeding") == "2" ? "checked" : "" }}>
                                ตักอาหารและช่วยตัวเองได้เป็นปกติ</label>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="question-block">
                        <h5>2. การดูแลร่างกาย:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="grooming" value="0" {{ old("grooming") == "0" ? "checked" : "" }} required>
                                ต้องการความช่วยเหลือ</label>
                            <label class="d-block mb-3"><input type="radio" name="grooming" value="1" {{ old("grooming") == "1" ? "checked" : "" }}> ทำเองได้
                                (รวมทั้งที่ทำได้เองถ้าเตรียมอุปกรณ์ไว้ให้)</label>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="question-block">
                        <h5>3. การย้ายตัว (เช่น จากเตียงไปเก้าอี้):</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="transfer" value="0" {{ old("transfer") == "0" ? "checked" : "" }} required> นั่งไม่ได้
                                หรือต้องใช้คนสองคนช่วยกันยก</label>
                            <label class="d-block mb-3"><input type="radio" name="transfer" value="1" {{ old("transfer") == "1" ? "checked" : "" }}> ต้องใช้คนแข็งแรง 1
                                คนหรือคนทั่วไป 2 คนช่วยพยุง</label>
                            <label class="d-block mb-3"><input type="radio" name="transfer" value="2" {{ old("transfer") == "2" ? "checked" : "" }}>
                                ต้องการคนช่วยพยุงเล็กน้อย หรือดูแลความปลอดภัย</label>
                            <label class="d-block mb-3"><input type="radio" name="transfer" value="3" {{ old("transfer") == "3" ? "checked" : "" }}> ทำได้เอง</label>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="question-block">
                        <h5>4. การใช้ห้องน้ำ (ขับถ่าย):</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="toilet_use" value="0" {{ old("toilet_use") == "0" ? "checked" : "" }} required>
                                ช่วยตัวเองไม่ได้</label>
                            <label class="d-block mb-3"><input type="radio" name="toilet_use" value="1" {{ old("toilet_use") == "1" ? "checked" : "" }}> ทำเองได้บ้าง
                                แต่ต้องการความช่วยเหลือบางส่วน</label>
                            <label class="d-block mb-3"><input type="radio" name="toilet_use" value="2" {{ old("toilet_use") == "2" ? "checked" : "" }}>
                                ทำเองได้ดีเรียบร้อย</label>
                        </div>
                    </div>

                    <!-- Q5 -->
                    <div class="question-block">
                        <h5>5. การเคลื่อนที่ภายในบ้าน:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="mobility" value="0" {{ old("mobility") == "0" ? "checked" : "" }} required>
                                เคลื่อนที่ไปไหนไม่ได้</label>
                            <label class="d-block mb-3"><input type="radio" name="mobility" value="1" {{ old("mobility") == "1" ? "checked" : "" }}>
                                เคลื่อนที่ได้เองโดยใช้รถเข็น</label>
                            <label class="d-block mb-3"><input type="radio" name="mobility" value="2" {{ old("mobility") == "2" ? "checked" : "" }}>
                                เดินได้แต่ต้องมีคนเดินช่วยพยุงดูแล</label>
                            <label class="d-block mb-3"><input type="radio" name="mobility" value="3" {{ old("mobility") == "3" ? "checked" : "" }}>
                                เดินหรือเคลื่อนที่ได้เอง</label>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Questions 6-10 and Submit -->
                <div class="wizard-step" id="step-3">
                    <h5 class="text-center text-primary mb-4">ส่วนที่ 3: การประเมินข้อ 6 - 10</h5>
                    <!-- Q6 -->
                    <div class="question-block">
                        <h5>6. การสวมใส่เสื้อผ้า:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="dressing" value="0" {{ old("dressing") == "0" ? "checked" : "" }} required>
                                ต้องมีคนช่วยสวมให้ทั้งหมด</label>
                            <label class="d-block mb-3"><input type="radio" name="dressing" value="1" {{ old("dressing") == "1" ? "checked" : "" }}>
                                ช่วยตัวเองได้ครึ่งหนึ่ง ที่เหลือต้องมีคนช่วย</label>
                            <label class="d-block mb-3"><input type="radio" name="dressing" value="2" {{ old("dressing") == "2" ? "checked" : "" }}>
                                ใส่เองได้ดีทั้งหมด</label>
                        </div>
                    </div>

                    <!-- Q7 -->
                    <div class="question-block">
                        <h5>7. การขึ้นลงบันได (1 ชั้น):</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="stairs" value="0" {{ old("stairs") == "0" ? "checked" : "" }} required>
                                ไม่สามารถทำได้</label>
                            <label class="d-block mb-3"><input type="radio" name="stairs" value="1" {{ old("stairs") == "1" ? "checked" : "" }}> ต้องการคนช่วย</label>
                            <label class="d-block mb-3"><input type="radio" name="stairs" value="2" {{ old("stairs") == "2" ? "checked" : "" }}> ขึ้นลงได้เอง</label>
                        </div>
                    </div>

                    <!-- Q8 -->
                    <div class="question-block">
                        <h5>8. การอาบน้ำ:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="bathing" value="0" {{ old("bathing") == "0" ? "checked" : "" }} required>
                                ต้องมีคนช่วยหรือทำให้</label>
                            <label class="d-block mb-3"><input type="radio" name="bathing" value="1" {{ old("bathing") == "1" ? "checked" : "" }}> อาบน้ำเองได้</label>
                        </div>
                    </div>

                    <!-- Q9 -->
                    <div class="question-block">
                        <h5>9. การกลั้นอุจจาระ:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="bowels" value="0" {{ old("bowels") == "0" ? "checked" : "" }} required> กลั้นไม่ได้
                                หรือต้องสวน</label>
                            <label class="d-block mb-3"><input type="radio" name="bowels" value="1" {{ old("bowels") == "1" ? "checked" : "" }}>
                                กลั้นไม่ได้บางครั้ง</label>
                            <label class="d-block mb-3"><input type="radio" name="bowels" value="2" {{ old("bowels") == "2" ? "checked" : "" }}>
                                กลั้นได้เป็นปกติ</label>
                        </div>
                    </div>

                    <!-- Q10 -->
                    <div class="question-block">
                        <h5>10. การกลั้นปัสสาวะ:</h5>
                        <div class="form-check">
                            <label class="d-block mb-3"><input type="radio" name="bladder" value="0" {{ old("bladder") == "0" ? "checked" : "" }} required>
                                กลั้นไม่ได้เลย</label>
                            <label class="d-block mb-3"><input type="radio" name="bladder" value="1" {{ old("bladder") == "1" ? "checked" : "" }}>
                                กลั้นไม่ได้บางครั้ง</label>
                            <label class="d-block mb-3"><input type="radio" name="bladder" value="2" {{ old("bladder") == "2" ? "checked" : "" }}>
                                กลั้นได้เป็นปกติ</label>
                        </div>
                    </div>

                    <!-- Result Summary -->
                    <div class="total-group mt-4">
                        <div>
                            <h5>คะแนนรวมทั้งหมด: <span id="total_score">0</span></h5>
                        </div>
                        <div>
                            <h5>จัดอยู่ในระดับ: <span id="group" class="badge bg-primary">N/A</span></h5>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <!-- Left: Back Button -->
                    <button type="button" class="btn btn-secondary btn-lg" id="btn-prev" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                    </button>

                    <!-- Right: Action Buttons Group -->
                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('adl.index') }}" class="btn btn-outline-danger btn-lg" id="btn-cancel">ยกเลิก</a>
                        <button type="button" class="btn btn-primary btn-lg" id="btn-next">
                            ถัดไป <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-success btn-lg" id="btn-submit" style="display: none;">
                            <i class="fas fa-save me-2"></i> บันทึกผลการประเมิน
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@push('scripts')
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
                if (step === 1) {
                    if (!document.getElementById('elderly_id').value) {
                        Swal.fire('แจ้งเตือน', 'กรุณาเลือกผู้สูงอายุก่อนไปขั้นตอนถัดไป', 'warning');
                        return false;
                    }
                }
                if (step === 2) {
                    const requiredNames = ['feeding', 'grooming', 'transfer', 'toilet_use', 'mobility'];
                    for (let name of requiredNames) {
                        if (!document.querySelector(`input[name="${name}"]:checked`)) {
                            Swal.fire('แจ้งเตือน', 'กรุณาตอบคำถามให้ครบทุกข้อในหน้านี้', 'warning');
                            return false;
                        }
                    }
                }
                return true;
            };

            @if ($errors->any())
                // Redisplayed after a validation error: jump to the first step that has one
                for (let s = 1; s <= totalSteps; s++) {
                    if (document.getElementById(`step-${s}`).querySelector(':invalid')) {
                        currentStep = s;
                        updateUI();
                        break;
                    }
                }
            @endif

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

            // Score Calculation logic
            const calculateTotalScore = () => {
                let score = 0;
                document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                    score += parseInt(radio.value);
                });

                document.getElementById('total_score').innerText = score;

                let group = 'N/A';
                let bgClass = 'bg-secondary';

                if (score >= 0 && score <= 4) {
                    group = 'กลุ่มติดเตียง (อาการหนัก)';
                    bgClass = 'bg-danger';
                } else if (score >= 5 && score <= 11) {
                    group = 'กลุ่มติดบ้าน (ช่วยเหลือตัวเองได้บ้าง)';
                    bgClass = 'bg-warning text-dark';
                } else if (score >= 12) {
                    group = 'กลุ่มติดสังคม (ช่วยเหลือตัวเองได้ดี)';
                    bgClass = 'bg-success';
                }

                const groupEl = document.getElementById('group');
                groupEl.innerText = group;
                groupEl.className = `badge ${bgClass}`;
            };

            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });
            calculateTotalScore();

            // Prevent double-submit on the final POST
            document.getElementById('adlForm').addEventListener('submit', function () {
                const submitBtn = document.getElementById('btn-submit');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> กำลังบันทึก...';
            });
        });
    </script>
@endpush