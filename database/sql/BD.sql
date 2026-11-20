DROP DATABASE IF EXISTS inscripcion_ingles;

CREATE DATABASE inscripcion_ingles
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE inscripcion_ingles;

-- ---------------------------------------------------------
-- 1. users: todos los que inician sesión
-- ---------------------------------------------------------
CREATE TABLE users (
  id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name               VARCHAR(150) NOT NULL,
  email              VARCHAR(150) NOT NULL,
  email_verified_at  TIMESTAMP NULL,
  password           VARCHAR(255) NULL,
  rol                ENUM('alumno', 'admin', 'consulta', 'docente') NOT NULL DEFAULT 'alumno',
  activo             BOOLEAN NOT NULL DEFAULT TRUE,
  remember_token     VARCHAR(100) NULL,
  created_at         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_users_email UNIQUE (email)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 2. niveles
-- ---------------------------------------------------------
CREATE TABLE niveles (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  orden       TINYINT UNSIGNED NOT NULL,
  clave       VARCHAR(20) NOT NULL,
  tipo        ENUM('interno', 'externo') NOT NULL,
  nombre      VARCHAR(50) NOT NULL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_niveles_clave       UNIQUE (clave),
  CONSTRAINT uq_niveles_tipo_orden  UNIQUE (tipo, orden),
  CONSTRAINT uq_niveles_tipo_nombre UNIQUE (tipo, nombre),
  CONSTRAINT ck_niveles_orden       CHECK (orden >= 1)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 3. periodos
-- ---------------------------------------------------------
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
  CONSTRAINT uq_periodos_nombre   UNIQUE (nombre),
  CONSTRAINT ck_periodos_fechas   CHECK (fecha_fin >= fecha_inicio),
  CONSTRAINT ck_periodos_internos CHECK (insc_internos_fin >= insc_internos_inicio),
  CONSTRAINT ck_periodos_externos CHECK (insc_externos_fin >= insc_externos_inicio)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 4. alumnos: datos que tienen todos
-- ---------------------------------------------------------
CREATE TABLE alumnos (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NULL,
  tipo        ENUM('interno', 'externo') NOT NULL,
  nombre      VARCHAR(150) NOT NULL,
  correo      VARCHAR(150) NOT NULL,
  celular     VARCHAR(15) NULL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_alumnos_user   UNIQUE (user_id),
  CONSTRAINT uq_alumnos_correo UNIQUE (correo),
  CONSTRAINT fk_alumnos_user FOREIGN KEY (user_id)
    REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 5. alumnos_internos: datos que solo tienen los internos
-- ---------------------------------------------------------
CREATE TABLE alumnos_internos (
  alumno_id       BIGINT UNSIGNED PRIMARY KEY,
  numero_control  VARCHAR(10) NOT NULL,
  semestre        TINYINT UNSIGNED NOT NULL,
  created_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_internos_control  UNIQUE (numero_control),
  CONSTRAINT ck_internos_semestre CHECK (semestre >= 1),
  CONSTRAINT fk_internos_alumno FOREIGN KEY (alumno_id)
    REFERENCES alumnos (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 6. grupos
-- ---------------------------------------------------------
CREATE TABLE grupos (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  periodo_id   BIGINT UNSIGNED NOT NULL,
  nivel_id     BIGINT UNSIGNED NOT NULL,
  salon        VARCHAR(10) NOT NULL,
  docente_id   BIGINT UNSIGNED NULL,
  hora_inicio  TIME NOT NULL,
  hora_fin     TIME NOT NULL,
  cupo         SMALLINT UNSIGNED NOT NULL,
  created_at   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_grupos_periodo FOREIGN KEY (periodo_id)
    REFERENCES periodos (id) ON DELETE RESTRICT,
  CONSTRAINT fk_grupos_nivel FOREIGN KEY (nivel_id)
    REFERENCES niveles (id) ON DELETE RESTRICT,
  CONSTRAINT fk_grupos_docente FOREIGN KEY (docente_id)
    REFERENCES users (id) ON DELETE SET NULL,
  CONSTRAINT uq_grupos_salon_horario UNIQUE (periodo_id, salon, hora_inicio),
  CONSTRAINT uq_grupos_id_periodo    UNIQUE (id, periodo_id),
  CONSTRAINT ck_grupos_horas CHECK (hora_fin > hora_inicio),
  CONSTRAINT ck_grupos_cupo  CHECK (cupo > 0)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 7. inscripciones
-- ---------------------------------------------------------
CREATE TABLE inscripciones (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  alumno_id   BIGINT UNSIGNED NOT NULL,
  grupo_id    BIGINT UNSIGNED NOT NULL,
  periodo_id  BIGINT UNSIGNED NOT NULL,
  estado      ENUM('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'aceptada',
  resultado   ENUM('pendiente', 'acreditado', 'no_acreditado') NOT NULL DEFAULT 'pendiente',
  activa      TINYINT GENERATED ALWAYS AS (IF(estado <> 'rechazada', 1, NULL)) VIRTUAL,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_insc_alumno FOREIGN KEY (alumno_id)
    REFERENCES alumnos (id) ON DELETE RESTRICT,
  CONSTRAINT fk_insc_grupo FOREIGN KEY (grupo_id, periodo_id)
    REFERENCES grupos (id, periodo_id) ON DELETE RESTRICT,
  CONSTRAINT uq_insc_activa UNIQUE (alumno_id, periodo_id, activa),
  CONSTRAINT ck_insc_resultado CHECK (resultado = 'pendiente' OR estado = 'aceptada')
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 8. pagos
-- ---------------------------------------------------------
CREATE TABLE pagos (
  id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  inscripcion_id    BIGINT UNSIGNED NOT NULL,
  referencia        VARCHAR(50)  NOT NULL,
  comprobante_ruta  VARCHAR(255) NOT NULL,
  motivo_rechazo    VARCHAR(255) NULL,
  created_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_pagos_inscripcion UNIQUE (inscripcion_id),
  CONSTRAINT fk_pagos_inscripcion FOREIGN KEY (inscripcion_id)
    REFERENCES inscripciones (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 9. acreditaciones
-- ---------------------------------------------------------
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

-- ---------------------------------------------------------
-- 10. codigos_verificacion (sin FK para permitir externos nuevos)
-- ---------------------------------------------------------
CREATE TABLE codigos_verificacion (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(150) NOT NULL,
  codigo      VARCHAR(255) NOT NULL,                      -- CAMBIO: cifrado, como la contraseña
  tipo        ENUM('registro', 'recuperacion') NOT NULL,
  expira_at   DATETIME NOT NULL,
  intentos    TINYINT UNSIGNED NOT NULL DEFAULT 0,        -- CAMBIO: máximo 5, lo controla la app
  usado       BOOLEAN NOT NULL DEFAULT FALSE,
  created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- CAMBIO: Laravel la necesita
  INDEX idx_codigos_email (email)                         -- CAMBIO: búsquedas rápidas por correo
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Datos base: los 14 niveles (7 internos y 7 externos)
-- ---------------------------------------------------------
INSERT INTO niveles (orden, clave, tipo, nombre) VALUES
  (1, 'INT-BEG', 'interno', 'Beginner'),
  (2, 'INT-ELE', 'interno', 'Elementary'),
  (3, 'INT-PIA', 'interno', 'Preintermediate A'),
  (4, 'INT-PIB', 'interno', 'Preintermediate B'),
  (5, 'INT-INT', 'interno', 'Intermediate'),
  (6, 'INT-UPI', 'interno', 'Upper-intermediate'),
  (7, 'INT-ADV', 'interno', 'Advanced'),
  (1, 'EXT-BEG', 'externo', 'Beginner'),
  (2, 'EXT-ELE', 'externo', 'Elementary'),
  (3, 'EXT-PIA', 'externo', 'Preintermediate A'),
  (4, 'EXT-PIB', 'externo', 'Preintermediate B'),
  (5, 'EXT-INT', 'externo', 'Intermediate'),
  (6, 'EXT-UPI', 'externo', 'Upper-intermediate'),
  (7, 'EXT-ADV', 'externo', 'Advanced');

-- Verificación: deben salir 10 tablas
SHOW TABLES;
DESCRIBE users;
DESCRIBE codigos_verificacion;