<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
</head>

<body>
    @include('layout.nav')

    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>สร้างบัญชี</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="Name_User">ชื่อ-นามสกุล (Name)</label>
                        <input type="text" id="Name_User" name="Name_User" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Username">ชื่อผู้ใช้ (Username)</label>
                        <input type="text" id="Username" name="Username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Email">อีเมล</label>
                        <input type="email" id="Email" name="Email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <label for="Password">รหัสผ่าน</label>
                        <input type="password" id="Password" name="Password" class="form-control" required>
                        <span class="fas fa-eye position-absolute" id="togglePassword"
                            style="top: 73%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Phone">เบอร์โทรศัพท์ (Phone)</label>
                        <input type="text" id="Phone" name="Phone" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="Address">ที่อยู่ (Address)</label>
                        <textarea id="Address" name="Address" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="Type_Personnel">ประเภทบุคลากร</label>
                        <select id="Type_Personnel" name="Type_Personnel" class="form-control" required>
                            <option value="">เลือกประเภทบุคลากร</option>
                            @foreach ($personnelTypes as $personnel)
                                @if ($personnel->Type_Personnel !== 'Admin')
                                    <option value="{{ $personnel->ID_Personnel }}">
                                        @php
                                            $roles = ['Staff' => 'เจ้าหน้าที่', 'Doctor' => 'แพทย์'];
                                        @endphp
                                        {{ $roles[$personnel->Type_Personnel] ?? $personnel->Type_Personnel }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3" id="elderly-type-group" style="display: none;">
                        <label for="Type_Elderly">ประเภทของผู้สูงอายุ</label>
                        <select id="Type_Elderly" name="Type_Elderly" class="form-control">
                            <option value="">เลือกประเภทของผู้สูงอายุ</option>
                            <option value="กลุ่มติดสังคม">ติดสังคม</option>
                            <option value="กลุ่มติดบ้าน">ติดบ้าน</option>
                            <option value="กลุ่มติดเตียง">ติดเตียง</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>

    <x-scripts />
    <x-register-user-scripts />
</body>

</html>