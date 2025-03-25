<?php

namespace App\Exports;

use App\Models\BarthelAdl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ADLExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BarthelAdl::select('created_at', 'Name_User', 'Name_Elderly', 'Score_ADL', 'Group_ADL')->get();
    }

    public function headings(): array
    {
        return [
            'วันที่',
            'ชื่อเจ้าหน้าที่',
            'ชื่อผู้สูงอายุ',
            'คะแนนการประเมิน ADL',
            'ประเภทกลุ่ม ADL'
        ];
    }

    public function map($adl): array
    {
        return [
            $adl->created_at ? $adl->created_at->format('Y-m-d') : '0000/00/00',
            $adl->Name_User,
            $adl->Name_Elderly,
            (string) ($adl->Score_ADL ?? 0),
            $adl->Group_ADL
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Making the header bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Optional: Set the date format for column A (Date)
        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('yyyy-mm-dd');
    }
}

