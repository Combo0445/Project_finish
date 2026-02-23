<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet"/>
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet"/>
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .login-header h4 {
            font-weight: bold;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form .form-group {
            margin-bottom: 1rem;
        }

        .login-form .form-control {
            padding: 0.75rem;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
        }

        .login-form .form-control {
            padding-right: 40px;
        }

        .login-form .btn {
            background: #2dce89;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form .btn:hover {
            background: #2dce89;
        }

        .login-footer {
            text-align: center;
            margin-top: 1rem;
        }

        .login-footer a {
            color: #2dce89;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #ea3005;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .brand-logo img {
            max-width: 100px;
        }

        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 70%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        footer {
            background-color: #344767;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        footer ul li {
            display: inline;
        }

        footer ul li a {
            color: #fff;
            text-decoration: none;
        }

        footer ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h4>เข้าสู่ระบบ</h4>
                <p>ป้อนอีเมลหรือชื่อผู้ใช้และรหัสผ่านของคุณเพื่อลงชื่อเข้าใช้</p>
            </div>
            <form class="login-form" method="POST" action="{{ route('login.submit') }}">
                @if (Session::has('fail'))
                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                @csrf
                <div class="form-group">
                    <label for="login">อีเมล์หรือชื่อผู้ใช้:</label>
                    <input type="text" name="login" id="login" class="form-control" required>
                </div>
                <div class="form-group position-relative">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="fas fa-eye toggle-password" id="togglePassword"></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-login">เข้าสู่ระบบ</button>
                </div>
            </form>
            {{--  <div class="login-footer">
                <p>
                    หากลืมรหัสผ่าน? <a href="{{ route('password.request') }}">กดที่นี่เพื่อรีเซ็ทรหัสผ่าน</a>
                </p>
            </div>  --}}

        </div>
    </div>
    <footer>
        <p>&copy; 2024 สำนักงานสาธารณสุข อำเภอห้วยราช จังหวัดบุรีรัมย์</p>
    </footer>
    <script src="{{ url('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
