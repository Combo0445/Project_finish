@extends('layouts.reports')

@section('title', 'รายงานผลการประเมินผู้สูงอายุ (TAI) - ' . ($tai->elderly->Name_Elderly ?? ''))
@section('report_title', 'รายงานผลการประเมินผู้สูงอายุ (TAI)')
@section('reporter_name', $tai->user->Name_User ?? 'เจ้าหน้าที่ผู้รับผิดชอบ')

@section('content')
    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th>วันที่ประเมิน</th>
            <td>{{ \Carbon\Carbon::parse($tai->updated_at)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>ชื่อผู้สูงอายุ</th>
            <td>{{ $tai->elderly->Name_Elderly ?? "-" }}</td>
        </tr>
        <tr>
            <th>ประเภทกลุ่ม (TAI Group)</th>
            <td><span class="badge" style="background: #5e72e4; color: #fff;">{{ $tai->group ?? "-" }}</span></td>
        </tr>
    </table>

    <div class="section-title">สรุปผลการประเมิน</div>
    <p>ผู้สูงอายุรายนี้ได้รับการประเมินความสามารถในการดำเนินชีวิตประจำวัน (Technical Aid Index - TAI)
        และถูกจัดอยู่ในกลุ่มด้านบน เพื่อใช้เป็นแนวทางในการวางแผนการดูแลรักษาพยาบาลและการฟื้นฟูสภาพร่างกายต่อไป</p>
@endsection