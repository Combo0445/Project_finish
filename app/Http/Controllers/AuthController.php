<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

/**
 * AuthController manages user authentication, including login, logout, and redirection.
 */
class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function login()
    {
        return view('login.login');
    }
    /**
     * Handle the user login request.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

            // Redirect the user based on their role using helper methods
            if ($user->isAdmin()) {
                return redirect()->intended('admin-dashboard');
            } elseif ($user->isDoctor()) {
                return redirect()->intended('doctor-dashboard');
            } else {
                return redirect()->intended('staff-dashboard');
            }
        }

        return back()->with('fail', 'รหัสผ่านไม่ถูกต้อง');
    }
    /**
     * Log the user out and invalidate the session.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login')->with('success', 'คุณออกจากระบบเรียบร้อยแล้ว');
    }
    /**
     * Display the doctor dashboard.
     */
    public function Dashboard_Dcotor()
    {
        return view('doctor.dashboard-doctor');
    }

    /////////////////////////////////////////////////////////
}
