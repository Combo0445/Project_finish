<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขแบบประเมิน TAI</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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

        .total-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>แก้ไขแบบประเมินความสามารถในการดำเนินชีวิตประจำวัน (TAI)</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('tai.update', $tai->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>ชื่อผู้สูงอายุ:</label>
                        <input type="text" class="form-control" value="{{ $elderly->Name_Elderly }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>เจ้าหน้าที่ผู้รับผิดชอบ:</label>
                        <span>{{ $user->Name_User }}</span>
                    </div>

                    <h5>1. Mobility:</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="mobility" value="{{ $i }}" {{ $tai->mobility == $i ? 'checked' : '' }}> {{ $i }}</label><br>
                        @endfor
                    </div>

                    <h5>2. Confuse:</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="confuse" value="{{ $i }}" {{ $tai->confuse == $i ? 'checked' : '' }}> {{ $i }}</label><br>
                        @endfor
                    </div>

                    <h5>3. Feeding:</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="feed" value="{{ $i }}" {{ $tai->feed == $i ? 'checked' : '' }}> {{ $i }}</label><br>
                        @endfor
                    </div>

                    <h5>4. Toilet:</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="toilet" value="{{ $i }}" {{ $tai->toilet == $i ? 'checked' : '' }}> {{ $i }}</label><br>
                        @endfor
                    </div>

                    <div class="total-group">
                        <div>
                            <h4>คะแนนรวม: <span id="total_score">0</span></h4>
                        </div>
                        <div>
                            <h4>ประเภทกลุ่ม: <span id="group">N/A</span></h4>
                        </div>
                        <div>
                            <h4>Group Code: <span id="group_code">N/A</span></h4>
                        </div>
                    </div>

                    <input type="hidden" name="group" id="group_code_input">

                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <a href="{{ route('tai.index') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculateTotalScore() {
            let score = 0;
            const radios = document.querySelectorAll('input[type="radio"]:checked');

            let mobility = null;
            let confuse = null;
            let feed = null;
            let toilet = null;

            radios.forEach(radio => {
                score += parseInt(radio.value);
                if (radio.name === 'mobility') mobility = radio.value;
                if (radio.name === 'confuse') confuse = radio.value;
                if (radio.name === 'feed') feed = radio.value;
                if (radio.name === 'toilet') toilet = radio.value;
            });

            document.getElementById('total_score').innerText = score;

            let groupText = '', groupCode = '';
            if (mobility !== null && confuse !== null && feed !== null && toilet !== null) {
                if (mobility === '5' && confuse === '5' && feed === '5' && toilet === '5') {
                    groupText = 'B5 เป็นกลุ่มปกติ';
                    groupCode = 'B5';
                } else if (mobility >= '3' && confuse >= '4' && feed >= '4' && toilet >= '4') {
                    groupText = 'B4 เป็นกลุ่มปกติ';
                    groupCode = 'B4';
                } else if (mobility >= '3' && confuse >= '4' && feed <= '3' && toilet <= '3') {
                    groupText = 'B3 เป็นกลุ่มปกติ';
                    groupCode = 'B3';
                } else if (mobility >= '3' && confuse <= '3' && feed >= '4' && toilet >= '4') {
                    groupText = 'C4 เป็นกลุ่มติดบ้าน';
                    groupCode = 'C4';
                } else if (mobility >= '3' && confuse <= '3' && feed === '3' && toilet === '4') {
                    groupText = 'C3 เป็นกลุ่มติดบ้าน';
                    groupCode = 'C3';
                } else if (mobility >= '3' && confuse <= '3' && feed === '4' && toilet === '3') {
                    groupText = 'C3 เป็นกลุ่มติดบ้าน';
                    groupCode = 'C3';
                } else if (mobility >= '3' && confuse <= '3' && feed <= '3' && toilet <= '3') {
                    groupText = 'C2 เป็นกลุ่มติดบ้าน';
                    groupCode = 'C2';
                } else if (mobility <= '2' && feed >= '4') {
                    groupText = 'I3 เป็นกลุ่มติดเตียง';
                    groupCode = 'I3';
                } else if (mobility <= '2' && feed === '3') {
                    groupText = 'I2 เป็นกลุ่มติดเตียง';
                    groupCode = 'I2';
                } else if (mobility <= '2' && feed <= '2') {
                    groupText = 'I1 เป็นกลุ่มติดเตียง';
                    groupCode = 'I1';
                } else {
                    groupText = 'ไม่พบคะแนน';
                    groupCode = '';
                }
            }

            document.getElementById('group').innerText = groupText;
            document.getElementById('group_code').innerText = groupCode;
            document.getElementById('group_code_input').value = groupCode;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });
            calculateTotalScore(); // <<<<=== สำคัญ ต้องมี
        });
    </script>
</body>

</html>
