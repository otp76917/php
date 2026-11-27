DROP DATABASE IF EXISTS AGENDA;
CREATE DATABASE AGENDA CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE AGENDA;

-- Tabla de usuarios
CREATE TABLE usuarios (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    rol TINYINT NOT NULL DEFAULT 0 COMMENT '1 = Administrador, 0 = Usuario'
);

-- Tabla de configuración global (id = 1)
CREATE TABLE configuracion (
    id INT PRIMARY KEY,
    max_lote INT NOT NULL CHECK (max_lote BETWEEN 3 AND 8),
    num_emojis INT NOT NULL CHECK (num_emojis BETWEEN 4 AND 10),
    max_contactos INT NOT NULL CHECK (max_contactos BETWEEN 10 AND 100)
);

-- Tabla de contactos
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigousu INT NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    telefono CHAR(9) NOT NULL,
    email VARCHAR(80) NOT NULL,
    emoji VARCHAR(8) NOT NULL,
    fecha DATETIME NOT NULL,
    CONSTRAINT fk_contactos_usuario FOREIGN KEY (codigousu)
        REFERENCES usuarios(codigo)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Configuración inicial por defecto
INSERT INTO configuracion (id, max_lote, num_emojis, max_contactos)
VALUES (1, 5, 10, 50);

