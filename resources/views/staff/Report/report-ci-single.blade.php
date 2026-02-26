<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานคำแนะนำการดูแล</title>
</head>

<body>
    <div id="report-content">
        <h5 style="font-size: 20px;">
            <img src="{{ $logo }}" alt="Logo" style="height: 40px; vertical-align: middle;">
            รายงานคำแนะนำการดูแล
        </h5>

        <table border="1" cellpadding="1" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>วันที่</th>
                <td>{{ $ci->Date_CI }}</td>
            </tr>
            <tr>
                <th>ชื่อผู้สูงอายุ</th>
                <td>{{ $ci->Name_Elderly }}</td>
            </tr>
            <tr>
                <th>ที่อยุ่</th>
                <td>{{ $ci->elderly->Address }}</td>
            </tr>
            <tr>
                <th>เบอร์โทร</th>
                <td>{{ $ci->elderly->Phone_Elderly }}</td>
            </tr>
            <tr>
                <th>ชื่อนายแพทย์</th>
                <td>{{ $ci->Name_Doctor }}</td>
            </tr>
            <tr>
                <th>คำแนะนำการดูแล</th>
                <td>{{ $ci->Instruction }}</td>
            </tr>
        </table>
    </div>
</body>

</html>