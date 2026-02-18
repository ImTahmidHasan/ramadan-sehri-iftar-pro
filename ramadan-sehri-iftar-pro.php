<?php
/**
 * Plugin Name: Ramadan Sehri Iftar Pro
 * Description: Official Bangladesh Ramadan Schedule with CSV upload and Real-time Countdown.
 * Version: 1.1.0
 * Author: Senior WordPress Developer
 * Text Domain: rsip-ramadan
 */
date_default_timezone_set('Asia/Dhaka');
if (!defined('ABSPATH')) exit;

// Define Constants
define('RSIP_PATH', plugin_dir_path(__FILE__));
define('RSIP_URL', plugin_dir_url(__FILE__));

// Includes
require_once RSIP_PATH . 'includes/admin/calendar-admin.php';
require_once RSIP_PATH . 'includes/data/calendar-handler.php';
require_once RSIP_PATH . 'includes/api/aladhan-api.php';
require_once RSIP_PATH . 'includes/shortcode/sehri-iftar.php';

// Asset Enqueuing (Only when shortcode is present)
add_action('wp_enqueue_scripts', 'rsip_register_assets');
function rsip_register_assets() {
    wp_register_style('rsip-style', RSIP_URL . 'assets/css/style.css', [], '1.1.0');
    wp_register_script('rsip-countdown', RSIP_URL . 'assets/js/countdown.js', ['jquery'], '1.1.0', true);
}

// Plugin Activation: Set default district
register_activation_hook(__FILE__, 'rsip_on_activation');
function rsip_on_activation() {
    if (!get_option('rsip_default_district')) {
        update_option('rsip_default_district', 'Dhaka');
    }
}