<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานกิจกรรมผู้ดูแลผู้สูงอายุทั้งหมด</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

</head>

<body>

    <div class="container">
        <h5>
            <img src="{{ url('images/Logo.png') }}" alt="Logo">
            รายงานกิจกรรมผู้ดูแลผู้สูงอายุทั้งหมด
        </h5>
        <table>
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>ชื่อผู้สูงอายุ</th>
                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $index => $activity)
                @if ($index % 17 == 0 && $index != 0)
                <tr class="page-break">
                    <th>วันที่</th>
                    <th>ชื่อผู้สูงอายุ</th>
                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                </tr>
                @endif
                <tr>
                    <td>{{ $activity->Date_ACG }}</td>
                    <td>{{ $activity->caregiver->Name_Elderly }}</td>
                    <td>{{ $activity->caregiver->Name_CG }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>
