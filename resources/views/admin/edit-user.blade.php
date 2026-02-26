@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <x-card>
                <x-slot name="header">
                    <h4>แก้ไขข้อมูลผู้ใช้งาน: {{ $user->Username }}</h4>
                </x-slot>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <x-validation-errors />

                <form method="POST" action="{{ route('user.update', $user->ID_User) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="Name_User">ชื่อ-นามสกุล</label>
                        <input type="text" id="Name_User" name="Name_User" class="form-control"
                            value="{{ old('Name_User', $user->Name_User) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Username">ชื่อผู้ใช้</label>
                        <input type="text" id="Username" name="Username" class="form-control"
                            value="{{ old('Username', $user->Username) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Email">อีเมล</label>
                        <input type="email" id="Email" name="Email" class="form-control"
                            value="{{ old('Email', $user->Email) }}" required>
                    </div>

                    <div class="form-group mb-3 position-relative">
                        <label for="Password">รหัสผ่านใหม่ (ปล่อยว่างหากไม่ต้องการเปลี่ยน)</label>
                        <div class="input-group">
                            <input type="password" id="Password" name="Password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Phone">เบอร์โทรศัพท์</label>
                        <input type="text" id="Phone" name="Phone" class="form-control"
                            value="{{ old('Phone', $user->Phone) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="Type_Personnel">ประเภทบุคลากร</label>
                        <select id="Type_Personnel" name="Type_Personnel" class="form-control" required
                            onchange="toggleElderlyType()">
                            <option value="Admin" {{ old('Type_Personnel', $user->Type_Personnel) == 'Admin' ? 'selected' : '' }}>เจ้าหน้าที่ (ผู้ดูแลระบบ)</option>
                            <option value="Staff" {{ old('Type_Personnel', $user->Type_Personnel) == 'Staff' ? 'selected' : '' }}>เจ้าหน้าที่ (Staff)</option>
                            <option value="Doctor" {{ old('Type_Personnel', $user->Type_Personnel) == 'Doctor' ? 'selected' : '' }}>แพทย์ (Doctor)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="elderly-type-group"
                        style="display: {{ old('Type_Personnel', $user->Type_Personnel) == 'Doctor' ? 'block' : 'none' }};">
                        <label for="Type_Doctor">ประเภทของผู้สูงอายุ (สำหรับแพทย์)</label>
                        <select id="Type_Doctor" name="Type_Doctor" class="form-control">
                            <option value="">เลือกประเภทที่ดูแล</option>
                            <option value="กลุ่มติดสังคม" {{ old('Type_Doctor', $user->Type_Doctor) == 'กลุ่มติดสังคม' ? 'selected' : '' }}>ติดสังคม</option>
                            <option value="กลุ่มติดบ้าน" {{ old('Type_Doctor', $user->Type_Doctor) == 'กลุ่มติดบ้าน' ? 'selected' : '' }}>ติดบ้าน</option>
                            <option value="กลุ่มติดเตียง" {{ old('Type_Doctor', $user->Type_Doctor) == 'กลุ่มติดเตียง' ? 'selected' : '' }}>ติดเตียง</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="line_token">Line Token (เลือกใส่ได้)</label>
                        <input type="text" id="line_token" name="line_token" class="form-control"
                            value="{{ old('line_token', $user->line_token) }}">
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleElderlyType() {
            const role = document.getElementById('Type_Personnel').value;
            const group = document.getElementById('elderly-type-group');
            group.style.display = (role === 'Doctor') ? 'block' : 'none';
        }

        const btnTogglePassword = document.querySelector('#btnTogglePassword');
        const password = document.querySelector('#Password');
        const toggleIcon = document.querySelector('#toggleIcon');

        btnTogglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@endpush