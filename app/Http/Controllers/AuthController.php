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
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return response()->json(['ok' => true]);
    }
}
