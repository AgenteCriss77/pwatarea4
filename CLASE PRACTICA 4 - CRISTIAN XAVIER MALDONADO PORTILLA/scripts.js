document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de registro
    const registroForm = document.getElementById('registroForm');
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            const contrasena = document.getElementById('contrasena').value;
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            
            if (contrasena.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
            }
            
            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre debe tener al menos 3 caracteres');
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Por favor, ingrese un email válido');
            }
        });
    }
});
  