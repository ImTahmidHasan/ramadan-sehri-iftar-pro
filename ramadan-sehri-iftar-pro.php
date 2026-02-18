<?php
/**
 * Plugin Name: Ramadan Sehri Iftar Pro
 * Description: Real-time Sehri and Iftar countdown and schedule for Bangladesh districts.
 * Version: 1.0.0
 * Author: Senior Dev
 * Text Domain: rsip-ramadan
 */

if (!defined('ABSPATH')) exit;

// Define Constants
define('RSIP_PATH', plugin_dir_path(__FILE__));
define('RSIP_URL', plugin_dir_url(__FILE__));

// Include Required Files
require_once RSIP_PATH . 'includes/api/aladhan-api.php';
require_once RSIP_PATH . 'includes/shortcode/sehri-iftar.php';

// Enqueue Assets
add_action('wp_enqueue_scripts', 'rsip_enqueue_assets');
function rsip_enqueue_assets() {
    wp_enqueue_style('rsip-style', RSIP_URL . 'assets/css/style.css', [], '1.0.0');
    wp_enqueue_script('rsip-countdown', RSIP_URL . 'assets/js/countdown.js', ['jquery'], '1.0.0', true);
}