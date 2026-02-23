<?php

namespace App\Exports;

use App\Models\ScoreTAI;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class Tai implements FromCollection, WithHeadings, WithMapping, WithStyles, WithStrictNullComparison
{
    public function collection()
    {
        return ScoreTAI::with('elderly', 'user')
            ->select('ID_Elderly', 'ID_User', 'mobility', 'confuse', 'feed', 'toilet', 'group', 'updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'วันที่',
            'ชื่อผู้สูงอายุ',
            'ชื่อเจ้าหน้าที่',
            'การเคลื่อนไหว',
            'การสับสน',
            'การให้อาหาร',
            'การเข้าห้องน้ำ',
            'กลุ่ม ADL',
        ];
    }

    public function map($item): array
    {
        $group = $item->group;
        if (in_array($group, ['B5', 'B4', 'B3'])) {
            $group = 'กลุ่มปกติ';
        } elseif (in_array($group, ['C4', 'C3', 'C2'])) {
            $group = 'กลุ่มติดบ้าน';
        } elseif (in_array($group, ['I3', 'I2', 'I1'])) {
            $group = 'กลุ่มติดเตียง';
        } else {
            $group = 'ยังไม่ได้ประเมิน';
        }

        return [
            $item->updated_at ? Carbon::parse($item->updated_at)->format('Y-m-d') : '0000-00-00',
            $item->elderly->Name_Elderly ?? '-',
            $item->user->Name_User ?? '-',
            $item->mobility?? '-',
            $item->confuse?? '-',
            $item->feed?? '-',
            $item->toilet?? '-',
            $group
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('yyyy-mm-dd');
    }
}
