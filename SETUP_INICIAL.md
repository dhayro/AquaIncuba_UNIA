# 🚀 Setup Inicial - AquaIncuba UNIA

## Paso 1: Instalar Dependencias

```bash
composer install
npm install
```

## Paso 2: Crear Archivo .env

```bash
cp .env.example .env
```

Luego edita `.env` y asegúrate de que:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aquaincuba_unia
DB_USERNAME=root
DB_PASSWORD=
```

## Paso 3: Generar Clave de Aplicación

```bash
php artisan key:generate
```

## Paso 4: Crear Base de Datos

```bash
# En MySQL, crea la base de datos
mysql -u root -p
```

Luego en el cliente MySQL:
```sql
CREATE DATABASE aquaincuba_unia;
EXIT;
```

## Paso 5: Ejecutar Migraciones

```bash
php artisan migrate
```

## Paso 6: Ejecutar Seeders (Datos Iniciales)

```bash
php artisan db:seed
```

O si quieres ser más específico:
```bash
php artisan db:seed --class=DatabaseSeeder
```

## Paso 7: Compilar Assets (Vite)

```bash
npm run dev
```

En otra terminal:
```bash
npm run build
```

## Paso 8: Iniciar Servidor Laravel

```bash
php artisan serve
```

---

## ✅ Resumen de Comandos (Copia y Pega)

```bash
# 1. Instalar dependencias
composer install
npm install

# 2. Copiar .env
cp .env.example .env

# 3. Generar clave
php artisan key:generate

# 4. Crear BD (ejecuta esto en MySQL primero)
# CREATE DATABASE aquaincuba_unia;

# 5. Migrar BD
php artisan migrate

# 6. Sembrar datos iniciales
php artisan db:seed

# 7. Compilar assets (en terminal 2)
npm run dev

# 8. Iniciar servidor (en terminal 3)
php artisan serve
```

---

## 🔐 Usuario de Acceso

**Email:** `admin@aquaincuba.com`  
**Contraseña:** `password123`  
**Rol:** Administrador  
**Empresa:** AquaIncuba UNIA

---

## 🌐 Acceso al Sistema

```
URL Local:        http://localhost:8000
Login:            http://localhost:8000/login
Dashboard:        http://localhost:8000/dashboard
```

---

## ⚡ Verificación Rápida

Después de iniciar el servidor, verifica que todo funciona:

1. **Accede a** `http://localhost:8000/login`
2. **Ingresa:**
   - Email: `admin@aquaincuba.com`
   - Contraseña: `password123`
3. **Deberías ver** el Dashboard con estadísticas

---

## 🆘 Si Algo Falla

### Error de BD
```bash
# Resetea la BD y comienza de nuevo
php artisan migrate:refresh --seed
```

### Cache Problem
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Port Already in Use
```bash
php artisan serve --port=8001
```

### Permisos (en Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📊 Estructura de Datos

Después de las migraciones y seeds, tendrás:

### Datos Iniciales
- **1 Usuario Admin** (admin@aquaincuba.com)
- **1 Empresa** (AquaIncuba UNIA)
- **3 Roles** (Administrador, Operador, Revisor)
- **2 Incubadoras** de ejemplo
- **5 Sensores** de ejemplo
- **1 Estudio** de prueba

### Estructura de BD
- 20 tablas principales
- 40+ índices para performance
- Relaciones Many-to-Many configuradas
- Timestamps en todas las tablas

---

## 🎯 Próximos Pasos

1. **Loguearse** con las credenciales del admin
2. **Explorar Dashboard** para ver estadísticas
3. **Navegar por Módulos** (Usuarios, Empresas, Incubadoras, Sensores, etc.)
4. **Crear Nuevos Registros** para familiarizarse con la interfaz
5. **Revisar Documentación** en INDEX.md para profundizar

---

## 📝 Notas Importantes

- El seeder crea datos de prueba automáticamente
- Las contraseñas se hashean con bcrypt
- El sistema es multi-tenant por empresa
- Los permisos se validan en cada controlador
- Las migraciones son reversibles con `php artisan migrate:rollback`

---

¡Sistema listo para iniciar! 🚀
