<?php
/**
 * Admin Calendar Management
 * Optimized with Documentation Sidebar
 */

if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'rsip_add_admin_menu');

function rsip_add_admin_menu() {
    add_menu_page(
        __('Ramadan Pro Settings', 'rsip-ramadan'),
        __('Ramadan Pro', 'rsip-ramadan'),
        'manage_options',
        'rsip-settings',
        'rsip_admin_page_html',
        'dashicons-calendar-alt',
        30
    );
}

function rsip_admin_page_html() {
    if (!current_user_can('manage_options')) return;

    // Handle CSV Upload (Previous logic remains the same)
    if (isset($_POST['rsip_upload_csv']) && check_admin_referer('rsip_csv_upload_nonce')) {
        $district = sanitize_text_field($_POST['district_name']);
        
        if (!empty($_FILES['csv_file']['tmp_name'])) {
            $file = $_FILES['csv_file']['tmp_name'];
            $file_info = wp_check_filetype(basename($_FILES['csv_file']['name']));
            
            if ($file_info['ext'] !== 'csv') {
                echo '<div class="error"><p>' . esc_html__('❌ Error: Please upload a valid CSV file.', 'rsip-ramadan') . '</p></div>';
            } else {
                $handle = fopen($file, 'r');
                $data_array = [];
                fgetcsv($handle); // Skip the header row

                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($row) >= 3) {
                        $data_array[] = [
                            'date'  => sanitize_text_field(trim($row[0])),
                            'sehri' => sanitize_text_field(trim($row[1])), 
                            'iftar' => sanitize_text_field(trim($row[2]))
                        ];
                    }
                }
                fclose($handle);

                if (!empty($data_array)) {
                    update_option("rsip_calendar_{$district}", $data_array);
                    echo '<div class="updated"><p>✅ ' . sprintf(esc_html__('Success! Saved %d days for %s.', 'rsip-ramadan'), count($data_array), esc_html($district)) . '</p></div>';
                } else {
                    echo '<div class="error"><p>' . esc_html__('❌ Error: No valid data found in CSV. Check your format.', 'rsip-ramadan') . '</p></div>';
                }
            }
        }
    }

    $districts = ['Dhaka', 'Gazipur', 'Narayanganj', 'Chattogram', 'Cox’s Bazar', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Cumilla', 'Bogura', 'Dinajpur'];
    ?>
    <div class="wrap">
        <h1>🌙 <?php esc_html_e('Ramadan Sehri Iftar Pro - Management', 'rsip-ramadan'); ?></h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 20px;">
            
            <div class="card" style="padding: 20px; margin: 0;">
                <h2 style="border-bottom: 2px solid #1b5e20; padding-bottom: 10px;"><?php esc_html_e('1. Data Upload', 'rsip-ramadan'); ?></h2>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('rsip_csv_upload_nonce'); ?>
                    <p><strong><?php esc_html_e('Select District:', 'rsip-ramadan'); ?></strong></p>
                    <select name="district_name" style="width: 100%; margin-bottom: 15px;">
                        <?php foreach($districts as $d) echo "<option value='".esc_attr($d)."'>".esc_html($d)."</option>"; ?>
                    </select>
                    <p><strong><?php esc_html_e('Choose CSV:', 'rsip-ramadan'); ?></strong></p>
                    <input type="file" name="csv_file" accept=".csv" required style="margin-bottom: 15px;">
                    <?php submit_button(__('Upload & Overwrite', 'rsip-ramadan'), 'primary', 'rsip_upload_csv'); ?>
                </form>
            </div>

            <div class="card" style="padding: 20px; margin: 0;">
                <h2 style="border-bottom: 2px solid #1b5e20; padding-bottom: 10px;"><?php esc_html_e('2. District Status', 'rsip-ramadan'); ?></h2>
                <div style="">
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach($districts as $d): 
                            $exists = get_option("rsip_calendar_{$d}");
                            $icon = $exists ? '✅' : '❌';
                            $color = $exists ? '#2e7d32' : '#d32f2f';
                            ?>
                            <li style="padding: 8px; background: #f9f9f9; margin-bottom: 5px; border-radius: 4px; border-left: 4px solid <?php echo $color; ?>;">
                                <?php echo $icon; ?> <strong><?php echo esc_html($d); ?></strong>: 
                                <span style="font-size: 12px; float: right;"><?php echo $exists ? count($exists) . ' days' : 'No Data'; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card" style="padding: 20px; margin: 0; background: #f1f8e9;">
                <h2 style="border-bottom: 2px solid #1b5e20; padding-bottom: 10px;"><?php esc_html_e('3. Documentation', 'rsip-ramadan'); ?></h2>
                
                <h3>📄 <?php esc_html_e('Shortcodes', 'rsip-ramadan'); ?></h3>
                <p><strong><?php esc_html_e('Main Widget (With Table):', 'rsip-ramadan'); ?></strong><br>
                <code>[sehri_iftar_pro]</code></p>
                
                <p><strong><?php esc_html_e('Homepage (Countdown Only):', 'rsip-ramadan'); ?></strong><br>
                <code>[ramadan_countdown]</code></p>

                <p><strong><?php esc_html_e('Force District:', 'rsip-ramadan'); ?></strong><br>
                <code>[ramadan_countdown district="Chattogram"]</code></p>

                <hr>

                <h3>📅 <?php esc_html_e('CSV Sample Structure', 'rsip-ramadan'); ?></h3>
                <table style="width: 100%; background: #fff; border: 1px solid #ddd; font-size: 11px;">
                    <thead><tr><th>date</th><th>sehri</th><th>iftar</th></tr></thead>
                    <tbody>
                        <tr><td>2026-03-02</td><td>05:04</td><td>18:02</td></tr>
                        <tr><td>2026-03-03</td><td>05:03</td><td>18:03</td></tr>
                    </tbody>
                </table>
                <p style="font-size: 11px; color: #666;"><?php esc_html_e('Note: Ensure date is YYYY-MM-DD.', 'rsip-ramadan'); ?></p>
            </div>

        </div>
    </div>
    <?php
}