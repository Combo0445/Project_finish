<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure 'Admin' personnel exists
        $personnel = Personnel::firstOrCreate([
            'Type_Personnel' => 'Admin'
        ]);

        // 2. Create the Admin user
        User::updateOrCreate(
            ['Username' => 'admin'],
            [
                'Email' => 'admin@example.com',
                'Password' => Hash::make('admin1234'),
                'ID_Personnel' => $personnel->ID_Personnel,
                'Type_Personnel' => 'Admin',
                'Name_User' => 'System Admin',
                'Image_User' => 'images-user/Admin.jpg',
                'Address' => 'System',
                'Phone' => '0000000000'
            ]
        );
    }
}
