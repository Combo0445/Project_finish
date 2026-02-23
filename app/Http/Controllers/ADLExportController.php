<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ADLExport;
use Maatwebsite\Excel\Facades\Excel;

class ADLExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ADLExport, 'adl_report.xlsx');
    }
}
