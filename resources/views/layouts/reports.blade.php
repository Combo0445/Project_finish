<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'รายงาน')</title>
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url('{{ public_path("fonts/Sarabun-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #5e72e4;
            padding-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #5e72e4;
            margin: 0;
            text-transform: uppercase;
        }

        .report-meta {
            font-size: 12px;
            color: #8898aa;
            margin-top: 5px;
        }

        .section-title {
            background: #f6f9fc;
            padding: 8px 15px;
            font-weight: bold;
            color: #32325d;
            border-left: 4px solid #5e72e4;
            margin: 25px 0 15px 0;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #e9ecef;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fe;
            color: #8898aa;
            font-weight: 600;
            width: 40%;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            width: 200px;
            display: inline-block;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: none;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container">
        <header class="header">
            <img src="{{ public_path('images/Logo.png') }}" alt="Logo" class="logo">
            <h1 class="report-title">@yield('report_title')</h1>
            <div class="report-meta">
                พิมพ์เมื่อวันที่: {{ now()->translatedFormat('d F Y H:i') }} น.
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="footer">
            <p>ลงชื่อ..........................................................</p>
            <p>( @yield('reporter_name', 'เจ้าหน้าที่ผู้รับผิดชอบ') )</p>
            <p>ตำแหน่ง..........................................................</p>
        </footer>
    </div>
</body>

</html>