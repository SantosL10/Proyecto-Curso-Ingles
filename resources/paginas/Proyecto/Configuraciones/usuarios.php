<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../login/css/estilos.css">
    <link rel="icon" href="../../img/logo_its.png">
</head>
<body>
<div class="panel">
    <aside class="menu">
        <div class="menu-nombre">CLE APP</div>
        <a href="../inicio/inicio.php"><i class="bi bi-house-heart"></i>Inicio</a>
        <a href="../Inscripciones/inscripciones.php"><i class="bi bi-journal-text"></i>Inscripciones</a>
        <a href="grupos.php" class="activo"><i class="bi bi-gear"></i>Configuración</a>
        <div class="submenu">
            <a href="grupos.php"><i class="bi bi-collection"></i>Grupos</a>
            <a href="nivel.php"><i class="bi bi-bar-chart-steps"></i>Nivel</a>
            <a href="horarios.php"><i class="bi bi-clock"></i>Horarios</a>
            <a href="periodos.php"><i class="bi bi-calendar-event"></i>Periodos</a>
            <a href="usuarios.php" class="activo"><i class="bi bi-people"></i>Usuarios</a>
        </div>
        <div class="menu-salir"><a href="#" id="cerrarSesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a></div>
    </aside>

    <main class="contenido">
        <header class="encabezado">
            <img src="../../img/logo_its.png" alt="Logo ITSCC">
            <h1>Instituto Tecnológico Superior de Ciudad Constitución</h1>
            <div class="usuario"><span id="nombreUsuario"></span><div class="avatar"><i class="bi bi-person"></i></div></div>
        </header>

        <div class="herramientas">
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" id="buscarUsuario" placeholder="Buscar usuario"></div>
            <button class="btn btn-borde" id="ordenarNombre">Nombre <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Usuarios</h2>
                <span class="contador"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Acceso</th>
                            <th class="centro">Acciones</th></tr>
                    </thead>
                    <tbody id="tablaUsuarios"></tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR USUARIO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2 id="tituloVentana"><i class="bi bi-person-plus"></i> Agregar usuario</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo completo">
                <label for="nombre">Nombre completo</label>
                <input class="input" type="text" id="nombre" placeholder="Nombre y apellidos">
            </div>
            <div class="campo">
                <label for="user">Correo</label>
                <input class="input" type="email" id="user" placeholder="correo@ejemplo.com">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input class="input" type="password" id="password" placeholder="Contraseña">
            </div>
            <div class="campo completo">
                <label for="acceso">Acceso</label>
                <select class="select" id="acceso">
                    <option value="">Selecciona el acceso</option>
                    <option value="admin">Admin</option>
                    <option value="consulta">Consulta</option>
                    <option value="docente">Docente</option>
                    <option value="alumno">Alumno</option>
                </select>
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde" id="guardarUsuario"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    // Abrir y cerrar la ventana de Agregar
    const ventana = document.getElementById('ventanaAgregar');
    let usuarioEditando = null;

    function abrirVentana(u) {
        usuarioEditando = u;
        document.querySelector('#ventanaAgregar form').reset();
        document.getElementById('tituloVentana').innerHTML = u
            ? '<i class="bi bi-pencil"></i> Editar usuario'
            : '<i class="bi bi-person-plus"></i> Agregar usuario';
        document.getElementById('password').placeholder = u ? 'Contraseña' : 'Contraseña';

        if (u) {
            document.getElementById('nombre').value = u.name;
            document.getElementById('user').value = u.email;
            document.getElementById('acceso').value = u.rol;
        }

        ventana.showModal();
    }

    document.getElementById('abrirAgregar').addEventListener('click', () => abrirVentana(null));
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());

    // Activar o desactivar usuario
    async function cambiarEstado(u) {
        const accion = u.activo ? 'desactivar' : 'activar';
        if (!confirm(`¿Quieres ${accion} a ${u.name}?`)) return;

        const respuesta = await fetch(`/usuarios/${u.id}/activo`, {
            method: 'PATCH',
            headers: { 'Accept': 'application/json' }
        });

        if (respuesta.ok) {
            cargarUsuarios();
        } else {
            const error = await respuesta.json();
            alert(error.message || 'No se pudo cambiar el estado');
        }
    }

    // Cargar la tabla
    async function cargarUsuarios() {
        const respuesta = await fetch('/usuarios', { headers: { 'Accept': 'application/json' } });
        const usuarios = await respuesta.json();
        const cuerpo = document.getElementById('tablaUsuarios');
        cuerpo.innerHTML = '';

        usuarios.forEach(u => {
            const fila = document.createElement('tr');
            [u.name, u.email, u.rol].forEach(texto => {
                const celda = document.createElement('td');
                celda.textContent = texto;
                fila.appendChild(celda);
            });

            if (!u.activo) fila.style.opacity = '0.5';

            const acciones = document.createElement('td');
            acciones.className = 'centro';

            const grupo = document.createElement('div');
            grupo.style.display = 'flex';
            grupo.style.justifyContent = 'center';
            grupo.style.gap = '8px';

            const botonEditar = document.createElement('button');
            botonEditar.type = 'button';
            botonEditar.className = 'btn-icono';
            botonEditar.title = 'Editar';
            botonEditar.innerHTML = '<i class="bi bi-pencil"></i>';
            botonEditar.addEventListener('click', () => abrirVentana(u));

            const botonEstado = document.createElement('button');
            botonEstado.type = 'button';
            botonEstado.className = 'btn-icono';
            botonEstado.title = u.activo ? 'Desactivar' : 'Activar';
            botonEstado.innerHTML = u.activo
                ? '<i class="bi bi-person-x"></i>'
                : '<i class="bi bi-person-check"></i>';
            botonEstado.addEventListener('click', () => cambiarEstado(u));

            grupo.appendChild(botonEditar);
            grupo.appendChild(botonEstado);
            acciones.appendChild(grupo);
            fila.appendChild(acciones);


            cuerpo.appendChild(fila);
        });

        document.querySelector('.contador').textContent = usuarios.length;
    }

    cargarUsuarios();

    document.getElementById('guardarUsuario').addEventListener('click', async () => {
        const datos = {
            name: document.getElementById('nombre').value,
            email: document.getElementById('user').value,
            password: document.getElementById('password').value,
            rol: document.getElementById('acceso').value
        };

        const url = usuarioEditando ? `/usuarios/${usuarioEditando.id}` : '/usuarios';
        const metodo = usuarioEditando ? 'PUT' : 'POST';

        const respuesta = await fetch(url, {
            method: metodo,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        if (respuesta.ok) {
            ventana.close();
            usuarioEditando = null;
            cargarUsuarios();
        } else {
            const error = await respuesta.json();
            alert(error.message || 'No se pudo guardar');
        }
    });

    // Buscador
    document.getElementById('buscarUsuario').addEventListener('input', (e) => {
        const texto = e.target.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaUsuarios tr');
        let visibles = 0;

        filas.forEach(fila => {
            const coincide = fila.textContent.toLowerCase().includes(texto);
            fila.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        document.querySelector('.contador').textContent = visibles;
    });

    // Ordenar por nombre
    let ordenAscendente = true;

    document.getElementById('ordenarNombre').addEventListener('click', () => {
        const cuerpo = document.getElementById('tablaUsuarios');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));

        filas.sort((a, b) => {
            const nombreA = a.cells[0].textContent;
            const nombreB = b.cells[0].textContent;
            return ordenAscendente
                ? nombreA.localeCompare(nombreB, 'es')
                : nombreB.localeCompare(nombreA, 'es');
        });

        filas.forEach(fila => cuerpo.appendChild(fila));
        ordenAscendente = !ordenAscendente;
    });

</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>