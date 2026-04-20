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

// Include required files
require_once plugin_dir_path( __FILE__ ) . 'includes/db.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/save.php';

// Activation hook
register_activation_hook( __FILE__, 'drg_create_db_table' );

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
 * Enqueue scripts and styles.
 */
function drg_enqueue_assets() {
    wp_enqueue_style( 'drg-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.0.0' );
    wp_enqueue_script( 'drg-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'drg_enqueue_assets' );

/**
 * Register shortcodes.
 */
function drg_register_shortcodes() {
    add_shortcode( 'daily_report_form', 'drg_render_shortcode_form' );
}
add_action( 'init', 'drg_register_shortcodes' );

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
