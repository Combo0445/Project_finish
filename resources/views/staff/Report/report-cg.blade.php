@extends('layouts.reports')

@section('title', 'รายงาน Care Giver รายบุคคล - ' . ($cg->elderly->Name_Elderly ?? ''))
@section('report_title', 'รายงาน Care Giver รายบุคคล')
@section('reporter_name', $cg->Reporter ?? 'เจ้าหน้าที่ผู้รับผิดชอบ')

@section('content')
    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th>ชื่อผู้สูงอายุ</th>
            <td>{{ $cg->elderly->Name_Elderly ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
        <tr>
            <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
            <td>{{ $cg->Name_CG ?? 'ไม่มีข้อมูล' }} ({{ $cg->Related ?? 'ไม่ระบุความสัมพันธ์' }})</td>
        </tr>
        <tr>
            <th>เบอร์ติดต่อ</th>
            <td>{{ $cg->Phone_CG ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
        <tr>
            <th>ที่อยู่</th>
            <td>{{ $cg->Address ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
    </table>

    <div class="section-title">ข้อมูลร่างกายและสิทธิการรักษา</div>
    <table>
        <tr>
            <th>น้ำหนัก / ส่วนสูง / รอบเอว</th>
            <td>{{ $cg->Weight ?? '-' }} กก. / {{ $cg->Height ?? '-' }} ซม. / {{ $cg->Waist ?? '-' }} ซม.</td>
        </tr>
        <tr>
            <th>กลุ่ม ADL</th>
            <td>{{ $cg->Group_ADL ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
        <tr>
            <th>โรคประจำตัว / ความพิการ</th>
            <td>{{ $cg->Disease ?? '-' }} / {{ $cg->Disability ?? '-' }}</td>
        </tr>
        <tr>
            <th>สิทธิการรักษา</th>
            <td>{{ $cg->Rights ?? 'ไม่มีข้อมูล' }}</td>
        </tr>
    </table>

    <div class="section-title">รายละเอียดการประเมินประจำวัน</div>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">หัวข้อการประเมิน</th>
                <th style="text-align: center;">ผลการประเมิน</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ความรู้สึกตัว</td>
                <td>{{ $cg->Consciousness ?? '-' }}</td>
            </tr>
            <tr>
                <td>สัญญาณชีพ</td>
                <td>{{ $cg->Vital_signs ?? '-' }}</td>
            </tr>
            <tr>
                <td>แผลกดทับ</td>
                <td>{{ $cg->Bedsores ?? '-' }}</td>
            </tr>
            <tr>
                <td>อาการปวด / อาการบวม</td>
                <td>{{ $cg->Pain ?? '-' }} / {{ $cg->Swelling ?? '-' }}</td>
            </tr>
            <tr>
                <td>การรับประทานอาหาร / การกลืน</td>
                <td>{{ $cg->Eating ?? '-' }} / {{ $cg->Swallowing ?? '-' }}</td>
            </tr>
            <tr>
                <td>การขับถ่ายอุจจาระ / ปัสสาวะ</td>
                <td>{{ $cg->Defecation ?? '-' }} / {{ $cg->Urinary_excretion ?? '-' }}</td>
            </tr>
            <tr>
                <td>สภาพอารมณ์</td>
                <td>{{ $cg->Emotional_state ?? '-' }}</td>
            </tr>
            <tr>
                <td>การช่วยเหลือที่ได้รับ</td>
                <td>{{ $cg->Assistance ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    @if($cg->Other_problems)
        <div class="section-title">ปัญหาอื่นๆ และข้อเสนอแนะ</div>
        <div style="padding: 10px; border: 1px solid #e9ecef;">
            {{ $cg->Other_problems }}
        </div>
    @endif
@endsection