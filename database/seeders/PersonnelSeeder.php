<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personnel;

class PersonnelSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            1 => 'Admin',
            2 => 'Staff',
            3 => 'Doctor',
            4 => 'Pharmacist',
        ];

        foreach ($types as $id => $type) {
            \App\Models\Personnel::updateOrCreate(
                ['ID_Personnel' => $id],
                ['Type_Personnel' => $type]
            );
        }
    }
}
