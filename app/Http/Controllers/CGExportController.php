<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CGExport;
use Maatwebsite\Excel\Facades\Excel;

class CGExportController extends Controller
{
    public function export()
    {
        return Excel::download(new CGExport, 'cg_report.xlsx');
    }
}
