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
            <img src="{{ url('images/Logo.png') }}" alt="Logo" style="height: 80px; vertical-align: middle;">
            รายงานคำแนะนำการดูแล
        </h5>

        <table border="1" cellpadding="1" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>วันที่</th>
                <td>{{ $careInstruction->Date_CI }}</td>
            </tr>
            <tr>
                <th>ชื่อผู้สูงอายุ</th>
                <td>{{ $careInstruction->Name_Elderly }}</td>
            </tr>
            <tr>
                <th>ที่อยู่</th>
                <td>{{ $careInstruction->elderly->Address }}</td>
            </tr>
            <tr>
                <th>เบอร์โทร</th>
                <td>{{ $careInstruction->elderly->Phone_Elderly }}</td>
            </tr>
            <tr>
                <th>ชื่อนายแพทย์</th>
                <td>{{ $careInstruction->Name_Doctor }}</td>
            </tr>
            <tr>
                <th>คำแนะนำการดูแล</th>
                <td>{{ $careInstruction->Care_instructions }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
