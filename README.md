# ERPia — ERP en PHP (MVC) + MySQL, desarrollado iterativamente con apoyo de IA

ERPia es un ERP web ligero construido en **PHP 8+** con un **MVC propio**, diseñado para ser **funcional, seguro y fácil de instalar** en cualquier computador con PHP + MySQL. El proyecto incluye módulos operativos típicos de un ERP (ventas, compras, inventario), además de **RBAC (roles/permisos)** y **auditoría** para trazabilidad.

Repositorio (rama estable): https://github.com/KevinMoscoso/erpia (usar `main`)

---

## Características

- **Facturación** (Facturas + Detalles + Pagos) con estados: `BORRADOR`, `EMITIDA`, `PAGADA`, `ANULADA`
- **Inventario + Kardex** (movimientos: entrada/salida/ajuste) con **stock seguro** (no permite negativo)
- **Compras** (Compras + Detalles) con entradas a inventario
- **Seguridad RBAC** (usuarios, roles, permisos) y **UI por permisos**
- **Auditoría visible** (filtro por usuario y fechas)
- **Reportes** (por fechas/estado) según los permisos

---

## Requisitos

- **PHP 8.0+**
- Extensiones PHP: `pdo`, `pdo_mysql`
- **MySQL / MariaDB**
- **Composer**
- Servidor web:
  - Recomendado: **Apache** (XAMPP / Laragon / WAMP)
  - Alternativa: servidor embebido de PHP (para pruebas)

---

## Instalación (paso a paso)

### Paso 1 — Clonar el proyecto (opción B)

```bash
git clone https://github.com/KevinMoscoso/erpia.git
cd erpia
git checkout main
```

> Nota: `main` debe ser la rama estable para que cualquiera pueda usar el sistema sin ajustes adicionales.

---

### Paso 2 — Instalar dependencias (Composer)

Para uso normal (usuario final / demo / producción):

```bash
composer install --no-dev
```

Para desarrollo (si estás contribuyendo o agregando tooling):

```bash
composer install
```

> ¿Es obligatorio `--no-dev`? No. Es recomendable para usuario final porque instala solo lo necesario.

---

### Paso 3 — Crear la base de datos

Crea un schema vacío llamado `erpia` (recomendado `utf8mb4`):

```sql
CREATE DATABASE erpia CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

---

### Paso 4 — Importar la base de datos inicial (instalación “tipo ERP”)

Para que cualquiera instale ERPia de forma consistente, el repositorio incluye un SQL de instalación **limpio**:

- `database/erpia_clean.sql`

Ese archivo debe contener:
- **todas las tablas** con columnas, índices y **foreign keys**,
- **datos mínimos**: roles, permisos, rol_permiso, y un usuario admin inicial,
- **sin datos de pruebas** (no clientes/facturas/compras reales).

#### Importación usando MySQL Workbench (recomendado)

1. Abre **MySQL Workbench** y conéctate a tu servidor.
2. Crea el schema `erpia` (si no lo hiciste).
3. Selecciona `erpia` como schema por defecto (doble click para que quede en negrita).
4. Ve a **File → Open SQL Script** y abre:
   - `database/erpia_clean.sql`
5. Ejecuta el script completo (ícono del rayo ⚡ / Execute).

---

### Paso 5 — Configurar credenciales (cada usuario usa las suyas)

Edita:

- `config/config.php`

Ejemplo:

```php
<?php

return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'erpia',
        'user' => 'root',
        'password' => 'TU_PASSWORD',
        'charset' => 'utf8mb4'
    ]
];
```

---

### Paso 6 — Ejecutar el proyecto

#### Servidor embebido de PHP

Desde la raíz del proyecto:

```bash
php -S localhost:8000 -t public
```

Abre:

```text
http://localhost:8000
```

---

### Paso 7 — Acceso inicial

El SQL de instalación crea un admin inicial:

- **Email:** `admin@erpia.local`
- **Contraseña:** `admin`

Pasos recomendados al primer ingreso:
1. Inicia sesión con el admin.
2. Cambia la contraseña.
3. Crea usuarios reales (VENTAS / INVENTARIO / COMPRAS) y asigna roles.
4. Verifica que el dashboard muestre accesos según permisos.

---

## Verificación rápida (post-instalación)

- Puedes abrir el dashboard y navegar según permisos del usuario.
- Comprueba que:
  - `Inventario` registra movimientos y respeta stock no negativo.
  - `Compras` incrementa stock al agregar detalles.
  - `Auditoría` registra acciones y permite filtrar por fechas/usuario.
  - `Reportes` funcionan con filtros simples (fechas/estado).

---

## Solución de problemas (rápido)

- **“Error de conexión DB”**
  - Revisa `config/config.php` (host, usuario, password).
  - Confirma que MySQL está encendido.
  - Confirma que importaste `database/erpia_clean.sql` en el schema correcto (`erpia`).

- **Error 500 o “Application error”**
  - Asegúrate de haber ejecutado `composer install`.
  - Verifica que el servidor apunte a `public/`.
  - Revisa que PHP sea 8+.

---

## Autor

Kevin Moscoso — ERPia
