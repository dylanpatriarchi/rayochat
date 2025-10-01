<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class InvalidateExpiredOtps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Clean up expired OTPs on every request
        User::where('otp_expires_at', '<', Carbon::now())
            ->whereNotNull('otp_code')
            ->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_session_token' => null,
            ]);

        return $next($request);
    }
}