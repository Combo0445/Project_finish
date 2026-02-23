<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน CG ทั้งหมด</title>

</head>

<body>

    <div class="container">
        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo" style="width: 100px; height: auto; padding-bottom: 15px;">
            รายงานการปฏิบัติงานของผู้ดูแลผู้สูงอายุทั้งหมด
        </h5>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ชื่อผู้สูงอายุ</th>
                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                    <th>กลุ่ม ADL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cgs as $index => $cg)
                @if ($index % 17 == 0 && $index != 0)
                <tr class="page-break">
                    <th>วันที่</th>
                    <th>ชื่อผู้สูงอายุ</th>
                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                    <th>กลุ่ม ADL</th>
                </tr>
                @endif
                    <tr>
                        <td>{{ $cg->Date_CG ?: 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $cg->Name_Elderly ?: 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $cg->Name_CG ?: 'ไม่มีข้อมูล' }}</td>
                        <td>{{ $cg->Group_ADL ?: 'ไม่มีข้อมูล' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
