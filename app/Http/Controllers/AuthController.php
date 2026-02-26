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

    // public function showPasswordRequestForm()
    // {
    //     return view('login.request_email'); // สร้าง view นี้เพื่อแสดงฟอร์มขอรีเซ็ทรหัสผ่าน
    // }

    // public function sendVerificationCode(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     // สร้างรหัสยืนยันแบบสุ่ม
    //     $verificationCode = rand(100000, 999999);

    //     // ส่งรหัสยืนยันไปยังอีเมล
    //     Mail::send('emails.verification_code', ['code' => $verificationCode], function ($message) use ($request) {
    //         $message->to($request->email);
    //         $message->subject('Verification Code');
    //     });

    //     // เก็บรหัสยืนยันไว้ใน session
    //     session(['verification_code' => $verificationCode]);
    //     session(['email' => $request->email]);

    //     return redirect()->route('password.verify-code')->with('status', 'Verification code sent to your email.');
    // }

    // public function verifyCode(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|numeric',
    //     ]);

    //     if ($request->code == session('verification_code')) {
    //         return view('login.reset-password'); // สร้าง view นี้เพื่อแสดงฟอร์มรีเซ็ทรหัสผ่าน
    //     }

    //     return back()->withErrors(['code' => 'Invalid verification code.']);
    // }

    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|confirmed|min:8',
    //     ]);

    //     // อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
    //     $user = User::where('email', session('email'))->first();
    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     // ล้างข้อมูลใน session
    //     session()->forget(['verification_code', 'email']);

    //     return redirect()->route('login')->with('status', 'Password has been reset.');
    // }


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
