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

            let mobility = null;
            let confuse = null;
            let feed = null;
            let toilet = null;

            radios.forEach(radio => {
                score += parseInt(radio.value);
                if (radio.name === 'mobility') {
                    mobility = parseInt(radio.value);
                }
                if (radio.name === 'confuse') {
                    confuse = parseInt(radio.value);
                }
                if (radio.name === 'feed') {
                    feed = parseInt(radio.value);
                }
                if (radio.name === 'toilet') {
                    toilet = parseInt(radio.value);
                }
            });

            let group = '';
            if (score >= 0 && score <= 4) {
                group = 'กลุ่มติดสังคม';
            } else if (score >= 5 && score <= 11) {
                group = 'กลุ่มติดบ้าน';
            } else if (score >= 12) {
                group = 'กลุ่มติดเตียง';
            }

            const groupCode = calculateGroupCode(mobility, confuse, feed, toilet);

            document.getElementById('total_score').innerText = score;
            document.getElementById('group').innerText = group;
            document.getElementById('group_code').innerText = groupCode;
        }

        function calculateGroupCode(mobility, confuse, feed, toilet) {
            if (mobility === 5 && confuse === 5 && feed === 5 && toilet === 5) {
                return 'B5';
            } else if (mobility >= 3 && confuse >= 4 && feed >= 4 && toilet >= 4) {
                return 'B4';
            } else if (mobility >= 3 && confuse >= 4 && feed <= 3 && toilet <= 3) {
                return 'B3';
            } else if (mobility >= 3 && confuse <= 3 && feed >= 4 && toilet >= 4) {
                return 'C4';
            } else if (mobility >= 3 && confuse <= 3 && feed === 3 && toilet === 4) {
                return 'C3';
            } else if (mobility >= 3 && confuse <= 3 && feed === 4 && toilet === 3) {
                return 'C3';
            } else if (mobility >= 3 && confuse <= 3 && feed <= 3 && toilet <= 3) {
                return 'C2';
            } else if (mobility <= 2 && feed >= 4) {
                return 'I3';
            } else if (mobility <= 2 && feed === 3) {
                return 'I2';
            } else if (mobility <= 2 && feed <= 2) {
                return 'I1';
            } else {
                return 'ไม่พบคะแนน';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });

            calculateTotalScore();
        });
    </script>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>เพิ่มแบบประเมินความสามารถในการดำเนินชีวิตประจำวัน (TAI)</h4>
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

                    <!-- Display Elderly Name -->
                    <div class="form-group">
                        <label for="elderly_name">ชื่อผู้สูงอายุ:</label>
                        <input type="text" class="form-control" id="elderly_name" value="{{ $elderly->Name_Elderly }}" readonly>
                        <input type="hidden" name="elderly_id" value="{{ $elderly->ID_Elderly }}">
                    </div>

                    <!-- Display User -->
                    <div class="form-group">
                        <label>เจ้าหน้าที่ผู้รับผิดชอบ:</label>
                        <span>{{ $user->Name_User }}</span>
                        <input type="hidden" name="user_id" value="{{ $user->ID_User }}">
                    </div>

                    <!-- Assessment -->
                    <h5>1. Mobility (การเคลื่อนที่):</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="mobility" value="{{ $i }}" {{ $tai->mobility == $i ? 'checked' : '' }}>                            > {{ $i }}
                        </label><br>
                        @endfor
                    </div>

                    <h5>2. Confuse (สับสน):</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="confuse" value="{{ $i }}" {{ $tai->confuse == $i ? 'checked' : '' }}></label><br>
                        @endfor
                    </div>

                    <h5>3. Feeding (การรับประทานอาหาร):</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="feed" value="{{ $i }}" {{ $tai->feed == $i ? 'checked' : '' }}></label><br>
                        @endfor
                    </div>

                    <h5>4. Toilet (การใช้ห้องน้ำ):</h5>
                    <div class="form-group">
                        @for ($i = 0; $i <= 5; $i++)
                        <label><input type="radio" name="toilet" value="{{ $i }}" {{ $tai->toilet == $i ? 'checked' : '' }}></label><br>
                        @endfor
                    </div>

                    <!-- Total -->
                    <div class="total-group">
                        <div>
                            <h4>คะแนนรวม:</h4>
                            <span id="total_score">0</span>
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
        // เพิ่มเก็บค่า group และ group_code ลง input
        function calculateTotalScore() {
            let score = 0;
            const radios = document.querySelectorAll('input[type="radio"]:checked');

            let mobility = null;
            let confuse = null;
            let feed = null;
            let toilet = null;

            radios.forEach(radio => {
                score += parseInt(radio.value);
                if (radio.name === 'mobility') {
                    mobility = radio.value;
                }
                if (radio.name === 'confuse') {
                    confuse = radio.value;
                }
                if (radio.name === 'feed') {
                    feed = radio.value;
                }
                if (radio.name === 'toilet') {
                    toilet = radio.value;
                }
            });

            document.getElementById('total_score').innerText = score;

            let groupText = '';
            let groupCode = '';

            // ถ้ามีข้อมูลครบทั้ง 4 ช่อง
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
            document.getElementById('group_input').value = groupText;
            document.getElementById('group_code_input').value = groupCode;
        }

    </script>
</body>

</html>
