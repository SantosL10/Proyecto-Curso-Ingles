<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nivel</title>
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
            <a href="nivel.php" class="activo"><i class="bi bi-bar-chart-steps"></i>Nivel</a>
            <a href="horarios.php"><i class="bi bi-clock"></i>Horarios</a>
            <a href="usuarios.php"><i class="bi bi-people"></i>Usuarios</a>
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
        <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" id="buscarNivel" placeholder="Buscar nivel"></div>
        <button type="button" class="btn btn-borde" id="ordenarClave">Clave <i class="bi bi-arrow-down-up"></i></button>
        <button type="button" class="btn btn-borde" id="ordenarNombre">Nombre <i class="bi bi-arrow-down-up"></i></button>
        <button type="button" class="btn btn-borde" id="ordenarTipo">Tipo <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Nivel</h2>
                <span class="contador" id="contadorNiveles"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th class="centro">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaNiveles"></tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR NIVEL -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2 id="tituloVentana"><i class="bi bi-bar-chart-steps"></i> Agregar nivel</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo">
                <label for="clave">Clave</label>
                <input class="input" type="text" id="clave" placeholder="Ej. INT-ELE">
            </div>
            <div class="campo">
                <label for="tipo">Tipo</label>
                <select class="select" id="tipo">
                    <option value="">Selecciona el tipo</option>
                    <option value="interno">Interno</option>
                    <option value="externo">Externo</option>
                </select>
            </div>
            <div class="campo completo">
                <label for="nombre">Nombre</label>
                <input class="input" type="text" id="nombre" placeholder="Ej. Elementary">
            </div>
            <div class="campo">
                <label for="orden">Orden</label>
                <input class="input" type="number" id="orden" min="1" placeholder="Ej. 2">
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde" id="guardarNivel"><i class="bi bi-check2"></i> Guardar</button>        </div>
    </form>
</dialog>

<script>
    const ventana = document.getElementById('ventanaAgregar');
    let nivelEditando = null;

    // Abre el modal vacío (Agregar) o con los datos del nivel (Editar)
    function abrirVentana(n) {
        nivelEditando = n;
        document.querySelector('#ventanaAgregar form').reset();
        document.getElementById('tituloVentana').innerHTML = n
            ? '<i class="bi bi-pencil"></i> Editar nivel'
            : '<i class="bi bi-bar-chart-steps"></i> Agregar nivel';

        if (n) {
            document.getElementById('clave').value = n.clave;
            document.getElementById('tipo').value = n.tipo;
            document.getElementById('nombre').value = n.nombre;
            document.getElementById('orden').value = n.orden;
        }

        ventana.showModal();
    }

    document.getElementById('abrirAgregar').addEventListener('click', () => abrirVentana(null));
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());

    // Llena la tabla con los niveles que vienen de Laravel
    async function cargarNiveles() {
        const respuesta = await fetch('/niveles', { headers: { 'Accept': 'application/json' } });
        const niveles = await respuesta.json();
        const tbody = document.getElementById('tablaNiveles');
        tbody.innerHTML = '';

        niveles.forEach(n => {
            const fila = document.createElement('tr');

            const celdaClave = document.createElement('td');
            celdaClave.textContent = n.clave;

            const celdaNombre = document.createElement('td');
            celdaNombre.textContent = n.nombre;

            const celdaTipo = document.createElement('td');
            celdaTipo.textContent = n.tipo.charAt(0).toUpperCase() + n.tipo.slice(1);

            const celdaAcciones = document.createElement('td');
            celdaAcciones.className = 'centro';

            const grupo = document.createElement('div');
            grupo.style.display = 'flex';
            grupo.style.justifyContent = 'center';
            grupo.style.gap = '8px';

            const botonEditar = document.createElement('button');
            botonEditar.type = 'button';
            botonEditar.className = 'btn-icono';
            botonEditar.title = 'Editar';
            botonEditar.innerHTML = '<i class="bi bi-pencil"></i>';
            botonEditar.addEventListener('click', () => abrirVentana(n));

            grupo.appendChild(botonEditar);
            celdaAcciones.appendChild(grupo);

            fila.append(celdaClave, celdaNombre, celdaTipo, celdaAcciones);
            tbody.appendChild(fila);
        });

        document.getElementById('contadorNiveles').textContent = niveles.length + ' niveles';
    }

    cargarNiveles();

    // Guardar (crea o edita según el caso)
    document.getElementById('guardarNivel').addEventListener('click', async () => {
        const datos = {
            clave: document.getElementById('clave').value,
            tipo: document.getElementById('tipo').value,
            nombre: document.getElementById('nombre').value,
            orden: document.getElementById('orden').value
        };

        const url = nivelEditando ? `/niveles/${nivelEditando.id}` : '/niveles';
        const metodo = nivelEditando ? 'PUT' : 'POST';

        const respuesta = await fetch(url, {
            method: metodo,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        if (respuesta.ok) {
            ventana.close();
            nivelEditando = null;
            cargarNiveles();
        } else {
            const error = await respuesta.json();
            const lista = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
            alert(lista || 'No se pudo guardar');
        }
    });
    // Buscador
    document.getElementById('buscarNivel').addEventListener('input', (e) => {
        const texto = e.target.value.toLowerCase();
        let visibles = 0;

        document.querySelectorAll('#tablaNiveles tr').forEach(fila => {
            const coincide = fila.textContent.toLowerCase().includes(texto);
            fila.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        document.getElementById('contadorNiveles').textContent = visibles + ' niveles';
    });

    // Ordenar por columna (0 = Clave, 1 = Nombre, 2 = Tipo)
    const direcciones = { 0: true, 1: true, 2: true };

    function ordenarPor(columna) {
        const cuerpo = document.getElementById('tablaNiveles');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const ascendente = direcciones[columna];

        filas.sort((a, b) => {
            const resultado = a.cells[columna].textContent
                .localeCompare(b.cells[columna].textContent, 'es', { numeric: true });
            return ascendente ? resultado : -resultado;
        });

        filas.forEach(fila => cuerpo.appendChild(fila));
        direcciones[columna] = !ascendente;
    }

    document.getElementById('ordenarClave').addEventListener('click', () => ordenarPor(0));
    document.getElementById('ordenarNombre').addEventListener('click', () => ordenarPor(1));
    document.getElementById('ordenarTipo').addEventListener('click', () => ordenarPor(2));

</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>