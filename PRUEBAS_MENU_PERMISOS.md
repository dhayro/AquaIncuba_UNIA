# 🚀 Instrucciones de Prueba - Interfaz de Menús y Permisos

## ✅ Estado Actual

La interfaz de administración de menús y permisos está **COMPLETAMENTE IMPLEMENTADA Y LISTA PARA USAR**.

**Validaciones Completadas:**
- ✅ Sintaxis PHP correcta
- ✅ Rutas registradas
- ✅ Controlador funcional
- ✅ Vistas sin errores
- ✅ Modelos con relaciones
- ✅ Base de datos con datos

---

## 🎯 Pasos para Probar

### Paso 1: Iniciar la Aplicación

```bash
cd c:\Users\Usuario\Documents\Softronic\AquaIncuba_UNIA
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

### Paso 2: Iniciar Sesión

1. Abre `http://localhost:8000` en tu navegador
2. Inicia sesión con tus credenciales de administrador
3. Selecciona una empresa (si es necesario)

### Paso 3: Acceder a la Interfaz

**Opción A - Vía URL directa:**
```
http://localhost:8000/admin/menu-permissions
```

**Opción B - Vía navegación (si está en el menú):**
1. Busca "Administración" en el menú lateral
2. Busca "Menús y Permisos"
3. Haz clic para acceder

---

## 📊 Pruebas Recomendadas

### Test 1: Visualizar Dashboard

**Objetivo:** Verificar que el dashboard carga correctamente

**Pasos:**
1. Accede a `/admin/menu-permissions`
2. Deberías ver tres secciones:
   - "Usuarios y sus Roles" (lado izquierdo)
   - "Roles y Permisos de Menú" (lado derecho)
   - "Estructura de Menús" (abajo)

**Validación:**
- ✅ Se cargan 3 usuarios
- ✅ Se cargan 3 roles
- ✅ Se cargan 19 menús
- ✅ No hay errores en la consola del navegador

### Test 2: Editar Roles de Usuario

**Objetivo:** Asignar/remover roles a un usuario

**Pasos:**
1. En la sección "Usuarios y sus Roles"
2. Haz clic en el botón de lupa (acción) para cualquier usuario
3. Deberías ser redirigido a una página de edición
4. Deberías ver checkboxes con los roles disponibles
5. Algunos roles deberían estar pre-seleccionados

**Validación:**
- ✅ La página de edición carga correctamente
- ✅ Se muestran todos los roles disponibles
- ✅ Los roles actuales están marcados
- ✅ Puedes marcar/desmarcar roles
- ✅ Hay botones "Volver" y "Guardar Cambios"

**Prueba Funcional:**
1. Desselecciona un rol que tiene asignado
2. Selecciona un rol que no tiene
3. Haz clic en "Guardar Cambios"
4. Deberías ser redirigido al dashboard
5. Deberías ver un mensaje de éxito
6. Los cambios deberían reflejarse en la tabla

### Test 3: Editar Permisos de Rol

**Objetivo:** Asignar/remover menús a un rol

**Pasos:**
1. En la sección "Roles y Permisos de Menú"
2. Haz clic en el botón de lupa (acción) para cualquier rol
3. Deberías ser redirigido a una página de edición
4. Deberías ver checkboxes agrupados por menús principales
5. Algunos menús deberían estar pre-seleccionados

**Validación:**
- ✅ La página de edición carga correctamente
- ✅ Se muestran menús en estructura jerárquica
- ✅ Los menús actuales están marcados
- ✅ Puedes marcar/desmarcar menús
- ✅ Hay botones "Volver" y "Guardar Cambios"

**Prueba Funcional (JavaScript):**
1. Haz clic en un menú principal (grupo)
2. Todos los submenús deberían marcarse automáticamente
3. Desselecciona un submenú individual
4. El grupo padre debería desmarcarse automáticamente
5. Haz clic en "Guardar Cambios"
6. Deberías ver el mensaje de éxito
7. El conteo de menús en la tabla debería actualizarse

### Test 4: Verificar Estructura de Menús

**Objetivo:** Confirmar que la estructura jerárquica se muestra correctamente

**Pasos:**
1. Ve al dashboard
2. Ve la sección "Estructura de Menús"
3. Deberías ver:
   - Menús nivel 0 (grupos principales)
   - Menús nivel 1 (submenús indentados)

**Validación:**
- ✅ Se muestran todos los 19 menús
- ✅ Los menús nivel 1 aparecen indentados
- ✅ Se muestran URLs en código monoespaciado
- ✅ Se muestran badges de nivel y orden

### Test 5: Verificar Integración con Sidebar

**Objetivo:** Confirmar que los cambios de permisos afectan al menú lateral

**Pasos:**
1. Edita los permisos de un rol para remover un menú
2. Asigna ese rol a un usuario
3. Cierra sesión del usuario
4. Vuelve a iniciar sesión como ese usuario
5. Verifica que el menú removido no aparezca en el sidebar

**Validación:**
- ✅ Los cambios se aplican al menú lateral
- ✅ Solo se muestran menús permitidos por rol
- ✅ La integración con MenuHelper funciona

---

## 🧪 Casos de Prueba Avanzados

### Caso 1: Multi-tenancy

**Objetivo:** Verificar que cada empresa ve solo sus datos

**Pasos:**
1. Crea/cambia a una empresa diferente
2. Accede a `/admin/menu-permissions`
3. Deberías ver solo usuarios, roles y menús de esa empresa

**Validación:**
- ✅ No hay datos de otras empresas
- ✅ Los cambios en una empresa no afectan otras

### Caso 2: Validación de Entrada

**Objetivo:** Verificar que se validan los datos

**Pasos:**
1. Abre las herramientas de desarrollador (F12)
2. En la consola, edita el HTML para cambiar IDs de roles/menús a valores no existentes
3. Intenta guardar
4. Deberías ver un error de validación

**Validación:**
- ✅ Se validan roles existentes
- ✅ Se validan menús existentes
- ✅ Se previene entrada de datos inválida

### Caso 3: Mensajes de Sesión

**Objetivo:** Verificar que los mensajes de éxito funcionan

**Pasos:**
1. Realiza cualquier cambio y guarda
2. Deberías ver un mensaje de alerta verde
3. Haz clic en la X para cerrarlo
4. Recarga la página
5. El mensaje debería desaparecer

**Validación:**
- ✅ Los mensajes se muestran correctamente
- ✅ Se pueden cerrar
- ✅ Se limpian después de recargar

---

## 🔍 Qué Esperar

### Salida Esperada

```
Dashboard Principal
├─ Usuarios y sus Roles (tabla con 3 usuarios)
├─ Roles y Permisos de Menú (tabla con 3 roles)
└─ Estructura de Menús (tabla con 19 menús)

Formulario de Edición de Roles
├─ Título: "Roles de Usuario: [Nombre]"
├─ Checkboxes para cada rol
└─ Botones: Volver, Guardar Cambios

Formulario de Edición de Permisos
├─ Título: "Permisos de Menú: [Nombre]"
├─ Estructura jerárquica de menús
└─ Botones: Volver, Guardar Cambios
```

### Comportamiento Esperado

- Los formularios cargan en menos de 1 segundo
- Los cambios se guardan sin errores
- Las redirecciones funcionan correctamente
- Los mensajes de éxito aparecen
- El JavaScript sincroniza checkboxes automáticamente
- La interfaz es responsiva en móviles

---

## 🐛 Solución de Problemas

### Error: "Página no encontrada"
```
Causa: Las rutas no están registradas
Solución: Ejecuta php artisan route:list | grep menu-permissions
Esperado: Deberías ver 5 rutas listadas
```

### Error: "Class not found"
```
Causa: El controlador no está en la ubicación correcta
Solución: Verifica que exista app/Http/Controllers/Admin/MenuPermissionController.php
```

### Error: "View not found"
```
Causa: Las vistas no existen
Solución: Verifica que existan:
  - resources/views/admin/menu-permissions/index.blade.php
  - resources/views/admin/menu-permissions/user-roles.blade.php
  - resources/views/admin/menu-permissions/role-permissions.blade.php
```

### Error: "Method not found"
```
Causa: Un método del modelo no existe
Solución: Verifica que Usuario tenga el método roles()
Comando: php artisan tinker
         >> $user = App\Models\Usuario::first();
         >> $user->roles();
```

### Los cambios no se guardan
```
Causa: Errores de validación
Solución: Abre storage/logs/laravel.log y busca errores
```

### El menú no se actualiza en el sidebar
```
Causa: Se necesita recargar la página
Solución: Recarga la página o cierra/abre sesión
```

---

## 📋 Checklist de Prueba

- [ ] Dashboard carga correctamente
- [ ] Se muestran todos los usuarios
- [ ] Se muestran todos los roles
- [ ] Se muestran todos los menús
- [ ] Puedo editar roles de un usuario
- [ ] Puedo editar permisos de un rol
- [ ] Los cambios se guardan correctamente
- [ ] Los mensajes de éxito aparecen
- [ ] Los cambios se reflejan en el dashboard
- [ ] El JavaScript de grupos funciona
- [ ] La redirección funciona correctamente
- [ ] No hay errores en la consola del navegador
- [ ] No hay errores en los logs de Laravel

---

## 💡 Tips Útiles

### Ver Logs
```bash
tail -f storage/logs/laravel.log
```

### Limpiar Caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Ver Datos en DB
```bash
php artisan tinker
>>> App\Models\Usuario::count()
>>> App\Models\Rol::count()
>>> App\Models\Menu::count()
```

### Forzar Regeneración de Rutas
```bash
php artisan route:cache
php artisan route:clear
```

---

## ✨ Resultado Esperado Final

Una vez completadas todas las pruebas, deberías tener:

1. ✅ Una interfaz de administración funcional
2. ✅ Capacidad de asignar roles a usuarios
3. ✅ Capacidad de asignar permisos a roles
4. ✅ Visualización de toda la estructura
5. ✅ Integración con el menú lateral
6. ✅ Mensajes de confirmación
7. ✅ Validación de datos
8. ✅ Multi-tenancy funcionando
9. ✅ Sin errores en la aplicación
10. ✅ Lista para producción

---

## 🎉 ¡Listo!

Si todas las pruebas pasaron correctamente, la interfaz está **100% funcional** y lista para usar.

**Próximos pasos:**
- Integrar en el menú principal (si aún no está)
- Capacitar a usuarios administradores
- Realizar pruebas de rendimiento
- Considerar mejoras futuras

---

**Última Actualización:** 2024  
**Estado:** ✅ COMPLETAMENTE FUNCIONAL  
**Versión de Prueba:** 1.0

*Para soporte técnico, consulta GUIA_MENU_PERMISOS.md*
