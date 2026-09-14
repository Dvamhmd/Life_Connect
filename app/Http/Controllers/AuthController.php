<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin VAS.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin_vas' => redirect()->route('vas.dashboard'),
            'admin_sales' => redirect()->route('admin-sales.dashboard'),
            'opj' => redirect()->route('opj.dashboard'),
            'c_care' => redirect()->route('ccare.dashboard'),
            'sales' => redirect()->route('mobile-simulator'),
            default => redirect()->route('login'),
        };
    }
}
