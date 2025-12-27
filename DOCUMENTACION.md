# AquaIncuba UNIA - Sistema de Gestión de Calidad de Agua

Sistema completo de gestión para monitoreo de calidad de agua en incubadoras de camarones y peces mediante integración MQTT, estudios de calidad paramétricos y gestión multi-empresa.

## 📋 Índice

1. [Características Principales](#características-principales)
2. [Estructura del Proyecto](#estructura-del-proyecto)
3. [Instalación y Configuración](#instalación-y-configuración)
4. [Base de Datos](#base-de-datos)
5. [Módulos Principales](#módulos-principales)
6. [API de Controladores](#api-de-controladores)
7. [Seguridad y Permisos](#seguridad-y-permisos)
8. [Rutas](#rutas)

## ✨ Características Principales

### 1. **Gestión Multi-Empresa**
- Soporte para múltiples empresas en una sola instancia
- Aislamiento de datos por empresa
- Configuración independiente por empresa

### 2. **Autenticación y Autorización**
- Sistema de login seguro con Hash de contraseñas
- Control de acceso basado en roles (RBAC)
- Permisos granulares a nivel de menú
- 3 roles predefinidos: Administrador, Operador, Revisor

### 3. **Gestión de Incubadoras**
- Creación y configuración de incubadoras/tanques
- Parámetros óptimos por incubadora (temperatura, pH, oxígeno)
- Asignación dinámica de sensores a incubadoras
- Relación muchos-a-muchos entre incubadoras y sensores

### 4. **Gestión de Sensores**
- Registro de sensores por tipo (temperatura, pH, oxígeno disuelto, turbidez, conductividad)
- Factor de calibración por sensor
- Rango de medición configurable
- Códigos únicos para identificación

### 5. **MQTT - IoT Integration**
- Configuración de conexiones MQTT
- Gestión de dispositivos PLC/Gateway
- Definición de tópicos y esquemas de datos
- Logging automático de mensajes MQTT
- Sistema de alertas basado en umbrales

### 6. **Estudios de Calidad de Agua**
- Creación de estudios paramétricos
- Múltiples muestras por estudio
- Recolección de datos crudos desde MQTT
- Procesamiento de datos con calibración
- Conclusiones automáticas y reportes

### 7. **Panel de Administración Completo**
- Dashboard con estadísticas en tiempo real
- Gestión de usuarios y roles
- Configuración de empresa y logo
- Historial de lecturas de sensores
- Seguimiento de estudios en progreso

## 📁 Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   └── LoginController.php
│   ├── Admin/
│   │   ├── UsuarioController.php
│   │   ├── EmpresaController.php
│   │   ├── RolController.php
│   │   ├── IncubadoraController.php
│   │   ├── SensorController.php
│   │   └── EstudioCalidadAguaController.php
│   └── DashboardController.php
├── Models/
│   ├── Empresa.php
│   ├── Usuario.php (Authenticatable)
│   ├── Rol.php
│   ├── Menu.php
│   ├── RolUsuario.php
│   ├── PermisoMenuRol.php
│   ├── Incubadora.php
│   ├── Sensor.php
│   ├── IncubadoraSensor.php
│   ├── LecturaSensor.php
│   ├── EstudioCalidadAgua.php
│   ├── MuestraEstudio.php
│   ├── DatoCrudoEstudio.php
│   ├── DatoProcessadoEstudio.php
│   ├── ConclusionEstudio.php
│   ├── ParametroEstudio.php
│   ├── ConfiguracionMqtt.php
│   ├── DispositivoMqtt.php
│   ├── TemaMqtt.php
│   ├── LogMqtt.php
│   └── AlertaMqtt.php
├── Providers/
│   └── AppServiceProvider.php
└── Helpers/
    └── helpers.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_companies_table.php
│   ├── 0001_01_01_000001_create_users_table.php
│   ├── 0001_01_01_000002_create_roles_table.php
│   ├── 0001_01_01_000003_create_menus_table.php
│   ├── 0001_01_01_000004_create_user_roles_table.php
│   ├── 0001_01_01_000005_create_role_menu_permissions_table.php
│   ├── 0001_01_01_000006_create_mqtt_configurations_table.php
│   ├── 0001_01_01_000007_create_mqtt_devices_table.php
│   ├── 0001_01_01_000008_create_mqtt_topics_table.php
│   ├── 0001_01_01_000009_create_mqtt_logs_table.php
│   ├── 0001_01_01_000010_create_incubators_table.php
│   ├── 0001_01_01_000011_create_sensors_table.php
│   ├── 0001_01_01_000012_create_incubator_sensors_table.php
│   ├── 0001_01_01_000013_create_sensor_readings_table.php
│   ├── 0001_01_01_000014_create_study_parameters_table.php
│   ├── 0001_01_01_000015_create_water_quality_studies_table.php
│   ├── 0001_01_01_000016_create_study_samples_table.php
│   ├── 0001_01_01_000017_create_study_raw_data_table.php
│   ├── 0001_01_01_000018_create_study_processed_data_table.php
│   ├── 0001_01_01_000019_create_study_conclusions_table.php
│   └── 0001_01_01_000020_create_mqtt_alerts_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── navbar.blade.php
│   ├── sidebar.blade.php
│   └── footer.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── auth/boxed/
    │   └── sign-in.blade.php
    ├── usuarios/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── roles/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── permisos.blade.php
    ├── empresa/
    │   ├── show.blade.php
    │   └── edit.blade.php
    ├── incubadoras/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── sensores.blade.php
    ├── sensores/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── estudios/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── show.blade.php

routes/
└── web.php
```

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 8.1+
- Laravel 11
- MySQL 8.0+
- Composer
- Node.js y npm (para assets)

### Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone <repositorio-url>
cd AquaIncuba_UNIA

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JavaScript
npm install

# 4. Crear archivo .env
cp .env.example .env

# 5. Generar key de la aplicación
php artisan key:generate

# 6. Configurar la base de datos en .env
DB_DATABASE=aquaincuba_unia
DB_USERNAME=root
DB_PASSWORD=tu_contraseña

# 7. Crear la base de datos
mysql -u root -p -e "CREATE DATABASE aquaincuba_unia;"

# 8. Ejecutar migraciones
php artisan migrate

# 9. Ejecutar seeders
php artisan db:seed

# 10. Compilar assets
npm run build

# 11. Iniciar servidor
php artisan serve
```

### Credenciales de Prueba

Después de ejecutar los seeders, puedes acceder con:

```
Email: admin@aquaincuba.com
Contraseña: password123
```

## 🗄️ Base de Datos

### Tablas Principales

#### **empresas**
- id (PK)
- nombre
- rfc
- correo
- telefono
- direccion, ciudad, estado, codigo_postal
- logo (ruta a storage)
- descripcion
- created_at, updated_at

#### **usuarios**
- id (PK)
- id_empresa (FK)
- nombre
- correo
- contraseña (hash)
- correo_verificado_en
- created_at, updated_at

#### **roles**
- id (PK)
- nombre (unique)
- descripcion
- created_at, updated_at

#### **roles_usuarios** (Pivot)
- id (PK)
- id_usuario (FK)
- id_rol (FK)
- id_empresa (FK)
- created_at, updated_at
- Unique: (usuario, rol, empresa)

#### **menus**
- id (PK)
- id_empresa (FK)
- nombre
- ruta
- icono
- nivel (0=grupo, 1=menú, 2=submenu)
- id_padre (self-referencing)
- orden
- created_at, updated_at

#### **permisos_menus_roles**
- id (PK)
- id_rol (FK)
- id_menu (FK)
- puede_ver
- puede_crear
- puede_editar
- puede_eliminar
- created_at, updated_at

#### **incubadoras**
- id (PK)
- id_empresa (FK)
- nombre
- codigo (unique)
- descripcion
- volumen_litros
- temperatura_optima
- ph_optimo
- oxigeno_disuelto_optimo
- created_at, updated_at

#### **sensores**
- id (PK)
- id_empresa (FK)
- nombre
- codigo (unique)
- tipo (enum: temperatura, ph, oxigeno_disuelto, turbidez, conductividad)
- unidad_medida
- rango_minimo
- rango_maximo
- factor_calibracion (default: 1)
- descripcion
- created_at, updated_at

#### **incubadoras_sensores** (Pivot)
- id (PK)
- id_incubadora (FK)
- id_sensor (FK)
- created_at, updated_at

#### **lecturas_sensores**
- id (PK)
- id_incubadora (FK)
- id_sensor (FK)
- valor_crudo
- valor_procesado
- created_at, updated_at

#### **estudios_calidad_agua**
- id (PK)
- id_incubadora (FK)
- nombre
- descripcion
- fecha_inicio
- fecha_fin
- created_at, updated_at

#### **muestras_estudio**
- id (PK)
- id_estudio (FK)
- numero_muestra
- created_at, updated_at

#### **datos_crudos_estudio**
- id (PK)
- id_muestra (FK)
- id_parametro (FK)
- id_sensor (FK)
- valor_crudo
- created_at, updated_at

#### **datos_procesados_estudio**
- id (PK)
- id_muestra (FK)
- id_parametro (FK)
- valor_procesado
- dentro_rango
- created_at, updated_at

#### **conclusiones_estudio**
- id (PK)
- id_estudio (FK)
- estado (aceptable/no_aceptable)
- observaciones
- recomendaciones
- created_at, updated_at

#### **parametros_estudio**
- id (PK)
- id_empresa (FK)
- nombre
- abreviatura
- valor_minimo
- valor_maximo
- created_at, updated_at

#### Tablas MQTT
- **configuraciones_mqtt**: Conexión MQTT por empresa
- **dispositivos_mqtt**: PLC/Gateway que envían datos
- **temas_mqtt**: Tópicos MQTT con esquemas de datos
- **logs_mqtt**: Historial de mensajes recibidos
- **alertas_mqtt**: Alertas basadas en umbrales de valores

## 📊 Módulos Principales

### 1. **Autenticación (LoginController)**

**Métodos:**
- `showLoginForm()` - Mostrar formulario de login
- `login(Request)` - Procesar login y crear sesión
- `logout()` - Destruir sesión de usuario

**Características:**
- Validación de credenciales
- Opción "Recuérdame"
- Redirección inteligente a dashboard si ya está autenticado
- Manejo de errores de autenticación

### 2. **Dashboard**

**Método:**
- `index()` - Cargar estadísticas y datos en tiempo real

**Datos Mostrados:**
- Total de incubadoras, sensores, estudios y usuarios
- Incubadoras activas con conteo de sensores
- Estudios en progreso
- Últimas 10 lecturas de sensores

### 3. **Gestión de Usuarios (UsuarioController)**

**Métodos RESTful:**
- `index()` - Listar usuarios paginados (15 por página)
- `create()` - Mostrar formulario de creación
- `store(Request)` - Guardar nuevo usuario con roles
- `edit(Usuario)` - Mostrar formulario de edición
- `update(Request, Usuario)` - Actualizar usuario y roles
- `destroy(Usuario)` - Eliminar usuario

**Validaciones:**
- Nombre requerido
- Email único y válido
- Contraseña mínimo 8 caracteres con confirmación
- Roles múltiples requeridos

### 4. **Gestión de Empresa (EmpresaController)**

**Métodos:**
- `show()` - Mostrar información de la empresa actual
- `edit()` - Mostrar formulario de edición
- `update(Request)` - Actualizar datos y subir logo

**Características:**
- Validación de RFC único
- Upload de logo (máx 2MB, formatos: JPEG, PNG, JPG, GIF)
- Almacenamiento en `storage/public/logos`

### 5. **Gestión de Roles (RolController)**

**Métodos:**
- `index()` - Listar roles con permisos asociados
- `create()` - Formulario de creación
- `store(Request)` - Crear nuevo rol
- `editPermisos(Rol)` - Mostrar permisos de un rol
- `actualizarPermisos(Request, Rol)` - Actualizar permisos granulares

**Permisos Granulares:**
- puede_ver (Ver/Leer)
- puede_crear (Crear)
- puede_editar (Editar)
- puede_eliminar (Eliminar)

### 6. **Gestión de Incubadoras (IncubadoraController)**

**Métodos RESTful:**
- `index()` - Listar incubadoras con conteo de sensores
- `create()` - Formulario de creación
- `store(Request)` - Crear incubadora
- `edit(Incubadora)` - Formulario de edición
- `update(Request, Incubadora)` - Actualizar
- `destroy(Incubadora)` - Eliminar incubadora y relaciones
- `asignarSensores(Incubadora)` - Interfaz para asignar sensores
- `guardarSensores(Request, Incubadora)` - Sincronizar sensores

**Validaciones:**
- Nombre y código únicos
- Volumen en litros positivo
- Parámetros óptimos numéricos

### 7. **Gestión de Sensores (SensorController)**

**Métodos RESTful:**
- `index()` - Listar sensores con tipo e información
- `create()` - Formulario de creación
- `store(Request)` - Crear sensor
- `edit(Sensor)` - Formulario de edición
- `update(Request, Sensor)` - Actualizar sensor
- `destroy(Sensor)` - Eliminar sensor y desvinculaciones

**Tipos de Sensores:**
- Temperatura
- pH
- Oxígeno Disuelto
- Turbidez
- Conductividad

**Parámetros:**
- Código único
- Rango mínimo/máximo
- Factor de calibración (default: 1)
- Descripción

### 8. **Estudios de Calidad (EstudioCalidadAguaController)**

**Métodos RESTful:**
- `index()` - Listar estudios con estado (activo/finalizado)
- `create()` - Formulario con selección de incubadora
- `store(Request)` - Crear estudio y muestras automáticas
- `show(Estudio)` - Ver detalles con muestras y datos
- `edit(Estudio)` - Editar información del estudio
- `update(Request, Estudio)` - Actualizar estudio
- `destroy(Estudio)` - Eliminar estudio y datos asociados

**Características:**
- Generación automática de muestras
- Relación con incubadora y sensores
- Historial de datos crudos y procesados
- Conclusiones automatizadas

## 🔒 Seguridad y Permisos

### Middleware de Autenticación
```php
Route::middleware('auth')->group(function () {
    // Rutas protegidas
});
```

### Control de Acceso en Controladores
Todos los controladores verifican que el usuario acceda solo a datos de su empresa:

```php
if ($recurso->id_empresa !== auth()->user()->id_empresa) {
    abort(403);
}
```

### Roles Predefinidos
1. **Administrador**: Acceso total a todas las funciones
2. **Operador**: Lectura de datos, creación de muestras
3. **Revisor**: Solo lectura de reportes y conclusiones

### Permisos por Menú
Cada menú puede tener permisos granulares por rol:
- Ver
- Crear
- Editar
- Eliminar

## 🛣️ Rutas

### Autenticación
```
GET  /login                    → Login formulario
POST /login                    → Procesar login
POST /logout                   → Cerrar sesión (auth)
GET  /                         → Redirección a dashboard o login
```

### Dashboard
```
GET  /dashboard                → Dashboard principal (auth)
```

### Usuarios
```
GET  /usuarios                 → Listar usuarios (auth)
GET  /usuarios/create          → Formulario crear (auth)
POST /usuarios                 → Guardar usuario (auth)
GET  /usuarios/{id}/edit       → Formulario editar (auth)
PUT  /usuarios/{id}            → Actualizar usuario (auth)
DELETE /usuarios/{id}          → Eliminar usuario (auth)
```

### Empresa
```
GET  /empresa                  → Ver información (auth)
GET  /empresa/editar           → Formulario editar (auth)
PUT  /empresa                  → Actualizar empresa (auth)
```

### Roles
```
GET  /roles                    → Listar roles (auth)
GET  /roles/create             → Formulario crear (auth)
POST /roles                    → Guardar rol (auth)
GET  /roles/{id}/permisos      → Gestionar permisos (auth)
PUT  /roles/{id}/permisos      → Actualizar permisos (auth)
```

### Incubadoras
```
GET  /incubadoras              → Listar incubadoras (auth)
GET  /incubadoras/create       → Formulario crear (auth)
POST /incubadoras              → Guardar incubadora (auth)
GET  /incubadoras/{id}/edit    → Formulario editar (auth)
PUT  /incubadoras/{id}         → Actualizar incubadora (auth)
DELETE /incubadoras/{id}       → Eliminar incubadora (auth)
GET  /incubadoras/{id}/sensores → Asignar sensores (auth)
PUT  /incubadoras/{id}/sensores → Guardar sensores (auth)
```

### Sensores
```
GET  /sensores                 → Listar sensores (auth)
GET  /sensores/create          → Formulario crear (auth)
POST /sensores                 → Guardar sensor (auth)
GET  /sensores/{id}/edit       → Formulario editar (auth)
PUT  /sensores/{id}            → Actualizar sensor (auth)
DELETE /sensores/{id}          → Eliminar sensor (auth)
```

### Estudios
```
GET  /estudios                 → Listar estudios (auth)
GET  /estudios/create          → Formulario crear (auth)
POST /estudios                 → Guardar estudio (auth)
GET  /estudios/{id}            → Ver detalles (auth)
GET  /estudios/{id}/edit       → Formulario editar (auth)
PUT  /estudios/{id}            → Actualizar estudio (auth)
DELETE /estudios/{id}          → Eliminar estudio (auth)
```

## 📝 Notas Adicionales

### Convenciones de Nombres
- Tablas en español con snake_case
- Modelos en español con PascalCase
- Rutas en español con kebab-case (plural)
- Métodos en camelCase

### Relaciones Importantes
```
Empresa → muchos Usuarios, Roles, Incubadoras, Sensores, Estudios
Usuario → muchos Roles (por empresa)
Rol → muchos Usuarios, muchos Menús
Incubadora → muchos Sensores, muchos Estudios
Sensor → muchos Incubadoras, muchas Lecturas
Estudio → muchas Muestras
Muestra → muchos Datos Crudos, muchos Datos Procesados
```

### Variables de Entorno Principales
```
APP_NAME=AquaIncuba
APP_ENV=local
DB_DATABASE=aquaincuba_unia
AUTH_MODEL=App\Models\Usuario
```

## 🤝 Soporte y Documentación

Para más información sobre Laravel 11, consulta:
- [Documentación Laravel](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

---

**Última actualización**: Enero 2025
**Versión**: 1.0.0
