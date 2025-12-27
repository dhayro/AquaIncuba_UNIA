# AquaIncuba UNIA - Guía Rápida de Inicio

## 🚀 Inicio Rápido (5 minutos)

### 1. Configuración Base
```bash
# Crear base de datos
mysql -u root -p -e "CREATE DATABASE aquaincuba_unia;"

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (crea datos de prueba)
php artisan db:seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

### 2. Acceder a la Aplicación

**URL**: http://localhost:8000/login

**Credenciales de Prueba**:
```
Email: admin@aquaincuba.com
Contraseña: password123
```

## 📊 Datos Creados por el Seeder

### Empresa
- **Nombre**: Acuacultura XYZ
- **RFC**: ACUA230101A12
- **Email**: admin@aquaincuba.com

### Usuario Admin
- **Nombre**: Administrador Sistema
- **Email**: admin@aquaincuba.com
- **Rol**: Administrador
- **Contraseña**: password123

### Usuarios Adicionales
1. **Juan Pérez** (juan@aquaincuba.com)
   - Rol: Operador
   - Contraseña: password123

2. **María García** (maria@aquaincuba.com)
   - Rol: Revisor
   - Contraseña: password123

### Roles
1. **administrador** - Acceso completo
2. **operador** - Crear y editar estudios
3. **revisor** - Solo lectura de reportes

### Menús (20 menús jerárquicos)
- Dashboard
- Administración
  - Usuarios
  - Empresa
  - Roles y Permisos
- Operaciones
  - Incubadoras
  - Sensores
  - Estudios
  - Lecturas
- Reportes
  - Estudios Finalizados
  - Conclusiones

### Parámetros de Estudio (5 parámetros)
1. **TEMP** - Temperatura (°C) [20, 30]
2. **pH** - Potencial Hidrógeno [6.5, 8.5]
3. **DISS_OXY** - Oxígeno Disuelto (mg/L) [5, 8]
4. **TURB** - Turbidez (NTU) [0, 5]
5. **COND** - Conductividad (mS/cm) [1000, 2000]

## 📋 Rutas Principales

| Función | URL | Acceso |
|---------|-----|--------|
| Login | /login | Público |
| Dashboard | /dashboard | Autenticado |
| Usuarios | /usuarios | Admin |
| Empresa | /empresa | Admin |
| Roles | /roles | Admin |
| Incubadoras | /incubadoras | Autenticado |
| Sensores | /sensores | Autenticado |
| Estudios | /estudios | Autenticado |

## ⚙️ Estructura de Directorios

```
storage/
└── public/
    └── logos/          → Logos de empresas

resources/views/
└── admin/
    ├── usuarios/       → CRUD de usuarios
    ├── roles/          → CRUD de roles y permisos
    ├── empresa/        → Info y edición de empresa
    ├── incubadoras/    → CRUD de incubadoras
    ├── sensores/       → CRUD de sensores
    ├── estudios/       → CRUD de estudios
    └── dashboard.blade.php  → Panel principal
```

## 🔐 Funcionalidades por Rol

### Administrador
- ✅ Gestionar usuarios
- ✅ Configurar empresa
- ✅ Crear y editar roles
- ✅ Asignar permisos por menú
- ✅ Todas las operaciones

### Operador
- ✅ Ver dashboard
- ✅ Crear y editar incubadoras
- ✅ Crear y editar sensores
- ✅ Crear y editar estudios
- ❌ Gestionar usuarios y roles
- ❌ Cambiar configuración empresa

### Revisor
- ✅ Ver dashboard
- ✅ Ver estudios finalizados
- ✅ Ver conclusiones
- ❌ Crear o editar registros
- ❌ Cambiar configuración

## 💡 Casos de Uso Comunes

### 1. Crear Nueva Incubadora
1. Ir a Incubadoras → Nuevo
2. Llenar formulario (nombre, código, volumen, parámetros)
3. Guardar
4. Ir a Incubadoras → Asignar Sensores
5. Seleccionar sensores disponibles

### 2. Crear Estudio de Calidad
1. Ir a Estudios → Nuevo
2. Seleccionar incubadora
3. Ingresar nombre, descripción, fechas
4. Definir número de muestras
5. El sistema crea automáticamente las muestras
6. Completar datos crudos y procesados manualmente o vía MQTT

### 3. Gestionar Permisos de Rol
1. Ir a Roles
2. Seleccionar rol
3. Clic en "Permisos"
4. Marcar/desmarcar permisos (Ver, Crear, Editar, Eliminar) por menú
5. Guardar cambios

### 4. Crear Nuevo Usuario
1. Ir a Usuarios → Nuevo
2. Ingresar nombre, email, contraseña
3. Asignar uno o más roles
4. Guardar
5. El usuario puede ingresar con las credenciales

## 🐛 Solución de Problemas

### Error: "SQLSTATE[HY000]: General error"
```bash
# Solución: Limpiar cache y ejecutar migraciones nuevamente
php artisan cache:clear
php artisan config:clear
php artisan migrate:fresh --seed
```

### Error: "Class ... not found"
```bash
# Solución: Regenrear autoload de composer
composer dump-autoload
```

### Assets no se muestran (CSS/JS)
```bash
# Solución: Compilar assets nuevamente
npm run build
# o en desarrollo
npm run dev
```

### No puedo subir logo a empresa
```bash
# Solución: Crear directorio y dar permisos
mkdir -p storage/public/logos
chmod -R 755 storage
```

## 📞 Contacto y Soporte

- **Documentación completa**: Ver archivo `DOCUMENTACION.md`
- **Base de datos**: Esquema completo en migrations
- **Email de soporte**: admin@aquaincuba.com

## ✅ Checklist de Implementación

- [x] Migraciones creadas (20 tablas)
- [x] Modelos Eloquent (21 modelos)
- [x] Controladores (7 controladores)
- [x] Vistas Blade (20+ vistas)
- [x] Sistema de autenticación
- [x] RBAC (Roles y permisos)
- [x] Validaciones en formularios
- [x] Dashboard con estadísticas
- [x] Seeder con datos de prueba
- [x] Documentación completa

## 🔄 Próximos Pasos (Fase 2)

- [ ] Integración real con MQTT
- [ ] Endpoint para recibir datos de PLC
- [ ] Procesamiento automático de datos
- [ ] Alertas por email/SMS
- [ ] Reportes PDF exportables
- [ ] Gráficos de tendencias
- [ ] API REST para terceros
- [ ] Autenticación 2FA
- [ ] Auditoría de cambios
- [ ] Backup automático de BD

---

**Última actualización**: Enero 2025
**Versión**: 1.0.0 - Beta
