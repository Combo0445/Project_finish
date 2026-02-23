<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงาน Care Giver รายบุคคล</title>
</head>

<body>

    <div class="container">
        <div style="text-align: center;">
            <img src="{{ url('images/Logo.png') }}" alt="Logo" style="width: 100px; height: auto; padding-bottom: 15px;">
        </div>
        <h5>รายงาน Care Giver รายบุคคล</h5>
        <div class="info-section">
            <table>
                <tr>
                    <th>ชื่อผู้สูงอายุ</th>
                    <td>{{ $cg->elderly->Name_Elderly ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>ชื่อผู้ดูแลผู้สูงอายุ</th>
                    <td>{{ $cg->Name_CG ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>เกี่ยวข้องเป็น</th>
                    <td>{{ $cg->Related ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>เบอร์ติดต่อ</th>
                    <td>{{ $cg->Phone_CG ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>อายุ</th>
                    <td>{{ $cg->Age ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>ที่อยู่</th>
                    <td>{{ $cg->Address ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>น้ำหนักตัว</th>
                    <td>{{ $cg->Weight ?? 'ไม่มีข้อมูล' }} กก.</td>
                </tr>
                <tr>
                    <th>ส่วนสูง</th>
                    <td>{{ $cg->Height ?? 'ไม่มีข้อมูล' }} ซม.</td>
                </tr>
                <tr>
                    <th>รอบเอว</th>
                    <td>{{ $cg->Waist ?? 'ไม่มีข้อมูล' }} ซม.</td>
                </tr>
                <tr>
                    <th>กลุ่ม ADL</th>
                    <td>{{ $cg->Group_ADL ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>โรคประจำตัว</th>
                    <td>{{ $cg->Disease ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>ความพิการ</th>
                    <td>{{ $cg->Disability ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <th>สิทธิการรักษา</th>
                    <td>{{ $cg->Rights ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th>คำถาม</th>
                    <th>คำตอบ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ว/ด/ป</td>
                    <td>{{ $cg->Date_CG ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ความรู้สึกตัว</td>
                    <td>{{ $cg->Consciousness ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>สัญญาณชีพ</td>
                    <td>{{ $cg->Vital_signs ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>แผลกดทับ</td>
                    <td>{{ $cg->Bedsores ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>อาการปวด</td>
                    <td>{{ $cg->Pain ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>อาการบวม</td>
                    <td>{{ $cg->Swelling ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ผื่นคัน</td>
                    <td>{{ $cg->Itchy_rash ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ข้อติดแข็ง</td>
                    <td>{{ $cg->Stiff_joints ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ทุพโภชนาการ</td>
                    <td>{{ $cg->Malnutrition ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การรับประทานอาหาร</td>
                    <td>{{ $cg->Eating ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การกลืน</td>
                    <td>{{ $cg->Swallowing ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การขับถ่ายอุจจาระ</td>
                    <td>{{ $cg->Defecation ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การขับถ่ายปัสสาวะ</td>
                    <td>{{ $cg->Urinary_excretion ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การรับประทานยา</td>
                    <td>{{ $cg->Taking_medicine ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>สภาพอารมณ์</td>
                    <td>{{ $cg->Emotional_state ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ปัญหาเศรษฐกิจ</td>
                    <td>{{ $cg->Economic_problems ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ปัญหาสังคม</td>
                    <td>{{ $cg->Social_problems ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>แพทย์นัด F/U</td>
                    <td>{{ $cg->Doctor_FU ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ปัญหาอื่น ๆ</td>
                    <td>{{ $cg->Other_problems ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>การช่วยเหลือ</td>
                    <td>{{ $cg->Assistance ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
                <tr>
                    <td>ผู้รายงาน</td>
                    <td>{{ $cg->Reporter ?? 'ไม่มีข้อมูล' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
