<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'สร้างผู้ดูแลระบบ (Admin) ใหม่ เข้าสู่ระบบ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        if (User::where('Username', $username)->exists()) {
            $this->error("ผู้ใช้ชื่อ {$username} มีอยู่ในระบบแล้ว!");
            return Command::FAILURE;
        }

        User::create([
            'Username' => $username,
            'Email' => $username . '@example.com',
            'Password' => Hash::make($password),
            'ID_Personnel' => 1,
            'Type_Personnel' => 'Admin',
            'Name_User' => 'System Administrator (' . $username . ')',
            'Address' => '-',
            'Phone' => '-',
            'Image_User' => 'images-user/Admin.jpg',
            'line_token' => null,
            'is_admin_permanent' => true,
        ]);

        $this->info("สร้างแอดมิน {$username} เรียบร้อยแล้ว!");
        return Command::SUCCESS;
    }
}
