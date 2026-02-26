@extends('layouts.app')

@section('title', 'Add Elderly')

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

        #image-preview {
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
                    <h4>เพิ่มข้อมูลผู้สูงอายุ</h4>
                </x-slot>

                <x-validation-errors />

                <form action="{{ route('store-elderly') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="concatenateAddress()">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Name_Elderly">ชื่อ-สกุล:</label>
                                <input type="text" id="Name_Elderly" name="Name_Elderly" class="form-control"
                                    value="{{ old('Name_Elderly') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Gender">เพศ:</label>
                                <select id="Gender" name="Gender" class="form-control" required>
                                    <option value="">เลือกเพศ</option>
                                    <option value="ชาย" {{ old('Gender') == 'ชาย' ? 'selected' : '' }}>ชาย</option>
                                    <option value="หญิง" {{ old('Gender') == 'หญิง' ? 'selected' : '' }}>หญิง</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Birthday">วัน/เดือน/ปีเกิด:</label>
                                <input type="date" id="Birthday" name="Birthday" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="Phone_Elderly">เบอร์โทรศัพท์:</label>
                                <input type="text" id="Phone_Elderly" name="Phone_Elderly" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Image_Elderly">รูปภาพ:</label><br>
                                <label class="custom-file-upload">
                                    <input type="file" id="Image_Elderly" name="Image_Elderly" style="display:none;"
                                        onchange="previewImage(event)">
                                    เลือกไฟล์รูปภาพ
                                </label>
                                <center><img id="image-preview" src="#" alt="พรีวิวรูปภาพ" style="display:none;"></center>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>ข้อมูลที่อยู่</h5>
                    <x-map-picker />

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">ยกเลิก</a>
                        <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush