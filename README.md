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

```bash
git clone https://github.com/KevinMoscoso/erpia.git
cd erpia
