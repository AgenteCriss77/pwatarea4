-- Crear base de datos con el conjunto de caracteres correcto
CREATE DATABASE IF NOT EXISTS lista_tareas
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lista_tareas;

-- Tabla de usuarios con restricciones y validaciones
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_nombre CHECK (LENGTH(TRIM(nombre)) > 0),
    CONSTRAINT chk_usuario CHECK (LENGTH(TRIM(usuario)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tareas con restricciones y validaciones
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    completada BOOLEAN NOT NULL DEFAULT FALSE,
    usuario_id INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT chk_descripcion CHECK (LENGTH(TRIM(descripcion)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para mejorar el rendimiento
CREATE INDEX idx_usuario_id ON tareas(usuario_id);
CREATE INDEX idx_completada ON tareas(completada);
