<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personnel;

class PersonnelSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Admin', 'Staff', 'Doctor'];

        foreach ($types as $type) {
            Personnel::updateOrCreate(
            ['Type_Personnel' => $type]
            );
        }
    }
}
