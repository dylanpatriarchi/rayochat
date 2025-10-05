<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function showLoginForm()
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        
        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Generate unique session token for this OTP request
        $otpSessionToken = Str::random(32);
        
        // Set OTP expiry (10 minutes from now)
        $otpExpiresAt = Carbon::now()->addMinutes(10);
        
        // Update user with OTP and session token
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpiresAt,
            'otp_session_token' => $otpSessionToken,
        ]);
        
        // Store session token in session for validation
        session(['otp_session_token' => $otpSessionToken]);

        // Send OTP email with custom template
        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpCode, 10));
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email: " . $e->getMessage());
        }

        return redirect()->route('otp.verify')->with('email', $user->email)->with('success', 'OTP inviato alla tua email.');
    }

    public function showVerifyForm()
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        // If no email in session, redirect to login
        if (!session('email')) {
            return redirect()->route('login');
        }
        
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Get session token
        $sessionToken = session('otp_session_token');
        
        $user = User::where('email', $request->email)
                   ->where('otp_code', $request->otp_code)
                   ->where('otp_expires_at', '>', Carbon::now())
                   ->where('otp_session_token', $sessionToken)
                   ->first();

        if (!$user) {
            return back()->withErrors(['otp_code' => 'Codice OTP invalido, scaduto o sessione scaduta.'])->withInput();
        }

        // Clear OTP and session token after successful verification
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_session_token' => null,
        ]);
        
        // Clear session token
        session()->forget('otp_session_token');

        // Update last login timestamp
        $user->update(['last_login_at' => Carbon::now()]);
        
        // Login the user with remember token
        Auth::login($user, true); // true = remember me

        return redirect()->route('dashboard');
    }

}