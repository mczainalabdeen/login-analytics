<?php

namespace LoginAnalytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LoginAnalytics\Models\LoginActivity;
use LoginAnalytics\Services\LoginMonitoringService;
use LoginAnalytics\Helpers\IpLocationHelper;

class MonitorLoginActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ip = $request->ip();
            $device = $request->header('User-Agent');
            $location = IpLocationHelper::getUserLocation($ip);

            // Analyze login
            $suspiciousScore = LoginMonitoringService::analyzeLogin($user, $ip, $device, $location);

            // Save login activity
            LoginActivity::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'device_info' => $device,
                'location' => $location,
                'login_time' => now(),
                'successful_attempt' => true,
                'suspicious_score' => $suspiciousScore,
            ]);

            // Block suspicious login
            if ($suspiciousScore >= 50) {
                Auth::logout();
                return response()->json(['message' => 'Suspicious login detected. Verify your identity.'], 403);
            }
        }

        return $next($request);
    }
}
