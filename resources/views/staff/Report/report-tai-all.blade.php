@extends('layouts.reports')

@section('title', 'รายงาน TAI ทั้งหมด')
@section('report_title', 'รายงานผลการประเมินผู้สูงอายุ (TAI) ทั้งหมด')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">วันที่</th>
                <th style="text-align: center;">ชื่อผู้สูงอายุ</th>
                <th style="text-align: center;">ชื่อผู้ดูแล</th>
                <th style="text-align: center;">กลุ่ม</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tais as $tai)
                <tr>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($tai->updated_at)->translatedFormat('d F Y') }}</td>
                    <td style="text-align: center;">{{ $tai->elderly->Name_Elderly ?? "-" }}</td>
                    <td style="text-align: center;">{{ $tai->user->Name_User ?? "-" }}</td>
                    <td style="text-align: center;">{{ $tai->group ?? "-" }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
