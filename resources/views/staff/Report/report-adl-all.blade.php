<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานข้อมูล ADL</title>
</head>

<body>

    <div class="container">
        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            รายงานข้อมูล ADL
        </h5>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ชื่อผู้สูงอายุ</th>
                    <th>ชื่อเจ้าหน้าที่ผู้รับผิดชอบ</th>
                    <th>กลุ่ม ADL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adls as $adl)
                    <tr>
                        <td>{{ $adl->created_at->date_format('Y-m-d') }}</td>
                        <td>{{ $adl->elderly ? $adl->elderly->Name_Elderly : 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $adl->Name_User ?: 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $adl->Group_ADL ?: 'ไม่มีข้อมูล' }}</td>
                    </tr>
                    @if($loop->iteration % 19 == 0 && !$loop->last)
                        </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table>
                    <thead>
                        <tr>
                            <th>ชื่อผู้สูงอายุ</th>
                            <th>ชื่อเจ้าหน้าที่ผู้รับผิดชอบ</th>
                            <th>กลุ่ม ADL</th>
                        </tr>
                    </thead>
                    <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
