<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis grupos</title>
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

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Mis grupos</h2>
                <span class="contador"></span>
            </div>

            <div class="grupos-lista">
            </div>
        </section>
    </main>
</div>
</body>
</html>