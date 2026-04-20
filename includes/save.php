<?php
/**
 * Data saving logic for Daily Report Generator.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AJAX handler to save the daily report.
 */
function drg_save_daily_report() {
    // Security check
    check_ajax_referer( 'drg_save_report', 'drg_report_nonce' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'daily_reports';

    // Sanitize data
    $area         = sanitize_text_field( $_POST['area'] ?? '' );
    $fecha        = sanitize_text_field( $_POST['fecha'] ?? '' );
    $jefe         = sanitize_text_field( $_POST['jefe'] ?? '' );
    $total_horas  = intval( $_POST['total_horas'] ?? 0 );
    $tareas       = isset( $_POST['tareas'] ) ? $_POST['tareas'] : [];
    $pausa_activa = isset( $_POST['pausa_activa'] ) && $_POST['pausa_activa'] === 'on' ? 1 : 0;
    $tiempo_pausa = sanitize_text_field( $_POST['tiempo_pausa'] ?? '' );

    // Validation
    if ( empty( $area ) || empty( $fecha ) || empty( $jefe ) || empty( $total_horas ) ) {
        wp_send_json_error( [ 'message' => 'Faltan campos obligatorios.' ] );
    }

    // Process tasks
    $sanitized_tareas = [];
    if ( is_array( $tareas ) ) {
        foreach ( $tareas as $tarea ) {
            $sanitized_tareas[] = [
                'nombre'            => sanitize_text_field( $tarea['nombre'] ?? '' ),
                'tipo'              => sanitize_text_field( $tarea['tipo'] ?? '' ),
                'porcentaje_inicio' => intval( $tarea['porcentaje_inicio'] ?? 0 ),
                'porcentaje_fin'    => intval( $tarea['porcentaje_fin'] ?? 0 ),
                'horas'             => floatval( $tarea['horas'] ?? 0 ),
            ];
        }
    }

    $data = [
        'area'         => $area,
        'fecha'        => $fecha,
        'jefe'         => $jefe,
        'total_horas'  => $total_horas,
        'tareas'       => json_encode( $sanitized_tareas ),
        'pausa_activa' => $pausa_activa,
        'tiempo_pausa' => $tiempo_pausa,
        'created_at'   => current_time( 'mysql' ),
    ];

    $format = [ '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%s' ];

    $inserted = $wpdb->insert( $table_name, $data, $format );

    if ( $inserted ) {
        wp_send_json_success( [ 'message' => 'Reporte guardado correctamente.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'Error al guardar el reporte en la base de datos.' ] );
    }
}
