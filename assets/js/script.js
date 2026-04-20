document.addEventListener('DOMContentLoaded', function() {
    const addTaskButton = document.getElementById('drg-add-task');
    const tasksContainer = document.getElementById('drg-tasks-container');
    const pausaActivaCheckbox = document.getElementById('drg_pausa_activa');
    const tiempoPausaContainer = document.getElementById('drg-tiempo-pausa-container');
    const modoAutomaticoCheckbox = document.getElementById('drg_modo_automatico');
    const totalHorasSelect = document.getElementById('drg_total_horas');
    const reportForm = document.getElementById('drg-report-form');

    let taskIndex = 1;

    function recalculateHours() {
        const rows = tasksContainer.querySelectorAll('.drg-task-row');
        const numTasks = rows.length;
        const totalHoras = parseFloat(totalHorasSelect.value);

        if (modoAutomaticoCheckbox.checked && numTasks > 0) {
            const horasPerTask = (totalHoras / numTasks).toFixed(2);
            rows.forEach(row => {
                const horasInput = row.querySelector('input[name*="[horas]"]');
                horasInput.value = horasPerTask;
                horasInput.readOnly = true;
            });
        } else {
            rows.forEach(row => {
                const horasInput = row.querySelector('input[name*="[horas]"]');
                horasInput.readOnly = false;
            });
        }
        validateTotalHours();
    }

    function validateTotalHours() {
        const rows = tasksContainer.querySelectorAll('.drg-task-row');
        const totalHorasPermitidas = parseFloat(totalHorasSelect.value);
        let currentTotal = 0;

        rows.forEach(row => {
            currentTotal += parseFloat(row.querySelector('input[name*="[horas]"]').value || 0);
        });

        // Remove existing error message
        const existingError = document.getElementById('drg-hours-error');
        if (existingError) existingError.remove();

        if (currentTotal > totalHorasPermitidas + 0.01) {
            const errorDiv = document.createElement('div');
            errorDiv.id = 'drg-hours-error';
            errorDiv.style.color = 'red';
            errorDiv.textContent = `Error: El total de horas de las tareas (${currentTotal.toFixed(2)}) excede el total de horas (${totalHorasPermitidas}).`;
            tasksContainer.after(errorDiv);
            return false;
        }
        return true;
    }

    if (addTaskButton && tasksContainer) {
        addTaskButton.addEventListener('click', function() {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'drg-task-row';
            rowDiv.innerHTML = `
                <div class="drg-form-group">
                    <label>Nombre Tarea:</label>
                    <input type="text" name="tareas[${taskIndex}][nombre]" required>
                </div>
                <div class="drg-form-group">
                    <label>Tipo:</label>
                    <select name="tareas[${taskIndex}][tipo]" required>
                        <option value="DESARROLLO">DESARROLLO</option>
                        <option value="REUNION">REUNION</option>
                        <option value="PAUSA">PAUSA</option>
                    </select>
                </div>
                <div class="drg-form-group">
                    <label>% Inicio:</label>
                    <input type="number" name="tareas[${taskIndex}][porcentaje_inicio]" min="0" max="100" required>
                </div>
                <div class="drg-form-group">
                    <label>% Fin:</label>
                    <input type="number" name="tareas[${taskIndex}][porcentaje_fin]" min="0" max="100" required>
                </div>
                <div class="drg-form-group">
                    <label>Horas:</label>
                    <input type="number" name="tareas[${taskIndex}][horas]" min="0" step="0.1" required>
                </div>
                <button type="button" class="drg-remove-task">Eliminar</button>
            `;
            tasksContainer.appendChild(rowDiv);
            taskIndex++;

            rowDiv.querySelector('.drg-remove-task').addEventListener('click', function() {
                rowDiv.remove();
                recalculateHours();
            });

            rowDiv.querySelector('input[name*="[horas]"]').addEventListener('input', validateTotalHours);

            recalculateHours();
        });
    }

    // Event listeners for existing rows
    tasksContainer.querySelectorAll('.drg-task-row').forEach(row => {
        row.querySelector('input[name*="[horas]"]').addEventListener('input', validateTotalHours);
    });

    if (modoAutomaticoCheckbox) {
        modoAutomaticoCheckbox.addEventListener('change', recalculateHours);
    }

    if (totalHorasSelect) {
        totalHorasSelect.addEventListener('change', recalculateHours);
    }

    if (pausaActivaCheckbox && tiempoPausaContainer) {
        pausaActivaCheckbox.addEventListener('change', function() {
            tiempoPausaContainer.style.display = this.checked ? 'block' : 'none';
        });
    }

    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            if (!validateTotalHours()) {
                e.preventDefault();
                alert('Por favor corrige los errores antes de guardar.');
            }
        });
    }
});
