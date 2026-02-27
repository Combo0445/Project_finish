@extends('layouts.reports')

@section('title', 'รายงาน CG ทั้งหมด')
@section('report_title', 'รายงานการปฏิบัติงานของผู้ดูแลผู้สูงอายุทั้งหมด')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">วันที่</th>
                <th style="text-align: center;">ชื่อผู้สูงอายุ</th>
                <th style="text-align: center;">ชื่อผู้ดูแลผู้สูงอายุ</th>
                <th style="text-align: center;">กลุ่ม ADL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cgs as $cg)
                <tr>
                    <td style="text-align: center;">{{ $cg->Date_CG ?: 'ไม่มีข้อมูล' }}</td>
                    <td style="text-align: center;">{{ $cg->Name_Elderly ?: 'ไม่มีข้อมูล' }}</td>
                    <td style="text-align: center;">{{ $cg->Name_CG ?: 'ไม่มีข้อมูล' }}</td>
                    <td style="text-align: center;">{{ $cg->Group_ADL ?: 'ไม่มีข้อมูล' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection