# 🛠️ Checklist de Despliegue - Proyecto Laravel Hartacho (reservas.artacho.org)

## ✅ Antes del despliegue
- [ ] Asegurarse de que todos los cambios estén **commiteados** y **pusheados** al repositorio remoto.
- [ ] Verificar que el proyecto funcione correctamente en local (login, rutas protegidas, JWT, etc.).
- [ ] Revisar si hay nuevas migraciones, seeds o variables `.env` necesarias.
- [ ] Actualizar el archivo `.env.production` en el servidor si se han agregado nuevas claves.

## 🚀 Despliegue en el servidor VPS
1. Conectarse por SSH al servidor:
   ```bash
   ssh usuario@IP_SERVIDOR
   ```

2. Navegar al proyecto:
   ```bash
   cd /var/www/reservas.artacho.org
   ```

3. Ejecutar los comandos de despliegue:
   ```bash
   git pull origin main
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan optimize
   ```

## 🔍 Verificaciones post-deploy
- [ ] Probar `/api/login` con credenciales válidas.
- [ ] Usar el token JWT recibido para consultar `/api/me`.
- [ ] Confirmar que no hay errores en los logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## ⚠️ Buenas prácticas
- No editar archivos directamente en producción.
- Si se hace un hotfix directo, documentarlo y sincronizarlo con el repositorio (`git pull` puede fallar si no).

## 📦 Revisión del sistema
- [ ] Certificados SSL configurados (revisar con `curl -k` solo si necesario).
- [ ] Verificar permisos de carpetas: `storage/`, `bootstrap/cache/`
- [ ] Verificar los cron jobs si existen (`php artisan schedule:run`).

## 🧪 Mantenimiento opcional
- [ ] Ejecutar tests si están disponibles: `php artisan test`
- [ ] Hacer respaldo de la base de datos si hay cambios mayores.

---

## Autor
Artacho DevOps ✨
