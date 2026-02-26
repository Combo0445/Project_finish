@extends('layouts.reports')

@section('title', 'Care Plan - ' . ($elder->Name_Elderly ?? ''))
@section('report_title', 'แบบรายงานผลการปฏิบัติงานตามแผนการดูแลรายบุคคล')
@section('reporter_name', $cg->Name_CG ?? 'เจ้าหน้าที่ผู้รับผิดชอบ')

@section('content')
    <div style="text-align: center; font-weight: bold; margin-bottom: 20px;">
        โครงการเพื่อจัดบริการดูแลระยะยาวสำหรับผู้สูงอายุที่มีภาวะพึ่งพิง (Long Term Care)
    </div>

    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th>ชื่อ-สกุล ผู้สูงอายุ</th>
            <td>{{ $elder->Name_Elderly ?? '-' }}</td>
            <th>อายุ</th>
            <td>{{ $age !== null ? $age . ' ปี' : '-' }}</td>
        </tr>
        <tr>
            <th>ที่อยู่</th>
            <td colspan="3">{{ $elder->Address ?? '-' }}</td>
        </tr>
        <tr>
            <th>สถานะสุขภาพ (ADL)</th>
            <td>{{ $adl->Group_ADL ?? '-' }} ({{ $adl->Score_ADL ?? 0 }} คะแนน)</td>
            <th>ประเภทกลุ่ม (TAI)</th>
            <td>กลุ่มที่ {{ $tai->group ?? '-' }}</td>
        </tr>
        <tr>
            <th>วินิจฉัยโรค</th>
            <td colspan="3">{{ $cg->Disease ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">บันทึกการปฏิบัติงานรายวันประจำเดือน {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </div>
    <table style="font-size: 11px;">
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