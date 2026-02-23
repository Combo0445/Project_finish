<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การเข้าถึงถูกปฏิเสธ</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 4rem;
            color: #dc3545;
        }

        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <h1>การเข้าถึงถูกปฏิเสธ</h1>
            <div class="alert alert-danger">
                @if(session('fail'))
                    {{ session('fail') }}
                @else
                    ข้อผิดพลาดไม่ทราบสาเหตุ
                @endif
            </div>
            <!-- ปุ่มกลับไปยังหน้าที่ผู้ใช้มาก่อนหน้านี้ -->
            <button onclick="history.back()" class="btn">ย้อนกลับ</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
