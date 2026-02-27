@extends('layouts.reports')

@section('title', 'รายงานข้อมูลผู้ใช้งานระบบ')
@section('report_title', 'รายงานข้อมูลผู้ใช้งานระบบ')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">รูป</th>
                <th style="text-align: center;">ชื่อ - นามสกุล</th>
                <th style="text-align: center;">ประเภทผู้ใช้</th>
                <th style="text-align: center;">ชื่อผู้ใช้</th>
                <th style="text-align: center;">อีเมล</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td style="text-align: center;">
                        @if($user->Image_User)
                            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path($user->Image_User))) }}"
                                style="height: 40px; border-radius: 50%;">
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $user->Name_User }}</td>
                    <td style="text-align: center;">{{ $user->Type_Personnel }}</td>
                    <td style="text-align: center;">{{ $user->Username }}</td>
                    <td style="text-align: center;">{{ $user->Email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection