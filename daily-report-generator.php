<?php
/**
 * Plugin Name: Daily Report Generator
 * Description: A plugin to generate daily reports for WordPress site activities.
 * Version: 1.0.0
 * Author: Jules
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register a custom menu page in the WordPress admin dashboard.
 */
function drg_add_admin_menu() {
    add_menu_page(
        'Daily Report',            // Page title
        'Daily Report',            // Menu title
        'manage_options',          // Capability
        'daily-report-generator',  // Menu slug
        'drg_render_report_page',  // Callback function
        'dashicons-chart-line',    // Icon URL
        20                         // Position
    );
}
add_action( 'admin_menu', 'drg_add_admin_menu' );

/**
 * Render the daily report page.
 */
function drg_render_report_page() {
    ?>
    <div class="wrap">
        <h1>Daily Report</h1>
        <p>Welcome to the Daily Report Generator. This is a placeholder for the daily report content.</p>
        <p>Summary for: <?php echo date('Y-m-d'); ?></p>
    </div>
    <?php
}
