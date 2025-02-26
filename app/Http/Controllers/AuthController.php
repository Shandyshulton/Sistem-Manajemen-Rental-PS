<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Employee;

class AuthController extends Controller
{
    // Menampilkan halaman forgot password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Memproses forgot password (cek email & redirect ke reset password)
    public function processForgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $employee = Employee::where('email', $request->email)->first();

    if ($employee) {
        return redirect()->route('reset.password', ['email' => $request->email]);
    } else {
        return back()->with('error', 'Email ini tidak terdaftar');
    }
}

    // Menampilkan halaman reset password
    public function showResetPasswordForm($email)
    {
        return view('auth.reset-password', compact('email'));
    }

    // Memproses reset password
    public function processResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:employees,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Update password di database
        $employee = Employee::where('email', $request->email)->first();
        $employee->password = Hash::make($request->password);
        $employee->save();

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if ($employee && Hash::check($request->password, $employee->password)) {
            Session::put('employee', $employee);
            return redirect('/dashboard')->with('success', 'Login Berhasil!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout()
    {
        Session::forget('employee');
        return redirect('/login')->with('success', 'Logout Berhasil!');
    }
}
