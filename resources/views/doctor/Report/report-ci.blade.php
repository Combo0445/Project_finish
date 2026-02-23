<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานคำแนะนำการดูแล</title>
</head>

<body>

    <div class="container">

            <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            รายงานคำแนะนำการดูแล
            </h5>

        <table>
            <thead>
                <tr>
                    <th style="width: 13%;";>วันที่</th>
                    <th style="width: 17%;">ชื่อผู้สูงอายุ</th>
                    <th style="width: 16%;">นายแพทย์</th>
                    <th style="width: 19%;">ชื่อเจ้าหน้าที่</th>
                    <th style="width: 40%;">คำแนะนำ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($careInstructions as $index => $ci)
                <!-- แบ่งตารางทุก 12 แถวขึ้นหน้าใหม่ -->
                    @if($index % 12 == 0 && $index != 0)
                    <tr class="page-break">
                        <th style="width: 13%;";>วันที่</th>
                        <th style="width: 17%;">ชื่อผู้สูงอายุ</th>
                        <th style="width: 16%;">นายแพทย์</th>
                        <th style="width: 19%;">ชื่อเจ้าหน้าที่</th>
                        <th style="width: 40%;">คำแนะนำ</th>
                    </tr>
                @endif
                <tr>
                    <td>{{ $ci->Date_CI }}</td>
                    <td style="text-align: left;">{{ $ci->Name_Elderly }}</td>
                    <td style="text-align: left;">{{ $ci->Name_Doctor }}</td>
                    <td style="text-align: left;">{{ $ci->Name_Staff }}</td>
                    <td class="care-instructions">{{ $ci->Care_instructions }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
