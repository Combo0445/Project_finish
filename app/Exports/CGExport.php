<?php

namespace App\Exports;

use App\Models\CareGiver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CGExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return CareGiver::select('Date_CG', 'Name_CG', 'Name_Elderly', 'Group_ADL')->get();
    }

    public function headings(): array
    {
        return [
            'วันที่',
            'ชื่อเจ้าหน้าที่',
            'ชื่อผู้สูงอายุ',
            'ประเภทกลุ่ม ADL'
        ];
    }

    public function map($cg): array
    {
        return [
            $cg->Date_CG ? Carbon::parse($cg->Date_CG)->format('Y-m-d') : '0000-00-00',
            $cg->Name_CG,
            $cg->Name_Elderly,
            $cg->Group_ADL
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Making the header bold
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Optional: Set the date format for column A (Date)
        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('yyyy-mm-dd');
    }
}
