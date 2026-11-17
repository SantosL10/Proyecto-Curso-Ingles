<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro </title>
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
            <ol class="pasos" aria-label="Paso 1 de 3"><li class="activo">1</li><li>2</li><li>3</li></ol>

            <div class="registro-titulo">
                <h1>Registro</h1>
                <p>Escribe tus datos y te enviaremos un código de verificación.</p>
            </div>

            <form action="php/Registro/enviar_codigo.php">
                <div class="campo">
                    <label for="correo">Correo</label>
                    <div class="input-icono">
                        <input class="input" type="email" id="correo" placeholder="correo@cdconstitucion.tecnm.mx">
                        <span class="icono"><i class="bi bi-envelope-fill"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-verde btn-ancho"><i class="bi bi-send-fill"></i> Enviar código</button>
            </form>

            <div class="login-registro">
                <a href="../index.php" class="enlace"><i class="bi bi-arrow-left"></i> Regresar al login</a>
            </div>
        </div>
    </section>
</div>
</body>
</html>
