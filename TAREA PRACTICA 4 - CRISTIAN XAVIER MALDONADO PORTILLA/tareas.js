// Función para confirmar eliminación de tarea
function confirmarEliminar(descripcionTarea) {
    const mensaje = `¿Estás seguro de que deseas eliminar la tarea "${descripcionTarea}"?`;
    return confirm(mensaje);
}

// Función para marcar/desmarcar tarea como completada
function toggleTarea(id, elemento) {
    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'toggle_tarea.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tarea_id';
        input.value = id;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    } catch (error) {
        console.error('Error al actualizar la tarea:', error);
        alert('Hubo un error al actualizar la tarea. Por favor, intenta nuevamente.');
    }
}

// Función para validar el formulario de nueva tarea
function validarNuevaTarea(form) {
    const descripcion = form.descripcion.value.trim();
    
    if (descripcion === '') {
        alert('Por favor, ingresa una descripción para la tarea.');
        form.descripcion.focus();
        return false;
    }
    
    if (descripcion.length > 255) {
        alert('La descripción no puede exceder los 255 caracteres.');
        form.descripcion.focus();
        return false;
    }
    
    return true;
}

// Evento para cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar validación a todos los formularios de tareas
    const formularios = document.querySelectorAll('form');
    formularios.forEach(form => {
        if (form.classList.contains('task-form')) {
            form.onsubmit = function() {
                return validarNuevaTarea(this);
            };
        }
    });
});
  