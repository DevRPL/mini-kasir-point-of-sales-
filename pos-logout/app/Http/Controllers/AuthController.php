<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show() { return Auth::check() ? redirect('/') : view('login'); }

    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (!Auth::attempt($c + ['is_active' => 1], $r->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah, atau akun nonaktif.'])->onlyInput('email');
        }
        $r->session()->regenerate();
        return redirect('/');
    }

    public function logout(Request $r)
    {
        $guard = Auth::guard('web');
        $guard->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        $res = $r->expectsJson() ? response()->json(['ok' => true]) : redirect('/login');
        // pastikan cookie "ingat saya" ikut dihapus & halaman tidak dipakai ulang dari cache
        return $res->withoutCookie($guard->getRecallerName())
                   ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
