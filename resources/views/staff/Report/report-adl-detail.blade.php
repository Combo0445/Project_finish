@extends('layouts.reports')

@section('title', 'รายงานการประเมิน ADL - ' . ($adl->elderly->Name_Elderly ?? ''))
@section('report_title', 'รายงานการประเมิน ADL')
@section('reporter_name', $adl->Name_User)

@section('content')
    <div class="section-title">ข้อมูลทั่วไป</div>
    <table>
        <tr>
            <th>ชื่อผู้สูงอายุ</th>
            <td>{{ $adl->elderly->Name_Elderly ?? "ไม่มีข้อมูล" }}</td>
        </tr>
        <tr>
            <th>กลุ่ม ADL</th>
            <td>{{ $adl->Group_ADL ?: "ไม่มีข้อมูล" }} (คะแนนรวม: {{ $adl->Score_ADL }})</td>
        </tr>
    </table>

    <div class="section-title">รายละเอียดการประเมิน</div>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">หัวข้อการประเมิน</th>
                <th style="text-align: center;">ผลการประเมิน</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>การรับประทานอาหาร</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('feeding', $adl->Feeding) }}</td>
            </tr>
            <tr>
                <td>การล้างหน้า แปรงฟัน แต่งตัว</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('grooming', $adl->Grooming) }}</td>
            </tr>
            <tr>
                <td>การขับถ่ายอุจจาระ</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('bowels', $adl->Bowels) }}</td>
            </tr>
            <tr>
                <td>การขับถ่ายปัสสาวะ</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('bladder', $adl->Bladder) }}</td>
            </tr>
            <tr>
                <td>การใช้ห้องน้ำ</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('toilet_use', $adl->Toilet_use) }}</td>
            </tr>
            <tr>
                <td>การเคลื่อนย้ายจากเตียงไปยังเก้าอี้</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('transfer', $adl->Transfer) }}</td>
            </tr>
            <tr>
                <td>การเดินบนทางราบ</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('mobility', $adl->Mobility) }}</td>
            </tr>
            <tr>
                <td>การใส่เสื้อผ้า</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('dressing', $adl->Dressing) }}</td>
            </tr>
            <tr>
                <td>การขึ้นลงบันได 1 ชั้น</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('stairs', $adl->Stairs) }}</td>
            </tr>
            <tr>
                <td>การอาบน้ำ</td>
                <td>{{ \App\Models\BarthelAdl::getAdlDescription('bathing', $adl->Bathing) }}</td>
            </tr>
        </tbody>
    </table>
@endsection