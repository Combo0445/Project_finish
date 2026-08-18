@extends('layouts.app')

@section('title', 'แก้ไขแบบประเมิน TAI')

@push('styles')
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
@endpush

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>แบบประเมินความสามารถในการดำเนินชีวิตประจำวัน (TAI)</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
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

                <form method="POST" action="{{ route('tai.update', $tai->id) }}">
                    @csrf
                    @method('patch')

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
                        @foreach ([0 => 'ไม่สามารถเคลื่อนไหวเองได้ (0 คะแนน)', 1 => 'Roll over (1 คะแนน)', 2 => 'สามารถยืนได้บางครั้ง (2 คะแนน)', 3 => 'สามารถเดินเองได้ในระยะสั้น (3 คะแนน)', 4 => 'สามารถเดินเองได้ในระยะยาว (4 คะแนน)', 5 => 'สามารถเคลื่อนไหวได้อย่างปกติ (5 คะแนน)'] as $value => $label)
                            <label>
                                <input type="radio" name="mobility" value="{{ $value }}"
                                    {{ old('mobility', $tai->mobility) == $value ? 'checked' : '' }}>
                                {{ $label }}
                            </label><br>
                        @endforeach
                    </div>

                    <h5>2. Confuse:</h5>
                    <div class="form-group">
                        @foreach ([0 => 'สับสนมากไม่สามารถทำกิจกรรมได้ (0 คะแนน)', 1 => 'สับสนเล็กน้อย (1 คะแนน)', 2 => 'สามารถทำกิจกรรมบางอย่างได้ (2 คะแนน)', 3 => 'ทำกิจกรรมได้บางประการ (3 คะแนน)', 4 => 'สามารถทำกิจกรรมได้เกือบทั้งหมด (4 คะแนน)', 5 => 'ทำกิจกรรมได้ตามปกติ (5 คะแนน)'] as $value => $label)
                            <label>
                                <input type="radio" name="confuse" value="{{ $value }}"
                                    {{ old('confuse', $tai->confuse) == $value ? 'checked' : '' }}> {{ $label }}
                            </label><br>
                        @endforeach
                    </div>

                    <h5>3. Feeding:</h5>
                    <div class="form-group">
                        @foreach ([0 => 'ไม่สามารถทานอาหารเองได้ (0 คะแนน)', 1 => 'ทานอาหารได้บ้างแต่ต้องการความช่วยเหลือ (1 คะแนน)', 2 => 'ทานอาหารได้บางประเภทเองได้ (2 คะแนน)', 3 => 'ทานอาหารได้เกือบทุกประเภทเองได้ (3 คะแนน)', 4 => 'ทานอาหารเองได้ทุกประเภท (4 คะแนน)', 5 => 'ทานอาหารเองได้ทั้งหมดอย่างไม่มีปัญหา (5 คะแนน)'] as $value => $label)
                            <label>
                                <input type="radio" name="feed" value="{{ $value }}"
                                    {{ old('feed', $tai->feed) == $value ? 'checked' : '' }}> {{ $label }}
                            </label><br>
                        @endforeach
                    </div>

                    <h5>4. Toilet:</h5>
                    <div class="form-group">
                        @foreach ([0 => 'ไม่สามารถใช้ห้องน้ำเองได้ (0 คะแนน)', 1 => 'ต้องการความช่วยเหลือในการใช้ห้องน้ำ (1 คะแนน)', 2 => 'สามารถใช้ห้องน้ำได้แต่ต้องการความช่วยเหลือบางอย่าง (2 คะแนน)', 3 => 'สามารถใช้ห้องน้ำได้เองเกือบทั้งหมด (3 คะแนน)', 4 => 'สามารถใช้ห้องน้ำเองได้ทั้งหมด (4 คะแนน)', 5 => 'สามารถใช้ห้องน้ำเองได้โดยไม่มีปัญหา (5 คะแนน)'] as $value => $label)
                            <label>
                                <input type="radio" name="toilet" value="{{ $value }}"
                                    {{ old('toilet', $tai->toilet) == $value ? 'checked' : '' }}> {{ $label }}
                            </label><br>
                        @endforeach
                    </div>

                    <div class="total-group">
                        <div>
                            <h4>ประเภทกลุ่ม: <span id="group_code">N/A</span></h4>
                        </div>
                        <div>
                            <h4>ประเภทกลุ่มผู้สูงอายุ: <span id="group">N/A</span></h4>
                        </div>
                    </div>
                    <input type="hidden" name="group" id="group_code_input">

                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <a href="{{ route('tai.index') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function calculateTotalScore() {
            let mobility = document.querySelector('input[name="mobility"]:checked');
            let confuse = document.querySelector('input[name="confuse"]:checked');
            let feed = document.querySelector('input[name="feed"]:checked');
            let toilet = document.querySelector('input[name="toilet"]:checked');

            // ตรวจสอบว่ามีค่าหรือไม่ ถ้าไม่มีให้เป็น null
            mobility = mobility ? parseInt(mobility.value, 10) : null;
            confuse = confuse ? parseInt(confuse.value, 10) : null;
            feed = feed ? parseInt(feed.value, 10) : null;
            toilet = toilet ? parseInt(toilet.value, 10) : null;

            let groupCode = '',
                groupText = '';

            // ตรวจสอบว่ามีค่าทั้งหมดหรือไม่
            if (mobility === null || confuse === null || feed === null || toilet === null) {
                groupCode = 'ไม่สามารถประเมินได้';
                groupText = 'ยังไม่ได้ประเมิน';
            } else {
                // เงื่อนไขการคำนวณ Group Code
                if (mobility === 5 && confuse === 5 && feed === 5 && toilet === 5) {
                    groupCode = 'B5';
                } else if (mobility >= 3 && confuse >= 4 && feed >= 4 && toilet >= 4) {
                    groupCode = 'B4';
                } else if (mobility >= 3 && confuse >= 4 && feed <= 3 && toilet <= 3) {
                    groupCode = 'B3';
                } else if (mobility >= 3 && confuse <= 3 && feed >= 4 && toilet >= 4) {
                    groupCode = 'C4';
                } else if (mobility >= 3 && confuse <= 3 && feed === 3 && toilet === 4) {
                    groupCode = 'C3';
                } else if (mobility >= 3 && confuse <= 3 && feed === 4 && toilet === 3) {
                    groupCode = 'C3';
                } else if (mobility >= 3 && confuse <= 3 && feed <= 3 && toilet <= 3) {
                    groupCode = 'C2';
                } else if (mobility <= 2 && feed >= 4) {
                    groupCode = 'I3';
                } else if (mobility <= 2 && feed === 3) {
                    groupCode = 'I2';
                } else if (mobility <= 2 && feed <= 2) {
                    groupCode = 'I1';
                }
            }

            // กำหนดประเภทของกลุ่มตาม Group Code
            if (groupCode.startsWith('B')) {
                groupText = 'กลุ่มปกติ';
            } else if (groupCode.startsWith('C')) {
                groupText = 'กลุ่มติดบ้าน';
            } else if (groupCode.startsWith('I')) {
                groupText = 'กลุ่มติดเตียง';
            } else if (groupCode === 'ไม่สามารถประเมินได้') {
                groupText = 'ยังไม่ได้ประเมิน';
            } else {
                groupText = 'ไม่พบคะแนน';
                groupCode = ''; // ถ้าไม่มีเงื่อนไขไหนตรง ให้เคลียร์ค่า
            }

            // แสดงผลในหน้าจอ
            document.getElementById('group').innerText = groupText;
            document.getElementById('group_code').innerText = groupCode;
            document.getElementById('group_code_input').value = groupCode;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });
            calculateTotalScore(); // คำนวณค่าครั้งแรกเมื่อโหลดหน้าเว็บ
        });
    </script>
@endpush
