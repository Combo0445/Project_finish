<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'รายงาน')</title>
    <style>
        body {
            font-family: 'sarabun', sans-serif;
            font-size: 18px;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 5px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin: 0;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .report-meta {
            font-size: 14px;
            color: #000;
            margin-top: 2px;
        }

        .section-title {
            background: #f0f0f0;
            padding: 2px 8px;
            font-weight: bold;
            color: #000;
            border-left: 5px solid #000;
            border-bottom: 1px solid #000;
            margin: 5px 0 2px 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 18px;
        }

        th {
            background-color: #e0e0e0;
            color: #000;
            font-weight: 600;
            width: 40%;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
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
            @if(!empty($logo))
                <img src="{{ $logo }}" class="logo">
            @endif
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