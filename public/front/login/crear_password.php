<!DOCTYPE html>
<html lang="es">
<head>c
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear contraseña </title>
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
            <ol class="pasos" aria-label="Paso 3 de 3"><li class="hecho"><i class="bi bi-check-lg"></i></li><li class="hecho"><i class="bi bi-check-lg"></i></li><li class="activo">3</li></ol>

            <div class="registro-titulo">
                <h1>Crea tu contraseña</h1>
                <p>Tu correo quedó verificado. Elige una contraseña de al menos 8 caracteres.</p>
            </div>

            <form action="php/Registro/guardar_password.php">
                <div class="campo">
                    <label for="pass1">Nueva contraseña</label>
                    <div class="input-icono">
                        <input class="input" type="password" id="pass1" placeholder="Mínimo 8 caracteres">
                        <button type="button" class="icono ver" data-campo="pass1"><i class="bi bi-lock-fill"></i></button>
                    </div>
                </div>
                <div class="campo">
                    <label for="pass2">Confirmar contraseña</label>
                    <div class="input-icono">
                        <input class="input" type="password" id="pass2" placeholder="Repite tu contraseña">
                        <button type="button" class="icono ver" data-campo="pass2"><i class="bi bi-lock-fill"></i></button>
                    </div>
                </div>
                <button type="submit" class="btn btn-verde btn-ancho"><i class="bi bi-check2"></i> Guardar contraseña</button>
            </form>

            <div class="login-registro">
                <a href="../index.php" class="enlace"><i class="bi bi-arrow-left"></i> Regresar al login</a>
            </div>
        </div>
    </section>
</div>
<script>
    // Mostrar y ocultar contraseña con el candado
    document.querySelectorAll('.ver').forEach(b => b.addEventListener('click', () => {
        const c = document.getElementById(b.dataset.campo), oculto = c.type === 'password';
        c.type = oculto ? 'text' : 'password';
        b.innerHTML = oculto ? '<i class="bi bi-unlock-fill"></i>' : '<i class="bi bi-lock-fill"></i>';
    }));
</script>
</body>
</html>
