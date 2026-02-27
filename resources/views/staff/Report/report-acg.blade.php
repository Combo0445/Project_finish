@extends('layouts.reports')

@section('title', 'รายงานกิจกรรมการดูแลผู้สูงอายุ - ' . ($acg->caregiver->Name_Elderly ?? ''))
@section('report_title', 'รายงานกิจกรรมการดูแลผู้สูงอายุ')
@section('reporter_name', $acg->Name_User)

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
            margin-top: 8px !important;
            margin-bottom: 5px !important;
            border-left: 5px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px !important;
            color: #000 !important;
        }

        th,
        td {
            border: 1px solid #000 !important;
            padding: 3px 6px !important;
            font-size: 18px !important;
            color: #000 !important;
            vertical-align: top;
        }

        th {
            background-color: #e0e0e0 !important;
            font-weight: bold;
            width: 35%;
        }

        p {
            margin: 0 0 5px 0 !important;
            font-size: 18px !important;
            color: #000 !important;
        }

        .report-footer {
            margin-top: 10px !important;
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
    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th>ชื่อผู้สูงอายุ</th>
            <td>{{ $acg->caregiver->Name_Elderly ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
        <tr>
            <th>วันที่ทำกิจกรรม</th>
            <td>{{ \Carbon\Carbon::parse($acg->Date_ACG)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div class="section-title">กิจกรรมด้านสาธารณสุข</div>
    <table>
        <tr>
            <th>ประเมิน/ติดตามอาการ</th>
            <td>{{ $acg->Evaluate ?? '-' }}</td>
        </tr>
        <tr>
            <th>ทำแผล</th>
            <td>{{ $acg->Dress_the_wound ?? '-' }}</td>
        </tr>
        <tr>
            <th>ฟื้นฟูสภาพฯ</th>
            <td>{{ $acg->Rehabilitate ?? '-' }}</td>
        </tr>
        <tr>
            <th>ทำความสะอาดร่างกาย</th>
            <td>{{ $acg->Clean_body ?? '-' }}</td>
        </tr>
        <tr>
            <th>ดูแลเรื่องยา</th>
            <td>{{ $acg->Take_care_medicine ?? '-' }}</td>
        </tr>
        <tr>
            <th>ดูแลให้อาหาร</th>
            <td>{{ $acg->Take_care_feeding ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">กิจกรรมด้านสังคม</div>
    <table>
        <tr>
            <th>พาไปทำบุญ / จ่ายตลาด</th>
            <td>{{ $acg->Take_to_make_merit ?? '-' }} / {{ $acg->Take_to_market ?? '-' }}</td>
        </tr>
        <tr>
            <th>พาไปพบเพื่อน / รับเบี้ย</th>
            <td>{{ $acg->Take_to_meet_friends ?? '-' }} / {{ $acg->Take_to_allowance ?? '-' }}</td>
        </tr>
        <tr>
            <th>พูดคุยเป็นเพื่อน</th>
            <td>{{ $acg->Talk_as_friends ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">สรุปการประเมินและแผนการดูแล</div>
    <p><strong>ปัญหาที่พบ:</strong> {{ $acg->Assessment ?? 'ไม่ระบุ' }}</p>
    <p><strong>แนวทางการแก้ไข:</strong> {{ $acg->Plan ?? 'ไม่ระบุ' }}</p>
@endsection