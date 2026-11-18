<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login/css/estilos.css">
    <link rel="icon" href="img/logo_its.png">
</head>
<body>
<div class="login">

    <!-- Panel decorativo  -->
    <section class="login-arte" aria-hidden="true">
        <span class="burbuja b1"></span><span class="burbuja b2"></span><span class="burbuja b3"></span>
        <span class="burbuja b4"></span><span class="burbuja b5"></span>
        <div class="frase">
            <h2>BIENVENIDO!<br>CLE APP</h2>
        </div>
    </section>

    <!-- Tarjeta del login -->
    <section class="login-lado">
        <div class="login-tarjeta">
            <div class="login-cabecera">
                <img src="img/logo_its.png" alt="Logo ITSCC">
                <div>
                    <h1>CLE APP</h1>
                    <small>Instituto Tecnológico Superior de Ciudad Constitución</small>
                </div>
            </div>

            <form action="/login" method="POST">
                <div class="campo">
                    <label for="email">Email</label>
                    <div class="input-icono">
                        <input class="input" type="email" id="email" name="email" placeholder="correo@cdconstitucion.tecnm.mx">
                        <span class="icono"><i class="bi bi-person-fill"></i></span>
                    </div>
                </div>

                <div class="campo">
                    <label for="password">Password</label>
                    <div class="input-icono">
                        <input class="input" type="password" id="password" name="email" placeholder="Tu contraseña">
                        <button type="button" class="icono" id="verPass" title="Mostrar contraseña"><i class="bi bi-lock-fill"></i></button>
                    </div>
                </div>

                <div class="login-pie">
                    <a href="login/registro.php" class="enlace">¿Olvidé mi password?</a>
                    <button type="submit" class="btn btn-verde">Login <i class="bi bi-arrow-right"></i></button>
                </div>
            </form>

            <div class="login-registro">
                <a href="login/registro.php" class="enlace-fuerte">Registrarse</a>
            </div>
        </div>
    </section>
</div>

<script>
    // Mostrar y ocultar contraseña con el candado
    const btn = document.getElementById('verPass'), campo = document.getElementById('password');
    btn.addEventListener('click', () => {
        const oculto = campo.type === 'password';
        campo.type = oculto ? 'text' : 'password';
        btn.innerHTML = oculto ? '<i class="bi bi-unlock-fill"></i>' : '<i class="bi bi-lock-fill"></i>';
    });
    const form = document.querySelector('form');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const respuesta = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            })
        });

        const datos = await respuesta.json();

        if (respuesta.ok) {
            window.location.href = '/front/Proyecto/inicio/inicio.php';
        } else {
            alert(datos.mensaje || 'Revisa tus datos');
        }
    });

</script>
</body>
</html>
