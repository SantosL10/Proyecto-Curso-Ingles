// Cerrar sesion (en todas las paginas)
const botonSalir = document.getElementById('cerrarSesion');

if (botonSalir) {
    botonSalir.addEventListener('click', async (e) => {
        e.preventDefault();
        await fetch('/logout', {
            method: 'POST',
            headers: { 'Accept': 'application/json' }
        });
        sessionStorage.removeItem('nombreUsuario');
        window.location.href = '/front/';
    });
}

// Mostrar el nombre del usuario (solo si existe el span en la página)
const spanNombre = document.getElementById('nombreUsuario');

if (spanNombre) {
    spanNombre.textContent = sessionStorage.getItem('nombreUsuario') || '';
    fetch('/sesion', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(usuario => {
            if (usuario) {
                sessionStorage.setItem('nombreUsuario', usuario.name);
                spanNombre.textContent = usuario.name;
            }
        });
}