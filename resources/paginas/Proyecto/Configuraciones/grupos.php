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
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" placeholder="Buscar grupo"></div>
            <select class="select" aria-label="Periodo"><option>Periodo</option></select>
            <select class="select" aria-label="Tipo"><option>Tipo</option></select>
            <button type="button" class="btn btn-amarillo separar" id="abrirAgregar"><i class="bi bi-plus-lg"></i> Agregar</button>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Grupos</h2>
                <span class="contador"></span>
            </div>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead><tr><th>Docente</th><th>Nivel</th><th>Tipo</th><th>Horario</th><th class="centro"></th></tr></thead>

                </table>
            </div>
        </section>
    </main>
</div>

<!-- Ventana para AGREGAR GRUPO -->
<dialog class="ventana" id="ventanaAgregar">
    <form>
        <div class="ventana-titulo">
            <h2><i class="bi bi-collection"></i> Agregar grupo</h2>
            <button type="button" class="btn-icono cerrar" id="cerrarAgregar" title="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="form-rejilla">
            <div class="campo">
                <label for="docente">Docente</label>
                <select class="select" id="docente"><option>Selecciona un docente</option></select>
            </div>
            <div class="campo">
                <label for="nivel">Nivel</label>
                <select class="select" id="nivel"><option>Selecciona un nivel</option></select>
            </div>
            <div class="campo">
                <label for="tipo">Tipo</label>
                <select class="select" id="tipo"><option>Selecciona el tipo</option></select>
            </div>
            <div class="campo">
                <label for="horario">Horario</label>
                <select class="select" id="horario"><option>Selecciona un horario</option></select>
            </div>
        </div>

        <div class="form-botones">
            <button type="button" class="btn btn-rosa" id="cancelarAgregar"><i class="bi bi-x-lg"></i> Cancelar</button>
            <button type="button" class="btn btn-verde"><i class="bi bi-check2"></i> Guardar</button>
        </div>
    </form>
</dialog>

<script>
    // Abrir y cerrar la ventana de Agregar grupo
    const ventana = document.getElementById('ventanaAgregar');
    document.getElementById('abrirAgregar').addEventListener('click', () => ventana.showModal());
    document.getElementById('cerrarAgregar').addEventListener('click', () => ventana.close());
    document.getElementById('cancelarAgregar').addEventListener('click', () => ventana.close());
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>