# 🚀 SETUP - Base de Datos AquaIncuba

## 📋 Resumen de lo implementado

Se han creado **20 migraciones** y **21 modelos Eloquent** para el sistema de control de calidad de agua en incubadoras.

## 🗄️ Tablas creadas

### Tablas Base
1. `empresas` - Datos de la empresa
2. `usuarios` - Usuarios del sistema
3. `roles` - Roles (administrador, operador, revisor)
4. `menus` - Menús de navegación jerárquicos
5. `roles_usuarios` - Relación usuario-rol por empresa
6. `permisos_menus_roles` - Permisos granulares por rol en menús

### Tablas MQTT
7. `configuraciones_mqtt` - Configuración del broker MQTT
8. `dispositivos_mqtt` - Dispositivos/PLCs conectados
9. `temas_mqtt` - Temas/canales MQTT
10. `logs_mqtt` - Datos recibidos en tiempo real

### Tablas Incubadoras y Sensores
11. `incubadoras` - Incubadoras/tanques de agua
12. `sensores` - Sensores configurables
13. `incubadoras_sensores` - Relación muchos-a-muchos
14. `lecturas_sensores` - Lecturas en tiempo real de sensores
15. `parametros_estudio` - Parámetros a medir en estudios

### Tablas Estudios de Calidad de Agua
16. `estudios_calidad_agua` - Estudios principales
17. `muestras_estudio` - Muestras tomadas durante el estudio
18. `datos_crudos_estudio` - Datos sin procesar del MQTT
19. `datos_procesados_estudio` - Datos analizados y calibrados
20. `conclusiones_estudio` - Conclusiones finales del estudio
21. `alertas_mqtt` - Alertas en tiempo real

## 📦 Modelos Creados

```
app/Models/
├── Empresa.php
├── Usuario.php
├── Rol.php
├── Menu.php
├── RolUsuario.php
├── PermisoMenuRol.php
├── ConfiguracionMqtt.php
├── DispositivoMqtt.php
├── TemaMqtt.php
├── LogMqtt.php
├── Incubadora.php
├── Sensor.php
├── IncubadoraSensor.php
├── LecturaSensor.php
├── EstudioCalidadAgua.php
├── MuestraEstudio.php
├── DatoCrudoEstudio.php
├── DatoProcessadoEstudio.php
├── ConclusionEstudio.php
├── ParametroEstudio.php
└── AlertaMqtt.php
```

## 🚀 Instrucciones para ejecutar

### 1. Actualizar archivo `.env`
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aquaincuba_unia
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Crear base de datos
```bash
mysql -u root -p -e "CREATE DATABASE aquaincuba_unia;"
```

### 3. Ejecutar migraciones
```bash
cd c:\Users\Usuario\Documents\Softronic\AquaIncuba_UNIA
php artisan migrate
```

### 4. Ejecutar seeders (datos iniciales)
```bash
php artisan db:seed
```

## 👤 Usuarios por defecto después del seeder

| Usuario | Correo | Contraseña | Rol |
|---------|--------|-----------|-----|
| Admin | admin@aquaincuba.com | password123 | Administrador |
| Operador 1 | operador@aquaincuba.com | password123 | Operador |
| Revisor 1 | revisor@aquaincuba.com | password123 | Revisor |

## 📊 Estructura de Menús

El sistema incluye 5 grupos de menús con submenús:

1. **DASHBOARD** - Analíticas y reportes
2. **ADMINISTRACIÓN** - Incubadoras, sensores, usuarios, roles
3. **ESTUDIOS** - Calidad de agua, muestras, reportes
4. **MONITOREO** - Lecturas en tiempo real, alertas, config MQTT
5. **CONFIGURACIÓN** - Empresa, perfil, sistema

## 🔧 Parámetros de Estudio precargados

- **TEMP** - Temperatura (°C): 25-30°C óptimo
- **PH** - pH: 6.8-7.5 óptimo
- **DISS_OXY** - Oxígeno Disuelto (ppm): 6.0-8.5 ppm óptimo
- **TURB** - Turbidez (NTU): 0-2 NTU óptimo
- **COND** - Conductividad (μS/cm): 100-500 μS/cm óptimo

## ⚙️ Configuración MQTT por defecto

Para agregar configuración MQTT a una empresa:
```php
ConfiguracionMqtt::create([
    'id_empresa' => 1,
    'host_broker' => '192.168.1.100',
    'puerto_broker' => 1883,
    'usuario' => 'user',
    'contraseña' => 'password',
    'id_cliente' => 'aquaincuba_client',
    'tema_base' => 'aquaincuba/unia'
]);
```

## 🔄 Flujo de datos

```
PLC/Sensores
    ↓
MQTT Broker (Tema: aquaincuba/unia/inc001/temp001)
    ↓
logs_mqtt (Datos en tiempo real)
    ↓
Si usuario inicia estudio:
    ├→ estudios_calidad_agua (Nuevo estudio)
    ├→ muestras_estudio (Muestras tomadas)
    ├→ datos_crudos_estudio (Datos del MQTT)
    ├→ datos_procesados_estudio (Análisis)
    └→ conclusiones_estudio (Informe)
```

## 📝 Comandos útiles

```bash
# Ver estado de migraciones
php artisan migrate:status

# Revertir todas las migraciones
php artisan migrate:reset

# Revertir y ejecutar nuevamente
php artisan migrate:refresh

# Revertir, ejecutar y ejecutar seeders
php artisan migrate:refresh --seed

# Ejecutar solo el seeder
php artisan db:seed
```

## ✅ Verificación

Después de ejecutar las migraciones, verificar que existan todas las tablas:

```bash
php artisan tinker
# Luego en la consola:
DB::table('empresas')->count();
DB::table('usuarios')->count();
DB::table('incubadoras')->count();
# etc...
```

---

**¡Sistema listo para usar!** 🎉
