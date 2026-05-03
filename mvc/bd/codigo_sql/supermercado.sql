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

-- VERIFICACIONES DE EMAIL (registros pendientes de confirmar)
CREATE TABLE verificaciones_email (
    token      VARCHAR(64)  PRIMARY KEY,
    nombre     VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    expira_en  DATETIME     NOT NULL
);


-- PRODUCTOS
CREATE TABLE productos (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    nombre               VARCHAR(150) NOT NULL,
    stock                DECIMAL(10,1) DEFAULT 0,
    precio               DECIMAL(10,2) NOT NULL,
    precio_por_peso      BIT DEFAULT 0,
    categoria            VARCHAR(80),
    subcategoria         VARCHAR(80) NULL,
    url_imagen           VARCHAR(500) NOT NULL,
    porcentaje_descuento INT(2) DEFAULT 0,
    inicio               BIT DEFAULT 0
);

-- RESERVAS (se borran a los 7 días)
CREATE TABLE reservas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario  INT NOT NULL,
    cantidad    DECIMAL NOT NULL,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario)  REFERENCES usuarios(id)  ON DELETE CASCADE
);

-- COMIDAS (catálogo de comidas que se pueden pedir)
CREATE TABLE comidas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL,
    precio      DECIMAL(10,2) NOT NULL,
    disponible  BIT DEFAULT 1,
    url_imagen  VARCHAR(500) DEFAULT NULL
);

-- PEDIDOS (se borran a los 30 días)
CREATE TABLE pedidos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario    INT NOT NULL,
    id_comida     INT NOT NULL,
    cantidad      INT NOT NULL,
    mensaje       VARCHAR(100) DEFAULT NULL,
    fecha_entrega DATE DEFAULT NULL,
    realizado     INT DEFAULT 0,
    fecha         DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_comida)  REFERENCES comidas(id)  ON DELETE CASCADE
);

-- Migración: añadir fecha_entrega si la tabla ya existe
ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS fecha_entrega DATE DEFAULT NULL AFTER mensaje;

-- EVENTOS DE LIMPIEZA AUTOMÁTICA
SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS evt_borrar_reservas
    ON SCHEDULE EVERY 1 DAY
    DO
        DELETE FROM reservas WHERE fecha < DATE_SUB(NOW(), INTERVAL 7 DAY);

CREATE EVENT IF NOT EXISTS evt_borrar_pedidos
    ON SCHEDULE EVERY 1 DAY
    DO
        DELETE FROM pedidos WHERE realizado = 1 AND fecha < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- TRIGGERS
DELIMITER //

CREATE TRIGGER trg_reserva_insert
AFTER INSERT ON reservas
FOR EACH ROW
BEGIN
    UPDATE productos SET stock = stock - NEW.cantidad WHERE id = NEW.id_producto;
END//

CREATE TRIGGER trg_reserva_delete
AFTER DELETE ON reservas
FOR EACH ROW
BEGIN
    UPDATE productos SET stock = stock + OLD.cantidad WHERE id = OLD.id_producto;
END//

DELIMITER ;