@extends('layouts.reports')

@section('title', 'Care Plan - ' . ($elder->Name_Elderly ?? ''))
@section('report_title', 'แบบรายงานผลการปฏิบัติงานตามแผนการดูแลรายบุคคล')
@section('reporter_name', $cg->Name_CG ?? 'เจ้าหน้าที่ผู้รับผิดชอบ')

@push('styles')
    <style>
        body {
            font-size: 18px !important;
            color: #000 !important;
            line-height: 1.1 !important;
        }

        .report-title h1 {
            color: #000 !important;
            margin-bottom: 5px !important;
        }

        .section-title {
            color: #000 !important;
            background-color: #f0f0f0 !important;
            padding: 3px 5px !important;
            margin-top: 5px !important;
            margin-bottom: 3px !important;
            border-left: 5px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px !important;
            color: #000 !important;
            font-size: 18px !important;
        }

        th,
        td {
            border: 1px solid #000 !important;
            padding: 2px 4px !important;
            color: #000 !important;
            vertical-align: top;
        }

        th {
            background-color: #e0e0e0 !important;
            font-weight: bold;
        }

        p {
            margin: 0 0 5px 0 !important;
            color: #000 !important;
        }

        .report-footer {
            margin-top: 5px !important;
            color: #000 !important;
        }

        /* Prevent page breaks */
        table,
        tr,
        td,
        th,
        div.section-title,
        p {
            page-break-inside: avoid !important;
        }
    </style>
@endpush

@section('content')
    <div style="text-align: center; font-weight: bold; margin-bottom: 20px;">
        โครงการเพื่อจัดบริการดูแลระยะยาวสำหรับผู้สูงอายุที่มีภาวะพึ่งพิง (Long Term Care)
    </div>

    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th width="30%">ชื่อ-สกุล ผู้สูงอายุ</th>
            <td width="70%">{{ $elder->Name_Elderly ?? '-' }}</td>
        </tr>
        <tr>
            <th>อายุ</th>
            <td>{{ $age !== null ? $age . ' ปี' : '-' }}</td>
        </tr>
        <tr>
            <th>ที่อยู่</th>
            <td>{{ $elder->Address ?? '-' }}</td>
        </tr>
        <tr>
            <th>สถานะสุขภาพ (ADL)</th>
            <td>{{ $adl->Group_ADL ?? '-' }} ({{ $adl->Score_ADL ?? 0 }} คะแนน)</td>
        </tr>
        <tr>
            <th>ประเภทกลุ่ม (TAI)</th>
            <td>กลุ่มที่ {{ $tai->group ?? '-' }}</td>
        </tr>
        <tr>
            <th>วินิจฉัยโรค</th>
            <td>{{ $cg->Disease ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">บันทึกการปฏิบัติงานรายวันประจำเดือน {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </div>
    <table class="daily-report-table">
        <thead>
            <tr>
                <th style="width:5%; text-align: center;">ลำดับ</th>
                <th style="width:12%; text-align: center;">ว/ด/ป</th>
                <th style="width:10%; text-align: center;">เวลา</th>
                <th style="width:15%; text-align: center;">สภาวะผู้ป่วย</th>
                <th style="width:25%; text-align: center;">กิจกรรมที่ดูแล</th>
                <th style="width:15%; text-align: center;">ปัญหา/อุปสรรค</th>
                <th style="width:10%; text-align: center;">ผู้ดูแล (CG)</th>
                <th style="width:8%; text-align: center;">ญาติ/หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $i => $row)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($row->Date)->translatedFormat('d M Y') }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($row->Date)->format('H:i') }} น.</td>
                    <td>{{ $row->State ?: '-' }}</td>
                    <td>{{ $row->Activity ?: '-' }}</td>
                    <td>{{ $row->Problems ?: '-' }}</td>
                    <td style="text-align: center;">{{ $cg->Name_CG ?? '-' }}</td>
                    <td style="text-align: center;">{{ $row->Relative ?: $row->Note ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 30px;">ไม่มีข้อมูลการปฏิบัติงานในเดือนนี้</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection