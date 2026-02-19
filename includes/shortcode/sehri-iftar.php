<?php
if (!defined('ABSPATH')) exit;

add_shortcode('sehri_iftar_pro', 'rsip_render_widget');

function rsip_render_widget($atts) {
    wp_enqueue_style('rsip-style');
    wp_enqueue_script('rsip-countdown');

    $atts = shortcode_atts(['district' => 'Dhaka', 'show_table' => 'yes'], $atts);
    $selected_district = isset($_GET['rsip_dist']) ? sanitize_text_field($_GET['rsip_dist']) : $atts['district'];
    
    $today_data = RSIP_Data_Handler::get_today_data($selected_district);
    $full_calendar = RSIP_Data_Handler::get_full_calendar($selected_district);
    $districts = ['Dhaka', 'Gazipur', 'Narayanganj', 'Chattogram', 'Cox’s Bazar', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Cumilla', 'Bogura', 'Dinajpur'];

    ob_start(); ?>
    <div class="rsip-container">
        <div class="rsip-card">
            <h2 class="rsip-title">🌙 Ramadan Schedule 2026</h2>
            <p class="rsip-meta"><?php echo date('d F, Y'); ?> | Ramadan Day: <?php echo esc_html($today_data['ramadan_day'] ?? '—'); ?></p>
            
            <form method="GET" class="rsip-dist-form">
                <select name="rsip_dist" onchange="window.location.href='?rsip_dist=' + this.value;">
                    <?php foreach($districts as $d): ?>
                        <option value="<?php echo $d; ?>" <?php selected($selected_district, $d); ?>><?php echo $d; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <div class="rsip-times">
                <div class="rsip-box sehri">
                    <span>Sehri End</span>
                    <strong><?php echo esc_html($today_data['sehri']); ?></strong>
                </div>
                <div class="rsip-box iftar">
                    <span>Iftar Start</span>
                    <strong><?php echo esc_html($today_data['iftar']); ?></strong>
                </div>
            </div>

            <div class="rsip-countdown">
                <p id="rsip-label">Calculating...</p>
                <div id="rsip-timer" data-sehri="<?php echo $today_data['sehri']; ?>" data-iftar="<?php echo $today_data['iftar']; ?>">00:00:00</div>
            </div>
            
            <div class="rsip-duas">
                <p><strong>Sehri Dua:</strong> নওয়াইতু আন আছুমা গদাম মিংশাহরি রমাদ্বানাল মুবারকি ফারদ্বাল্লাকা ইয়া আল্লাহু ফাতাক্বাব্বাল মিন্নী ইন্নাকা আনতাস সামীয়ুল আলীম।</p>
                <p><strong>Iftar Dua:</strong> আল্লাহুম্মা লাকা ছুমতু ওয়া আলা রিযক্বিকা আফত্বারতু।</p>
            </div>
        </div>

        <?php if($atts['show_table'] == 'yes' && !empty($full_calendar)): ?>
        <div class="rsip-table-wrapper">
            <h3>Full Monthly Schedule (<?php echo esc_html($selected_district); ?>)</h3>
            <table class="rsip-table">
                <thead>
                    <tr><th>Day</th><th>Date</th><th>Sehri</th><th>Iftar</th></tr>
                </thead>
                <tbody>
                    <?php foreach($full_calendar as $index => $row): 
                        $is_today = ($row['date'] == date('Y-m-d')) ? 'class="today-row"' : ''; ?>
                        <tr <?php echo $is_today; ?>>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo date('d M', strtotime($row['date'])); ?></td>
                            <td><?php echo $row['sehri']; ?></td>
                            <td><?php echo $row['iftar']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        <p class="rsip-credit">Source: Islamic Foundation Bangladesh</p>
    </div>
    <?php return ob_get_clean();
}

// New Shortcode for Homepage (Countdown + Boxes Only)
add_shortcode('ramadan_countdown', 'rsip_render_homepage_widget');

function rsip_render_homepage_widget($atts) {
    wp_enqueue_style('rsip-style');
    wp_enqueue_script('rsip-countdown');

    $atts = shortcode_atts(['district' => 'Dhaka'], $atts);
    $selected_district = isset($_GET['rsip_dist']) ? sanitize_text_field($_GET['rsip_dist']) : $atts['district'];
    
    $today_data = RSIP_Data_Handler::get_today_data($selected_district);

    ob_start(); ?>
    <div class="rsip-card rsip-lite">
        <div class="rsip-header-lite">
            <strong><?php echo esc_html($selected_district); ?></strong> | 
            Ramadan Day: <?php echo esc_html($today_data['ramadan_day'] ?? '—'); ?>
        </div>

        <div class="rsip-countdown">
            <p id="rsip-label">Loading...</p>
            <div id="rsip-timer" 
                 data-sehri="<?php echo esc_attr($today_data['sehri']); ?>" 
                 data-iftar="<?php echo esc_attr($today_data['iftar']); ?>">
                 00:00:00
            </div>
        </div>

        <div class="rsip-times">
            <div class="rsip-box sehri">
                <span>Sehri End</span>
                <strong><?php echo esc_html($today_data['sehri']); ?></strong>
            </div>
            <div class="rsip-box iftar">
                <span>Iftar Start</span>
                <strong><?php echo esc_html($today_data['iftar']); ?></strong>
            </div>
        </div>
        
        <a href="<?php echo site_url('/full-schedule'); ?>" class="rsip-view-more">View Full Calendar →</a>
    </div>
    <?php return ob_get_clean();
}