<?php
namespace LoginAnalytics\Services;

use LoginAnalytics\Models\LoginActivity;

class LoginMonitoringService
{
    public static function analyzeLogin($user, $ip, $device, $location)
    {
        $suspiciousScore = 0;
        $lastLogin = LoginActivity::where('user_id', $user->id)->latest()->first();

        if ($lastLogin && $lastLogin->ip_address !== $ip) $suspiciousScore += 30;
        if ($lastLogin && $lastLogin->device_info !== $device) $suspiciousScore += 20;
        if ($lastLogin && $lastLogin->location !== $location) $suspiciousScore += 20;

        $recentFailedLogins = LoginActivity::where('user_id', $user->id)
            ->where('successful_attempt', false)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        if ($recentFailedLogins > 3) $suspiciousScore += 40;

        return $suspiciousScore;
    }
}
