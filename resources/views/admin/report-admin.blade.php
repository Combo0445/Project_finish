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
                        @php
                            $imageUrl = $user->image_url;
                            $src = $imageUrl;
                            // For mPDF, if it's a local file, try to base64 encode it for better compatibility
                            if (strpos($imageUrl, asset('')) !== false) {
                                $relativePath = str_replace(asset(''), '', $imageUrl);
                                $fullPath = public_path($relativePath);
                                if (file_exists($fullPath)) {
                                    $src = 'data:image/' . pathinfo($fullPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($fullPath));
                                }
                            }
                        @endphp
                        <img src="{{ $src }}" style="height: 40px; border-radius: 50%;">
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