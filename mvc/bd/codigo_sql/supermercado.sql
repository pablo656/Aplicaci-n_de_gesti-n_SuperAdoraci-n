CREATE DATABASE IF NOT EXISTS supermercado CHARACTER SET utf8mb4;
USE supermercado;

-- USUARIOS
CREATE TABLE usuarios (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    email                VARCHAR(100) NOT NULL UNIQUE,
    nombre               VARCHAR(100) NOT NULL UNIQUE,
    contrasena           VARCHAR(255) NOT NULL,
    rol                  ENUM('dueno', 'administrador', 'cliente') DEFAULT 'cliente',
    ultimo_inicio_sesion DATETIME DEFAULT NULL
);

-- PRODUCTOS
CREATE TABLE productos (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    nombre               VARCHAR(150) NOT NULL,
    stock                INT DEFAULT 0,
    precio               DECIMAL(10,2) NOT NULL,
    precio_por_peso      INT DEFAULT 0,
    categoria            VARCHAR(80),
    url_imagen           VARCHAR(500) NOT NULL,
    porcentaje_descuento DECIMAL(5,2) DEFAULT 0.00
);

-- RESERVAS (se borran a los 7 días)
CREATE TABLE reservas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario  INT NOT NULL,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_usuario)  REFERENCES usuarios(id)
);

-- EVENTOS DE LIMPIEZA AUTOMÁTICA
SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS evt_borrar_reservas
    ON SCHEDULE EVERY 1 DAY
    DO
        DELETE FROM reservas WHERE fecha < DATE_SUB(NOW(), INTERVAL 7 DAY);

CREATE EVENT IF NOT EXISTS evt_borrar_pedidos
    ON SCHEDULE EVERY 1 DAY
    DO
        DELETE FROM pedidos WHERE fecha < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- PEDIDOS (se borran a los 30 días)
CREATE TABLE pedidos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario  INT NOT NULL,
    mensaje     VARCHAR(100) DEFAULT NULL,
    realizado   INT DEFAULT 0,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);