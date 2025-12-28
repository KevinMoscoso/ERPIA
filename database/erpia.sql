/*
 ERPia - Base de datos inicial (instalación)
 - Diseñada para importarse en una base vacía (MySQL 8.x / MariaDB 10.x)
 - Contiene: estructura + semillas mínimas (roles, permisos, admin)
*/

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Desactivar checks para drops ordenados
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `pagos`;
DROP TABLE IF EXISTS `inventario_movimientos`;
DROP TABLE IF EXISTS `compra_detalles`;
DROP TABLE IF EXISTS `compras`;
DROP TABLE IF EXISTS `factura_detalles`;
DROP TABLE IF EXISTS `facturas`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `auditoria`;
DROP TABLE IF EXISTS `rol_permiso`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `permisos`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `proveedores`;
DROP TABLE IF EXISTS `categorias`;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================
-- TABLAS MAESTRAS
-- =========================

CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `proveedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permisos_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- SEGURIDAD / RBAC
-- =========================

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuarios_email` (`email`),
  KEY `idx_usuarios_rol_id` (`rol_id`),
  CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rol_permiso` (
  `rol_id` int NOT NULL,
  `permiso_id` int NOT NULL,
  PRIMARY KEY (`rol_id`,`permiso_id`),
  KEY `idx_rp_permiso_id` (`permiso_id`),
  CONSTRAINT `fk_rp_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rp_permisos` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `auditoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `accion` varchar(100) NOT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_auditoria_usuario_id` (`usuario_id`),
  KEY `idx_auditoria_created_at` (`created_at`),
  CONSTRAINT `fk_auditoria_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- PRODUCTOS / INVENTARIO
-- =========================

CREATE TABLE `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `proveedor_id` int DEFAULT NULL,
  `precio` decimal(20,2) NOT NULL,
  `stock` int NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_productos_categoria_id` (`categoria_id`),
  KEY `idx_productos_proveedor_id` (`proveedor_id`),
  CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `inventario_movimientos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int NOT NULL,
  `tipo` enum('ENTRADA','SALIDA','AJUSTE') NOT NULL,
  `cantidad` int NOT NULL,
  `referencia_tipo` enum('FACTURA','COMPRA','AJUSTE') NOT NULL,
  `referencia_id` int DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_inventario_producto` (`producto_id`),
  KEY `idx_inventario_referencia` (`referencia_tipo`,`referencia_id`),
  KEY `idx_inventario_created_at` (`created_at`),
  CONSTRAINT `fk_movimiento_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- FACTURACIÓN
-- =========================

CREATE TABLE `facturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `cliente_id` int NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `estado` enum('BORRADOR','EMITIDA','PAGADA','ANULADA') NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_facturas_numero` (`numero`),
  KEY `idx_facturas_cliente_id` (`cliente_id`),
  KEY `idx_facturas_fecha` (`fecha`),
  CONSTRAINT `fk_facturas_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `factura_detalles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_factura_detalle_factura` (`factura_id`),
  KEY `idx_factura_detalle_producto` (`producto_id`),
  CONSTRAINT `fk_factura_detalles_facturas` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_factura_detalles_productos` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pagos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura_id` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pagos_factura_id` (`factura_id`),
  KEY `idx_pagos_fecha` (`fecha`),
  CONSTRAINT `fk_pagos_facturas` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- COMPRAS
-- =========================

CREATE TABLE `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `proveedor_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('BORRADOR','CONFIRMADA','ANULADA') DEFAULT 'BORRADOR',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_compras_numero` (`numero`),
  KEY `idx_compras_proveedor_id` (`proveedor_id`),
  KEY `idx_compras_fecha` (`fecha`),
  CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `compra_detalles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compra_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_compra_detalle_compra` (`compra_id`),
  KEY `idx_compra_detalle_producto` (`producto_id`),
  CONSTRAINT `fk_detalle_compra` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- SEMILLAS MÍNIMAS (RBAC)
-- =========================

-- Roles
INSERT INTO `roles` (`id`,`nombre`) VALUES
(1,'ADMIN'),
(2,'VENTAS'),
(3,'INVENTARIO'),
(4,'COMPRAS'),
(5,'LECTOR');

-- Permisos
INSERT INTO `permisos` (`id`,`clave`,`descripcion`) VALUES
(1,'facturas.ver','Ver facturas'),
(2,'facturas.crear','Crear factura'),
(3,'facturas.editar','Editar factura'),
(4,'facturas.eliminar','Eliminar factura'),
(5,'facturas.detalle','Ver detalle factura'),
(6,'facturas.detalle.modificar','Modificar detalle factura'),
(7,'facturas.emitir','Emitir factura'),
(8,'facturas.anular','Anular factura'),
(9,'inventario.ver','Ver inventario'),
(10,'inventario.ajustar','Ajustar inventario'),
(11,'compras.ver','Ver compras'),
(12,'compras.crear','Crear compras'),
(13,'compras.detalle.modificar','Agregar detalle a compra'),
(14,'usuarios.gestionar','Gestionar usuarios'),
(15,'roles.gestionar','Gestionar roles y permisos del sistema');

-- Rol-Permiso (según tu matriz actual)
INSERT INTO `rol_permiso` (`rol_id`,`permiso_id`) VALUES
-- ADMIN: todo
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),
-- VENTAS
(2,1),(2,2),(2,3),(2,5),(2,6),(2,7),(2,9),
-- INVENTARIO
(3,1),(3,5),(3,9),(3,10),
-- COMPRAS
(4,9),(4,11),(4,12),(4,13),

-- Usuario admin inicial (password: admin)
INSERT INTO `usuarios` (`id`,`nombre`,`email`,`password`,`rol_id`,`activo`)
VALUES (1,'Admin','admin@erpia.local','$2b$12$WjcGkRoVoFthozk56gSZLeDoknncTk45fRR93GEnCneZFWZSEClHe',1,1);