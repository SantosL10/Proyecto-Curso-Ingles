-- SELECT VERSION();
CREATE DATABASE IF NOT EXISTS inscripcion_ingles
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE inscripcion_ingles;
--  Tabla carreras
CREATE TABLE carreras (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave       VARCHAR(10)  NOT NULL,
  nombre      VARCHAR(100) NOT NULL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_carreras_clave UNIQUE (clave)
) ENGINE=InnoDB;
-- PRUEBA
INSERT INTO carreras (clave, nombre) VALUES
  ('PRO-03', 'Ing. Industrial'),
  ('PRO-04', 'Ing. Sistemas Computacionales');
SELECT * FROM carreras;

-- Tabla niveles
CREATE TABLE niveles (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orden       TINYINT UNSIGNED NOT NULL,
  nombre      VARCHAR(50) NOT NULL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_niveles_orden  UNIQUE (orden),
  CONSTRAINT uq_niveles_nombre UNIQUE (nombre),
  CONSTRAINT ck_niveles_orden  CHECK (orden >= 1)
) ENGINE=InnoDB;
SELECT * FROM niveles;
-- PRUEBA Niveles
INSERT INTO niveles (orden, nombre) VALUES
  (1, 'Beginner'),
  (2, 'Elementary'),
  (3, 'Preintermediate A'),
  (4, 'Preintermediate B'),
  (5, 'Intermediate'),
  (6, 'Upper-intermediate'),
  (7, 'Advanced');

SELECT * FROM niveles ORDER BY orden;
-- Tabla PERIODOS
CREATE TABLE periodos (
  id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre                VARCHAR(50) NOT NULL,
  fecha_inicio          DATE NOT NULL,
  fecha_fin             DATE NOT NULL,
  insc_internos_inicio  DATE NOT NULL,
  insc_internos_fin     DATE NOT NULL,
  insc_externos_inicio  DATE NOT NULL,
  insc_externos_fin     DATE NOT NULL,
  created_at            TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at            TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_periodos_nombre    UNIQUE (nombre),
  CONSTRAINT ck_periodos_fechas    CHECK (fecha_fin >= fecha_inicio),
  CONSTRAINT ck_periodos_internos  CHECK (insc_internos_fin >= insc_internos_inicio),
  CONSTRAINT ck_periodos_externos  CHECK (insc_externos_fin >= insc_externos_inicio)
) ENGINE=InnoDB;

-- PRUEBA DE PERIODOS
INSERT INTO periodos
  (nombre, fecha_inicio, fecha_fin,
   insc_internos_inicio, insc_internos_fin,
   insc_externos_inicio, insc_externos_fin)
VALUES
  ('Ene-Jun 2026', '2026-01-26', '2026-06-05',
   '2026-01-12', '2026-01-16', '2026-01-19', '2026-01-23'),
  ('Ago-Dic 2026', '2026-08-24', '2026-12-11',
   '2026-08-10', '2026-08-14', '2026-08-17', '2026-08-21');
SELECT * FROM periodos;

-- Tabla users
CREATE TABLE users (
  id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name               VARCHAR(150) NOT NULL,
  email              VARCHAR(150) NOT NULL,
  email_verified_at  TIMESTAMP NULL,
  password           VARCHAR(255) NOT NULL,
  rol                ENUM('alumno', 'admin', 'consulta') NOT NULL DEFAULT 'alumno',
  remember_token     VARCHAR(100) NULL,
  created_at         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_users_email UNIQUE (email)
) ENGINE=InnoDB;
SELECT * FROM users;

-- Usuarios de prueba
INSERT INTO users (name, email, password, rol) VALUES
  ('Coordinador CLE', 'coordinacion@ejemplo.com', 'HASH_DE_PRUEBA', 'admin'),
  ('Consulta CLE',    'consulta@ejemplo.com',     'HASH_DE_PRUEBA', 'consulta');
SELECT id, name, email, rol FROM users;

-- Tabla ALUMNOS
CREATE TABLE alumnos (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         BIGINT UNSIGNED NULL,
  tipo            ENUM('interno', 'externo') NOT NULL,
  numero_control  VARCHAR(10) NULL,
  nombre          VARCHAR(150) NOT NULL,
  correo          VARCHAR(150) NOT NULL,
  celular         VARCHAR(15) NULL,
  semestre        TINYINT UNSIGNED NULL,
  carrera_id      BIGINT UNSIGNED NULL,
  created_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT uq_alumnos_user     UNIQUE (user_id),
  CONSTRAINT uq_alumnos_control  UNIQUE (numero_control),
  CONSTRAINT uq_alumnos_correo   UNIQUE (correo),

  CONSTRAINT fk_alumnos_user FOREIGN KEY (user_id)
    REFERENCES users (id) ON DELETE SET NULL,
  CONSTRAINT fk_alumnos_carrera FOREIGN KEY (carrera_id)
    REFERENCES carreras (id) ON DELETE RESTRICT,

  CONSTRAINT ck_alumnos_tipo CHECK (
    (tipo = 'interno' AND numero_control IS NOT NULL
                      AND carrera_id IS NOT NULL
                      AND semestre IS NOT NULL)
    OR
    (tipo = 'externo' AND numero_control IS NULL
                      AND carrera_id IS NULL
                      AND semestre IS NULL)
  )
) ENGINE=InnoDB;
-- PRUEBA alumnos
INSERT INTO alumnos (tipo, numero_control, nombre, correo, semestre, carrera_id)
VALUES ('interno', '21110001', 'Ana Ruiz', 'ana@ejemplo.com', 5,
        (SELECT id FROM carreras WHERE clave = 'PRO-04'));

INSERT INTO alumnos (tipo, nombre, correo, celular)
VALUES ('externo', 'Carla Soto', 'carla@ejemplo.com', '6131234567');

SELECT a.id, a.tipo, a.numero_control, a.nombre, c.nombre AS carrera
FROM alumnos a
LEFT JOIN carreras c ON c.id = a.carrera_id;

-- TABLA GRUPOS
CREATE TABLE grupos (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  periodo_id   BIGINT UNSIGNED NOT NULL,
  nivel_id     BIGINT UNSIGNED NOT NULL,
  tipo         ENUM('interno', 'externo') NOT NULL,
  hora_inicio  TIME NOT NULL,
  hora_fin     TIME NOT NULL,
  cupo         SMALLINT UNSIGNED NOT NULL,
  created_at   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_grupos_periodo FOREIGN KEY (periodo_id)
    REFERENCES periodos (id) ON DELETE RESTRICT,
  CONSTRAINT fk_grupos_nivel FOREIGN KEY (nivel_id)
    REFERENCES niveles (id) ON DELETE RESTRICT,

  CONSTRAINT uq_grupos_horario UNIQUE (periodo_id, nivel_id, tipo, hora_inicio),
  CONSTRAINT uq_grupos_id_periodo UNIQUE (id, periodo_id),

  CONSTRAINT ck_grupos_horas CHECK (hora_fin > hora_inicio),
  CONSTRAINT ck_grupos_cupo  CHECK (cupo > 0)
) ENGINE=InnoDB;
-- Prueba GRUPOS
INSERT INTO grupos (periodo_id, nivel_id, tipo, hora_inicio, hora_fin, cupo)
VALUES
  ((SELECT id FROM periodos WHERE nombre = 'Ago-Dic 2026'),
   (SELECT id FROM niveles  WHERE orden = 3), 'interno', '13:00', '14:00', 30),
  ((SELECT id FROM periodos WHERE nombre = 'Ago-Dic 2026'),
   (SELECT id FROM niveles  WHERE orden = 3), 'interno', '15:00', '16:00', 30),
  ((SELECT id FROM periodos WHERE nombre = 'Ago-Dic 2026'),
   (SELECT id FROM niveles  WHERE orden = 3), 'externo', '16:00', '18:00', 25);

SELECT g.id, p.nombre AS periodo, n.nombre AS nivel, g.tipo,
       g.hora_inicio, g.hora_fin, g.cupo
FROM grupos g
JOIN periodos p ON p.id = g.periodo_id
JOIN niveles  n ON n.id = g.nivel_id
ORDER BY g.tipo, g.hora_inicio;

-- TABLA INSCRIPCIONES
CREATE TABLE inscripciones (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  alumno_id         BIGINT UNSIGNED NOT NULL,
  grupo_id          BIGINT UNSIGNED NOT NULL,
  periodo_id        BIGINT UNSIGNED NOT NULL,
  semestre          TINYINT UNSIGNED NULL,
  estado            ENUM('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
  motivo_rechazo    VARCHAR(255) NULL,
  referencia_pago   VARCHAR(50)  NULL,
  comprobante_ruta  VARCHAR(255) NULL,
  resultado         ENUM('pendiente', 'acreditado', 'no_acreditado') NOT NULL DEFAULT 'pendiente',
  activa            TINYINT GENERATED ALWAYS AS (IF(estado <> 'rechazada', 1, NULL)) VIRTUAL,
  created_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_insc_alumno FOREIGN KEY (alumno_id)
    REFERENCES alumnos (id) ON DELETE RESTRICT,
  CONSTRAINT fk_insc_grupo FOREIGN KEY (grupo_id, periodo_id)
    REFERENCES grupos (id, periodo_id) ON DELETE RESTRICT,

  CONSTRAINT uq_insc_activa UNIQUE (alumno_id, periodo_id, activa),

  CONSTRAINT ck_insc_motivo    CHECK (estado <> 'rechazada' OR motivo_rechazo IS NOT NULL),
  CONSTRAINT ck_insc_pago      CHECK ((referencia_pago IS NULL) = (comprobante_ruta IS NULL)),
  CONSTRAINT ck_insc_resultado CHECK (resultado = 'pendiente' OR estado = 'aceptada')
) ENGINE=InnoDB;
-- PREPARAR LAS VARIABLES
SET @periodo = (SELECT id FROM periodos WHERE nombre = 'Ago-Dic 2026');
SET @otro    = (SELECT id FROM periodos WHERE nombre = 'Ene-Jun 2026');
SET @ana     = (SELECT id FROM alumnos  WHERE correo = 'ana@ejemplo.com');
SET @g13     = (SELECT id FROM grupos WHERE periodo_id = @periodo AND tipo = 'interno' AND hora_inicio = '13:00');
SET @g15     = (SELECT id FROM grupos WHERE periodo_id = @periodo AND tipo = 'interno' AND hora_inicio = '15:00');

SELECT @periodo, @otro, @ana, @g13, @g15;
-- 1) Ana se preinscribe al grupo de 13:00 → funciona
INSERT INTO inscripciones (alumno_id, grupo_id, periodo_id, semestre)
VALUES (@ana, @g13, @periodo, 5);

-- 2) Intenta otra en el mismo periodo → debe FALLAR (uq_insc_activa)
-- INSERT INTO inscripciones (alumno_id, grupo_id, periodo_id, semestre)
-- VALUES (@ana, @g15, @periodo, 5);

-- 3) El coordinador la rechaza sin motivo → debe FALLAR (ck_insc_motivo)
-- UPDATE inscripciones SET estado = 'rechazada'
-- WHERE alumno_id = @ana AND periodo_id = @periodo;

-- 4) La rechaza con motivo → funciona
UPDATE inscripciones SET estado = 'rechazada', motivo_rechazo = 'Prueba de rechazo'
WHERE alumno_id = @ana AND periodo_id = @periodo;

-- 5) Ana se vuelve a preinscribir → AHORA funciona, porque la otra está rechazada
INSERT INTO inscripciones (alumno_id, grupo_id, periodo_id, semestre)
VALUES (@ana, @g15, @periodo, 5);

-- 6) Grupo de Ago-Dic con periodo Ene-Jun → debe FALLAR (fk_insc_grupo)
-- INSERT INTO inscripciones (alumno_id, grupo_id, periodo_id, semestre)
-- VALUES (@ana, @g13, @otro, 5);

-- SELECT id, grupo_id, estado, motivo_rechazo, activa FROM inscripciones;
SELECT id, grupo_id, estado, motivo_rechazo, activa FROM inscripciones;
-- LUGARES DISPONIBLES
SELECT g.id, g.tipo, g.hora_inicio, g.cupo,
       COUNT(i.id)                        AS ocupados,
       CAST(g.cupo AS SIGNED) - COUNT(i.id) AS disponibles
FROM grupos g
LEFT JOIN inscripciones i
       ON i.grupo_id = g.id AND i.estado <> 'rechazada'
WHERE g.periodo_id = @periodo
GROUP BY g.id, g.tipo, g.hora_inicio, g.cupo
ORDER BY g.tipo, g.hora_inicio;

-- TABLA ACREDITACIONES
CREATE TABLE acreditaciones (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  alumno_id   BIGINT UNSIGNED NOT NULL,
  nivel_id    BIGINT UNSIGNED NOT NULL,
  origen      ENUM('curso', 'examen', 'carga_inicial') NOT NULL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_acred_alumno FOREIGN KEY (alumno_id)
    REFERENCES alumnos (id) ON DELETE RESTRICT,
  CONSTRAINT fk_acred_nivel FOREIGN KEY (nivel_id)
    REFERENCES niveles (id) ON DELETE RESTRICT,

  CONSTRAINT uq_acred_alumno_nivel UNIQUE (alumno_id, nivel_id)
) ENGINE=InnoDB;
-- PRUEBAS 
SET @carla = (SELECT id FROM alumnos WHERE correo = 'carla@ejemplo.com');

INSERT INTO acreditaciones (alumno_id, nivel_id, origen) VALUES
  (@ana,   (SELECT id FROM niveles WHERE orden = 1), 'curso'),
  (@ana,   (SELECT id FROM niveles WHERE orden = 2), 'curso'),
  (@carla, (SELECT id FROM niveles WHERE orden = 2), 'examen');
  SELECT * FROM ACREDITACIONES;
  -- MOSTRAR
  SELECT a.nombre,
       COALESCE(MAX(n.orden), 0)     AS nivel_mas_alto,
       COALESCE(MAX(n.orden), 0) + 1 AS le_toca_orden,
       (SELECT nombre FROM niveles
         WHERE orden = COALESCE(MAX(n.orden), 0) + 1) AS le_toca_nivel
FROM alumnos a
LEFT JOIN acreditaciones ac ON ac.alumno_id = a.id
LEFT JOIN niveles n         ON n.id = ac.nivel_id
GROUP BY a.id, a.nombre;