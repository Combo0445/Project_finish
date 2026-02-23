<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register User</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="Username">ชื่อผู้ใช้</label>
                        <input type="text" id="Username" name="Username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Email">อีเมล</label>
                        <input type="email" id="Email" name="Email" class="form-control" required>
                    </div>
                    <div class="form-group position-relative">
                        <label for="Password">รหัสผ่าน</label>
                        <input type="password" id="Password" name="Password" class="form-control" required>
                        <span class="fas fa-eye position-absolute" id="togglePassword"
                            style="top: 73%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                    </div>

                    <div class="form-group">
                        <label for="Type_Personnel">ประเภทบุคลากร</label>
                        <select id="Type_Personnel" name="Type_Personnel" class="form-control" required>
                            <option value="">เลือกประเภทบุคลากร</option>
                            @foreach ($personnelTypes as $personnel)
                                @if ($personnel->Type_Personnel !== 'Admin')
                                    <option value="{{ $personnel->ID_Personnel }}">
                                        @if ($personnel->Type_Personnel === 'Staff')
                                            เจ้าหน้าที่
                                        @elseif ($personnel->Type_Personnel === 'Doctor')
                                            แพทย์
                                        @else
                                            {{ $personnel->Type_Personnel }}
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="elderly-type-group" style="display: none;">
                        <label for="Type_Elderly">ประเภทของผู้สูงอายุ</label>
                        <select id="Type_Elderly" name="Type_Elderly" class="form-control">
                            <option value="">เลือกประเภทของผู้สูงอายุ</option>
                            <option value="กลุ่มติดสังคม">ติดสังคม</option>
                            <option value="กลุ่มติดบ้าน">ติดบ้าน</option>
                            <option value="กลุ่มติดเตียง">ติดเตียง</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('Type_Personnel').addEventListener('change', function() {
            var elderlyTypeGroup = document.getElementById('elderly-type-group');
            if (this.options[this.selectedIndex].text === 'แพทย์') {
                elderlyTypeGroup.style.display = 'block';
            } else {
                elderlyTypeGroup.style.display = 'none';
            }
        });

        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('Password');

        togglePassword.addEventListener('click', function() {

            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);


            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
