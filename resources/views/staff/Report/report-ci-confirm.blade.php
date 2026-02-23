<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานคำแนะนำการดูแลที่ยืนยันแล้ว</title>

</head>

<body>

    <div class="container">
        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            รายงานคำแนะนำการดูแลที่ยืนยันแล้ว
        </h5>
        <table>
            <thead>
                <tr>
                    <th class="date-col">วันที่</th>
                    <th class="name-col">ชื่อผู้สูงอายุ</th>
                    <th class="doctor-col">ชื่อนายแพทย์</th>
                    <th class="staff-col">ชื่อเจ้าหน้าที่</th>
                    <th class="instructions-col">คำแนะนำ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($careInstructions as $index => $ci)
                @if ($index % 17 == 0 && $index != 0)
                <tr class="page-break">
                    <th class="date-col">วันที่</th>
                    <th class="name-col">ชื่อผู้สูงอายุ</th>
                    <th class="doctor-col">ชื่อนายแพทย์</th>
                    <th class="staff-col">ชื่อเจ้าหน้าที่</th>
                    <th class="instructions-col">คำแนะนำ</th>
                </tr>
                @endif
                <tr>
                    <td class="date-col">{{ $ci->Date_CI ?? 'ไม่มีข้อมูล' }}</td>
                    <td class="name-col">{{ $ci->Name_Elderly ?? 'ไม่มีข้อมูล' }}</td>
                    <td class="doctor-col">{{ $ci->Name_Doctor ?? 'ไม่มีข้อมูล' }}</td>
                    <td class="staff-col">{{ $ci->Name_Staff ?? 'ไม่มีข้อมูล' }}</td>
                    <td class="instructions-col">{{ $ci->Care_instructions ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
