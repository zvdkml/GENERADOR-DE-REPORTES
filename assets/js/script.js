document.addEventListener('DOMContentLoaded', function() {
    const addTaskButton = document.getElementById('drg-add-task');
    const tasksContainer = document.getElementById('drg-tasks-container');
    const pausaActivaCheckbox = document.getElementById('drg_pausa_activa');
    const tiempoPausaContainer = document.getElementById('drg-tiempo-pausa-container');

    let taskIndex = 1;

    if (addTaskButton && tasksContainer) {
        addTaskButton.addEventListener('click', function() {
            const taskRow = document.createElement('div');
            taskRow.className = 'drg-task-row';
            taskRow.innerHTML = `
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
                    <input type="number" name="tareas[${taskIndex}][horas]" min="0" step="0.5" required>
                </div>
                <button type="button" class="drg-remove-task">Eliminar</button>
            `;
            tasksContainer.appendChild(taskRow);
            taskIndex++;

            taskRow.querySelector('.drg-remove-task').addEventListener('click', function() {
                taskRow.remove();
            });
        });
    }

    if (pausaActivaCheckbox && tiempoPausaContainer) {
        pausaActivaCheckbox.addEventListener('change', function() {
            if (this.checked) {
                tiempoPausaContainer.style.display = 'block';
            } else {
                tiempoPausaContainer.style.display = 'none';
            }
        });
    }
});
