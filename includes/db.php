<?php
/**
 * Database operations for Daily Report Generator.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create the daily reports table.
 */
function drg_create_db_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'daily_reports';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        area varchar(100) NOT NULL,
        fecha date NOT NULL,
        jefe varchar(100) NOT NULL,
        total_horas int(11) NOT NULL,
        tareas longtext NOT NULL,
        pausa_activa tinyint(1) NOT NULL DEFAULT 0,
        tiempo_pausa varchar(50) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
