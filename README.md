# README.md

## Estructura del Proyecto

Este proyecto es una aplicación web basada en Laravel que incluye autenticación de usuarios y diferentes roles con permisos específicos. A continuación se detalla la estructura y las rutas disponibles.

### Roles y Permisos

- **Usuarios autenticados**: Acceso básico al dashboard y gestión de perfil.
- **Administradores (`admin`)**: Gestión completa de usuarios.
- **Gestores (`gestor`)**: Acceso limitado a funciones específicas, incluyendo una gestión restringida de usuarios y notificaciones.

### Rutas Disponibles

#### Rutas Públicas
- `/`: Página de bienvenida.

#### Rutas de Autenticación
- Rutas generadas por `auth.php` (login, registro, recuperación de contraseña, etc.).

#### Rutas Protegidas (requieren autenticación)
- **Dashboard**:
  - `/dashboard`: Panel principal para usuarios autenticados.

- **Perfil de Usuario**:
  - `/profile`: Edición, actualización y eliminación del perfil.

- **Administrador** (requiere rol `admin`):
  - `/admin/users`: CRUD completo de usuarios (índice, creación, almacenamiento, edición, actualización, eliminación).

- **Gestor** (requiere rol `gestor`):
  - `/gestor/dashboard`: Panel principal para gestores.
  - `/gestor/users`: Listado de usuarios (solo lectura limitada).
  - `/gestor/users/{user}/edit`: Edición de usuarios (acceso limitado).
  - `/gestor/users/{user}`: Actualización de usuarios.
  - `/gestor/notifications`: Listado y envío de notificaciones.

### Controladores

- `DashboardController`: Maneja la vista principal del dashboard.
- `ProfileController`: Gestiona las operaciones relacionadas con el perfil del usuario.
- `Admin\UserController`: Controlador de recursos para la gestión de usuarios por parte del administrador.
- `GestorController`: Panel de control específico para gestores.
- `GestorUserController`: Gestión limitada de usuarios para gestores.
- `GestorNotificationController`: Maneja las notificaciones enviadas por gestores.

### Middlewares

- `auth`: Asegura que el usuario esté autenticado.
- `verified`: Verifica que el correo electrónico del usuario esté confirmado.
- `role`: Restringe el acceso basado en roles (`admin` o `gestor`).


### Instalación y Configuración

#### Clonar el repositorio, `git clone git@github.com:martinartacho/mhartacho.git `
#### Ejecutar `composer install` para instalar las dependencias. 
`npm install && npm run dev`  la primera vez.
`npm run dev` Las siguientes veces 
#### Configurar el archivo `.env` con los datos de la base de datos.
#### Ejecuta `php artisan key:generate`
##### Ejecutar las migraciones con `php artisan migrate`.
#### Opcional: Ejecutar los seeders para rellenar la BBDD con datos de prueba.
`php artisan db:seed`
o Ejecutar un seeder específico: `php artisan db:seed --class=NotificationsTableSeeder`


### Notas

- Asegúrese de que los roles `admin` y `gestor` estén correctamente configurados en el sistema de permisos.
- Las rutas comentadas en `web.php` pueden ser reactivadas según necesidades específicas.

Para más detalles, consulte la documentación de Laravel o los comentarios en el código fuente.

### SOBRE LA API
Esta API está desarrollada en Laravel y utiliza JWT para autenticación. Está desplegada en:

🔗 https://reservas.artacho.org/api

---

## 🔐 Autenticación (JWT)

### Login
**POST** `/api/login`

**Parámetros:**
```json
{
  "email": "usuario@example.com",
  "password": "123456"
}
```

**Respuesta:**
```json
{
  "access_token": "jwt_token",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

## 👤 Perfil del usuario

### Obtener usuario autenticado
**GET** `/api/me`  
**Header:** `Authorization: Bearer {token}`

### Actualizar perfil
**PUT** `/api/profile`  
**Body:** `{ "name": "Nuevo Nombre", "email": "nuevo@email.com" }`

---

## 🔒 Seguridad

### Cambiar contraseña
**PUT** `/api/change-password`  
```json
{
  "current_password": "anterior",
  "new_password": "nueva"
}
```

### Eliminar cuenta
**DELETE** `/api/delete-account`

---

## 🔔 Notificaciones

### Guardar token FCM
**POST** `/api/save-fcm-token`  
**Header:** `Authorization: Bearer {token}`  
```json
{
  "fcm_token": "firebase_token"
}
```

---

## 🧪 Test de logging
**GET** `/api/test-log`  
Genera un warning en `laravel.log`.

---

## ℹ️ Notas
- Las rutas protegidas requieren token JWT en la cabecera `Authorization`.
- No uses `curl -k` salvo para pruebas con certificados no verificados.

---

## Autor
Artacho DevTeam ✨