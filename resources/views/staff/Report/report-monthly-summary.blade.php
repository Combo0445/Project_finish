@extends('layouts.reports')

@section('title', 'รายงานสรุปสถิติประจำเดือน - ' . $now->translatedFormat('F Y'))
@section('report_title', 'รายงานสรุปสถิติประจำเดือน')

@section('content')
    <div style="text-align: center; font-weight: bold; margin-bottom: 30px; color: #5e72e4;">
        ประจำเดือน {{ $now->translatedFormat('F Y') }}
    </div>

    <div class="section-title">สรุปภาพรวมการดำเนินงาน</div>
    <table>
        <tr>
            <th>จำนวนผู้สูงอายุในระบบทั้งหมด</th>
            <td style="text-align: center; font-size: 18px; font-weight: bold;">{{ number_format($stats['total_elderly']) }}
                คน</td>
        </tr>
        <tr>
            <th>การประเมิน ADL ใหม่ (เดือนนี้)</th>
            <td style="text-align: center; font-size: 18px; font-weight: bold; color: #2dce89;">{{ $stats['new_adl'] }}
                ครั้ง</td>
        </tr>
        <tr>
            <th>การบันทึกรายงาน CG ใหม่ (เดือนนี้)</th>
            <td style="text-align: center; font-size: 18px; font-weight: bold; color: #fb6340;">{{ $stats['new_cg'] }} ครั้ง
            </td>
        </tr>
        <tr>
            <th>การประเมิน TAI ใหม่ (เดือนนี้)</th>
            <td style="text-align: center; font-size: 18px; font-weight: bold; color: #11cdef;">{{ $stats['new_tai'] }}
                ครั้ง</td>
        </tr>
        <tr>
            <th>การออกคำแนะนำแพทย์ CI ใหม่ (เดือนนี้)</th>
            <td style="text-align: center; font-size: 18px; font-weight: bold; color: #5e72e4;">{{ $stats['new_ci'] }} ครั้ง
            </td>
        </tr>
    </table>

    <div class="section-title">สัดส่วนกลุ่มสถานะสุขภาพ (ADL Groups)</div>
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="width: 70%; text-align: center;">กลุ่มสถานะสุขภาพ</th>
                <th style="width: 30%; text-align: center;">จำนวน (คน)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>กลุ่มติดสังคม (Social-bound)</td>
                <td style="text-align: center; font-weight: bold;">{{ $adlGroups['กลุ่มติดสังคม'] }}</td>
            </tr>
            <tr>
                <td>กลุ่มติดบ้าน (Home-bound)</td>
                <td style="text-align: center; font-weight: bold;">{{ $adlGroups['กลุ่มติดบ้าน'] }}</td>
            </tr>
            <tr>
                <td>กลุ่มติดเตียง (Bed-bound)</td>
                <td style="text-align: center; font-weight: bold;">{{ $adlGroups['กลุ่มติดเตียง'] }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; padding: 15px; background: #f8f9fe; border-radius: 4px; font-size: 13px;">
        <strong>หมายเหตุ:</strong> ข้อมูลนี้สรุปจากฐานข้อมูลระบบ Long Term Care อัตโนมัติ
        เพื่อใช้ในการติดตามและประเมินผลการดำเนินโครงการในภาพรวม
    </div>
@endsection