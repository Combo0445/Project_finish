@extends('layouts.reports')

@section('title', 'รายงานกิจกรรมผู้ดูแลผู้สูงอายุทั้งหมด')
@section('report_title', 'รายงานกิจกรรมผู้ดูแลผู้สูงอายุทั้งหมด')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">วันที่</th>
                <th style="text-align: center;">ชื่อผู้สูงอายุ</th>
                <th style="text-align: center;">ชื่อผู้ดูแลผู้สูงอายุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td style="text-align: center;">{{ $activity->Date_ACG }}</td>
                    <td style="text-align: center;">{{ $activity->caregiver->Name_Elderly ?? '-' }}</td>
                    <td style="text-align: center;">{{ $activity->caregiver->Name_CG ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection