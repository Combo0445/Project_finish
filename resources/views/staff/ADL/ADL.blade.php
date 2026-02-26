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

        form div {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="radio"] {
            margin-right: 10px;
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
    </style>
@endpush

@section('content')
    <div class="assessment-container">
        <x-card>
            <x-slot name="header">
                <h4 class="mb-0">แบบฟอร์มประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL)</h4>
            </x-slot>

            @if(session('success'))
                <div class="alert alert-success text-white">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('adl.submit') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="elderly_id">เลือกผู้สูงอายุ:</label>
                            <select name="elderly_id" id="elderly_id" class="form-control" required>
                                @foreach($elderlies as $elderly)
                                    <option value="{{ $elderly->ID_Elderly }}">{{ $elderly->Name_Elderly }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>เจ้าหน้าที่ผู้ประเมิน:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->Name_User }}" readonly disabled>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- ADL Questions -->
                <div class="question-block">
                    <h5>1. Feeding (การรับประทานอาหาร):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="feeding" value="0" required>
                            ไม่สามารถตักอาหารเข้าปากได้ต้องมีคนป้อนให้</label><br>
                        <label><input type="radio" name="feeding" value="1"> ตักอาหารเองได้แต่ต้องมีคนช่วย เช่น
                            ช่วยใช้ช้อนตักเตรียมไว้ให้หรือตัดเป็นเล็ก ๆ ไว้ล่วงหน้า</label><br>
                        <label><input type="radio" name="feeding" value="2"> ตักอาหารและช่วยตัวเองได้เป็นปกติ</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>2. Grooming (การดูแลร่างกาย):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="grooming" value="0" required> ต้องการความช่วยเหลือ</label><br>
                        <label><input type="radio" name="grooming" value="1"> ทำเองได้
                            (รวมทั้งที่ทำได้เองถ้าเตรียมอุปกรณ์ไว้ให้)</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>3. Transfer (การย้ายตัว):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="transfer" value="0" required> ไม่สามารถนั่งได้ (นั่งแล้วจะล้มเสมอ)
                            หรือต้องใช้คนสองคนช่วยกันยกขึ้น</label><br>
                        <label><input type="radio" name="transfer" value="1"> ต้องการความช่วยเหลืออย่างมากจึงจะนั่งได้ เช่น
                            ต้องใช้คนที่แข็งแรงหรือมีทักษะ 1 คน หรือใช้คนทั่วไป 2
                            คนพยุงหรือดันขึ้นมาจึงจะนั่งอยู่ได้</label><br>
                        <label><input type="radio" name="transfer" value="2"> ต้องการความช่วยเหลือบ้าง เช่น บอกให้ทำตาม
                            หรือช่วยพยุงเล็กน้อย หรือต้องมีคนดูแลเพื่อความปลอดภัย</label><br>
                        <label><input type="radio" name="transfer" value="3"> ทำได้เอง</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>4. Toilet use (การใช้ห้องน้ำ):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="toilet_use" value="0" required> ช่วยตัวเองไม่ได้</label><br>
                        <label><input type="radio" name="toilet_use" value="1"> ทำเองได้บ้าง
                            (อย่างน้อยทำความสะอาดตัวเองได้หลังจากเสร็จธุระ) แต่ต้องการความช่วยเหลือในบางสิ่ง</label><br>
                        <label><input type="radio" name="toilet_use" value="2"> ทำเองได้ดี (ขึ้นนั่งและลงจากโถส้วมเองได้
                            ทำความสะอาดได้เรียบร้อยหลังจากเสร็จธุระ ถอดใส่เสื้อผ้าได้เรียบร้อย)</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>5. Mobility (การเคลื่อนที่ภายในห้องหรือบ้าน):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="mobility" value="0" required> เคลื่อนที่ไปไหนไม่ได้</label><br>
                        <label><input type="radio" name="mobility" value="1"> ต้องใช้รถเข็นช่วยตัวเองให้เคลื่อนที่ได้เอง
                            (ไม่ต้องมีคนเข็นให้) และจะต้องเข้าออกมุมห้องหรือประตูได้</label><br>
                        <label><input type="radio" name="mobility" value="2"> เดินหรือเคลื่อนที่โดยมีคนช่วย เช่น พยุง
                            หรือบอกให้ทำตาม หรือต้องให้ความสนใจดูแลเพื่อความปลอดภัย</label><br>
                        <label><input type="radio" name="mobility" value="3"> เดินหรือเคลื่อนที่ได้เอง</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>6. Dressing (การสวมใส่เสื้อผ้า):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="dressing" value="0" required> ต้องมีคนสวมให้
                            ช่วยตัวเองแทบไม่ได้หรือได้น้อย</label><br>
                        <label><input type="radio" name="dressing" value="1"> ช่วยตัวเองได้ประมาณร้อยละ 50
                            ที่เหลือต้องมีคนช่วย</label><br>
                        <label><input type="radio" name="dressing" value="2"> ช่วยตัวเองได้ดี (รวมทั้งการติดกระดุม รูดซิบ
                            หรือใช้เสื้อผ้าที่ดัดแปลงให้เหมาะสมก็ได้)</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>7. Stairs (การขึ้นลงบันได 1 ชั้น):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="stairs" value="0" required> ไม่สามารถทำได้</label><br>
                        <label><input type="radio" name="stairs" value="1"> ต้องการคนช่วย</label><br>
                        <label><input type="radio" name="stairs" value="2"> ขึ้นลงได้เอง (ถ้าต้องใช้เครื่องช่วยเดิน เช่น
                            walker จะต้องเอาขึ้นลงได้ด้วย)</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>8. Bathing (การอาบน้ำ):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="bathing" value="0" required> ต้องมีคนช่วยหรือทำให้</label><br>
                        <label><input type="radio" name="bathing" value="1"> อาบน้ำเองได้</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>9. Bowels (การกลั้นการถ่ายอุจจาระ):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="bowels" value="0" required> กลั้นไม่ได้
                            หรือต้องการการสวนอุจจาระอยู่เสมอ</label><br>
                        <label><input type="radio" name="bowels" value="1"> กลั้นไม่ได้บางครั้ง (เป็นน้อยกว่า 1
                            ครั้งต่อสัปดาห์)</label><br>
                        <label><input type="radio" name="bowels" value="2"> กลั้นได้เป็นปกติ</label><br>
                    </div>
                </div>

                <div class="question-block">
                    <h5>10. Bladder (การกลั้นปัสสาวะ):</h5>
                    <div class="form-check">
                        <label><input type="radio" name="bladder" value="0" required> กลั้นไม่ได้
                            หรือใส่สายสวนปัสสาวะแต่ไม่สามารถดูแลเองได้</label><br>
                        <label><input type="radio" name="bladder" value="1"> กลั้นไม่ได้บางครั้ง (เป็นน้อยกว่าวันละ 1
                            ครั้ง)</label><br>
                        <label><input type="radio" name="bladder" value="2"> กลั้นได้เป็นปกติ</label><br>
                    </div>
                </div>

                <!-- Total Score and Group -->
                <div class="total-group">
                    <div>
                        <h5>คะแนนรวม: <span id="total_score">0</span></h5>
                    </div>
                    <div>
                        <h5>ประเภทกลุ่ม: <span id="group">N/A</span></h5>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-success">ยืนยันการประเมิน</button>
                    <a href="{{ route('adl.index') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@push('scripts')
    <script>
        function calculateTotalScore() {
            let score = 0;
            const radios = document.querySelectorAll('input[type="radio"]:checked');
            radios.forEach(radio => {
                score += parseInt(radio.value);
            });
            document.getElementById('total_score').innerText = score;

            let group = '';
            if (score >= 0 && score <= 4) {
                group = 'กลุ่มติดเตียง';
            } else if (score >= 5 && score <= 11) {
                group = 'กลุ่มติดบ้าน';
            } else if (score >= 12) {
                group = 'กลุ่มติดสังคม';
            }
            document.getElementById('group').innerText = group;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });
            calculateTotalScore(); // Initial calculation
        });
    </script>
@endpush