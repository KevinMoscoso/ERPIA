# Erpia

**Erpia** es un sistema ERP experimental desarrollado como parte de un proyecto de tesis, orientado a pequeñas y medianas empresas. Permite gestionar facturación, inventario, compras, clientes, proveedores, reportes operativos y auditoría con seguridad RBAC.

---

## Características

- Módulos núcleo: Facturas, Clientes, Proveedores, Productos, Categorías, Pagos.
- Inventario con trazabilidad (kardex y stock en tiempo real).
- Compras con control de stock.
- Seguridad basada en roles y permisos (RBAC).
- Reportes operativos por fechas.
- Auditoría visible filtrable por usuario y fechas.
- Dashboard con KPIs relevantes.
- Arquitectura MVC en PHP con MySQL.

---

## Requisitos

- **PHP** 8.0 o superior
- **MySQL / MariaDB** 8+
- Extensiones PHP: pdo_mysql, mbstring, json, openssl
- Servidor web (Apache o Nginx)

---

## Instalación

### Clonar el repositorio
# ERPia

ERPia es un **ERP experimental desarrollado en PHP (MVC) + MySQL**, construido como parte de un **proyecto de tesis** enfocado en comparar el desarrollo de un ERP “humano” vs un ERP “generado/dirigido por IA”.

El sistema está orientado a pequeñas y medianas empresas y permite gestionar **facturación, inventario (kardex), compras, clientes, proveedores, reportes operativos y auditoría**, todo bajo un esquema de **seguridad RBAC (roles y permisos)**.

---

## Características principales

- **Módulos núcleo**
  - Facturas (estados: BORRADOR/EMITIDA/PAGADA/ANULADA)
  - Clientes, Proveedores
  - Productos, Categorías
  - Pagos (afecta estados de factura)
- **Inventario**
  - Kardex / movimientos (entradas, salidas, ajustes)
  - Validación de stock (no permite negativos)
- **Compras**
  - Compras + detalle
  - Entrada automática de stock por compra
- **Seguridad**
  - Login y sesiones
  - RBAC: Roles + Permisos
  - Auditoría de acciones críticas
- **Reportes / Visualización**
  - Reporte de facturas por rango de fechas
  - Auditoría visible filtrable por usuario y fechas
  - Dashboard con accesos según permisos

---

## Tecnologías

- PHP 8+
- MySQL / MariaDB
- PDO (prepared statements)
- Bootstrap 5 (CDN)
- Arquitectura MVC propia
- Composer (autoload PSR-4)

---

## Requisitos

- **PHP 8.0 o superior**
  - Extensiones recomendadas:
    - pdo_mysql
    - mbstring
    - json
    - openssl
- **MySQL / MariaDB**
- **MySQL Workbench** (para crear/importar la base)
- **Composer**
- Servidor web:
  - Apache (XAMPP / Laragon / WAMP) recomendado, o
  - Servidor embebido de PHP (para pruebas)

---

# Instalación (para cualquier computador)

## 1) Clonar el repositorio

Clona el proyecto y entra al directorio:

```bash
git clone https://github.com/KevinMoscoso/erpia.git
cd erpia

```bash
git clone https://github.com/KevinMoscoso/erpia.git
cd erpia
