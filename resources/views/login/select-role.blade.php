<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>เลือกบทบาท</title>
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

        .role-container {
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .role-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
        }

        .role-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .role-header h4 {
            font-weight: bold;
        }

        .role-options {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .role-option-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 1rem 1.25rem;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff;
            color: #344767;
            font-size: 1rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .role-option-btn:hover {
            border-color: #2dce89;
            background: #f0fdf7;
            color: #2dce89;
        }

        .role-option-btn i {
            font-size: 1.4rem;
            width: 28px;
            text-align: center;
        }

        footer {
            background-color: #344767;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="role-container">
        <div class="role-card">
            <div class="role-header">
                <h4>เลือกบทบาทที่ต้องการเข้าใช้งาน</h4>
                <p class="mb-0 text-muted">บัญชีนี้สามารถเข้าใช้งานได้หลายบทบาท กรุณาเลือกบทบาทที่ต้องการ</p>
            </div>

            <div class="role-options">
                @php
                    $icons = [
                        'Admin' => 'fa-user-shield',
                        'Staff' => 'fa-user-tie',
                        'Doctor' => 'fa-user-doctor',
                    ];
                @endphp
                @foreach ($roles as $value => $label)
                    <form method="POST" action="{{ route('select-role.submit') }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ $value }}">
                        <button type="submit" class="role-option-btn">
                            <i class="fas {{ $icons[$value] ?? 'fa-user' }}"></i>
                            {{ $label }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2026 สำนักงานสาธารณสุขตัวอย่าง (Demo)</p>
    </footer>
    <script src="{{ url('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/core/bootstrap.min.js') }}"></script>
</body>

</html>
