<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones</title>
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
        <a href="inscripciones.php" class="activo"><i class="bi bi-journal-text"></i>Inscripciones</a>
        <a href="../Configuraciones/grupos.php"><i class="bi bi-gear"></i>Configuración</a>
        <div class="submenu">
            <a href="../Configuraciones/grupos.php"><i class="bi bi-collection"></i>Grupos</a>
            <a href="../Configuraciones/nivel.php"><i class="bi bi-bar-chart-steps"></i>Nivel</a>
            <a href="../Configuraciones/horarios.php"><i class="bi bi-clock"></i>Horarios</a>
            <a href="../Configuraciones/periodos.php"><i class="bi bi-calendar-event"></i>Periodos</a>
            <a href="../Configuraciones/usuarios.php"><i class="bi bi-people"></i>Usuarios</a>
        </div>
        <div class="menu-salir"><a href="#" id="cerrarSesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a></div>
    </aside>

    <main class="contenido">
        <header class="encabezado">
            <img src="../../img/logo_its.png" alt="Logo ITSCC">
            <h1>Instituto Tecnológico Superior de Ciudad Constitución</h1>
            <div class="usuario"><span id="nombreUsuario"></span><div class="avatar"><i class="bi bi-person"></i></div></div>
        </header>

        <!--
            FECHAS DE INSCRIPCIÓN
         -->
        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Control de inscripción</h2>
                <span class="contador"></span>
            </div>

            <form>
                <div class="periodos">
                    <div class="periodo-bloque">
                        <h3><span class="chip interno">Internos</span> Registros internos</h3>
                        <div class="form-rejilla">
                            <div class="campo">
                                <label for="internosDe">Fecha de</label>
                                <input class="input" type="date" id="internosDe">
                            </div>
                            <div class="campo">
                                <label for="internosA">Fecha a</label>
                                <input class="input" type="date" id="internosA">
                            </div>
                        </div>
                    </div>

                    <div class="periodo-bloque">
                        <h3><span class="chip externo">Externos</span> Registros externos</h3>
                        <div class="form-rejilla">
                            <div class="campo">
                                <label for="externosDe">Fecha de</label>
                                <input class="input" type="date" id="externosDe">
                            </div>
                            <div class="campo">
                                <label for="externosA">Fecha a</label>
                                <input class="input" type="date" id="externosA">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-botones">
                    <button type="button" class="btn btn-verde"><i class="bi bi-check2"></i> Guardar fechas</button>
                </div>
            </form>
        </section>

        <!--
           SOLICITUDES DE INSCRIPCIÓN
        -->
        <div class="herramientas espacio-arriba">
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" placeholder="Buscar nombre del alumno"></div>
            <select class="select" aria-label="Nivel"><option>Nivel</option></select>
            <select class="select" aria-label="Horario"><option>Horario</option></select>
            <select class="select" aria-label="Estado"><option>Estado</option></select>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2 id="tituloTabla">Solicitudes internas</h2>
                <div class="pestanas" role="tablist">
                    <button class="activo" data-ver="internos">Internos</button>
                    <button data-ver="externos">Externos</button>
                </div>
            </div>

            <div id="internos">
            <div class="tabla-caja">
                <table class="tabla acciones-fijas">
                    <thead><tr><th>No. Control</th><th>Nombre</th><th>Carrera</th><th>Sem.</th>
                    <th>Nivel</th><th>Horario</th><th>Fecha</th><th>Estado</th><th class="centro">Acciones</th></tr></thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>

            <div id="externos" hidden>
            <div class="tabla-caja">
                <table class="tabla acciones-fijas">
                    <thead><tr><th>Nombre</th><th>Ref. Pago</th><th>Nivel</th><th>Horario</th>
                    <th>Fecha</th><th>Estado</th><th class="centro">Acciones</th></tr></thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        </section>
    </main>
</div>

<!-- 
     ACEPTAR INSCRIPCIÓN
-->
<dialog class="ventana" id="ventanaAceptar">
    <form>
        <div class="ventana-titulo">
            <h2><i class="bi bi-person-check"></i> Aceptar inscripción</h2>
            <button type="button" class="btn-icono cerrar" data-cerrar title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="resumen">
            <div><span>Alumno</span><b></b></div>
            <div><span>Nivel solicitado</span><b></b></div>
            <div><span>Horario solicitado</span><b></b></div>
        </div>

        <div class="campo">
            <label for="grupo">Grupo</label>
            <select class="select" id="grupo"><option>Selecciona el grupo</option></select>
            <small class="ayuda">Solo aparecen los grupos del mismo nivel y tipo que todavía tienen cupo.</small>
        </div>
        <div class="campo">
            <label for="horario">Horario</label>
            <select class="select" id="horario"><option>Selecciona el horario</option></select>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" data-cerrar><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde"><i class="bi bi-check2"></i> Aceptar en este grupo</button>
        </div>
    </form>
</dialog>

<!-- 
     RECHAZAR INSCRIPCIÓN 
-->
<dialog class="ventana" id="ventanaRechazar">
    <form>
        <div class="ventana-titulo">
            <h2><i class="bi bi-person-x"></i> Rechazar inscripción</h2>
            <button type="button" class="btn-icono cerrar" data-cerrar title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="resumen">
            <div><span>Alumno</span><b></b></div>
        </div>

        <div class="campo">
            <label for="motivo">Motivo del rechazo</label>
            <textarea class="input" id="motivo" rows="3" placeholder="Ej. No se encontró el comprobante de pago"></textarea>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-borde" data-cerrar>Cancelar</button>
            <button type="button" class="btn btn-rosa"><i class="bi bi-x-lg"></i> Rechazar</button>
        </div>
    </form>
</dialog>

<script>
    // Cambiar entre solicitudes Internas y Externas
    document.querySelectorAll('.pestanas button').forEach(b => b.addEventListener('click', () => {
        document.querySelectorAll('.pestanas button').forEach(x => x.classList.toggle('activo', x === b));
        ['internos', 'externos'].forEach(id => document.getElementById(id).hidden = id !== b.dataset.ver);
        document.getElementById('tituloTabla').textContent = 'Solicitudes ' + (b.dataset.ver === 'internos' ? 'internas' : 'externas');
    }));


    document.addEventListener('click', e => {
        const abrir = e.target.closest('[data-abrir]');
        if (abrir) document.getElementById(abrir.dataset.abrir).showModal();
        const cerrar = e.target.closest('[data-cerrar]');
        if (cerrar) cerrar.closest('dialog').close();
    });

</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>