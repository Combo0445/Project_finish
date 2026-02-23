<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>คณะบุคลากร</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h3 {

            margin-bottom: 30px;
            font-size: 24px;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 20px auto; /* จัดตารางให้อยู่ตรงกลาง */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #508b59;
            color: white;
            font-size: 18px;
            padding: 15px;
        }
        td {
            font-size: 16px;
            padding: 12px;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            font-size: 18px;
            color: #355e3b;
        }
    </style>
</head>
<body>

    @include('layout.nav')

    <div class="container">
        <h3>คณะบุคลากร</h3>

        <table>
            <thead>
                <tr>
                    <th>ประเภทบุคลากร</th>
                    <th>จำนวนบุคลากร</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ผู้ดูแลระบบ (Admin)</td>
                    <td>{{ $adminCount }}</td>
                </tr>
                <tr>
                    <td>นายแพทย์ (Doctor)</td>
                    <td>{{ $doctorCount }}</td>
                </tr>
                <tr>
                    <td>เจ้าหน้าที่ (Staff)</td>
                    <td>{{ $staffCount }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <p>จำนวนบุคลากรรวม: {{ $adminCount + $doctorCount + $staffCount }} คน</p>
        </div>
    </div>
    @include('layout.footer')
</body>
</html>
