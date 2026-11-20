<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos</title>
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
            <a href="grupos.php" class="activo"><i class="bi bi-collection"></i>Grupos</a>
            <a href="nivel.php"><i class="bi bi-bar-chart-steps"></i>Nivel</a>
            <a href="horarios.php"><i class="bi bi-clock"></i>Horarios</a>
            <a href="periodos.php"><i class="bi bi-calendar-event"></i>Periodos</a>
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
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" id="buscarGrupo" placeholder="Buscar grupo"></div>
            <select class="select" id="filtroPeriodo" aria-label="Periodo"></select>
            <select class="select" id="filtroTipo" aria-label="Tipo">
                <option value="">Todos los tipos</option>
                <option value="interno">Interno</option>
                <option value="externo">Externo</option>
            </select>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Asignar docente</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Grupos</h2>
                <span class="contador" id="contadorGrupos"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Docente</th>
                            <th>Nivel</th>
                            <th>Tipo</th>
                            <th>Horario</th>
                            <th class="centro">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaGrupos"></tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR GRUPO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2 id="tituloVentana"><i class="bi bi-collection"></i> Asignar docente</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo">
                <label for="tipo">Tipo</label>
                <select class="select" id="tipo">
                    <option value="">Selecciona el tipo</option>
                    <option value="interno">Interno</option>
                    <option value="externo">Externo</option>
                </select>
            </div>
            <div class="campo">
                <label for="nivel">Nivel</label>
                <select class="select" id="nivel"></select>
            </div>
            <div class="campo">
                <label for="horario">Horario</label>
                <select class="select" id="horario"></select>
            </div>
            <div class="campo">
                <label for="docente">Docente</label>
                <select class="select" id="docente"></select>
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde" id="guardarGrupo"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    const ventana = document.getElementById('ventanaAgregar');
    const filtroPeriodo = document.getElementById('filtroPeriodo');
    const filtroTipo = document.getElementById('filtroTipo');
    const selTipo = document.getElementById('tipo');
    const selNivel = document.getElementById('nivel');
    const selHorario = document.getElementById('horario');
    const selDocente = document.getElementById('docente');

    let grupos = [];
    let docentes = [];
    let grupoEditando = null;

    const mayuscula = (t) => t.charAt(0).toUpperCase() + t.slice(1);
    const hora = (t) => t.slice(0, 5);
    // Convierte 13:30 (o 13:30:00) en 1:30 PM
    function a12(texto) {
        const [h, m] = texto.slice(0, 5).split(':').map(Number);
        const sufijo = h >= 12 ? 'PM' : 'AM';
        const h12 = h % 12 === 0 ? 12 : h % 12;
        return `${h12}:${String(m).padStart(2, '0')} ${sufijo}`;
    }
    const cabeceras = { 'Accept': 'application/json' };

    // Llena un select con opciones (sin usar innerHTML)
    function llenarSelect(select, textoInicial, items, etiqueta) {
        select.innerHTML = '';
        const inicial = document.createElement('option');
        inicial.value = '';
        inicial.textContent = textoInicial;
        select.appendChild(inicial);

        items.forEach(item => {
            const opcion = document.createElement('option');
            opcion.value = item.id;
            opcion.textContent = etiqueta(item);
            select.appendChild(opcion);
        });
    }

    // Horarios del periodo que está elegido en el filtro
    function delPeriodo() {
        return grupos.filter(g => g.periodo_id === Number(filtroPeriodo.value));
    }

    // Cascada del modal: tipo -> nivel -> horario
    function actualizarNiveles() {
        const unicos = new Map();
        delPeriodo()
            .filter(g => !selTipo.value || g.tipo === selTipo.value)
            .forEach(g => unicos.set(g.nivel_id, { id: g.nivel_id, clave: g.clave, nivel: g.nivel }));

        llenarSelect(selNivel, 'Selecciona un nivel', [...unicos.values()], n => `${n.clave} · ${n.nivel}`);
    }

    function actualizarHorarios() {
        const items = delPeriodo().filter(g => String(g.nivel_id) === selNivel.value);
        llenarSelect(selHorario, 'Selecciona un horario', items,
        h => `${a12(h.hora_inicio)} - ${a12(h.hora_fin)} (${h.docente || 'Sin asignar'})`);
    }

    selTipo.addEventListener('change', () => { actualizarNiveles(); actualizarHorarios(); });
    selNivel.addEventListener('change', actualizarHorarios);
    selHorario.addEventListener('change', () => {
        const h = grupos.find(g => String(g.id) === selHorario.value);
        selDocente.value = h && h.docente_id ? h.docente_id : '';
    });

    // Abre el modal vacío o con los datos del grupo
    function abrirVentana(g) {
        grupoEditando = g;
        document.querySelector('#ventanaAgregar form').reset();
        llenarSelect(selDocente, 'Sin docente', docentes, d => d.name);

        selTipo.value = g ? g.tipo : '';
        actualizarNiveles();

        if (g) {
            selNivel.value = g.nivel_id;
            actualizarHorarios();
            selHorario.value = g.id;
            selDocente.value = g.docente_id || '';
        } else {
            actualizarHorarios();
        }

        ventana.showModal();
    }

    document.getElementById('abrirAgregar').addEventListener('click', () => abrirVentana(null));
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());

    // Pinta la tabla según periodo, tipo y buscador
    function pintarTabla() {
        const texto = document.getElementById('buscarGrupo').value.toLowerCase();
        const cuerpo = document.getElementById('tablaGrupos');
        cuerpo.innerHTML = '';

        const visibles = delPeriodo().filter(g => {
            if (filtroTipo.value && g.tipo !== filtroTipo.value) return false;
            const linea = `${g.docente || 'Sin asignar'} ${g.clave} ${g.nivel} ${g.tipo} ${a12(g.hora_inicio)} ${a12(g.hora_fin)}`.toLowerCase();
            return linea.includes(texto);
        });

        visibles.forEach(g => {
            const fila = document.createElement('tr');

            const celdaDocente = document.createElement('td');
            celdaDocente.textContent = g.docente || 'Sin asignar';
            if (!g.docente) celdaDocente.style.opacity = '0.6';

            const celdaNivel = document.createElement('td');
            celdaNivel.textContent = `${g.clave} · ${g.nivel}`;

            const celdaTipo = document.createElement('td');
            celdaTipo.textContent = mayuscula(g.tipo);

            const celdaHorario = document.createElement('td');
            celdaHorario.textContent = `${a12(g.hora_inicio)} - ${a12(g.hora_fin)}`;

            const acciones = document.createElement('td');
            acciones.className = 'centro';

            const botonEditar = document.createElement('button');
            botonEditar.type = 'button';
            botonEditar.className = 'btn-icono';
            botonEditar.title = 'Asignar o cambiar docente';
            botonEditar.innerHTML = '<i class="bi bi-pencil"></i>';
            botonEditar.addEventListener('click', () => abrirVentana(g));
            acciones.appendChild(botonEditar);

            fila.append(celdaDocente, celdaNivel, celdaTipo, celdaHorario, acciones);
            cuerpo.appendChild(fila);
        });

        document.getElementById('contadorGrupos').textContent = visibles.length + ' grupos';
    }

    async function cargarGrupos() {
        const respuesta = await fetch('/grupos', { headers: cabeceras });
        grupos = await respuesta.json();
        pintarTabla();
    }

    // Carga inicial: periodos (filtro), docentes (modal) y grupos (tabla)
    async function iniciar() {
        const [resPeriodos, resDocentes] = await Promise.all([
            fetch('/periodos', { headers: cabeceras }),
            fetch('/docentes', { headers: cabeceras })
        ]);
        const periodos = await resPeriodos.json();
        docentes = await resDocentes.json();

        llenarSelect(filtroPeriodo, 'Periodo', periodos, p => p.nombre);
        if (periodos.length) filtroPeriodo.value = periodos[0].id; // el más reciente

        await cargarGrupos();
    }

    iniciar();

    filtroPeriodo.addEventListener('change', pintarTabla);
    filtroTipo.addEventListener('change', pintarTabla);
    document.getElementById('buscarGrupo').addEventListener('input', pintarTabla);

    // Guardar: asigna el docente al horario elegido
    document.getElementById('guardarGrupo').addEventListener('click', async () => {
        if (!selHorario.value) {
            alert('Selecciona un horario');
            return;
        }

        const respuesta = await fetch(`/grupos/${selHorario.value}`, {
            method: 'PUT',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ docente_id: selDocente.value || null })
        });

        if (respuesta.ok) {
            ventana.close();
            grupoEditando = null;
            cargarGrupos();
        } else {
            const error = await respuesta.json();
            const lista = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
            alert(lista || 'No se pudo guardar');
        }
    });
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>