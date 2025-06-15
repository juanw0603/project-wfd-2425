<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Attribute\WithLogLevel;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Kalau sudah login, langsung redirect ke dashboard sesuai role
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'kasir' => redirect()->route('kasir.transaksi'),
                'gudang' => redirect()->route('dashboard'),
                default => abort(403),
            };
        }

        return view('Login');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect sesuai role
            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard')->with('success','Berhasil login sebagai admin!!!'),
                'kasir' => redirect()->route('kasir.transaksi')->with('success','Berhasil login sebagai kasir!!!'),
                'gudang' => redirect()->route('testing')->with('success','Berhasil login sebagai gudang!!!'),
                default => abort(403),
            };
        }

        return back()->with('error', 'Email atau password salah');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
