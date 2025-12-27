# 📚 Referencia Técnica Rápida - Sistema de Menús y Permisos

## 🔗 URLs y Rutas

```
GET  /admin/menu-permissions                           → Dashboard principal
GET  /admin/menu-permissions/user/{user}/roles         → Editar roles de usuario
PUT  /admin/menu-permissions/user/{user}/roles         → Guardar roles de usuario
GET  /admin/menu-permissions/role/{role}/permissions   → Editar permisos de rol
PUT  /admin/menu-permissions/role/{role}/permissions   → Guardar permisos de rol
```

## 📁 Estructura de Archivos

```
app/
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── MenuPermissionController.php (138 líneas)

resources/
└── views/
    └── admin/
        └── menu-permissions/
            ├── index.blade.php (183 líneas)
            ├── user-roles.blade.php (102 líneas)
            └── role-permissions.blade.php (128 líneas)

routes/
└── web.php (líneas 93-101)

Documentation/
├── GUIA_MENU_PERMISOS.md (Guía de uso completa)
├── IMPLEMENTACION_MENU_PERMISOS.md (Resumen de implementación)
├── PRUEBAS_MENU_PERMISOS.md (Guía de pruebas)
└── REFERENCIA_TECNICA.md (Este archivo)
```

---

## 🧠 Modelos y Relaciones

### Usuario
```php
public function roles()
{
    return $this->hasManyThrough(
        Rol::class,
        RolUsuario::class,
        'id_usuario',  // FK en RolUsuario
        'id',          // PK en Rol
        'id',          // PK en Usuario
        'id_rol'       // FK hacia Rol en RolUsuario
    );
}
```

### Rol
```php
public function rolesUsuarios(): HasMany
{
    return $this->hasMany(RolUsuario::class, 'id_rol');
}

public function permisosMenus(): HasMany
{
    return $this->hasMany(PermisoMenuRol::class, 'id_rol');
}
```

### Menu
```php
public function padre(): BelongsTo
{
    return $this->belongsTo(Menu::class, 'id_padre');
}

public function submenus(): HasMany
{
    return $this->hasMany(Menu::class, 'id_padre');
}

public function permisosRoles(): HasMany
{
    return $this->hasMany(PermisoMenuRol::class, 'id_menu');
}
```

---

## 🗄️ Tablas de Base de Datos

### usuarios
```sql
id (PK)
name
email
id_empresa (FK)
...
```

### roles
```sql
id (PK)
nombre
descripcion
id_empresa (FK)
created_at
updated_at
```

### menus
```sql
id (PK)
nombre
url
nivel (0=grupo, 1=item)
id_padre (FK self)
orden
id_empresa (FK)
created_at
updated_at
```

### roles_usuarios (Pivot)
```sql
id (PK)
id_usuario (FK)
id_rol (FK)
id_empresa (FK)
```

### permisos_menus_roles (Pivot)
```sql
id (PK)
id_rol (FK)
id_menu (FK)
puede_ver (boolean)
puede_crear (boolean)
puede_editar (boolean)
puede_eliminar (boolean)
```

---

## 💻 Métodos del Controlador

### index()
```php
// Obtiene:
$usuarios    // Con relación de roles eager loaded
$roles       // Todos los roles de la empresa
$menus       // Todos los menús de la empresa
$mainMenus   // Menús nivel 0
$subMenus    // Menús nivel 1

// Devuelve:
view('admin.menu-permissions.index', [...])
```

### showRolePermissions($roleId)
```php
// Parámetro:
$roleId  // ID del rol a editar

// Obtiene:
$role        // El rol específico
$menus       // Todos los menús
$permissions // Array de IDs de menús permitidos

// Devuelve:
view('admin.menu-permissions.role-permissions', [...])
```

### updateRolePermissions($roleId)
```php
// Valida:
request()->validate([
    'permissions' => 'array',
    'permissions.*' => 'exists:menus,id',
])

// Actualiza:
PermisoMenuRol::where('id_rol', $roleId)->delete()
foreach (permissions as $menuId) {
    PermisoMenuRol::create(...)
}

// Redirige:
redirect()->route('menu-permissions.index')
         ->with('success', '...')
```

### showUserPermissions($userId)
```php
// Parámetro:
$userId  // ID del usuario a editar

// Obtiene:
$user      // El usuario con relación de roles
$allRoles  // Todos los roles disponibles
$userRoles // Array de IDs de roles actuales

// Devuelve:
view('admin.menu-permissions.user-roles', [...])
```

### updateUserRoles($userId)
```php
// Valida:
request()->validate([
    'roles' => 'array',
    'roles.*' => 'exists:roles,id',
])

// Actualiza:
RolUsuario::where('id_usuario', $userId)->delete()
foreach (roles as $roleId) {
    RolUsuario::create(...)
}

// Redirige:
redirect()->route('menu-permissions.index')
         ->with('success', '...')
```

---

## 🎨 Variables de Plantilla (Blade)

### index.blade.php
```
@foreach($usuarios as $usuario)
    $usuario->id
    $usuario->name
    $usuario->email
    $usuario->roles    // Colección de roles

@foreach($roles as $rol)
    $rol->id
    $rol->nombre

@foreach($menus as $menu)
    $menu->id
    $menu->nombre
    $menu->url
    $menu->nivel       // 0 o 1
    $menu->id_padre
    $menu->orden
```

### user-roles.blade.php
```
$user              // Usuario a editar
$user->name
$user->email
$user->id_empresa

$allRoles          // Roles disponibles
$userRoles         // IDs de roles actuales (array)
```

### role-permissions.blade.php
```
$role              // Rol a editar
$role->nombre

$menus             // Todos los menús

$permissions       // IDs de menús permitidos (array)
```

---

## 🔐 Seguridad

### Autenticación
```php
// Requerida en todas las rutas
protected $middleware = ['auth'];
```

### Multi-tenancy
```php
// Filtrado automático por empresa
auth()->user()->id_empresa

// Aplicado en:
- Carga de usuarios
- Carga de roles
- Carga de menús
- Creación de registros
```

### CSRF Protection
```php
// En todas las formas
@csrf
@method('PUT')  // Para PUT requests
```

### Validación
```php
// Valida que los IDs existan
'roles.*' => 'exists:roles,id'
'permissions.*' => 'exists:menus,id'
```

---

## 📊 Flujo de Datos

### Editar Roles de Usuario
```
GET /user/{id}/roles
        ↓
showUserPermissions()
        ↓
Carga Usuario con roles
        ↓
Carga todos los roles
        ↓
Renderiza formulario
        ↓
Usuario marca/desmarca roles
        ↓
PUT /user/{id}/roles
        ↓
updateUserRoles()
        ↓
Valida roles
        ↓
Elimina roles viejos
        ↓
Inserta roles nuevos
        ↓
Redirige a dashboard
```

### Editar Permisos de Rol
```
GET /role/{id}/permissions
        ↓
showRolePermissions()
        ↓
Carga Rol
        ↓
Carga menús jerarquizados
        ↓
Carga permisos actuales
        ↓
Renderiza formulario
        ↓
Usuario marca/desmarca menús
        ↓
JavaScript sincroniza grupos
        ↓
PUT /role/{id}/permissions
        ↓
updateRolePermissions()
        ↓
Valida menús
        ↓
Elimina permisos viejos
        ↓
Inserta permisos nuevos
        ↓
Redirige a dashboard
```

---

## 🎯 Componentes Clave

### JavaScript en role-permissions.blade.php
```javascript
// Al cambiar grupo, cambia todos los items
.group-checkbox.addEventListener('change', () => {
    document.querySelectorAll(`.item-checkbox[data-group="${groupId}"]`)
        .forEach(item => item.checked = isChecked)
})

// Al cambiar item, sincroniza el grupo
.item-checkbox.addEventListener('change', () => {
    const allItems = document.querySelectorAll(...)
    const checkedItems = document.querySelectorAll(...:checked)
    if (allItems.length === checkedItems.length)
        groupCheckbox.checked = true
    else
        groupCheckbox.checked = false
})
```

### Bootstrap Classes
```
.table-responsive     // Tablas responsivas
.badge              // Badges de información
.form-check-input   // Checkboxes estilo
.card               // Contenedores
.btn-primary        // Botones primarios
.btn-secondary      // Botones secundarios
.btn-link           // Botones de enlace
.alert-success      // Alertas de éxito
```

---

## 📈 Rendimiento

### Optimizaciones Implementadas
- Eager loading de relaciones: `with(['roles' => ...])`
- Filtrado en base de datos: `where('id_empresa', ...)`
- Paginación: No implementada (datos pequeños)
- Caché: No implementado (datos dinámicos)

### Posibles Mejoras
- Agregar paginación para muchos usuarios
- Implementar búsqueda/filtrado
- Agregar índices en BD para búsquedas frecuentes
- Implementar caché de permisos

---

## 🧪 Testing

### Unit Tests (Recomendado)
```php
test('usuario puede asignar roles')
test('rol puede asignar menús')
test('validación de datos')
test('filtrado por empresa')
```

### Integration Tests
```php
test('flujo completo de edición de roles')
test('flujo completo de edición de permisos')
```

---

## 📝 Logs y Debug

### Ver logs
```bash
tail -f storage/logs/laravel.log
```

### Debug en Browser
```javascript
// Consola del navegador
console.log('checkboxes:', document.querySelectorAll('input[type="checkbox"]'))
```

### Debug en Tinker
```bash
php artisan tinker
>>> $user = App\Models\Usuario::find(1);
>>> $user->roles()->get();
>>> App\Models\Menu::where('nivel', 0)->get();
```

---

## 🔧 Configuración

### .env (No modificar para esto)
```
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
```

### config/app.php (No modificar para esto)
```
'timezone' => 'America/Argentina/Buenos_Aires'
'locale' => 'es'
```

---

## 📚 Referencias Relacionadas

- MenuHelper: `app/Helpers/helpers.php`
- Sidebar dinámico: `resources/views/layouts/sidebar.blade.php`
- Model Relationships: Laravel Docs
- Blade Templating: Laravel Docs

---

## ✅ Verificación Rápida

```bash
# Sintaxis
php -l app/Http/Controllers/Admin/MenuPermissionController.php

# Rutas
php artisan route:list | grep menu-permissions

# Modelos
php artisan tinker
>>> App\Models\Usuario::with('roles')->first();

# Vistas
ls resources/views/admin/menu-permissions/
```

---

## 🎓 Conceptos Clave

### hasManyThrough
Relación "a través de" para conectar Usuario → Rol sin RolUsuario
```
Usuario (1) → RolUsuario (muchos) → Rol (muchos)
```

### Pivot Tables
Tablas de relación many-to-many
```
roles_usuarios: Conecta Usuario y Rol
permisos_menus_roles: Conecta Rol y Menu
```

### Blade Directives
```
@extends('layouts.app')     // Hereda layout
@section('content')         // Define sección
@forelse($items as $item)   // Loop con fallback
@csrf                       // Token CSRF
@method('PUT')              // Simular verbo HTTP
```

---

## 🚀 Deployment

### Pasos Pre-Producción
1. [ ] Ejecutar tests
2. [ ] Verificar logs en vacío
3. [ ] Backup de BD
4. [ ] Clear de caché
5. [ ] Migrar BD (si es necesario)
6. [ ] Verificar permisos de archivos

### Comandos
```bash
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Versión:** 1.0  
**Última Actualización:** 2024  
**Estado:** ✅ PRODUCCIÓN READY
