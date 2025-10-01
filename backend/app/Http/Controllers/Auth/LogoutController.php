<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Clear any pending OTP data
        if (Auth::check()) {
            Auth::user()->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_session_token' => null,
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sei stato disconnesso con successo.');
    }
}
