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
use App\Models\CareInstruction;
use App\Models\ActivityCaregiver;
use App\Models\BarthelAdl;
use App\Models\ScoreTAI;


class ReportController extends Controller
{
  private function getLogoBase64()
  {
    $path = public_path('images/Logo.png');
    if (file_exists($path)) {
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return '';
  }


  private function getMpdf($orientation = 'A4')
  {
    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    // Standardize on 'sarabun' name for the font
    return new Mpdf([
      'mode' => 'utf-8',
      'format' => $orientation,
      'default_font_size' => 14,
      'fontDir' => array_merge($fontDirs, [
        public_path('fonts'),
        public_path('fonts/thsarabun'),
      ]),
      'fontdata' => $fontData + [
        'sarabun' => [
          'R' => 'THSarabun.ttf',
          'B' => 'THSarabun Bold.ttf',
          'I' => 'THSarabun Italic.ttf',
          'BI' => 'THSarabun BoldItalic.ttf',
        ],
      ],
      'default_font' => 'sarabun',
      'margin_left' => 15,
      'margin_right' => 15,
      'margin_top' => 15,
      'margin_bottom' => 15,
    ]);
  }

  private function generatePdfResponse($view, $data, $filename, $orientation = 'A4')
  {
    $mpdf = $this->getMpdf($orientation);
    $html = view($view, array_merge($data, ['logo' => $this->getLogoBase64()]))->render();

    $mpdf->WriteHTML($html);
    return Response::make($mpdf->Output('', 'S'), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="' . rawurlencode($filename) . '"',
    ]);
  }

  public function ReportPerformanceReport($id)
  {
    $report = PerformanceReport::with(['elderly', 'caregiver', 'adl', 'tai', 'user'])->findOrFail($id);
    $reports = PerformanceReport::where('ID_Elderly', $report->ID_Elderly)
      ->orderBy('Date', 'desc')
      ->get();
    $age = $report->elderly->Birthday ? Carbon::parse($report->elderly->Birthday)->age : null;

    return $this->generatePdfResponse('staff.Report.report-performance-report', [
      'report' => $report,
      'reports' => $reports,
      'elder' => $report->elderly,
      'cg' => $report->caregiver,
      'tai' => $report->tai,
      'adl' => $report->adl,
      'age' => $age,
    ], ($report->elderly->Name_Elderly ?? "Report") . "_CarePlan.pdf", 'A4-L');
  }

  public function ReportCGAll(Request $request)
  {
    // Limit to 200 to prevent PDF memory exhaustion on large datasets
    $cgs = CareGiver::with('elderly')->orderBy('Date_CG', 'desc')->limit(200)->get();
    return $this->generatePdfResponse('staff.Report.report-cg-all', ['cgs' => $cgs], "CG_Report_All.pdf", 'A4-L');
  }

  public function ReportCG($id)
  {
    $cg = CareGiver::with('elderly')->findOrFail($id);
    return $this->generatePdfResponse('staff.Report.report-cg', ['cg' => $cg], ($cg->elderly->Name_Elderly ?? "Report") . "_CG.pdf");
  }

  public function ReportTAI($id)
  {
    $tai = ScoreTAI::with(['elderly', 'user'])->findOrFail($id);
    return $this->generatePdfResponse('staff.Report.report-tai', ['tai' => $tai], ($tai->elderly->Name_Elderly ?? "Report") . "_TAI.pdf");
  }

  public function ReportADL($id)
  {
    $adl = BarthelAdl::with('elderly')->findOrFail($id);
    return $this->generatePdfResponse('staff.Report.report-adl-detail', ['adl' => $adl], ($adl->elderly->Name_Elderly ?? "Report") . "_ADL.pdf");
  }

  public function ReportACG($id)
  {
    $acg = ActivityCaregiver::with('caregiver')->findOrFail($id);
    return $this->generatePdfResponse('staff.Report.report-acg', ['acg' => $acg], ($acg->caregiver->Name_Elderly ?? "Report") . "_ACG.pdf");
  }

  public function ReportACGAll(Request $request)
  {
    // Limit to 200 to prevent PDF memory exhaustion on large datasets
    $activities = ActivityCaregiver::with('caregiver')->orderBy('Date_ACG', 'desc')->limit(200)->get();
    return $this->generatePdfResponse('staff.Report.report-acg-all', ['activities' => $activities], "ACG_Report_All.pdf", 'A4-L');
  }

  public function ReportCI_Single($id)
  {
    $ci = CareInstruction::with('elderly')->findOrFail($id);
    return $this->generatePdfResponse('staff.Report.report-ci-pdf', ['ci' => $ci], ($ci->Name_Elderly ?? "Report") . "_CI.pdf");
  }

  public function MonthlySummary()
  {
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $stats = [
      'total_elderly' => Elderly::count(),
      'new_adl' => BarthelAdl::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
      'new_cg' => CareGiver::whereBetween('Date_CG', [$startOfMonth, $endOfMonth])->count(),
      'new_tai' => ScoreTAI::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
      'new_ci' => CareInstruction::whereBetween('Date_CI', [$startOfMonth, $endOfMonth])->count(),
    ];

    $adlGroups = [
      'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
      'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
      'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
    ];

    return $this->generatePdfResponse('staff.Report.report-monthly-summary', [
      'now' => $now,
      'stats' => $stats,
      'adlGroups' => $adlGroups,
    ], "Monthly_Summary_" . $now->format('Y_m') . ".pdf");
  }

  public function ReportCIConfirm()
  {
    $careInstructions = \App\Models\CareInstruction::whereNotNull('Confirm')->paginate(20);
    return view('staff.Report.report-ci-confirm', compact('careInstructions'));
  }
}
