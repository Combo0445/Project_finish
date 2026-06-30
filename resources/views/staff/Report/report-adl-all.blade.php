@extends('layouts.reports')

@section('title', 'รายงานข้อมูล ADL ทั้งหมด')
@section('report_title', 'รายงานความสามารถในการดำเนินชีวิตประจำวัน (ADL) ทั้งหมด')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">วันที่</th>
                <th style="text-align: center;">ชื่อผู้สูงอายุ</th>
                <th style="text-align: center;">คะแนน</th>
                <th style="text-align: center;">กลุ่ม</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adls as $adl)
                <tr>
                    <td style="text-align: center;">
                        {{ $adl->created_at ? \Carbon\Carbon::parse($adl->created_at)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $adl->elderly->Name_Elderly ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $adl->Score_ADL ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $adl->Group_ADL ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection