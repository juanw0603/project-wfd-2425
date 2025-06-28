<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
        public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($request->email));

        Mail::raw("Klik link berikut untuk mereset password Anda: $resetUrl", function ($message) use ($request) {
            $message->to($request->email)->subject('Reset Password');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }
}
