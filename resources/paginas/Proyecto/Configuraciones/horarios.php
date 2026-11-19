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
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" placeholder="Buscar horario"></div>
            <button class="btn btn-borde">Clave <i class="bi bi-arrow-down-up"></i></button> <button class="btn btn-borde">Horario <i class="bi bi-arrow-down-up"></i></button> <button class="btn btn-borde">Tipo <i class="bi bi-arrow-down-up"></i></button>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Horarios</h2>
                <span class="contador"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead><tr><th>Clave</th><th>Horario</th><th>Cupo</th><th>Registrados</th><th>Disponible</th><th>Tipo</th><th class="centro"></th></tr></thead>
                    
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR HORARIO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2><i class="bi bi-clock"></i> Agregar horario</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo">
                <label for="clave">Clave</label>
                <input class="input" type="text" id="clave" placeholder="Ej. INT-13">
            </div>
            <div class="campo">
                <label for="tipo">Tipo</label>
                <select class="select" id="tipo"><option>Selecciona el tipo</option></select>
            </div>
            <div class="campo">
                <label for="horaInicio">Hora de inicio</label>
                <input class="input" type="time" id="horaInicio">
            </div>
            <div class="campo">
                <label for="horaFin">Hora de fin</label>
                <input class="input" type="time" id="horaFin">
            </div>
            <div class="campo">
                <label for="cupo">Cupo</label>
                <input class="input" type="number" id="cupo" placeholder="Ej. 30" min="1">
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    // Abrir y cerrar la ventana de Agregar
    const ventana = document.getElementById('ventanaAgregar');
    document.getElementById('abrirAgregar').addEventListener('click', () => ventana.showModal());
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>