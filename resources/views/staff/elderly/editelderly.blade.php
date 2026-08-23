@extends('layouts.app')

@section('title', 'Edit Elderly')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #fb6340;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
        }

        .custom-file-upload:hover {
            background-color: #ea3005;
        }

        #image-preview,
        #current-image {
            margin-top: 15px;
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <x-card>
                <x-slot name="header">
                    <h4>แก้ไขข้อมูลผู้สูงอายุ: {{ $elderly->Name_Elderly }}</h4>
                </x-slot>

                <x-validation-errors />

                <form action="{{ route('update-elderly', ['id' => $elderly->ID_Elderly]) }}" method="POST"
                    enctype="multipart/form-data" onsubmit="concatenateAddress()">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Name_Elderly">ชื่อ-สกุล:</label>
                                <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                    value="{{ old('Name_Elderly', $elderly->Name_Elderly) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="ID_Card">เลขบัตรประชาชน:</label>
                                <input type="text" id="ID_Card" name="ID_Card" class="form-control" maxlength="13"
                                    inputmode="numeric" pattern="\d{13}" placeholder="13 หลัก"
                                    value="{{ old('ID_Card', $elderly->ID_Card) }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="Gender">เพศ:</label>
                                <select id="Gender" name="Gender" class="form-control" required>
                                    <option value="">เลือกเพศ</option>
                                    <option value="ชาย" {{ old('Gender', $elderly->Gender) == 'ชาย' ? 'selected' : '' }}>ชาย
                                    </option>
                                    <option value="หญิง" {{ old('Gender', $elderly->Gender) == 'หญิง' ? 'selected' : '' }}>
                                        หญิง</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Age_input">อายุ (ปี) — กรอกแทนวันเกิดได้ถ้าไม่ทราบวันที่แน่นอน:</label>
                                <input type="number" id="Age_input" class="form-control" min="0" max="150"
                                    placeholder="เช่น 75" oninput="calculateBirthdayFromAge()">
                            </div>

                            <div class="form-group mb-3">
                                <label for="Birthday">วัน/เดือน/ปีเกิด:</label>
                                <input type="date" id="Birthday" name="Birthday" class="form-control"
                                    value="{{ $elderly->Birthday }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Phone_Elderly">เบอร์โทรศัพท์:</label>
                                <input type="text" id="Phone_Elderly" name="Phone_Elderly" class="form-control"
                                    value="{{ $elderly->Phone_Elderly }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Image_Elderly">รูปภาพ:</label><br>
                                <label class="custom-file-upload">
                                    <input type="file" id="Image_Elderly" name="Image_Elderly" style="display:none;"
                                        onchange="previewImage(event)">
                                    เลือกรูปภาพใหม่
                                </label>

                                <center>
                                    @if($elderly->image_url)
                                        <div id="current-image-container" style="margin-top: 15px;">
                                            <img src="{{ $elderly->image_url }}" alt="Elderly Image" id="current-image">
                                        </div>
                                    @endif
                                    <div id="new-image-container" style="display: none; margin-top: 15px;">
                                        <img id="image-preview" src="#" alt="Image Preview">
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>ข้อมูลที่อยู่</h5>
                    <x-map-picker :lat="$addressElderly->Latitude_position" :lng="$addressElderly->Longitude_position"
                        :address="$elderly->Address" />

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">ยกเลิก</a>
                        <button type="submit" class="btn btn-success">อัพเดตข้อมูล</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ผู้สูงอายุหลายคนไม่ทราบวันเกิดที่แน่นอน แต่ทราบอายุ -- ใช้ปีปัจจุบันลบด้วย
        // อายุที่กรอก (คงเดือน/วันเป็นวันนี้) เป็นวันเกิดโดยประมาณ ให้ผลลัพธ์อายุตรงกับ
        // ที่กรอกไว้พอดีถ้าคำนวณอายุใหม่จากวันเกิดนี้ในวันเดียวกัน
        function calculateBirthdayFromAge() {
            const age = parseInt(document.getElementById('Age_input').value, 10);
            if (isNaN(age) || age < 0) return;

            const today = new Date();
            const year = today.getFullYear() - age;
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            document.getElementById('Birthday').value = `${year}-${month}-${day}`;
        }

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                document.getElementById('new-image-container').style.display = 'block';
                if (document.getElementById('current-image-container')) document.getElementById('current-image-container').style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush