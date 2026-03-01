<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function login()
    {
        return view('login.login');
    }
    public function loginUser(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        // Determine if the login input is an email or username
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'Username';
        $user = User::where($fieldType, $request->login)->first();


        // Check if user exists
        if (!$user) {
            return back()->with('fail', 'ไม่มีบัญชีผู้ใช้นี้ในระบบ');
        }

        // Verify password and login user
        if (Hash::check($request->password, $user->Password)) {
            Auth::login($user);

            return redirect()->intended('dashboard');
        }

        // If password is incorrect, return with error
        return back()->with('fail', 'รหัสผ่านไม่ถูกต้อง');
    }




    ////////////////////////////ล็อกเอ้า/////////////////////////////
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')->with('success', 'คุณออกจากระบบเรียบร้อยแล้ว');
    }
    public function Dashboard_Dcotor()
    {
        return view('doctor.dashboard-doctor');
    }

    /////////////////////////////////////////////////////////
}
