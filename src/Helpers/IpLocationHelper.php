<?php

namespace LoginAnalytics\Helpers;

use Illuminate\Support\Facades\Http;

class IpLocationHelper
{
    public static function getUserLocation($ip)
    {
        $url = "http://ip-api.com/json/{$ip}";

        try {
            $response = Http::get($url);
            $data = $response->json();

            if ($data && isset($data['status']) && $data['status'] === 'success') {
                return $data['city'] . ', ' . $data['country'];
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }

        return 'Unknown';
    }
}
