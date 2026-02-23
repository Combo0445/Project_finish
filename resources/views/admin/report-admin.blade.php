<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานข้อมูลผู้ใช้</title>
</head>

<body>

    <div id="report-content">

        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            รายงานข้อมูลผู้ใช้
        </h5>

        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">รูป</th>
                    <th style="width: 20%;">ชื่อ - นามสกุล</th>
                    <th style="width: 15%;">ประเภทผู้ใช้</th>
                    <th style="width: 40%;">ที่อยู่</th>
                    <th style="width: 15%;">เบอร์โทร</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <!-- แบ่งตารางทุก 13 แถวขึ้นหน้าใหม่ -->
                    @if($index % 13 == 0 && $index != 0)
                        <tr class="page-break">
                            <th style="width: 10%;">รูป</th>
                            <th style="width: 20%;">ชื่อ - นามสกุล</th>
                            <th style="width: 15%;">ประเภทผู้ใช้</th>
                            <th style="width: 40%;">ที่อยู่</th>
                            <th style="width: 15%;">เบอร์โทร</th>
                        </tr>
                    @endif
                    <tr>
                        <td>
                            <img src="{{ url($user->Image_User) }}" alt="User Image" class="user-image">
                        </td>
                        <td>{{ $user->Name_User ?: 'ไม่มีข้อมูล' }}</td>
                        <td style="text-align: center;">{{ $user->Type_Personnel }}</td>
                        <td class="address-column">{{ $user->Address ?: 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $user->Phone ?: 'ไม่มีข้อมูล' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
