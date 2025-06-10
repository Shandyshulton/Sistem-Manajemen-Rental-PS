<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if ($staff && Hash::check($request->password, $staff->password)) {
            Auth::login($staff);
            return redirect('/dashboard')->with('success', 'Login Berhasil! Selamat datang, ' . $staff->name);
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login')->with('success', 'Logout Berhasil!');
    }

    // ============== FORGOT & RESET PASSWORD ==============

    // Menampilkan halaman forgot password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Proses forgot password
    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if ($staff) {
            $token = Str::random(64); 
            return redirect()->route('reset.password', ['token' => $token, 'email' => $request->email]);
        }

        return back()->with('error', 'Email ini tidak terdaftar');
    }

    // Menampilkan halaman reset password
    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->route('token');

        if (!$email || !$token) {
            return redirect()->route('forgot.password')->with('error', 'Link reset tidak valid');
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    // Proses reset password
    public function processResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:staffs,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff) {
            return back()->with('error', 'Email tidak ditemukan');
        }
        $staff->password = Hash::make($request->password);
        $staff->save();

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui.');
    }
}
