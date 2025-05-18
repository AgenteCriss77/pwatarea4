-- Crear base de datos con soporte UTF-8
CREATE DATABASE `01_calif` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleccionar la base de datos
USE `01_calif`;

-- Tabla: usuarios
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `rol` int(1) NOT NULL DEFAULT 2 COMMENT '1 Docente, 2 Estudiante',
  `contrasena` varchar(255) NOT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hora_creacion` time NOT NULL DEFAULT CURRENT_TIME,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: lugares
CREATE TABLE `lugares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabla: asignaturas
CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabla: asignaturas_estudiante
CREATE TABLE `asignaturas_estudiante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar_id` int(11) DEFAULT NULL,
  `asignatura_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Estudiante',
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabla: asignaturas_docente
CREATE TABLE `asignaturas_docente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asignatura_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Docente',
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_asignatura_docente_asignatura` (`asignatura_id`),
  KEY `fk_asignatura_docente_usuario` (`usuario_id`),
  CONSTRAINT `fk_asignatura_docente_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  CONSTRAINT `fk_asignatura_docente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: notas
CREATE TABLE `notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asignatura_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Estudiante',
  `parcial` int(1) DEFAULT NULL COMMENT '1 1er, 2 2do, 3 Mejoramiento',
  `teoria` float(6,2) DEFAULT NULL,
  `practica` float(6,2) DEFAULT NULL,
  `obs` text,
  `usuario_id_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT NULL,
  `hora_creacion` time DEFAULT NULL,
  `usuario_id_actualizacion` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `hora_actualizacion` time DEFAULT NULL,
  `usuario_id_eliminacion` int(11) DEFAULT NULL,
  `fecha_eliminacion` timestamp NULL DEFAULT NULL,
  `hora_eliminacion` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Añadir restricciones de clave foránea
ALTER TABLE `asignaturas_estudiante`
  ADD CONSTRAINT `fk_asignatura_estudiante_lugar` FOREIGN KEY (`lugar_id`) REFERENCES `lugares` (`id`),
  ADD CONSTRAINT `fk_asignatura_estudiante_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `fk_asignatura_estudiante_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

ALTER TABLE `notas`
  ADD CONSTRAINT `fk_notas_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `fk_notas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `chk_parcial` CHECK (`parcial` IN (1,2,3)),
  ADD CONSTRAINT `chk_teoria` CHECK (`teoria` >= 0 AND `teoria` <= 10),
  ADD CONSTRAINT `chk_practica` CHECK (`practica` >= 0 AND `practica` <= 10);

-- Insertar asignaturas básicas
INSERT INTO `asignaturas` (`nombre`, `fecha_creacion`, `hora_creacion`) VALUES
('Matemáticas', CURRENT_TIMESTAMP, CURRENT_TIME),
('Lenguaje', CURRENT_TIMESTAMP, CURRENT_TIME),
('Ciencias Naturales', CURRENT_TIMESTAMP, CURRENT_TIME),
('Estudios Sociales', CURRENT_TIMESTAMP, CURRENT_TIME);

-- Insertar lugares de ejemplo
INSERT INTO `lugares` (`nombre`, `fecha_creacion`, `hora_creacion`) VALUES
('Aula 101', CURRENT_TIMESTAMP, CURRENT_TIME),
('Aula 102', CURRENT_TIMESTAMP, CURRENT_TIME),
('Laboratorio 1', CURRENT_TIMESTAMP, CURRENT_TIME);

-- Crear un trigger para asignar automáticamente las asignaturas a nuevos estudiantes
DELIMITER //
CREATE TRIGGER asignar_asignaturas_estudiante 
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.rol = 2 THEN
        INSERT INTO asignaturas_estudiante (
            lugar_id,
            asignatura_id,
            usuario_id,
            fecha_creacion,
            hora_creacion
        )
        SELECT 
            1, -- Aula 101 por defecto
            id,
            NEW.id,
            CURRENT_TIMESTAMP,
            CURRENT_TIME
        FROM asignaturas;
    END IF;
END //
DELIMITER ;

-- Asignar asignaturas a estudiantes existentes
INSERT INTO asignaturas_estudiante (
    lugar_id,
    asignatura_id,
    usuario_id,
    fecha_creacion,
    hora_creacion
)
SELECT 
    1, -- Aula 101 por defecto
    a.id,
    u.id,
    CURRENT_TIMESTAMP,
    CURRENT_TIME
FROM usuarios u
CROSS JOIN asignaturas a
WHERE u.rol = 2
AND NOT EXISTS (
    SELECT 1 
    FROM asignaturas_estudiante ae 
    WHERE ae.usuario_id = u.id 
    AND ae.asignatura_id = a.id
);

-- Crear trigger para asignar automáticamente las asignaturas a nuevos docentes
DELIMITER //
CREATE TRIGGER asignar_asignaturas_docente 
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.rol = 1 THEN
        INSERT INTO asignaturas_docente (
            asignatura_id,
            usuario_id,
            fecha_creacion,
            hora_creacion
        )
        SELECT 
            id,
            NEW.id,
            CURRENT_TIMESTAMP,
            CURRENT_TIME
        FROM asignaturas;
    END IF;
END //
DELIMITER ;

-- Asignar asignaturas a docentes existentes
INSERT INTO asignaturas_docente (
    asignatura_id,
    usuario_id,
    fecha_creacion,
    hora_creacion
)
SELECT 
    a.id,
    u.id,
    CURRENT_TIMESTAMP,
    CURRENT_TIME
FROM usuarios u
CROSS JOIN asignaturas a
WHERE u.rol = 1
AND NOT EXISTS (
    SELECT 1 
    FROM asignaturas_docente ad 
    WHERE ad.usuario_id = u.id 
    AND ad.asignatura_id = a.id
);

-- FIN DEL SCRIPT
COMMIT;
