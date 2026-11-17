<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción </title>
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
        <a href="alumno_inscripcion.php" class="activo"><i class="bi bi-journal-text"></i>Inscripciones</a>
        <div class="menu-salir"><a href="../../login/logout.php"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a></div>
    </aside>

    <main class="contenido">
        <header class="encabezado">
            <img src="../../img/logo_its.png" alt="Logo ITSCC">
            <h1>Instituto Tecnológico Superior de Ciudad Constitución</h1>
            <div class="usuario"><span></span><div class="avatar"><i class="bi bi-person"></i></div></div>
        </header>

        <section class="tarjeta">
            <div class="tarjeta-titulo">
                <h2>Formulario de inscripciones</h2>
                <span class="estado activo">Activo</span>
            </div>

            <form enctype="multipart/form-data">
                <div class="form-rejilla">
                    <div class="campo completo">
                        <label for="tipoRegistro">Selecciona el tipo de registro</label>
                        <select class="select" id="tipoRegistro" name="tipo">
                            <option value="interno">Estudiante interno</option>
                            <option value="externo">Estudiante externo</option>
                        </select>
                    </div>

                    
                    <div class="campo solo-interno">
                        <label>No. Control</label>
                        <input class="input" type="text" name="numero_control" placeholder="Ej. 233110169">
                    </div>

                    <div class="campo">
                        <label>Nivel</label>
                        <select class="select" name="nivel"></select>
                    </div>

                    
                    <div class="campo solo-interno">
                        <label>Semestre</label>
                        <select class="select" name="semestre"></select>
                    </div>

                    <div class="campo">
                        <label>Nombre completo</label>
                        <input class="input" type="text" name="nombre" placeholder="Nombre y apellidos">
                    </div>
                    <div class="campo">
                        <label>Celular</label>
                        <input class="input" type="tel" name="celular" placeholder="613 000 0000">
                    </div>
                    <div class="campo">
                        <label>Correo</label>
                        <input class="input" type="email" name="correo" placeholder="Correo electrónico">
                    </div>
                    <div class="campo">
                        <label>Horario</label>
                        <select class="select" name="horario"></select>
                    </div>

                    
                    <div class="campo solo-interno">
                        <label>Carrera</label>
                        <select class="select" name="carrera"></select>
                    </div>

                    
                    <div class="campo solo-externo" hidden>
                        <label>Referencia de pago</label>
                        <input class="input" type="text" name="referencia_pago" placeholder="Número de referencia del pago">
                    </div>

                    <!-- SOL PARA  EXTERNOS -->
                    <div class="campo solo-externo" hidden>
                        <label>Comprobante de pago</label>
                        <label class="subir-archivo" for="comprobante">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span id="nombreArchivo">Subir imagen</span>
                            <small>JPG o PNG</small>
                        </label>
                        <input type="file" id="comprobante" name="comprobante" accept="image/png, image/jpeg" hidden>
                    </div>
                </div>

                <div class="form-botones">
                    <button type="reset" class="btn btn-rosa"><i class="bi bi-x-lg"></i> Cancelar</button>
                    <button type="submit" class="btn btn-verde"><i class="bi bi-check2"></i> Guardar</button>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
    // Muestra los campos de interno o de externo según lo que se elija
    const tipo = document.getElementById('tipoRegistro');

    function mostrarCampos() {
        const esInterno = tipo.value === 'interno';
        document.querySelectorAll('.solo-interno').forEach(c => c.hidden = !esInterno);
        document.querySelectorAll('.solo-externo').forEach(c => c.hidden = esInterno);
    }
    tipo.addEventListener('change', mostrarCampos);
    // Al presionar "Cancelar" el formulario regresa a "interno"
    document.querySelector('form').addEventListener('reset', () => setTimeout(() => {
        mostrarCampos();
        document.getElementById('nombreArchivo').textContent = 'Subir imagen';
    }));
    mostrarCampos();

    // Muestra el nombre de la imagen que se eligió
    document.getElementById('comprobante').addEventListener('change', e => {
        const archivo = e.target.files[0];
        document.getElementById('nombreArchivo').textContent =
            archivo ? archivo.name : 'Subir imagen';
    });
</script>
</body>
</html>