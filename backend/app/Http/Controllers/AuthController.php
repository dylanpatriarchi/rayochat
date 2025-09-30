<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Send OTP code to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
                    ->where('is_active', true)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Nessun account trovato con questo indirizzo email.',
            ]);
        }

        // Generate 6-digit OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP (expires in 10 minutes)
        OtpCode::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new OtpMail($code, $user->name));

        return redirect()->route('auth.verify', ['email' => $user->email])
                        ->with('success', 'Codice inviato alla tua email!');
    }

    /**
     * Show OTP verification form
     */
    public function showVerify(Request $request)
    {
        return view('auth.verify', ['email' => $request->email]);
    }

    /**
     * Verify OTP and login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $otpCode = OtpCode::where('email', $request->email)
                         ->where('code', $request->code)
                         ->where('used', false)
                         ->where('expires_at', '>', now())
                         ->first();

        if (!$otpCode) {
            return back()->withErrors([
                'code' => 'Codice non valido o scaduto.',
            ]);
        }

        $user = User::where('email', $request->email)
                    ->where('is_active', true)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Utente non trovato.',
            ]);
        }

        // Mark OTP as used
        $otpCode->markAsUsed();

        // Login user
        session([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
            'user_email' => $user->email,
        ]);

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('site-owner.dashboard');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('auth.login');
    }
}
