<?php
if (!defined('ABSPATH')) exit;

add_shortcode('sehri_iftar_pro', 'rsip_render_shortcode');

function rsip_render_shortcode() {
    $districts = ['Dhaka', 'Gazipur', 'Narayanganj', 'Chattogram', 'Cox’s Bazar', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Cumilla', 'Bogura', 'Dinajpur'];
    
    $current_city = isset($_GET['rsip_city']) ? sanitize_text_field($_GET['rsip_city']) : 'Dhaka';
    $times = RSIP_API::get_times($current_city);

    if (!$times) return '<p>Unable to load prayer times.</p>';

    ob_start();
    ?>
    <div class="rsip-card" id="rsip-app">
        <div class="rsip-header">
            <h3>🌙 Ramadan Pro</h3>
            <p><?php echo esc_html($times['date']); ?></p>
        </div>

        <div class="rsip-selector">
            <form method="GET" action="">
                <select name="rsip_city" onchange="this.form.submit()">
                    <?php foreach ($districts as $district): ?>
                        <option value="<?php echo esc_attr($district); ?>" <?php selected($current_city, $district); ?>>
                            <?php echo esc_html($district); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="rsip-grid">
            <div class="rsip-box">
                <span class="label">Sehri Ends</span>
                <span class="time"><?php echo esc_html($times['fajr']); ?></span>
            </div>
            <div class="rsip-box">
                <span class="label">Iftar Starts</span>
                <span class="time"><?php echo esc_html($times['maghrib']); ?></span>
            </div>
        </div>

        <div class="rsip-countdown-container">
            <div id="rsip-status-text">Loading Countdown...</div>
            <div id="rsip-timer" 
                 data-fajr="<?php echo esc_attr($times['fajr']); ?>" 
                 data-maghrib="<?php echo esc_attr($times['maghrib']); ?>">
                 00:00:00
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}