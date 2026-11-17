<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar código </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="icon" href="../img/logo_its.png">
</head>
<body>
<div class="login">

    <section class="login-arte" aria-hidden="true">
        <span class="burbuja b1"></span><span class="burbuja b2"></span><span class="burbuja b3"></span>
        <span class="burbuja b4"></span><span class="burbuja b5"></span>
        <div class="frase">
            <h2>Crea tu cuenta<br>en tres pasos.</h2>
            <p>Te enviaremos un código a tu correo para que crees tu contraseña.</p>
        </div>
    </section>

    <section class="login-lado">
        <div class="login-tarjeta">
            <ol class="pasos" aria-label="Paso 2 de 3"><li class="hecho"><i class="bi bi-check-lg"></i></li><li class="activo">2</li><li>3</li></ol>

            <div class="registro-titulo">
                <h1>Revisa tu correo</h1>
                <p>Enviamos un código de 6 dígitos a <b>co••••••@cdconstitucion.tecnm.mx</b>. Expira en 10 minutos.</p>
            </div>

            <form action="php/Registro/verificar_codigo.php">
                <div class="campo">
                    <label for="codigo">Código de verificación</label>
                    <input class="input input-codigo" type="text" id="codigo" maxlength="6" placeholder="••••••" inputmode="numeric">
                </div>
                <button type="submit" class="btn btn-verde btn-ancho">Verificar código</button>
                <p class="reenviar"><a href="#" class="enlace"><i class="bi bi-arrow-repeat"></i> Reenviar código</a></p>
            </form>

            <div class="login-registro">
                <a href="../index.php" class="enlace"><i class="bi bi-arrow-left"></i> Regresar al login</a>
            </div>
        </div>
    </section>
</div>
</body>
</html>
