<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios</title>
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
            <a href="horarios.php" class="activo"><i class="bi bi-clock"></i>Horarios</a>
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
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" id="buscarHorario" placeholder="Buscar horario"></div>
            <button type="button" class="btn btn-borde" id="ordenarClave">Clave <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-borde" id="ordenarHorario">Horario <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-borde" id="ordenarTipo">Tipo <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Horarios</h2>
                <span class="contador" id="contadorHorarios"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Clave</th>
                            <th>Horario</th>
                            <th>Cupo</th>
                            <th>Registrados</th>
                            <th>Disponible</th>
                            <th>Tipo</th>
                            <th class="centro">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaHorarios"></tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR HORARIO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2 id="tituloVentana"><i class="bi bi-clock"></i> Agregar horario</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo">
                <label for="periodo">Periodo</label>
                <select class="select" id="periodo"></select>
            </div>
            <div class="campo">
                <label for="nivel">Nivel</label>
                <select class="select" id="nivel"></select>
            </div>
            <div class="campo">
                <label for="horaInicio">Hora de inicio</label>
                <input class="input" type="time" id="horaInicio">
            </div>
            <div class="campo">
                <label for="horaFin">Hora de fin</label>
                <input class="input" type="time" id="horaFin">
            </div>
            <div class="campo completo">
                <label for="cupo">Cupo</label>
                <input class="input" type="number" id="cupo" placeholder="Ej. 30" min="1">
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde" id="guardarHorario"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    // Abrir y cerrar la ventana de Agregar
    const ventana = document.getElementById('ventanaAgregar');
    const selPeriodo = document.getElementById('periodo');
    const selNivel = document.getElementById('nivel');
    let horarioEditando = null;
    let periodos = [];

    const mayuscula = (t) => t.charAt(0).toUpperCase() + t.slice(1);
    const hora = (t) => t.slice(0, 5);

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

    // Trae periodos y niveles para los selects del modal
    async function cargarSelects() {
        const cabeceras = { 'Accept': 'application/json' };
        const [resPeriodos, resNiveles] = await Promise.all([
            fetch('/periodos', { headers: cabeceras }),
            fetch('/niveles', { headers: cabeceras })
        ]);
        periodos = await resPeriodos.json();
        const niveles = await resNiveles.json();

        llenarSelect(selPeriodo, 'Selecciona el periodo', periodos, p => p.nombre);
        llenarSelect(selNivel, 'Selecciona el nivel', niveles, n => `${n.clave} · ${n.nombre} (${mayuscula(n.tipo)})`);
    }

    // Abre el modal vacío (Agregar) o con los datos del horario (Editar)
    function abrirVentana(h) {
        horarioEditando = h;
        document.querySelector('#ventanaAgregar form').reset();
        document.getElementById('tituloVentana').innerHTML = h
            ? '<i class="bi bi-pencil"></i> Editar horario'
            : '<i class="bi bi-clock"></i> Agregar horario';

        // Al editar no se cambia el periodo ni el nivel, para no mover alumnos ya registrados
        selPeriodo.disabled = !!h;
        selNivel.disabled = !!h;

        if (h) {
            selPeriodo.value = h.periodo_id;
            selNivel.value = h.nivel_id;
            document.getElementById('horaInicio').value = hora(h.hora_inicio);
            document.getElementById('horaFin').value = hora(h.hora_fin);
            document.getElementById('cupo').value = h.cupo;
        } else if (periodos.length) {
            selPeriodo.value = periodos[0].id; // el más reciente
        }

        ventana.showModal();
    }

    document.getElementById('abrirAgregar').addEventListener('click', () => abrirVentana(null));
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());

    // Cargar la tabla
    async function cargarHorarios() {
        const respuesta = await fetch('/horarios', { headers: { 'Accept': 'application/json' } });
        const horarios = await respuesta.json();
        const cuerpo = document.getElementById('tablaHorarios');
        cuerpo.innerHTML = '';

        horarios.forEach(h => {
            const fila = document.createElement('tr');
            const registrados = Number(h.registrados);

            [
                h.clave,
                `${hora(h.hora_inicio)} - ${hora(h.hora_fin)}`,
                h.cupo,
                registrados,
                h.cupo - registrados,
                mayuscula(h.tipo)
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
            botonEditar.addEventListener('click', () => abrirVentana(h));

            grupo.appendChild(botonEditar);
            acciones.appendChild(grupo);
            fila.appendChild(acciones);

            cuerpo.appendChild(fila);
        });

        document.getElementById('contadorHorarios').textContent = horarios.length + ' horarios';
    }

    cargarSelects();
    cargarHorarios();

    // Guardar (crea o edita según el caso)
    document.getElementById('guardarHorario').addEventListener('click', async () => {
        const datos = {
            periodo_id: horarioEditando ? horarioEditando.periodo_id : selPeriodo.value,
            nivel_id: horarioEditando ? horarioEditando.nivel_id : selNivel.value,
            hora_inicio: document.getElementById('horaInicio').value,
            hora_fin: document.getElementById('horaFin').value,
            cupo: document.getElementById('cupo').value
        };

        const url = horarioEditando ? `/horarios/${horarioEditando.id}` : '/horarios';
        const metodo = horarioEditando ? 'PUT' : 'POST';

        const respuesta = await fetch(url, {
            method: metodo,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        if (respuesta.ok) {
            ventana.close();
            horarioEditando = null;
            cargarHorarios();
        } else {
            const error = await respuesta.json();
            const lista = error.errors ? Object.values(error.errors).flat().join('\n') : error.message;
            alert(lista || 'No se pudo guardar');
        }
    });

    // Buscador
    document.getElementById('buscarHorario').addEventListener('input', (e) => {
        const texto = e.target.value.toLowerCase();
        let visibles = 0;

        document.querySelectorAll('#tablaHorarios tr').forEach(fila => {
            const coincide = fila.textContent.toLowerCase().includes(texto);
            fila.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        document.getElementById('contadorHorarios').textContent = visibles + ' horarios';
    });

    // Ordenar por columna (0 = Clave, 1 = Horario, 5 = Tipo)
    const direcciones = {};

    function ordenarPor(columna) {
        const cuerpo = document.getElementById('tablaHorarios');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const ascendente = direcciones[columna] !== false;

        filas.sort((a, b) => {
            const resultado = a.cells[columna].textContent
                .localeCompare(b.cells[columna].textContent, 'es', { numeric: true });
            return ascendente ? resultado : -resultado;
        });

        filas.forEach(fila => cuerpo.appendChild(fila));
        direcciones[columna] = !ascendente;
    }

    document.getElementById('ordenarClave').addEventListener('click', () => ordenarPor(0));
    document.getElementById('ordenarHorario').addEventListener('click', () => ordenarPor(1));
    document.getElementById('ordenarTipo').addEventListener('click', () => ordenarPor(5));
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>