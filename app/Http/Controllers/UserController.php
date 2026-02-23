<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser()
    {
        $personnelTypes = ['Admin'];
        $usersData = [
            'Admin' => [
                'Username' => 'admin',
                'Email' => 'admin@gmail.com',
                'Password' => 'admin',
                'Image' => 'images-user/Admin.jpg'
            ],
        ];

        foreach ($personnelTypes as $type) {
            $personnel = Personnel::where('Type_Personnel', $type)->first();
            if (!$personnel) {
                return response()->json(['message' => "Personnel type {$type} not found"], 404);
            }

            $userData = $usersData[$type];
            $user = new User();
            $user->Username = $userData['Username'];
            $user->Email = $userData['Email'];
            $user->Password = Hash::make($userData['Password']);
            $user->ID_Personnel = $personnel->ID_Personnel;
            $user->Type_Personnel = $type;
            $user->Image_User = $userData['Image'];
            $user->save();
        }

        return response()->json(['message' => 'เพิ่มผู้ใช้ทั้งหมดเรียบร้อยแล้ว'], 201);
    }
}
