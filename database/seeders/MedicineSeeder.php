<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\MedicineLot;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol (500mg)',
                'description' => 'ยาแก้ปวด ลดไข้',
                'type' => 'ยาเม็ด',
                'stock' => 1000,
                'price' => 1.5,
                'lots' => [
                    ['lot_number' => 'LOT-P001', 'stock' => 500, 'mfd_date' => '2025-01-01', 'exp_date' => '2027-01-01'],
                    ['lot_number' => 'LOT-P002', 'stock' => 500, 'mfd_date' => '2025-02-01', 'exp_date' => '2027-02-01'],
                ]
            ],
            [
                'name' => 'Amlodipine (5mg)',
                'description' => 'ยาลดความดันโลหิตสูง',
                'type' => 'ยาเม็ด',
                'stock' => 500,
                'price' => 5.0,
                'lots' => [
                    ['lot_number' => 'LOT-A001', 'stock' => 500, 'mfd_date' => '2025-01-15', 'exp_date' => '2028-01-15'],
                ]
            ],
            [
                'name' => 'Metformin (500mg)',
                'description' => 'ยาควบคุมระดับน้ำตาลในเลือด',
                'type' => 'ยาเม็ด',
                'stock' => 800,
                'price' => 3.0,
                'lots' => [
                    ['lot_number' => 'LOT-M001', 'stock' => 800, 'mfd_date' => '2025-02-10', 'exp_date' => '2028-02-10'],
                ]
            ],
            [
                'name' => 'Simvastatin (20mg)',
                'description' => 'ยาลดไขมันในเส้นเลือด',
                'type' => 'ยาเม็ด',
                'stock' => 400,
                'price' => 10.0,
                'lots' => [
                    ['lot_number' => 'LOT-S001', 'stock' => 400, 'mfd_date' => '2025-03-01', 'exp_date' => '2027-03-01'],
                ]
            ],
            [
                'name' => 'Aspirin (81mg)',
                'description' => 'ยาต้านเกล็ดเลือด',
                'type' => 'ยาเม็ด',
                'stock' => 600,
                'price' => 2.5,
                'lots' => [
                    ['lot_number' => 'LOT-AS01', 'stock' => 600, 'mfd_date' => '2025-01-20', 'exp_date' => '2028-01-20'],
                ]
            ],
        ];

        foreach ($medicines as $medData) {
            $lots = $medData['lots'];
            unset($medData['lots']);

            $medicine = Medicine::create($medData);

            foreach ($lots as $lotData) {
                $lotData['medicine_id'] = $medicine->id;
                MedicineLot::create($lotData);
            }
        }
    }
}
