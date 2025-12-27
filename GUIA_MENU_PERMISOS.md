# 📋 Guía de Administración de Menús y Permisos

## 🎯 Introducción

Se ha implementado una interfaz de administración completa para gestionar menús y permisos en la aplicación AquaIncuba UNIA. Esta guía te mostrará cómo acceder y utilizar las funcionalidades disponibles.

---

## 🌐 Acceso a la Interfaz

### URL de Acceso
```
http://localhost:8000/admin/menu-permissions
```

### Requisitos Previos
- Estar autenticado en la aplicación
- Tener permisos de administrador
- Estar en la misma empresa (multi-tenancy)

---

## 📊 Dashboard Principal

El dashboard principal muestra tres secciones:

### 1️⃣ **Usuarios y sus Roles** (Lado Izquierdo)
- **Columnas:**
  - Usuario: Nombre del usuario
  - Email: Correo electrónico
  - Roles: Roles asignados (mostrados como badges azules)
  - Acciones: Botón para editar roles

- **Acciones disponibles:**
  - Hacer clic en el icono de búsqueda para editar los roles de un usuario

### 2️⃣ **Roles y Permisos de Menú** (Lado Derecho)
- **Columnas:**
  - Rol: Nombre del rol
  - Menús Permitidos: Número de menús que puede acceder (badge verde)
  - Acciones: Botón para editar permisos

- **Acciones disponibles:**
  - Hacer clic en el icono de búsqueda para editar los permisos de menú de un rol

### 3️⃣ **Estructura de Menús** (Abajo)
- **Columnas:**
  - Nombre: Nombre del menú
  - URL: Ruta de acceso
  - Nivel: Nivel jerárquico (0 = grupo principal, 1 = submenu)
  - Padre: Menú padre si es aplicable
  - Orden: Orden de aparición

- **Visualización:**
  - Los menús principales están en nivel 0 (ej: DASHBOARD, ADMINISTRACIÓN)
  - Los submenús están en nivel 1 y están indentados
  - Se muestra la jerarquía completa del árbol de menús

---

## 👤 Gestión de Roles por Usuario

### Acceso
1. En el dashboard principal, en la sección "Usuarios y sus Roles"
2. Haz clic en el icono de búsqueda para el usuario deseado

### Interfaz
- **Título:** Muestra el nombre del usuario (ej: "Roles de Usuario: Juan Perez")
- **Formulario:** Lista de checkboxes con todos los roles disponibles
- **Pre-selección:** Los roles ya asignados aparecen marcados
- **Información lateral:** Muestra los datos del usuario (nombre, email, empresa, roles actuales)

### Proceso de Edición
1. **Marcar roles:** Selecciona los roles que deseas asignar
2. **Desmarcar roles:** Desselecciona los roles que deseas remover
3. **Guardar:** Haz clic en el botón "Guardar Cambios"
4. **Confirmación:** Se redireccionará al dashboard con un mensaje de éxito

### Ejemplo de Uso
```
Usuario: María García
Roles disponibles:
- [✓] Administrador (Acceso total a la aplicación)
- [ ] Editor (Puede crear y editar contenido)
- [ ] Visualizador (Solo puede ver datos)

Acción: Marcar "Editor" y "Visualizador", dejar "Administrador" sin marcar
Resultado: María Garcia tendrá los roles Editor y Visualizador
```

---

## 🔐 Gestión de Permisos por Rol

### Acceso
1. En el dashboard principal, en la sección "Roles y Permisos de Menú"
2. Haz clic en el icono de búsqueda para el rol deseado

### Interfaz
- **Título:** Muestra el nombre del rol (ej: "Permisos de Menú: Administrador")
- **Estructura jerárquica:** Los menús están organizados en grupos
  - **Grupos principales:** Aparecen en negrita y primario color
  - **Submenús:** Indentados bajo su grupo padre

### Funcionalidad JavaScript
- **Seleccionar un grupo:** Automáticamente selecciona todos sus submenús
- **Deseleccionar un grupo:** Automáticamente deselecciona todos sus submenús
- **Seleccionar todos los submenús:** El grupo padre se marca automáticamente
- **Deseleccionar un submenú:** Si ya no están todos seleccionados, el grupo se desmarca

### Proceso de Edición
1. **Marcar menús:** Selecciona los menús que deseas dar permiso
2. **Usar la agrupación:** Puedes marcar un grupo principal para marcar rápidamente todos sus submenús
3. **Guardar:** Haz clic en el botón "Guardar Cambios"
4. **Confirmación:** Se redireccionará al dashboard con un mensaje de éxito

### Ejemplo de Uso
```
Rol: Editor

Menús disponibles:
📌 DASHBOARD
   └─ Panel Principal
   └─ Estadísticas

📌 ADMINISTRACIÓN
   └─ Usuarios
   └─ Roles
   └─ Menús

Acción: Marcar "DASHBOARD" (se marcan automáticamente todos sus submenús)
Resultado: El rol Editor puede acceder a Dashboard y todos sus submenús
```

---

## 🔄 Flujo de Operaciones

### Flujo Completo de Asignación de Permisos

```
1. Usuario (Juan) necesita acceso a un menú (Gestión de Estudios)
                    ↓
2. Ir a dashboard /admin/menu-permissions
                    ↓
3. En "Usuarios y sus Roles", editar roles de Juan
                    ↓
4. Asignar el rol "Editor" a Juan
                    ↓
5. Guardar cambios (Juan ahora tiene rol Editor)
                    ↓
6. En "Roles y Permisos de Menú", editar permisos del rol Editor
                    ↓
7. Marcar "ESTUDIOS" para dar acceso a ese menú
                    ↓
8. Guardar cambios
                    ↓
9. Juan ahora puede ver "ESTUDIOS" en su menú lateral ✅
```

---

## 🗄️ Base de Datos

### Tablas Relacionadas

```
usuarios (id, nombre, email, id_empresa)
    ↓
roles_usuarios (id_usuario, id_rol, id_empresa)
    ↓
roles (id, nombre, descripcion, id_empresa)
    ↓
permisos_menus_roles (id_rol, id_menu, puede_ver, puede_crear, puede_editar, puede_eliminar)
    ↓
menus (id, nombre, url, nivel, id_padre, orden, id_empresa)
```

### Niveles de Menú
- **Nivel 0:** Menús principales (grupos, ej: DASHBOARD, ADMINISTRACIÓN)
- **Nivel 1:** Submenús (elementos dentro de un grupo)

---

## ✅ Validaciones

### Validaciones de Entrada
1. **Roles:** Deben existir en la base de datos
2. **Menús:** Deben existir en la base de datos
3. **Usuarios:** Deben existir en la base de datos
4. **Empresa:** Los datos se filtran automáticamente por la empresa del usuario autenticado

### Mensajes de Éxito
- "Roles del usuario actualizados correctamente" - Cuando se actualizan roles de usuario
- "Permisos actualizados correctamente" - Cuando se actualizan permisos de menú

---

## 🎨 Características de UI

### Elementos Visuales
- **Badges de colores:**
  - Azul: Roles asignados
  - Verde: Conteo de menús permitidos
  - Gris: Información secundaria

- **Iconos:**
  - Flecha de atrás: Botón para regresar al dashboard
  - Lupa: Botón de acciones para editar

- **Estructura:**
  - Tarjetas (Cards) para cada sección
  - Encabezados descriptivos
  - Mensajes de alerta para confirmación

---

## 🔒 Seguridad

### Protecciones Implementadas
1. **Autenticación:** Solo usuarios autenticados pueden acceder
2. **Multi-tenancy:** Solo ven datos de su empresa
3. **Validación:** Se valida que roles y menús existan antes de guardar
4. **CSRF:** Protección contra ataques Cross-Site Request Forgery (formularios con @csrf)

---

## 📝 Notas Importantes

### Consideraciones
- Los cambios se aplican inmediatamente al guardar
- Los usuarios deben recargar la página para ver los menús actualizados en el sidebar
- Los permisos se aplican al siguiente acceso de la aplicación
- Backup automático de la BD antes de cambios masivos (recomendado)

### Mejoras Futuras
- Agregar campos adicionales de permisos (crear, editar, eliminar)
- Implementar auditoría de cambios
- Agregar roles y menús directamente desde esta interfaz
- Búsqueda y filtrado en las tablas

---

## 🆘 Solución de Problemas

### Problema: No aparecen usuarios en el dashboard
**Solución:** Verifica que existan usuarios en la BD y que tengan la misma empresa

### Problema: Los cambios no se guardan
**Solución:** Verifica que tengas permisos de administrador y que no haya errores en la consola

### Problema: Los menús no aparecen en el sidebar después de editar
**Solución:** Recarga la página o cierra sesión y vuelve a iniciar sesión

---

## 📚 Recursos Relacionados

- **MenuHelper:** Controla qué menús ve cada usuario en el sidebar
- **Models:** Usuario, Rol, Menu, RolUsuario, PermisoMenuRol
- **Controllers:** MenuPermissionController
- **Views:** resources/views/admin/menu-permissions/

---

## ✨ Resumen Rápido

| Acción | Ruta | Descripción |
|--------|------|-------------|
| Ver dashboard | `/admin/menu-permissions` | Panel principal |
| Editar roles de usuario | `/admin/menu-permissions/user/{id}/roles` | Asignar roles |
| Guardar roles | `PUT /admin/menu-permissions/user/{id}/roles` | Actualizar |
| Editar permisos de rol | `/admin/menu-permissions/role/{id}/permissions` | Asignar menús |
| Guardar permisos | `PUT /admin/menu-permissions/role/{id}/permissions` | Actualizar |

---

**Última actualización:** 2024
**Versión:** 1.0
**Estado:** ✅ Completamente implementado y funcional
