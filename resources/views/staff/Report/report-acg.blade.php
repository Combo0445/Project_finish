<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานกิจกรรมการดูแลผู้สูงอายุ</title>
    <link href="{{ url('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

</head>

<body>

    <div class="container">

        <div style="text-align: center;">
            <img src="{{ url('images/Logo.png') }}" alt="Logo" style="width: 100px; height: auto; padding-bottom: 15px;">
        </div>
        <h5>รายงานกิจกรรมการดูแลผู้สูงอายุ</h5>

        <div class="section-title">ข้อมูลทั่วไป</div>
        <table>
            <tr>
                <th>ชื่อผู้สูงอายุ</th>
                <td>{{ $activity->caregiver->Name_Elderly }}</td>
            </tr>
            <tr>
                <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                <td>{{ $activity->caregiver->Name_CG }}</td>
            </tr>
            <tr>
                <th>วันที่ทำกิจกรรม</th>
                <td>{{ $activity->Date_ACG }}</td>
            </tr>
        </table>

        <div class="section-title">กิจกรรมด้านสาธารณสุข</div>
        <table>
            <tr>
                <th>ประเมิน/ติดตามอาการ</th>
                <td>{{ $activity->Evaluate ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ทำแผล</th>
                <td>{{ $activity->Dress_the_wound ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ฟื้นฟูสภาพฯ</th>
                <td>{{ $activity->Rehabilitate ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ทำความสะอาดร่างกาย</th>
                <td>{{ $activity->Clean_body ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ดูแลเรื่องยา</th>
                <td>{{ $activity->Take_care_medicine ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ดูแลให้อาหาร</th>
                <td>{{ $activity->Take_care_feeding ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>การจัดสิ่งแวดล้อม</th>
                <td>{{ $activity->Environmental ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>พาออกกำลังกาย</th>
                <td>{{ $activity->Take_exercise ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>ให้คำแนะนำ/ปรึกษา</th>
                <td>{{ $activity->Give_advice_consult ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>พาพบแพทย์</th>
                <td>{{ $activity->Take_to_see_a_doctor ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th>อื่น ๆ ระบุ</th>
                <td>{{ $activity->Other ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
        </table>

        <div class="section-title page-break">กิจกรรมด้านสังคม</div>
        <table>
            <tr>
                <th style="width: 50%">พาไปทำบุญ</th>
                <td style="width: 50%">{{ $activity->Take_to_make_merit ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%">พาไปจ่ายตลาด</th>
                <td style="width: 50%">{{ $activity->Take_to_market ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%">พาไปพบเพื่อน</th>
                <td style="width: 50%">{{ $activity->Take_to_meet_friends ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%">พาไปรับเบี้ย</th>
                <td style="width: 50%">{{ $activity->Take_to_allowance ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%">พูดคุยเป็นเพื่อน</th>
                <td style="width: 50%">{{ $activity->Talk_as_friends ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%">อื่น ๆ ระบุ</th>
                <td style="width: 50%">{{ $activity->Other_specified ?? 'ไม่มีข้อมูล' }}</td>
            </tr>
        </table>
        <div class="section-title">ปัญหาที่พบ</div>
        <span style="font-size: 14px;">{{ $activity->Problem ?? 'ไม่มีข้อมูล' }}</span>
        <br>
        <div class="section-title">แนวทางการแก้ไข</div>
        <span style="font-size: 14px;">{{ $activity->Solution ?? 'ไม่มีข้อมูล' }}</span>
    </div>

</body>

</html>
