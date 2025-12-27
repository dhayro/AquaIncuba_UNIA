# 🔄 Instrucciones para Refresh Seed

## ¿Qué hace el refresh seed?

El refresh seed:
1. Deshace todas las migraciones (borra la BD)
2. Ejecuta todas las migraciones nuevamente (recrea la estructura)
3. Ejecuta el seeder (rellena con datos de prueba)

**ADVERTENCIA:** Esto **borrará todos los datos** de tu base de datos local.

---

## 📋 Menús Incluidos Ahora

La estructura de menús se ha actualizado con:

### ADMINISTRACIÓN (nuevo menú agregado):
- Incubadoras
- Sensores
- Usuarios ← Aquí irían los usuarios
- Roles y Permisos
- **Menús y Permisos** ← NUEVO: Interfaz de administración de menús y permisos

---

## 🚀 Cómo Ejecutar el Refresh Seed

### Opción 1: Forma Segura (Recomendado)

```bash
# 1. Asegúrate de estar en el directorio del proyecto
cd c:\Users\Usuario\Documents\Softronic\AquaIncuba_UNIA

# 2. Ejecuta el refresh seed
php artisan migrate:refresh --seed

# 3. Verifica que se ejecutó correctamente
# Deberías ver: ✅ Seeder completado exitosamente
```

### Opción 2: Sin Migraciones (Solo si NO cambió la estructura de BD)

```bash
# Si solo quieres resetear los datos, sin volver a migrar:
php artisan db:seed
```

---

## 📝 Cambios en el DatabaseSeeder

### Mejoras Implementadas

✅ **Método `cleanData()`**
- Limpia datos en orden inverso de dependencias
- Desactiva/activa FOREIGN_KEY_CHECKS para evitar conflictos
- Muestra mensajes claros del progreso

✅ **Métodos Mejorados**
- Todos ahora usan `create()` en lugar de `firstOrCreate()`
- Mejor manejo de tipos (void, array, etc.)
- Mensajes informativos en cada paso

✅ **Nuevo Menú**
- "Menús y Permisos" agregado a ADMINISTRACIÓN
- URL: `/admin/menu-permissions`
- Icono: feather-menu
- Orden: 5

✅ **Mensajes de Progreso**
```
📋 Creando roles...
  ✓ Rol 'administrador' creado
  ✓ Rol 'operador' creado
  ✓ Rol 'revisor' creado
🏢 Creando empresa...
  ✓ Empresa 'AquaIncuba UNIA' creada
👥 Creando usuarios...
  ✓ Usuario 'Admin' (admin@aquaincuba.com) creado
  ...
✅ Seeder completado exitosamente
```

---

## 📊 Datos Que Se Crearán

### Roles (3)
- administrador
- operador
- revisor

### Usuarios (3)
- Admin (admin@aquaincuba.com) → Rol: administrador
- Operador 1 (operador@aquaincuba.com) → Rol: operador
- Revisor 1 (revisor@aquaincuba.com) → Rol: revisor

### Menús (19)
- **DASHBOARD** (1 submenú)
  - Dashboard
- **ADMINISTRACIÓN** (5 submenús)
  - Incubadoras
  - Sensores
  - Usuarios ← Aquí se listarían usuarios
  - Roles y Permisos
  - **Menús y Permisos** ← NUEVO
- **ESTUDIOS** (2 submenús)
  - Calidad de Agua
  - Parámetros
- **MONITOREO** (5 submenús)
  - Lecturas
  - Alertas
  - Dispositivos
  - Temas MQTT
  - Logs MQTT
- **CONFIGURACIÓN** (3 submenús)
  - Empresa
  - Perfil de Usuario
  - Sistema

### Parámetros de Estudio (5)
- Temperatura (°C)
- pH
- Oxígeno Disuelto (ppm)
- Turbidez (NTU)
- Conductividad (μS/cm)

---

## ✅ Verificación Después del Refresh

Después de ejecutar el comando, verifica:

### 1. Accede a la Aplicación
```
URL: http://localhost:8000
Usuario: admin@aquaincuba.com
Contraseña: password123
```

### 2. Verifica que Ves el Menú "Menús y Permisos"
- Haz clic en "ADMINISTRACIÓN" en el sidebar
- Deberías ver 5 opciones:
  1. Incubadoras
  2. Sensores
  3. Usuarios
  4. Roles y Permisos
  5. **Menús y Permisos** ← Aquí aparecerá

### 3. Accede a la Interfaz de Administración
```
URL: http://localhost:8000/admin/menu-permissions
```

Deberías ver:
- Dashboard con usuarios, roles y menús
- Tabla de usuarios
- Tabla de roles
- Estructura de menús

---

## 🔐 Permisos Configurados

### Administrador
✅ Acceso total a todos los menús
- Dashboard
- Incubadoras
- Sensores
- Usuarios
- Roles y Permisos
- Menús y Permisos (NUEVO)
- Calidad de Agua
- Parámetros
- Lecturas
- Alertas
- Dispositivos
- Temas MQTT
- Logs MQTT
- Empresa
- Perfil de Usuario
- Sistema

### Operador
✅ Acceso a menús de operación:
- Dashboard
- Incubadoras
- Sensores
- Lecturas
- Alertas
- Dispositivos

### Revisor
✅ Acceso a menús de revisión:
- Dashboard
- Calidad de Agua
- Parámetros
- Lecturas

---

## 🚨 Si Algo Sale Mal

### Problema: "SQLSTATE[23000]: Integrity constraint violation"
**Solución:** Asegúrate de que no hay datos en conflicto. Usa:
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Problema: "No such table: menus"
**Solución:** Las migraciones aún no se ejecutaron:
```bash
php artisan migrate
php artisan db:seed
```

### Problema: "Column not found"
**Solución:** Una migración no se ejecutó correctamente:
```bash
php artisan migrate:refresh
php artisan db:seed
```

---

## 📈 Verificación en Terminal

```bash
# Ver que el seeder se ejecutó correctamente
php artisan db:seed --verbose

# Verificar menús en la BD
php artisan tinker
>>> App\Models\Menu::count()  // Debería ser 19
>>> App\Models\Menu::where('nombre', 'Menús y Permisos')->first()  // Debería existir
>>> App\Models\Usuario::count()  // Debería ser 3
>>> App\Models\Rol::count()  // Debería ser 3
```

---

## 📚 Comandos Útiles

```bash
# Ver estructura de menús
php artisan tinker
>>> App\Models\Menu::where('nivel', 0)->with('submenus')->get()

# Ver permisos del rol administrador
>>> App\Models\Rol::where('nombre', 'administrador')->with('permisosMenus')->first()

# Ver usuarios y sus roles
>>> App\Models\Usuario::with('roles')->get()
```

---

## ✨ Resumen de Cambios

| Cambio | Antes | Después |
|--------|-------|---------|
| Menús ADMINISTRACIÓN | 4 | **5** (nuevo: Menús y Permisos) |
| Método de seed | firstOrCreate | **create()** |
| Limpieza de datos | No existía | **Agregada** |
| Mensajes de progreso | Ausentes | **Agregados** |
| Tipos de retorno | Implícitos | **Explícitos** |

---

## 🎯 Próximas Acciones

1. **Ejecuta el refresh:**
   ```bash
   php artisan migrate:refresh --seed
   ```

2. **Accede a la interfaz:**
   ```
   http://localhost:8000/admin/menu-permissions
   ```

3. **Prueba las funcionalidades:**
   - Editar roles de usuario
   - Editar permisos de rol
   - Ver estructura de menús

---

**Estado:** ✅ Listo para Refresh Seed  
**Último cambio:** 2024-12-26  
**Versión del Seeder:** 2.0 (Mejorado)
