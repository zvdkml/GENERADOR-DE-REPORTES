<?php
/**
 * Form rendering for Daily Report Generator.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Callback for [daily_report_form] shortcode.
 */
function drg_render_shortcode_form() {
    ob_start();
    ?>
    <form id="drg-report-form" class="drg-form" method="POST">
        <?php wp_nonce_field( 'drg_save_report', 'drg_report_nonce' ); ?>

        <div class="drg-form-group">
            <label for="drg_area">Área:</label>
            <input type="text" name="area" id="drg_area" required>
        </div>

        <div class="drg-form-group">
            <label for="drg_fecha">Fecha:</label>
            <input type="date" name="fecha" id="drg_fecha" required value="<?php echo function_exists('wp_date') ? wp_date('Y-m-d') : date('Y-m-d'); ?>">
        </div>

        <div class="drg-form-group">
            <label for="drg_jefe">Jefe:</label>
            <input type="text" name="jefe" id="drg_jefe" required>
        </div>

        <div class="drg-form-group">
            <label for="drg_total_horas">Total Horas:</label>
            <select name="total_horas" id="drg_total_horas" required>
                <?php for ($i = 4; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div id="drg-tasks-container">
            <h3>Tareas</h3>
            <div class="drg-task-row">
                <div class="drg-form-group">
                    <label>Nombre Tarea:</label>
                    <input type="text" name="tareas[0][nombre]" required>
                </div>
                <div class="drg-form-group">
                    <label>Tipo:</label>
                    <select name="tareas[0][tipo]" required>
                        <option value="DESARROLLO">DESARROLLO</option>
                        <option value="REUNION">REUNION</option>
                        <option value="PAUSA">PAUSA</option>
                    </select>
                </div>
                <div class="drg-form-group">
                    <label>% Inicio:</label>
                    <input type="number" name="tareas[0][porcentaje_inicio]" min="0" max="100" required>
                </div>
                <div class="drg-form-group">
                    <label>% Fin:</label>
                    <input type="number" name="tareas[0][porcentaje_fin]" min="0" max="100" required>
                </div>
                <div class="drg-form-group">
                    <label>Horas:</label>
                    <input type="number" name="tareas[0][horas]" min="0" step="0.5" required>
                </div>
            </div>
        </div>

        <div class="drg-form-group">
            <label>
                <input type="checkbox" id="drg_modo_automatico" name="modo_automatico"> Modo automático (Distribuir horas)
            </label>
        </div>

        <button type="button" id="drg-add-task">Agregar tarea</button>

        <div class="drg-form-group">
            <label>
                <input type="checkbox" name="pausa_activa" id="drg_pausa_activa"> Pausa activa
            </label>
        </div>

        <div class="drg-form-group" id="drg-tiempo-pausa-container" style="display: none;">
            <label for="drg_tiempo_pausa">Duración:</label>
            <input type="text" name="tiempo_pausa" id="drg_tiempo_pausa">
        </div>

        <div class="drg-form-submit">
            <button type="submit">Guardar reporte</button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Format the report as a text string.
 *
 * @param object|array $report The report data from the database.
 * @return string Formatted report.
 */
function drg_format_report( $report ) {
    if ( is_array( $report ) ) {
        $report = (object) $report;
    }

    $output = "Final [" . $report->area . " - " . $report->fecha . "]\n\n";

    $tareas = json_decode( $report->tareas, true );
    if ( is_array( $tareas ) ) {
        foreach ( $tareas as $tarea ) {
            $output .= "✅[" . $tarea['tipo'] . "] " . $tarea['nombre'] . " [" . $tarea['porcentaje_inicio'] . "% - " . $tarea['porcentaje_fin'] . "%] " . $tarea['horas'] . " hrs\n";
        }
    }

    if ( $report->pausa_activa ) {
        $output .= "\n⏲️[PAUSA ACTIVA] descripcion [" . $report->tiempo_pausa . "]\n";
    }

    $output .= "\n⏰Total horas: " . $report->total_horas . " hrs\n";
    $output .= "Jefe de área: " . $report->jefe . "\n";

    return $output;
}
