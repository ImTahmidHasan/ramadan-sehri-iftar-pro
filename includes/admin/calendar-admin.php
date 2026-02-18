<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'rsip_add_admin_menu');

function rsip_add_admin_menu() {
    add_menu_page(
        'Ramadan Pro Settings',
        'Ramadan Pro',
        'manage_options',
        'rsip-settings',
        'rsip_admin_page_html',
        'dashicons-calendar-alt',
        30
    );
}

function rsip_admin_page_html() {
    if (!current_user_can('manage_options')) return;

    // Handle CSV Upload
    if (isset($_POST['rsip_upload_csv']) && check_admin_referer('rsip_csv_upload_nonce')) {
        $district = sanitize_text_field($_POST['district_name']);
        if (!empty($_FILES['csv_file']['tmp_name'])) {
            $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
            $data_array = [];
            fgetcsv($handle); // Skip header

            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) >= 3) {
                    $data_array[] = [
                        'date'  => sanitize_text_field($row[0]),
                        'sehri' => sanitize_text_field($row[1]),
                        'iftar' => sanitize_text_field($row[2])
                    ];
                }
            }
            fclose($handle);
            update_option("rsip_calendar_{$district}", $data_array);
            echo '<div class="updated"><p>Calendar updated for ' . esc_html($district) . '!</p></div>';
        }
    }

    $districts = ['Dhaka', 'Gazipur', 'Narayanganj', 'Chattogram', 'Cox’s Bazar', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Cumilla', 'Bogura', 'Dinajpur'];
    ?>
    <div class="wrap">
        <h1>🌙 Ramadan Sehri Iftar Pro - Admin</h1>
        <div class="card" style="max-width: 600px; padding: 20px;">
            <h2>Upload Official Timetable (CSV)</h2>
            <p>Format: <code>date,sehri,iftar</code> (e.g., 2026-03-02, 05:04, 18:02)</p>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('rsip_csv_upload_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Select District</th>
                        <td>
                            <select name="district_name">
                                <?php foreach($districts as $d) echo "<option value='$d'>$d</option>"; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">CSV File</th>
                        <td><input type="file" name="csv_file" accept=".csv" required></td>
                    </tr>
                </table>
                <p class="submit"><input type="submit" name="rsip_upload_csv" class="button button-primary" value="Upload & Overwrite"></p>
            </form>
        </div>
    </div>
    <?php
}