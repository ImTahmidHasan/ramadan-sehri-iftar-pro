<?php
if (!defined('ABSPATH')) exit;

class RSIP_API {
    public static function fetch_times($city) {
        $transient_key = 'rsip_api_' . sanitize_title($city);
        $cached = get_transient($transient_key);
        if ($cached) return $cached;

        $url = "https://api.aladhan.com/v1/timingsByCity?city=" . urlencode($city) . "&country=Bangladesh&method=1";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) return false;

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($body['data']['timings'])) {
            $data = [
                'date' => date('Y-m-d'),
                'sehri' => $body['data']['timings']['Fajr'],
                'iftar' => $body['data']['timings']['Maghrib'],
                'ramadan_day' => 'N/A'
            ];
            set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);
            return $data;
        }
        return false;
    }
}