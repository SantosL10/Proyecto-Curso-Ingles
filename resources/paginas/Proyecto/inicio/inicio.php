<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio </title>
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
        <a href="inicio.php" class="activo"><i class="bi bi-house-heart"></i>Inicio</a>
        <a href="../Inscripciones/inscripciones.php"><i class="bi bi-journal-text"></i>Inscripciones</a>
        <a href="../Configuraciones/grupos.php"><i class="bi bi-gear"></i>Configuración</a>
        <div class="submenu">
            <a href="../Configuraciones/grupos.php"><i class="bi bi-collection"></i>Grupos</a>
            <a href="../Configuraciones/nivel.php"><i class="bi bi-bar-chart-steps"></i>Nivel</a>
            <a href="../Configuraciones/horarios.php"><i class="bi bi-clock"></i>Horarios</a>
            <a href="../Configuraciones/usuarios.php"><i class="bi bi-people"></i>Usuarios</a>
        </div>
        <div class="menu-salir"><a href="#" id="cerrarSesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a></div>
    </aside>

    <main class="contenido">
        <header class="encabezado">
            <img src="/front/img/logo_its.png" alt="Logo ITSCC">
            <h1>Instituto Tecnológico Superior de Ciudad Constitución</h1>
            <div class="usuario"><span id="nombreUsuario"></span><div class="avatar"><i class="bi bi-person"></i></div></div>
        </header>

        <div class="herramientas">
            <div class="buscador"><i class="bi bi-search"></i><input class="input" type="search" placeholder="Buscar nombre del alumno"></div>
            <select class="select" aria-label="Tipo"><option>Tipo</option></select> <select class="select" aria-label="Carrera"><option>Carrera</option></select>
            <select class="select" aria-label="Semestre"><option>Semestre</option></select> <select class="select" aria-label="Nivel"><option>Nivel</option></select>
            <select class="select" aria-label="Horario"><option>Horario</option></select> <select class="select" aria-label="Estatus"><option>Estatus</option></select>
        </div>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2 id="tituloTabla">Inscritos Internos</h2>
                <div class="pestanas" role="tablist">
                    <button class="activo" data-ver="internos">Internos</button>
                    <button data-ver="externos">Externos</button>
                </div>
            </div>

            <div id="internos">
            <div class="tabla-caja">
                <table class="tabla">
                    <thead><tr><th>No. Control</th><th>Nombre</th><th>Carrera</th><th>Sem.</th>
                    <th>Nivel</th><th>Horario</th><th>Fecha</th><th>Estado</th><th class="centro"></th></tr></thead>
                    
                </table>
            </div>
            </div>
            <div id="externos" hidden>
            <div class="tabla-caja">
                <table class="tabla">
                    <thead><tr><th>Nombre</th><th>Ref. Pago</th><th>Nivel</th><th>Horario</th>
                    <th>Fecha</th><th>Estado</th><th class="centro"></th></tr></thead>
                    
                </table>
            </div>
            </div>
        </section>
    </main>
</div>
<script>
    // Cambiar entre Inscritos Internos y Externos
    document.querySelectorAll('.pestanas button').forEach(b => b.addEventListener('click', () => {
        document.querySelectorAll('.pestanas button').forEach(x => x.classList.toggle('activo', x === b));
        ['internos', 'externos'].forEach(id => document.getElementById(id).hidden = id !== b.dataset.ver);
        document.getElementById('tituloTabla').textContent = 'Inscritos ' + (b.dataset.ver === 'internos' ? 'Internos' : 'Externos');
    }));
</script>
<script src="/front/login/sesion.js"></script>
</body>
</html>