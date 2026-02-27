@extends('layouts.reports')

@section('title', 'รายงานคำแนะนำการดูแล - ' . $ci->Name_Elderly)
@section('report_title', 'รายงานคำแนะนำการดูแล')
@section('reporter_name', $ci->Name_Doctor)

@section('content')
    <div class="section-title">ข้อมูลผู้สูงอายุ</div>
    <table>
        <tr>
            <th>วันที่</th>
            <td>{{ \Carbon\Carbon::parse($ci->Date_CI)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>ชื่อผู้สูงอายุ</th>
            <td>{{ $ci->Name_Elderly }}</td>
        </tr>
        <tr>
            <th>ที่อยู่</th>
            <td>{{ $ci->elderly->Address ?? '-' }}</td>
        </tr>
        <tr>
            <th>เบอร์โทรศัพท์</th>
            <td>{{ $ci->elderly->Phone_Elderly ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">คำแนะนำการดูแลจากแพทย์</div>
    <div
        style="padding: 15px; border: 1px solid #e9ecef; border-radius: 4px; background: #fff; min-height: 100px; margin-bottom: 20px;">
        {!! nl2br(e($ci->Care_instructions)) !!}
    </div>

    @if($ci->prescriptions && $ci->prescriptions->count() > 0)
        <div class="section-title">รายการยาที่สั่ง (Prescriptions)</div>
        <table style="margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th>ลำดับ</th>
                    <th>ชื่อยา</th>
                    <th>รูปแบบ</th>
                    <th>จำนวน</th>
                    <th>วิธีรับประทาน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ci->prescriptions as $index => $prescription)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $prescription->medicine->name ?? 'ไม่ระบุ' }}</td>
                        <td>{{ $prescription->medicine->type ?? '-' }}</td>
                        <td style="text-align: center;">{{ $prescription->amount }}</td>
                        <td>{{ $prescription->dosage ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">ข้อมูลเจ้าหน้าที่ผู้ดูแล</div>
    <table>
        <tr>
            <th>ชื่อแพทย์ผู้ดูแล</th>
            <td>{{ $ci->Name_Doctor }}</td>
        </tr>
        <tr>
            <th>ชื่อเจ้าหน้าที่รับผิดชอบ</th>
            <td>{{ $ci->Name_Staff }}</td>
        </tr>
    </table>
@endsection