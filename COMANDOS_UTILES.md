# 🛠️ Comandos Útiles para AquaIncuba UNIA

## 🚀 Inicio y Configuración

### Instalación Inicial
```bash
# Clonar repositorio
git clone <url>
cd AquaIncuba_UNIA

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# DB_DATABASE=aquaincuba_unia
# DB_USERNAME=root
# DB_PASSWORD=

# Crear base de datos
mysql -u root -p -e "CREATE DATABASE aquaincuba_unia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## 💻 Comandos Artisan Diarios

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Hacer rollback última migración
php artisan migrate:rollback

# Rollback todas
php artisan migrate:reset

# Refresh (rollback + migrate)
php artisan migrate:refresh

# Refresh con seeders
php artisan migrate:refresh --seed

# Refrescar todo de cero
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Crear migración nueva
php artisan make:migration nombre_migracion --create=nombre_tabla

# Crear modelo con migración
php artisan make:model NombreModelo -m
```

### Modelos y Seeds
```bash
# Crear modelo
php artisan make:model NombreModelo

# Crear modelo con migración y factory
php artisan make:model NombreModelo -mf

# Crear factory
php artisan make:factory NombreFactory

# Crear seeder
php artisan make:seeder NombreSeeder

# Ejecutar seeder específico
php artisan db:seed --class=NombreSeeder

# Reseed todo
php artisan db:seed
```

### Controladores
```bash
# Crear controlador
php artisan make:controller NombreController

# Controlador con métodos CRUD
php artisan make:controller NombreController --resource

# Controlador en subdirectorio
php artisan make:controller Admin/NombreController --resource

# Controlador API (sin create/edit)
php artisan make:controller API/NombreController --api
```

### Middleware
```bash
# Crear middleware
php artisan make:middleware NombreMiddleware

# Registrar en Kernel.php
# protected $middleware = [...]
# protected $middlewareGroups = [...]
```

## 🔍 Testing y Validación

### Tests
```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test tests/Feature/AuthenticationTest.php
php artisan test tests/Unit/Models/UsuarioTest.php

# Con coverage
php artisan test --coverage

# Coverage HTML
php artisan test --coverage --coverage-html=coverage

# Apenas rápido
php artisan test --no-coverage

# Debug detallado
php artisan test -v
```

### Linting y Análisis
```bash
# Verificar código con Pint (PHP)
./vendor/bin/pint

# Análisis de seguridad
./vendor/bin/phpstan analyse app/

# Verificar sintaxis
php -l app/Models/Usuario.php
```

## 📊 Cache y Compilación

### Cache
```bash
# Limpiar todo cache
php artisan cache:clear

# Limpiar config cache
php artisan config:clear

# Limpiar view cache
php artisan view:clear

# Limpiar route cache
php artisan route:clear

# Regenerar autoload
composer dump-autoload

# Optimizar con cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Assets
```bash
# Build para desarrollo
npm run dev

# Build para producción
npm run build

# Watch para desarrollo
npm run watch

# Limpiar build
rm -rf public/build/
```

## 📝 Logs y Debug

### Logs
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Últimas 100 líneas
tail -100 storage/logs/laravel.log

# Ver logs más antiguos
ls -la storage/logs/

# Limpiar logs
rm storage/logs/laravel.log
```

### Debug
```bash
# Activar debug en .env
# APP_DEBUG=true
# APP_ENV=local

# Desactivar en producción
# APP_DEBUG=false
# APP_ENV=production

# Ver info de aplicación
php artisan about

# Listar comandos disponibles
php artisan list

# Ayuda de comando
php artisan make:model --help
```

## 🗄️ Base de Datos

### MySQL Directamente
```bash
# Conectar a MySQL
mysql -u root -p

# En MySQL:
USE aquaincuba_unia;
SHOW TABLES;
DESC usuarios;
SELECT COUNT(*) FROM usuarios;

# Backup de BD
mysqldump -u root -p aquaincuba_unia > backup.sql

# Restaurar backup
mysql -u root -p aquaincuba_unia < backup.sql
```

### Desde Laravel Tinker
```bash
# Abrir Tinker
php artisan tinker

# En Tinker:
>>> $usuarios = App\Models\Usuario::all();
>>> $usuarios->count();
>>> $usuario = App\Models\Usuario::first();
>>> $usuario->empresa;
>>> App\Models\Incubadora::where('id_empresa', 1)->count();
>>> $rol = App\Models\Rol::where('nombre', 'administrador')->first();
>>> $rol->usuarios;
```

## 🌐 Routes y URLs

### Ver Rutas
```bash
# Listar todas las rutas
php artisan route:list

# Filtrar por nombre
php artisan route:list --name=usuarios

# Ver detalles de ruta específica
php artisan route:list --name=usuarios.index
```

### URLs Comunes
```
http://localhost:8000/login                 # Login
http://localhost:8000/dashboard              # Dashboard
http://localhost:8000/usuarios              # Usuarios
http://localhost:8000/incubadoras           # Incubadoras
http://localhost:8000/sensores              # Sensores
http://localhost:8000/estudios              # Estudios
http://localhost:8000/roles                 # Roles
http://localhost:8000/empresa               # Empresa
```

## 📦 Composer y NPM

### Composer
```bash
# Instalar dependencias
composer install

# Actualizar dependencias
composer update

# Instalar paquete específico
composer require nombre/paquete

# Instalar para desarrollo
composer require --dev nombre/paquete

# Remover paquete
composer remove nombre/paquete

# Autoload regenerate
composer dump-autoload
```

### NPM
```bash
# Instalar dependencias
npm install

# Instalar paquete específico
npm install nombre-paquete

# Instalar para desarrollo
npm install --save-dev nombre-paquete

# Actualizar paquetes
npm update

# Ver dependencias
npm list
```

## 🔧 Desarrollo

### Crear Estructura de Modelos y Migraciones
```bash
# Crear modelo + migración + controller + factory + seeder
php artisan make:model NombreModelo -m -c -f --seed

# -m: Migración
# -c: Controlador
# -f: Factory
# --seed: Seeder
```

### Crear Formulario/Vista
```bash
# Crear vista manualmente
touch resources/views/admin/nuevo/create.blade.php
touch resources/views/admin/nuevo/edit.blade.php
```

### Tinker para Testing
```bash
php artisan tinker

# Crear usuario
>>> $usuario = App\Models\Usuario::create(['nombre' => 'Test', 'correo' => 'test@test.com', 'contraseña' => Hash::make('password'), 'id_empresa' => 1])

# Verificar relaciones
>>> $usuario->rolesUsuarios
>>> $usuario->empresa

# Crear entrada
>>> $incubadora = App\Models\Incubadora::create(['nombre' => 'Test', 'codigo' => 'TEST-001', 'id_empresa' => 1])

# Verificar
>>> App\Models\Incubadora::all()
```

## 📋 Workflow Típico

### 1. Crear Nueva Entidad
```bash
# Crear modelo con migración
php artisan make:model NuevaEntidad -m

# Crear controlador
php artisan make:controller Admin/NuevaEntidadController --resource

# Crear factory para testing
php artisan make:factory NuevaEntidadFactory

# Crear seeder
php artisan make:seeder NuevaEntidadSeeder

# Crear migraciones secundarias si es necesario
php artisan make:migration create_nueva_entidad_relacionada_table --create=nueva_entidad_relacionada

# Ejecutar migraciones
php artisan migrate

# Crear vistas
# resources/views/admin/nueva_entidad/{index,create,edit}.blade.php

# Agregar rutas a routes/web.php
# Route::resource('nueva-entidad', NuevaEntidadController::class)
```

### 2. Testing Local
```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear

# Ejecutar tests
php artisan test

# Ver logs
tail -f storage/logs/laravel.log

# Usar Tinker para probar
php artisan tinker
```

### 3. Preparar para Producción
```bash
# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar dev
npm run build
composer dump-autoload -o

# Verificar .env
# APP_DEBUG=false
# APP_ENV=production

# Generar link de storage
php artisan storage:link

# Migrar BD
php artisan migrate --force
```

## 🆘 Troubleshooting

### Errores Comunes
```bash
# "Class not found"
composer dump-autoload

# "No routes registered"
php artisan route:clear
php artisan route:cache

# "View not found"
php artisan view:clear

# "Cache problems"
php artisan cache:clear
php artisan config:clear

# "Storage permission"
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# "Node modules issue"
rm -rf node_modules
npm install
npm run build
```

### Ver Versiones
```bash
# Versión de PHP
php -v

# Versión de Laravel
php artisan --version

# Versión de MySQL
mysql --version

# Versión de Composer
composer --version

# Versión de NPM
npm --version
```

## 🎯 Comandos Rápidos

```bash
# Ejecutar todo de cero
php artisan migrate:fresh --seed && npm run build

# Limpiar completamente
php artisan cache:clear && php artisan config:clear && php artisan view:clear && php artisan route:clear

# Testing rápido
php artisan test --no-coverage

# Ver resumen proyecto
php artisan about
```

---

**Última actualización**: Enero 2025  
**Compatible con**: Laravel 11, PHP 8.1+
