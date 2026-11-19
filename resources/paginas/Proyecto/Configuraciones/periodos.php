<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periodos</title>
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
            <a href="periodos.php" class="activo"><i class="bi bi-calendar-event"></i>Periodos</a>
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
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" id="buscarPeriodo" placeholder="Buscar periodo"></div>
            <button type="button" class="btn btn-borde" id="ordenarNombre">Nombre <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Periodos</h2>
                <span class="contador" id="contadorPeriodos"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Periodo</th>
                            <th>Inscripción internos</th>
                            <th>Inscripción externos</th>
                            <th class="centro">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPeriodos"></tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR / EDITAR PERIODO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2 id="tituloVentana"><i class="bi bi-calendar-event"></i> Agregar periodo</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo completo">
                <label for="nombre">Nombre</label>
                <input class="input" type="text" id="nombre" placeholder="Ej. Enero-Junio 2027">
            </div>
            <div class="campo">
                <label for="fechaInicio">Inicio del periodo</label>
                <input class="input" type="date" id="fechaInicio">
            </div>
            <div class="campo">
                <label for="fechaFin">Fin del periodo</label>
                <input class="input" type="date" id="fechaFin">
            </div>
            <div class="campo">
                <label for="internosInicio">Inicio inscripción internos</label>
                <input class="input" type="date" id="internosInicio">
            </div>
            <div class="campo">
                <label for="internosFin">Fin inscripción internos</label>
                <input class="input" type="date" id="internosFin">
            </div>
            <div class="campo">
                <label for="externosInicio">Inicio inscripción externos</label>
                <input class="input" type="date" id="externosInicio">
            </div>
            <div class="campo">
                <label for="externosFin">Fin inscripción externos</label>
                <input class="input" type="date" id="externosFin">
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde" id="guardarPeriodo"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    const ventana = document.getElementById('ventanaAgregar');
    let periodoEditando = null;

    // Convierte 2027-02-01 en 01/02/2027
    function formatoFecha(texto) {
        const [anio, mes, dia] = texto.split('-');
        return `${dia}/${mes}/${anio}`;
    }

    // Abre el modal vacío (Agregar) o con los datos del periodo (Editar)
    function abrirVentana(p) {
        periodoEditando = p;
        document.querySelector('#ventanaAgregar form').reset();
        document.getElementById('tituloVentana').innerHTML = p
            ? '<i class="bi bi-pencil"></i> Editar periodo'
            : '<i class="bi bi-calendar-event"></i> Agregar periodo';

        if (p) {
            document.getElementById('nombre').value = p.nombre;
            document.getElementById('fechaInicio').value = p.fecha_inicio;
            document.getElementById('fechaFin').value = p.fecha_fin;
            document.getElementById('internosInicio').value = p.insc_internos_inicio;
            document.getElementById('internosFin').value = p.insc_internos_fin;
            document.getElementById('externosInicio').value = p.insc_externos_inicio;
            document.getElementById('externosFin').value = p.insc_externos_fin;
        }

        ventana.showModal();
    }

    document.getElementById('abrirAgregar').addEventListener('click', () => abrirVentana(null));
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());

    // Cargar la tabla
    async function cargarPeriodos() {
        const respuesta = await fetch('/periodos', { headers: { 'Accept': 'application/json' } });
        const periodos = await respuesta.json();
        const cuerpo = document.getElementById('tablaPeriodos');
        cuerpo.innerHTML = '';

        periodos.forEach(p => {
            const fila = document.createElement('tr');

            [
                p.nombre,
                `${formatoFecha(p.fecha_inicio)} - ${formatoFecha(p.fecha_fin)}`,
                `${formatoFecha(p.insc_internos_inicio)} - ${formatoFecha(p.insc_internos_fin)}`,
                `${formatoFecha(p.insc_externos_inicio)} - ${formatoFecha(p.insc_externos_fin)}`
            ].forEach(texto => {
                const celda = document.createElement('td');
                celda.textContent = texto;
                fila.appendChild(celda);
            });

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
            botonEditar.addEventListener('click', () => abrirVentana(p));

            grupo.appendChild(botonEditar);
            acciones.appendChild(grupo);
            fila.appendChild(acciones);

            cuerpo.appendChild(fila);
        });

        document.getElementById('contadorPeriodos').textContent = periodos.length + ' periodos';
    }

    cargarPeriodos();

    // Guardar (crea o edita según el caso)
    document.getElementById('guardarPeriodo').addEventListener('click', async () => {
        const datos = {
            nombre: document.getElementById('nombre').value,
            fecha_inicio: document.getElementById('fechaInicio').value,
            fecha_fin: document.getElementById('fechaFin').value,
            insc_internos_inicio: document.getElementById('internosInicio').value,
            insc_internos_fin: document.getElementById('internosFin').value,
            insc_externos_inicio: document.getElementById('externosInicio').value,
            insc_externos_fin: document.getElementById('externosFin').value
        };

        const url = periodoEditando ? `/periodos/${periodoEditando.id}` : '/periodos';
        const metodo = periodoEditando ? 'PUT' : 'POST';

        const respuesta = await fetch(url, {
            method: metodo,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        if (respuesta.ok) {
            ventana.close();
            periodoEditando = null;
            cargarPeriodos();
        } else {
            const error = await respuesta.json();
            const lista = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
            alert(lista || 'No se pudo guardar');
        }
    });

    // Buscador
    document.getElementById('buscarPeriodo').addEventListener('input', (e) => {
        const texto = e.target.value.toLowerCase();
        let visibles = 0;

        document.querySelectorAll('#tablaPeriodos tr').forEach(fila => {
            const coincide = fila.textContent.toLowerCase().includes(texto);
            fila.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        document.getElementById('contadorPeriodos').textContent = visibles + ' periodos';
    });

    // Ordenar por nombre
    let ordenAscendente = true;

    document.getElementById('ordenarNombre').addEventListener('click', () => {
        const cuerpo = document.getElementById('tablaPeriodos');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));

        filas.sort((a, b) => {
            const resultado = a.cells[0].textContent.localeCompare(b.cells[0].textContent, 'es', { numeric: true });
            return ordenAscendente ? resultado : -resultado;
        });

        filas.forEach(fila => cuerpo.appendChild(fila));
        ordenAscendente = !ordenAscendente;
    });
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>