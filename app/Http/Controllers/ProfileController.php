<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Retrieve the logged-in user

        if (!$user) {
            return redirect('login')->with('error', 'กรุณาล็อกอินเพื่อเข้าถึงหน้านี้');
        }
        return view('layout.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('login')->with('error', 'กรุณาล็อกอินเพื่อเข้าถึงหน้านี้');
        }
        return view('layout.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('profile-user')->with('error', 'คุณสามารถแก้ไขโปรไฟล์ของคุณเองเท่านั้น');
        }

        $request->validate([
            'Name_User' => 'required|string',
            'Email' => 'required|string|email',
            'Address' => 'nullable|string',
            'Phone' => 'nullable|string',
            'Image_User' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->Name_User = $request->Name_User;
        $user->Email = $request->Email;
        $user->Address = $request->Address ?? '';
        $user->Phone = $request->Phone ?? '';

        if ($request->hasFile('Image_User')) {
            $imageName = time() . '.' . $request->Image_User->extension();
            $request->Image_User->move(public_path('images'), $imageName);
            $user->Image_User = 'images/' . $imageName;
        } else {
            // Set default profile image if none is provided
            if (!$user->Image_User) {
                switch ($user->Type_Personnel) {
                    case 'Admin':
                        $user->Image_User = 'images-user/Admin.jpg';
                        break;
                    case 'Staff':
                        $user->Image_User = 'images-user/Staff.png';
                        break;
                    case 'Doctor':
                        $user->Image_User = 'images-user/Doctor.png';
                        break;
                    default:
                        $user->Image_User = '';
                        break;
                }
            }
        }

        $user->save();

        return redirect()->route('profile-user')->with('success', 'อัปเดตโปรไฟล์สำเร็จแล้ว');
    }
}
