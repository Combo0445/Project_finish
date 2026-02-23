<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน ADL</title>

</head>

<body>

    <div class="container">

        <div style="text-align: center;">
            <img src="{{ url('images/Logo.png') }}" alt="Logo" style="width: 100px; height: auto; padding-bottom: 15px;">
        </div>

        <h5>รายงาน ADL</h5>
        <table>
            <tr>
                <th style="width: 50%;">ชื่อผู้สูงอายุ</th>
                <td style="width: 50%;">{{ $adl->elderly ? $adl->elderly->Name_Elderly : 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%;">ชื่อเจ้าหน้าที่ผู้รับผิดชอบ</th>
                <td style="width: 50%;">{{ $adl->Name_User ?: 'ไม่มีข้อมูล' }}</td>
            </tr>
            <tr>
                <th style="width: 50%;">กลุ่ม ADL</th>
                <td style="width: 50%;">{{ $adl->Group_ADL ?: 'ไม่มีข้อมูล' }}</td>
            </tr>
        </table>

        <div class="questions">
            <h5>โจทย์และคำตอบของแบบประเมิน ADL</h5>
            <table>
                <tr>
                    <th style="width: 30%;">ข้อคำถาม</th>
                    <th style="width: 70%;">คำตอบ</th>
                </tr>
                <tr>
                    <td>การรับประทานอาหาร</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('feeding', $adl->Feeding) }}</td>
                </tr>
                <tr>
                    <td>การดูแลร่างกาย</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('grooming', $adl->Grooming) }}</td>
                </tr>
                <tr>
                    <td>การย้ายตัว</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('transfer', $adl->Transfer) }}</td>
                </tr>
                <tr>
                    <td>การใช้ห้องน้ำ</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('toilet_use', $adl->Toilet_use) }}</td>
                </tr>
                <tr>
                    <td>การเคลื่อนที่ภายในห้องหรือบ้าน</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('mobility', $adl->Mobility) }}</td>
                </tr>
                <tr>
                    <td>การสวมใส่เสื้อผ้า</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('dressing', $adl->Dressing) }}</td>
                </tr>
                <tr>
                    <td>การขึ้นลงบันได 1 ชั้น</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('stairs', $adl->Stairs) }}</td>
                </tr>
                <tr>
                    <td>การอาบน้ำ</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bathing', $adl->Bathing) }}</td>
                </tr>
                <tr>
                    <td>การกลั้นการถ่ายอุจจาระในระยะ 1 สัปดาห์ที่ผ่านมา</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bowels', $adl->Bowels) }}</td>
                </tr>
                <tr>
                    <td>การกลั้นปัสสาวะในระยะ 1 สัปดาห์ที่ผ่านมา</td>
                    <td>{{ \App\Models\BarthelAdl::getAdlDescription('bladder', $adl->Bladder) }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
