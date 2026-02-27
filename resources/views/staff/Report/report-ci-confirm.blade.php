<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'รายงานคำแนะนำการดูแล' }}</title>
    <style>
        body {
            font-family: 'sarabun', sans-serif;
            font-size: 18px;
            color: #000;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #2d5a27;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .report-title {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            text-align: right;
        }

        .agency-info {
            text-align: left;
            font-size: 16px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #e0e0e0;
            color: #000;
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-weight: bold;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            font-size: 18px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 12px;
            text-align: right;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Status colors */
        .status-confirmed {
            color: #28a745;
            font-weight: bold;
        }

        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="15%">
                @if(!empty($logo))
                    <img src="{{ $logo }}" class="logo">
                @endif
            </td>
            <td width="45%" class="agency-info">
                <div class="text-bold" style="font-size: 20px;">
                    ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ</div>
                <div>เทศบาลตำบลห้วยราช จังหวัดบุรีรัมย์</div>
                <div>พิมพ์เมื่อ: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} น.</div>
            </td>
            <td width="40%" class="report-title">
                {{ $title ?? 'รายงานคำแนะนำการดูแล' }}
            </td>
        </tr>
    </table>

    <!-- Main Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">วันที่</th>
                <th width="18%">ชื่อผู้สูงอายุ</th>
                <th width="18%">ผู้ออกคำแนะนำ (แพทย์)</th>
                <th width="15%">ผู้รับดำเนินการ</th>
                <th width="29%">คำแนะนำการดูแล / กิจกรรม</th>
                <th width="10%">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($careInstructions as $ci)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($ci->Date_CI)->addYears(543)->format('d/m/Y') }}</td>
                    <td class="text-bold">{{ $ci->Name_Elderly }}</td>
                    <td>{{ $ci->Name_Doctor }}</td>
                    <td>
                        {{ $ci->Care_instructions }}
                        @if($ci->prescriptions && $ci->prescriptions->count() > 0)
                            <br><span style="color: #000; font-weight: bold;">จ่ายยา:</span>
                            <span>
                                @foreach($ci->prescriptions as $p)
                                    {{ $p->medicine->name ?? 'ไม่ระบุ' }} ({{ $p->amount }}),
                                @endforeach
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($ci->Confirm)
                            <span class="status-confirmed">ยืนยันแล้ว</span>
                        @else
                            <span class="status-pending">รอยืนยัน</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">ไม่พบข้อมูลคำแนะนำการดูแล</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Footer (Optional, can be added if needed) -->
    <div style="margin-top: 50px;">
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" class="text-center">
                    <p>ลงชื่อ...........................................................</p>
                    <p>(.................................................................)</p>
                    <p>ผู้ออกรายงาน</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Page Numbering (mPDF specific) -->
    <htmlpagefooter name="myFooter">
        <div class="footer">
            หน้า {PAGENO} / {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="myFooter" value="on" />

</body>

</html>