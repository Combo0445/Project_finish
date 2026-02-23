<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            min-height: 100vh;
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

        .login-form .btn {
            background: #fb6340;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form .btn:hover {
            background: #ea3005;
        }

        .login-footer {
            text-align: center;
            margin-top: 1rem;
        }

        .login-footer a {
            color: #fb6340;
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
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h4>รีเซ็ตรหัสผ่าน</h4>
                <p>ป้อนอีเมลเพื่อขอรีเซ็ทรหัสผ่าน</p>
            </div>
            <form action="{{ route('password.verify') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">อีเมล์</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Verification Code</button>
            </form>
        </div>
    </div>

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
    </script>
</body>

</html>
