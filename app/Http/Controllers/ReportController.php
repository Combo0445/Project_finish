<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceReport;
use Carbon\Carbon;
use Validator;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Response;
use App\Models\CareGiver;
use App\Models\Elderly;
use App\Models\BarthelAdl;
use App\Models\ScoreTAI;


class ReportController extends Controller
{
    // public function reportExportPDFcommission_employee(Request $request)
    // {
    //     $defaultConfig     = (new ConfigVariables())->getDefaults();
    //     $fontDirs          = $defaultConfig['fontDir'];
    //     $defaultFontConfig = (new FontVariables())->getDefaults();
    //     $fontData          = $defaultFontConfig['fontdata'];

    //     $mpdf = new \Mpdf\Mpdf([
    //         'mode'              => 'utf-8',
    //         'format'            => 'A4',
    //         'default_font_size' => 14,
    //         'fontDir'           => array_merge($fontDirs, [
    //             base_path() . '/custom/font/directory',
    //         ]),
    //         'fontdata'          => $fontData + [
    //             'th-sarabun' => [
    //                 'R'  => 'THSarabun.ttf',
    //                 'I'  => 'THSarabun Italic.ttf',
    //                 'B'  => 'THSarabun Bold.ttf',
    //                 'BI' => 'THSarabun BoldItalic.ttf',
    //             ],
    //         ],
    //         'default_font'      => 'th-sarabun',
    //         'margin_left'       => 5,
    //         'margin_right'      => 5,
    //         'margin_top'        => 5,
    //         'margin_bottom'     => 5,
    //         'margin_header'     => 5,
    //         'margin_footer'     => 5,
    //     ]);

    //     $mpdf->SetTitle('Commission Employee Report');
    //     $mpdf->AddPage();

    //     $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
    //     $endDate   = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;
    //     $validator = Validator::make($request->all(), [
    //         'start_date' => 'required|date',
    //         'end_date'   => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->returnBadRequest($validator->errors(), 'ข้อมูลไม่ครบ');
    //     }
    //     $branchId = trim($request->input('branch_id', null));

    //     $query = Employees::query()
    //         ->with([
    //             'inspections' => function ($query) use ($startDate, $endDate) {
    //                 if ($startDate && $endDate) {
    //                     $query->whereBetween('date', [$startDate, $endDate]);
    //                 }
    //                 $query->with('serviceTransactions.service');
    //             },
    //         ]);

    //     if (! is_null($branchId) && $branchId !== '') {
    //         $query->where('branch_id', $branchId);
    //     }

    //     $employees = $query->get();

    //     $serviceNames = [
    //         1  => 'ตรวจสภาพ',
    //         2  => 'พ.ร.บ.',
    //         3  => 'ภาษีประจำปี',
    //         4  => 'ค่าปรับภาษี',
    //         5  => 'บริการต่อภาษี',
    //         6  => 'ค่าบริการออกพ.ร.บ.',
    //         7  => 'ประกัน',
    //         8  => 'ขนส่ง',
    //         9  => 'ตรวจแก๊ส LPG',
    //         10 => 'ตรวจแก๊ส NGV ',
    //         11 => 'หนังสือรับรองวิศวกร',
    //         12 => 'อื่นๆ',
    //         13 => 'EMS',
    //     ];

    //     $serviceCounts = [];
    //     foreach ($employees as $employee) {
    //         $employeeName                 = $employee->first_name . ' ' . $employee->last_name;
    //         $serviceCounts[$employeeName] = $employee->inspections->flatMap(function ($inspection) {
    //             return $inspection->serviceTransactions->map(function ($transaction) {
    //                 return $transaction->service->id;
    //             });
    //         })->countBy();
    //     }

    //     $html = '
    //     <style>
    //         .no-data {
    //             text-align: center;
    //             color: #888;
    //             padding: 20px 0;
    //         }
    //         table {
    //             width: 100%;
    //             border-collapse: collapse;
    //         }
    //         th, td {
    //             border: 1px solid #000;
    //             padding: 8px;
    //             text-align: center;
    //         }
    //     </style>
    //     <thead>
    //     <table border="1" style="width:100%; border-collapse: collapse;">
    //         <thead>
    //             <tr>
    //                 <th rowspan="2">#</th>
    //                 <th rowspan="2">ชื่อ</th>
    //                 <th rowspan="2">นามสกุล</th>
    //                 <th rowspan="2">อีเมล</th>
    //                 <th rowspan="2">เบอร์โทร</th>
    //                 <th colspan="13">บริการ</th>
    //             </tr>
    //             <tr>';

    //     foreach ($serviceNames as $serviceName) {
    //         $html .= '<th>' . $serviceName . '</th>';
    //     }

    //     $html .= '</tr></thead><tbody>';

    //     // Add employee data to HTML
    //     foreach ($employees as $index => $employee) {
    //         $employeeName = $employee->first_name . ' ' . $employee->last_name;
    //         $services     = $serviceCounts[$employeeName] ?? [];

    //         $html .= '<tr>';
    //         $html .= '<td>' . ($index + 1) . '</td>';
    //         $html .= '<td>' . $employee->first_name . '</td>';
    //         $html .= '<td>' . $employee->last_name . '</td>';
    //         $html .= '<td>' . $employee->email . '</td>';
    //         $html .= '<td>' . $employee->phone_number . '</td>';

    //         foreach ($serviceNames as $serviceId => $serviceName) {
    //             $html .= '<td>' . ($services[$serviceId] ?? 0) . '</td>';
    //         }

    //         $html .= '</tr>';
    //     }

    //     if ($employees->isEmpty()) {
    //         $html .= '<tr>
    //             <td colspan="' . (count($serviceNames) + 5) . '" class="no-data">ไม่มีรายการ</td>
    //         </tr>';
    //     }

    //     $html .= '</tbody></table></thead>';

    //     $mpdf->WriteHTML($html);
    //     $pdfContent = $mpdf->Output('', 'S');

    //     $contentLength = strlen($pdfContent);

    //     $headers = [
    //         'Content-Type'                  => 'application/pdf',
    //         'Content-Disposition'           => 'inline; filename=mpdf.pdf',
    //         'Access-Control-Expose-Headers' => 'Accept-Ranges',
    //         'Access-Control-Allow-Headers'  => 'Accept-Ranges,range',
    //         'Accept-Ranges'                 => 'bytes',
    //         'Content-Length'                => $contentLength,
    //     ];

    //     return Response::make($pdfContent, 200, $headers);
    // }

    public function ReportPerformanceReport($id)
    {
        // 1. ตั้งค่า mPDF + ฟอนต์ไทย
        $defaultConfig     = (new ConfigVariables())->getDefaults();
        $fontDirs          = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4-L',
            'default_font_size' => 14,
            'fontDir'           => array_merge($fontDirs, [
                public_path('fonts/thsarabun'),
            ]),
            'fontdata'          => $fontData + [
                'th-sarabun' => [
                    'R'  => 'THSarabun.ttf',
                    'B'  => 'THSarabun Bold.ttf',
                    'I'  => 'THSarabun Italic.ttf',
                    'BI' => 'THSarabun BoldItalic.ttf',
                ],
                'dejavusans' => [  // ฟอนต์ที่รองรับ ✔ แน่ ๆ
                    'R' => 'DejaVuSans.ttf',
                ]
            ],
            'default_font'      => 'th-sarabun',
            'margin_left'       => 5,
            'margin_right'      => 5,
            'margin_top'        => 5,
            'margin_bottom'     => 5,
            'margin_header'     => 5,
            'margin_footer'     => 5,
        ]);

        // 2. หา row แรก ตาม performance_report.id
        $report = PerformanceReport::with([
            'elderly',
            'caregiver',
            'adl',
            'tai',
            'user'
        ])->findOrFail($id);

        // ถ้าต้องการดึงทุกแถวของคนไข้เดียวกันในเดือนนี้ ให้กรองเพิ่ม
        $elderId = $report->ID_Elderly;

        $reports = PerformanceReport::with([
            'elderly',
            'caregiver',
            'adl',
            'tai',
            'user'
        ])
            ->where('ID_Elderly', $elderId)
            ->orderBy('Date', 'desc')
            ->get();

        // 3. เตรียมข้อมูล header
        $elder = $report->elderly;
        $cg    = $report->caregiver;
        $tai   = $report->tai;
        $adl   = $report->adl;

        $age = $elder->Birthday
            ? Carbon::parse($elder->Birthday)->diffInYears(Carbon::now())
            : null;

        $html = '
<style>
  table { width:100%; border-collapse: collapse; }
  th, td { border:1px solid #000; padding:4px; font-size:12pt; vertical-align: top; }
  th { background-color:#eee; }
  .header { text-align:center; margin-bottom:5px; font-size:14pt; }
  .sub { text-align:center; margin-bottom:5px; font-size:12pt; }
  .info td { border:none; padding:2px; font-size:12pt; }
</style>

<div class="header">แบบรายงานผลการปฏิบัติงานตามแผนการดูแลรายบุคคลสำหรับผู้สูงอายุที่มีภาวะพึ่งพิง</div>
<div class="header">โครงการเพื่อจัดบริการดูแลระยะยาวฯ ตามประกาศคปสอ.</div>
<div class="header">Care Giver: ' . ($cg->Name_CG ?? '-') . '</div>
<br>
<table class="info">
  <tr>
    <td>ชื่อ-สกุล ผู้ป่วย: ' . ($elder->Name_Elderly ?? '-') . '</td>
<td>อายุ: ' . ($age !== null ? $age . ' ปี' : '-') . '</td>
    <td>ที่อยู่: ' . ($elder->Address ?? '-') . '</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>สถานะสุขภาพ (ADL): ' . ($adl->Group_ADL ?? '-') . '</td>
    <td>&nbsp;</td>
    <td>TAI = กลุ่มที่ ' . ($tai->group ?? '-') . '</td>
    <td>ADL ' . $adl->Score_ADL . ' คะแนน</td>
    
  </tr>
  <tr>
    <td colspan="4">วินิจฉัย: ' . ($cg->Disease ?? '-') . '</td>
  </tr>
  <tr>
    <td colspan="4">ประจำเดือน: ' . Carbon::now()->format('F Y') . '</td>
  </tr>
</table>
<br>

<table>
  <thead>
    <tr>
      <th style="width:5%;">ลำดับ</th>
      <th style="width:12%;">ว/ด/ป</th>
      <th style="width:15%;">เวลาที่เยี่ยม</th>
      <th style="width:15%;">สภาวะ</th>
      <th style="width:15%;">กิจกรรม</th>
      <th style="width:10%;">ปัญหา</th>
      <th style="width:10%;">Care Giver</th>
      <th style="width:10%;">ญาติ</th>
      <th style="width:8%;">หมายเหตุ</th>
    </tr>
  </thead>
  <tbody>';
        foreach ($reports as $i => $row) {
            $time = Carbon::parse($row->Date)->format('H:i');
            $html .= '
    <tr>
      <td>' . ($i + 1) . '</td>
      <td>' . Carbon::parse($row->Date)->format('Y-m-d') . '</td>
      <td>' . $time . '</td>
      <td>' . ($row->State ?: '-') . '</td>
      <td>' . ($row->Activity ?: '-') . '</td>
      <td>' . ($row->Problems ?: '-') . '</td>
      <td>' . ($cg->Name_CG ?? '-') . '</td>
      <td>' . ($row->Relative ?: '-') . '</td>
      <td>' . ($row->Note ?: '-') . '</td>
    </tr>';
        }
        if ($reports->isEmpty()) {
            $html .= '<tr><td colspan="9" style="text-align:center;">ไม่มีข้อมูลในเดือนนี้</td></tr>';
        }
        $html .= '</tbody></table>';

        // 4. สร้าง PDF และส่งกลับ
        $mpdf->SetTitle('Care Plan รายบุคคล');
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return Response::make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="CarePlan_' . $id . '.pdf"',
            'Accept-Ranges'       => 'bytes',
        ]);
    }

    public function ReportCG($id)
    {
        // 1. ตั้งค่า mPDF + ฟอนต์ไทย
        $defaultConfig     = (new ConfigVariables())->getDefaults();
        $fontDirs          = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode'         => 'utf-8',
            'format'       => 'A4',
            'fontDir'      => array_merge($fontDirs, [public_path('fonts/thsarabun')]),
            'fontdata'     => $fontData + [
                'thsarabun' => [
                    'R'  => 'THSarabun.ttf',
                    'B'  => 'THSarabun Bold.ttf',
                    'I'  => 'THSarabun Italic.ttf',
                    'BI' => 'THSarabun BoldItalic.ttf',
                ],
            ],
            'default_font'  => 'thsarabun',
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        // 2. โหลดข้อมูล CG + Elderly
        $cg    = CareGiver::with('elderly')->findOrFail($id);
        $elder = $cg->elderly;

        // แปลงวันเกิด → อายุ
        $age = $elder->Birthday
            ? Carbon::parse($elder->Birthday)->age
            : '-';

        // 3. แมปหัวข้อกับคอลัมน์ในฐานข้อมูล
        $fieldMap = [
            'ความรู้สึกตัว'          => 'Consciousness',
            'สัญญาณชีพ'            => 'Vital_signs',
            'แผลกดทับ'             => 'Bedsores',
            'อาการปวด'             => 'Pain',
            'อาการบวม'             => 'Swelling',
            'ผืนคัน'               => 'Itchy_rash',
            'ข้อติดแข็ง'            => 'Stiff_joints',
            'ทุพโภชนาการ'          => 'Malnutrition',
            'การรับประทานอาหาร'     => 'Eating',
            'การกลืน'              => 'Swallowing',
            'การขับถ่ายอุจจาระ'       => 'Defecation',
            'การขับถ่ายปัสสาวะ'       => 'Urinary_excretion',
            'การรับประทานยา'        => 'Taking_medicine',
            'อุปกรณ์การแพทย์'        => 'Assistance',
            'สภาพอารมณ์'           => 'Emotional_state',
            'ปัญหาเศฐษกิจ'         => 'Economic_problems',
            'ปัญหาพฤติกรรม'        => 'Social_problems',
            'ปัญหาสังคม'           => 'Social_problems',
            'แพทย์นัด F/U'          => 'Doctor_FU',
            'ปัญหาอื่นๆ'           => 'Other_problems',
            'การช่วยเหลือ'         => 'Assistance',
            'การจำหน่าย'           => null,           // ไม่มีคอลัมน์ใน DB
            'ผู้รายงาน'             => 'Reporter',
        ];

        // 4. สร้าง HTML ตามแบบฟอร์มต้นแบบ
        $html = '
        <style>
          body { font-family: thsarabun, sans-serif; font-size:12pt; }
          table { width:100%; border-collapse: collapse; }
          th, td { border:1px solid #000; padding:4px; vertical-align: top; }
          th { background-color: #eee; }
          .header { text-align: center; margin-bottom:5px; font-size:14pt; }
          .info td { border:none; padding:2px; }
          .section-title { background-color:#ddd; font-weight: bold; }
        </style>
    
        <div class="header">
            แบบรายงานผลการปฏิบัติงานผู้ดูแลผู้สูงอายุ Care Giver จังหวัดบุรีรัมย์
        </div>
        <div class="header">
            Care Giver: ' . ($cg->Reporter ?? '-') . '
        </div>
        <br>
        <div>ส่วนที่ 1 ข้อมูลทั่วไป</div>
        <table class="info">
          <tr>
            <td>ชื่อ-สกุล ผู้สูงอายุ: ' . ($elder->Name_Elderly ?? '-') . '</td>
            <td>อายุ: ' . $age . ' ปี</td>
            <td>ที่อยู่: ' . ($elder->Address ?? '-') . '</td>
          </tr>
          <tr>
            <td>โรคประจำตัว: ' . ($cg->Disease ?? '-') . '</td>
            <td>ความพิการ: ' . ($cg->Disability ?? '-') . '</td>
            <td>สิทธิรักษา: ' . ($cg->Rights ?? '-') . '</td>
          </tr>
          <tr>
            <td>ชื่อ-สกุล ผู้ดูแล: ' . ($cg->Name_CG ?? '-') . '</td>
            <td>เกี่ยวข้องเป็น: ' . ($cg->Related ?? '-') . '</td>
            <td>เบอร์โทร: ' . ($cg->Phone_CG ?? '-') . '</td>
          </tr>
        </table>
        <br>
        <div>ส่วนที่ 2 ข้อมูลสุขภาพ &nbsp;&nbsp; น้ำหนัก ' . ($cg->Weight ?? '-') . ' กก. &nbsp;&nbsp; ส่วนสูง ' . ($cg->Height ?? '-') . ' ซม. &nbsp;&nbsp; รอบเอว ' . ($cg->Waist ?? '-') . ' ซม.</div>
        <table>
          <thead>
            <tr class="section-title">
              <th style="width:33%;">ลำดับ</th>
              <th style="width:33%;">หัวข้อประเมิน</th>
              <th style="width:33%;" colspan="2">สัปดาห์ที่ &nbsp; วดป.</th>
            </tr>
          </thead>
          <tbody>';

        // 5. วนลูปแสดงแต่ละหัวข้อ
        $i = 0;
foreach ($fieldMap as $label => $field) {
    $i++;
    $customField = ''; // สำหรับฟิลด์พิเศษ เช่น ระบุเพิ่ม

    // ฟิลด์พิเศษ: สัญญาณชีพ
    if ($field === 'Vital_signs') {
        $html .= '
        <tr>
            <td style="text-align:center;">' . $i . '</td>
            <td>' . $label . '</td>
            <td colspan="2">
                BP: ' . ($cg->Vital_signs ?? '-') . '</td>
        </tr>';
        continue;
    }

    // ฟิลด์แบบระบุเพิ่มเติม
    if (in_array($field, ['Other_problems', 'Assistance'])) {
        $val = trim($cg->$field ?? '');
        $customField = ($val !== '' && $val !== '-') ? 'ระบุ: ' . $val : 'ไม่มี';
        $html .= '
        <tr>
            <td style="text-align:center;">' . $i . '</td>
            <td>' . $label . '</td>
            <td colspan="2">' . $customField . '</td>
        </tr>';
        continue;
    }

    // ผู้รายงาน
    if ($field === 'Reporter') {
        $html .= '
        <tr>
            <td style="text-align:center;">' . $i . '</td>
            <td>' . $label . '</td>
            <td colspan="2">' . ($cg->Reporter ?? '-') . '</td>
        </tr>';
        continue;
    }

    // ฟิลด์ทั่วไป (ติ๊กถูก มี/ไม่มี)
    $has = null; // ใช้ null เพื่อให้รู้ว่า "ไม่เข้าเงื่อนไขเลย"

if ($field && isset($cg->$field)) {
    $val = trim((string) $cg->$field);

    if ($val !== '' && $val !== '-') {
        if (mb_strtolower($val) === 'ไม่มี') {
            $has = false; // เจาะจงว่าไม่มี
        } else {
            $has = true;  // มีข้อมูลอื่น
        }
    }
}

            

            $html .= '
            <tr>
              <td style="text-align:center;">' . $i . '</td>
              <td>' . $label . '</td>
              <td style="text-align:center;"><span style="font-family: dejavusans;">[' . ($has ? '&#10004;' : '&nbsp;&nbsp;') . ']</span> มี</td>
              <td style="text-align:center;"><span style="font-family: dejavusans;">[' . (!$has ? '&#10004;' : '&nbsp;&nbsp;') . ']</span> ไม่มี</td>

            </tr>';
        }

        $html .= '
          </tbody>
        </table>';

        // 6. สร้าง PDF และส่งกลับ
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return Response::make($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="CG_Report_' . $id . '.pdf"',
            'Accept-Ranges'       => 'bytes',
        ]);
    }
}
