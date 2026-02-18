<?php
if (!defined('ABSPATH')) exit;

class RSIP_API {
    public static function get_times($city = 'Dhaka') {
        $city = sanitize_text_field($city);
        $transient_key = 'rsip_times_' . strtolower($city);
        $cached_data = get_transient($transient_key);

        if ($cached_data !== false) {
            return $cached_data;
        }

        // Aladhan API: Method 1 (MWL), Country BD
        $url = "https://api.aladhan.com/v1/timingsByCity?city=" . urlencode($city) . "&country=Bangladesh&method=1";
        
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) return false;

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['data']['timings'])) {
            $data = [
                'fajr'    => $body['data']['timings']['Fajr'],
                'maghrib' => $body['data']['timings']['Maghrib'],
                'date'    => $body['data']['date']['readable']
            ];
            // Cache for 12 hours
            set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);
            return $data;
        }

        return false;
    }
}