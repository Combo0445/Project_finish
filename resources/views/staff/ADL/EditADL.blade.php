<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ADL Assessment</title>
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

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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
        }

        .total-group span {
            font-size: 18px;
        }

        button,
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }

        button:hover,
        .back-button:hover {
            background-color: #2980b9;
        }

        .back-button {
            margin-left: 10px;
        }

        .success {
            background-color: #2ecc71;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
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
                group = 'กลุ่มติดสังคม';
            } else if (score >= 5 && score <= 11) {
                group = 'กลุ่มติดบ้าน';
            } else if (score >= 12) {
                group = 'กลุ่มติดเตียง';
            }
            document.getElementById('group').innerText = group;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });

            calculateTotalScore(); // Calculate initial score
        });
    </script>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>แก้ไขแบบฟอร์มประเมินความสามารถในการดำเนินชีวิตประจำวัน (ADL)</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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

                <form method="POST" action="{{ route('adl.update', ['id' => $adl->ID_ADL]) }}">
                    @csrf
                    @method('PATCH')

                    <!-- Select Elderly Name -->
                    <!-- ซ่อนค่า ID_Elderly ที่แท้จริง -->
<input type="hidden" name="elderly_id" value="{{ $adl->ID_Elderly }}">

<!-- โชว์ชื่อเฉย ๆ ให้ผู้ใช้ดู -->
<div class="form-group">
    <label for="elderly_name">ชื่อผู้สูงอายุ:</label>
    <input type="text" id="elderly_name" class="form-control" value="{{ $adl->Name_Elderly }}" readonly>
</div>

                    <!-- Display Name of User performing the assessment -->
                    <div class="form-group">
                        <label>เจ้าหน้าที่ผู้รับผิดชอบในการทำแบบฟอร์ม:</label>
                        <span>{{ $adl->Name_User }}</span>
                    </div>

                    <!-- ADL Questions -->
                    <h5>1. Feeding (การรับประทานอาหาร):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="feeding" value="0" required {{ $adl->Feeding == 0 ? 'checked' : '' }}> ไม่สามารถตักอาหารเข้าปากได้ต้องมีคนป้อนให้</label><br>
                        <label><input type="radio" name="feeding" value="1" {{ $adl->Feeding == 1 ? 'checked' : '' }}> ตักอาหารเองได้แต่ต้องมีคนช่วย เช่น ช่วยใช้ช้อนตักเตรียมไว้ให้หรือตัดเป็นเล็ก ๆ ไว้ล่วงหน้า</label><br>
                        <label><input type="radio" name="feeding" value="2" {{ $adl->Feeding == 2 ? 'checked' : '' }}> ตักอาหารและช่วยตัวเองได้เป็นปกติ</label><br>
                    </div>

                    <h5>2. Grooming (การดูแลร่างกาย):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="grooming" value="0" required {{ $adl->Grooming == 0 ? 'checked' : '' }}> ต้องการความช่วยเหลือ</label><br>
                        <label><input type="radio" name="grooming" value="1" {{ $adl->Grooming == 1 ? 'checked' : '' }}> ทำเองได้ (รวมทั้งที่ทำได้เองถ้าเตรียมอุปกรณ์ไว้ให้)</label><br>
                    </div>

                    <h5>3. Transfer (การย้ายตัว):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="transfer" value="0" required {{ $adl->Transfer == 0 ? 'checked' : '' }}> ไม่สามารถนั่งได้ (นั่งแล้วจะล้มเสมอ) หรือต้องใช้คนสองคนช่วยกันยกขึ้น</label><br>
                        <label><input type="radio" name="transfer" value="1" {{ $adl->Transfer == 1 ? 'checked' : '' }}> ต้องการความช่วยเหลืออย่างมากจึงจะนั่งได้ เช่น ต้องใช้คนที่แข็งแรงหรือมีทักษะ 1 คน หรือใช้คนทั่วไป 2 คนพยุงหรือดันขึ้นมาจึงจะนั่งอยู่ได้</label><br>
                        <label><input type="radio" name="transfer" value="2" {{ $adl->Transfer == 2 ? 'checked' : '' }}> ต้องการความช่วยเหลือบ้าง เช่น บอกให้ทำตาม หรือช่วยพยุงเล็กน้อย หรือต้องมีคนดูแลเพื่อความปลอดภัย</label><br>
                        <label><input type="radio" name="transfer" value="3" {{ $adl->Transfer == 3 ? 'checked' : '' }}> ทำได้เอง</label><br>
                    </div>

                    <h5>4. Toilet use (การใช้ห้องน้ำ):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="toilet_use" value="0" required {{ $adl->Toilet_use == 0 ? 'checked' : '' }}> ช่วยตัวเองไม่ได้</label><br>
                        <label><input type="radio" name="toilet_use" value="1" {{ $adl->Toilet_use == 1 ? 'checked' : '' }}> ทำเองได้บ้าง (อย่างน้อยทำความสะอาดตัวเองได้หลังจากเสร็จธุระ) แต่ต้องการความช่วยเหลือในบางสิ่ง</label><br>
                        <label><input type="radio" name="toilet_use" value="2" {{ $adl->Toilet_use == 2 ? 'checked' : '' }}> ทำเองได้ดี (ขึ้นนั่งและลงจากโถส้วมเองได้ ทำความสะอาดได้เรียบร้อยหลังจากเสร็จธุระ ถอดใส่เสื้อผ้าได้เรียบร้อย)</label><br>
                    </div>

                    <h5>5. Mobility (การเคลื่อนที่ภายในห้องหรือบ้าน):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="mobility" value="0" required {{ $adl->Mobility == 0 ? 'checked' : '' }}> เคลื่อนที่ไปไหนไม่ได้</label><br>
                        <label><input type="radio" name="mobility" value="1" {{ $adl->Mobility == 1 ? 'checked' : '' }}> ต้องใช้รถเข็นช่วยตัวเองให้เคลื่อนที่ได้เอง (ไม่ต้องมีคนเข็นให้) และจะต้องเข้าออกมุมห้องหรือประตูได้</label><br>
                        <label><input type="radio" name="mobility" value="2" {{ $adl->Mobility == 2 ? 'checked' : '' }}> เดินหรือเคลื่อนที่โดยมีคนช่วย เช่น พยุง หรือบอกให้ทำตาม หรือต้องให้ความสนใจดูแลเพื่อความปลอดภัย</label><br>
                        <label><input type="radio" name="mobility" value="3" {{ $adl->Mobility == 3 ? 'checked' : '' }}> เดินหรือเคลื่อนที่ได้เอง</label><br>
                    </div>

                    <h5>6. Dressing (การสวมใส่เสื้อผ้า):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="dressing" value="0" required {{ $adl->Dressing == 0 ? 'checked' : '' }}> ต้องมีคนสวมให้ ช่วยตัวเองแทบไม่ได้หรือได้น้อย</label><br>
                        <label><input type="radio" name="dressing" value="1" {{ $adl->Dressing == 1 ? 'checked' : '' }}> ช่วยตัวเองได้ประมาณร้อยละ 50 ที่เหลือต้องมีคนช่วย</label><br>
                        <label><input type="radio" name="dressing" value="2" {{ $adl->Dressing == 2 ? 'checked' : '' }}> ช่วยตัวเองได้ดี (รวมทั้งการติดกระดุม รูดซิบ หรือใช้เสื้อผ้าที่ดัดแปลงให้เหมาะสมก็ได้)</label><br>
                    </div>

                    <h5>7. Stairs (การขึ้นลงบันได 1 ชั้น):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="stairs" value="0" required {{ $adl->Stairs == 0 ? 'checked' : '' }}> ไม่สามารถทำได้</label><br>
                        <label><input type="radio" name="stairs" value="1" {{ $adl->Stairs == 1 ? 'checked' : '' }}> ต้องการคนช่วย</label><br>
                        <label><input type="radio" name="stairs" value="2" {{ $adl->Stairs == 2 ? 'checked' : '' }}> ขึ้นลงได้เอง (ถ้าต้องใช้เครื่องช่วยเดิน เช่น walker จะต้องเอาขึ้นลงได้ด้วย)</label><br>
                    </div>

                    <h5>8. Bathing (การอาบน้ำ):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="bathing" value="0" required {{ $adl->Bathing == 0 ? 'checked' : '' }}> ต้องมีคนช่วยหรือทำให้</label><br>
                        <label><input type="radio" name="bathing" value="1" {{ $adl->Bathing == 1 ? 'checked' : '' }}> อาบน้ำเองได้</label><br>
                    </div>

                    <h5>9. Bowels (การกลั้นการถ่ายอุจจาระในระยะ 1 สัปดาห์ที่ผ่านมา):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="bowels" value="0" required {{ $adl->Bowels == 0 ? 'checked' : '' }}> กลั้นไม่ได้ หรือต้องการการสวนอุจจาระอยู่เสมอ</label><br>
                        <label><input type="radio" name="bowels" value="1" {{ $adl->Bowels == 1 ? 'checked' : '' }}> กลั้นไม่ได้บางครั้ง (เป็นน้อยกว่า 1 ครั้งต่อสัปดาห์)</label><br>
                        <label><input type="radio" name="bowels" value="2" {{ $adl->Bowels == 2 ? 'checked' : '' }}> กลั้นได้เป็นปกติ</label><br>
                    </div>

                    <h5>10. Bladder (การกลั้นปัสสาวะในระยะ 1 สัปดาห์ที่ผ่านมา):</h5>
                    <div class="form-group">
                        <label><input type="radio" name="bladder" value="0" required {{ $adl->Bladder == 0 ? 'checked' : '' }}> กลั้นไม่ได้ หรือใส่สายสวนปัสสาวะแต่ไม่สามารถดูแลเองได้</label><br>
                        <label><input type="radio" name="bladder" value="1" {{ $adl->Bladder == 1 ? 'checked' : '' }}> กลั้นไม่ได้บางครั้ง (เป็นน้อยกว่าวันละ 1 ครั้ง)</label><br>
                        <label><input type="radio" name="bladder" value="2" {{ $adl->Bladder == 2 ? 'checked' : '' }}> กลั้นได้เป็นปกติ</label><br>
                    </div>

                    <!-- Total Score and Group -->
                    <div class="total-group">
                        <div>
                            <h4>คะแนนรวม:</h4>
                            <span id="total_score">0</span>
                        </div>
                        <div>
                            <h4>ประเภทกลุ่ม: <span id="group">N/A</span></h4>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">อัพเดต</button>
                    <a href="{{ route('adl.index') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
