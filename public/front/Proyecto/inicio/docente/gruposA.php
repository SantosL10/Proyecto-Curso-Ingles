<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista del grupo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../login/css/estilos.css">
    <link rel="icon" href="../../../img/logo_its.png">
</head>
<body>
<div class="panel">
    <aside class="menu">
        <div class="menu-nombre">CLE APP</div>
        <a href="misGrupos.php" class="activo"><i class="bi bi-collection"></i>Mis grupos</a>
        <div class="menu-salir"><a href="../../../login/logout.php"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a></div>
    </aside>

    <main class="contenido">
        <header class="encabezado">
            <img src="../../../img/logo_its.png" alt="Logo ITSCC">
            <h1>Instituto Tecnológico Superior de Ciudad Constitución</h1>
            <div class="usuario"><span></span><div class="avatar"><i class="bi bi-person"></i></div></div>
        </header>

        <a href="misGrupos.php" class="volver"><i class="bi bi-arrow-left"></i> Mis grupos</a>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Lista del grupo</h2>
                <span class="contador"></span>
            </div>

            <div class="tabla-caja">
                <table class="tabla">
                    <thead><tr><th>Alumno</th><th>No. control</th><th>Resultado</th></tr></thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="form-botones">
                <a href="misGrupos.php" class="btn btn-rosa"><i class="bi bi-x-lg"></i> Cancelar</a>
                <button type="button" class="btn btn-verde"><i class="bi bi-check2"></i> Guardar resultados</button>
            </div>
        </section>
    </main>
</div>

<script>
    // Al dar clic en "Acreditó" o "No acreditó", se marca ese y se desmarca el otro
    document.querySelectorAll('.resultado').forEach(grupo => {
        grupo.querySelectorAll('button').forEach(boton => {
            boton.addEventListener('click', () => {
                grupo.querySelectorAll('button').forEach(b => b.classList.remove('activo'));
                boton.classList.add('activo');
            });
        });
    });
</script>
</body>
</html>