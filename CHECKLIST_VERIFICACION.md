# ✅ Checklist de Verificación - AquaIncuba UNIA

## 📋 Verificación Inicial

- [ ] **Clonar repositorio**
  - [ ] Git instalado
  - [ ] Acceso a repositorio

- [ ] **Instalación de dependencias**
  - [ ] PHP 8.1+ instalado
  - [ ] Composer instalado
  - [ ] Node.js + NPM instalado
  - [ ] `composer install` ejecutado
  - [ ] `npm install` ejecutado

- [ ] **Configuración del entorno**
  - [ ] `.env` creado (copia de `.env.example`)
  - [ ] `APP_KEY` generado con `php artisan key:generate`
  - [ ] `APP_DEBUG=true` en desarrollo
  - [ ] `APP_URL=http://localhost:8000` configurado

## 🗄️ Base de Datos

- [ ] **MySQL instalado**
  - [ ] Servidor MySQL corriendo
  - [ ] Acceso con usuario root o credenciales configuradas

- [ ] **Base de datos creada**
  - [ ] Nombre: `aquaincuba_unia`
  - [ ] Charset: `utf8mb4`
  - [ ] Collation: `utf8mb4_unicode_ci`

- [ ] **Configuración en .env**
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=aquaincuba_unia
  DB_USERNAME=root
  DB_PASSWORD=
  ```

- [ ] **Migraciones ejecutadas**
  - [ ] `php artisan migrate` completado sin errores
  - [ ] 20 tablas creadas en base de datos
  - [ ] `migrations` table creada

- [ ] **Seeders ejecutados**
  - [ ] `php artisan db:seed` completado
  - [ ] Empresa default creada
  - [ ] Usuarios test creados (admin@aquaincuba.com)
  - [ ] Roles creados (administrador, operador, revisor)
  - [ ] Menús y permisos populados

## 🔐 Autenticación

- [ ] **Credenciales de prueba**
  - [ ] Usuario: `admin@aquaincuba.com`
  - [ ] Contraseña: `password123`
  - [ ] Rol: Administrador

- [ ] **Login funcionando**
  - [ ] Acceso a `/login`
  - [ ] Formulario se carga correctamente
  - [ ] Login exitoso con credenciales
  - [ ] Redirección a `/dashboard`

- [ ] **Logout funcionando**
  - [ ] Botón logout visible
  - [ ] Logout exitoso
  - [ ] Redirección a login

- [ ] **Sesión protegida**
  - [ ] No se puede acceder a rutas sin autenticación
  - [ ] Middleware `auth` funcionando
  - [ ] CSRF protection activo

## 📊 Dashboard

- [ ] **Dashboard cargando**
  - [ ] Acceso a `/dashboard`
  - [ ] Estadísticas mostrándose
  - [ ] Sin errores en console

- [ ] **Estadísticas mostradas**
  - [ ] Total Incubadoras
  - [ ] Total Sensores
  - [ ] Estudios Activos
  - [ ] Total Usuarios

- [ ] **Datos actuales**
  - [ ] Incubadoras listadas
  - [ ] Estudios activos mostrados
  - [ ] Lecturas recientes visibles

## 👥 Módulo de Usuarios

- [ ] **Lista de usuarios**
  - [ ] Acceso a `/usuarios`
  - [ ] Tabla cargando con datos
  - [ ] Paginación funcionando (si hay muchos usuarios)

- [ ] **Crear usuario**
  - [ ] Botón "Crear" visible
  - [ ] Formulario cargando en `/usuarios/create`
  - [ ] Campos requeridos validados
  - [ ] Roles seleccionables como checkboxes
  - [ ] Guardado exitoso
  - [ ] Redirección a lista

- [ ] **Editar usuario**
  - [ ] Botón editar en cada fila
  - [ ] Formulario pre-poblado en `/usuarios/{id}/edit`
  - [ ] Cambio de datos exitoso
  - [ ] Cambio de contraseña funciona (opcional)
  - [ ] Cambio de roles funciona

- [ ] **Eliminar usuario**
  - [ ] Botón eliminar visible
  - [ ] Confirmación de eliminación
  - [ ] Eliminación exitosa
  - [ ] Usuario removido de lista

## 🎯 Módulo de Roles

- [ ] **Lista de roles**
  - [ ] Acceso a `/roles`
  - [ ] Tabla con roles existentes
  - [ ] Roles por defecto: administrador, operador, revisor

- [ ] **Crear rol**
  - [ ] Botón "Crear" funcional
  - [ ] Formulario en `/roles/create`
  - [ ] Nombre obligatorio
  - [ ] Descripción (opcional)
  - [ ] Guardado exitoso

- [ ] **Gestionar permisos**
  - [ ] Botón "Permisos" en cada rol
  - [ ] Página de permisos en `/roles/{id}/permisos`
  - [ ] Matriz de permisos visible
  - [ ] Checkboxes para: Ver, Crear, Editar, Eliminar
  - [ ] Menús listados: Usuarios, Roles, Empresa, Incubadoras, Sensores, Estudios
  - [ ] Cambios guardados correctamente

- [ ] **Eliminar rol**
  - [ ] Rol puede ser eliminado
  - [ ] No afecta usuarios asignados al rol anterior

## 🏢 Módulo de Empresa

- [ ] **Ver empresa**
  - [ ] Acceso a `/empresa`
  - [ ] Información de empresa mostrada
  - [ ] Logo visible (si existe)

- [ ] **Editar empresa**
  - [ ] Botón "Editar" visible
  - [ ] Acceso a `/empresa/editar`
  - [ ] Todos los campos editables
  - [ ] Logo puede ser subido (JPG, PNG, GIF)
  - [ ] Tamaño máximo 2MB validado
  - [ ] Cambios guardados correctamente

## 🏭 Módulo de Incubadoras

- [ ] **Lista de incubadoras**
  - [ ] Acceso a `/incubadoras`
  - [ ] Tabla con incubadoras
  - [ ] Badge mostrando cantidad de sensores
  - [ ] Botones de acción: Sensores, Editar, Eliminar

- [ ] **Crear incubadora**
  - [ ] Botón "Crear" funcional
  - [ ] Formulario en `/incubadoras/create`
  - [ ] Campos requeridos:
    - [ ] Nombre
    - [ ] Código único
    - [ ] Volumen (litros)
    - [ ] Temperatura óptima
    - [ ] pH óptimo
    - [ ] Oxígeno disuelto óptimo
    - [ ] Descripción (opcional)
  - [ ] Validación de campos numéricos
  - [ ] Guardado exitoso

- [ ] **Editar incubadora**
  - [ ] Botón editar funcional
  - [ ] Formulario pre-poblado en `/incubadoras/{id}/edit`
  - [ ] Todos los campos editables
  - [ ] Cambios guardados

- [ ] **Asignar sensores**
  - [ ] Botón "Sensores" funcional
  - [ ] Lista de sensores disponibles en `/incubadoras/{id}/sensores`
  - [ ] Checkboxes para seleccionar sensores
  - [ ] Información del sensor visible (código, tipo, unidad)
  - [ ] Guardado de asignación funciona
  - [ ] Relación Many-to-Many actualizada

- [ ] **Eliminar incubadora**
  - [ ] Botón eliminar funcional
  - [ ] Confirmación antes de eliminar
  - [ ] Eliminación en cascada correcta
  - [ ] Sensores desvinculados

## 📡 Módulo de Sensores

- [ ] **Lista de sensores**
  - [ ] Acceso a `/sensores`
  - [ ] Tabla con sensores
  - [ ] Tipo de sensor visible
  - [ ] Unidad de medida visible
  - [ ] Factor de calibración visible

- [ ] **Crear sensor**
  - [ ] Botón "Crear" funcional
  - [ ] Formulario en `/sensores/create`
  - [ ] Campos requeridos:
    - [ ] Código único
    - [ ] Nombre
    - [ ] Tipo (dropdown): Temperatura, pH, Oxígeno, Turbidez, Conductividad
    - [ ] Unidad de medida
    - [ ] Rango mínimo
    - [ ] Rango máximo
    - [ ] Factor de calibración
  - [ ] Validación numérica
  - [ ] Guardado exitoso

- [ ] **Editar sensor**
  - [ ] Botón editar funcional
  - [ ] Formulario pre-poblado
  - [ ] Tipo seleccionable
  - [ ] Cambios guardados

- [ ] **Eliminar sensor**
  - [ ] Botón eliminar funcional
  - [ ] Confirmación requerida
  - [ ] Desvinculación de incubadoras

## 📊 Módulo de Estudios

- [ ] **Lista de estudios**
  - [ ] Acceso a `/estudios`
  - [ ] Tabla con estudios
  - [ ] Estado visible (Activo/Finalizado)
  - [ ] Fechas mostradas
  - [ ] Botones de acción: Ver, Editar, Eliminar

- [ ] **Crear estudio**
  - [ ] Botón "Crear" funcional
  - [ ] Formulario en `/estudios/create`
  - [ ] Campos requeridos:
    - [ ] Nombre
    - [ ] Incubadora (dropdown con cantidad de sensores)
    - [ ] Descripción (opcional)
    - [ ] Fecha inicio
    - [ ] Fecha fin
    - [ ] Número de muestras (spinner)
  - [ ] Validación de fechas
  - [ ] Guardado exitoso
  - [ ] Muestras creadas automáticamente

- [ ] **Ver estudio**
  - [ ] Acceso a `/estudios/{id}`
  - [ ] Información del estudio mostrada
  - [ ] Tabla de muestras
  - [ ] Modales por muestra:
    - [ ] Datos crudos del MQTT
    - [ ] Datos procesados/calibrados
  - [ ] Datos correctamente formateados

- [ ] **Editar estudio**
  - [ ] Botón editar funcional
  - [ ] Campos editables: Nombre, Descripción, Fechas
  - [ ] Campo Incubadora NO editable
  - [ ] Cambios guardados

- [ ] **Eliminar estudio**
  - [ ] Botón eliminar funcional
  - [ ] Confirmación requerida
  - [ ] Eliminación en cascada de muestras

## 🔒 Seguridad y Aislamiento

- [ ] **Multi-tenant isolation**
  - [ ] Usuario solo ve datos de su empresa
  - [ ] No puede acceder a empresas ajenas
  - [ ] Queries filtradas por `id_empresa`

- [ ] **Control de roles**
  - [ ] Permisos se respetan en UI (botones)
  - [ ] Permisos se validan en backend
  - [ ] Intentos no autorizados devuelven 403

- [ ] **CSRF protection**
  - [ ] Tokens CSRF en todos los formularios
  - [ ] Validación en backend

- [ ] **Password security**
  - [ ] Contraseñas hasheadas con Hash::make()
  - [ ] No se muestran en ningún lado
  - [ ] Reset password funciona

## 🛠️ Technical

- [ ] **Rutas**
  - [ ] `php artisan route:list` muestra todas
  - [ ] Nombres de rutas correctos (usuarios.index, etc.)
  - [ ] Middleware auth en rutas protegidas

- [ ] **Modelos**
  - [ ] Todas las relaciones funcionan
  - [ ] Timestamps activos (created_at, updated_at)
  - [ ] Soft deletes funcionan (si aplica)
  - [ ] Casting de atributos correcto

- [ ] **Vistas**
  - [ ] Sin errores Blade
  - [ ] Formularios con validación
  - [ ] Mensajes de error mostrados
  - [ ] Mensajes de éxito mostrados
  - [ ] Responsive design funciona
  - [ ] Iconos cargando correctamente

- [ ] **Assets**
  - [ ] CSS cargando (Bootstrap 5)
  - [ ] JavaScript funcional
  - [ ] Iconos visibles
  - [ ] Sin errores en console

## 📝 Logs y Monitoring

- [ ] **Laravel logs**
  - [ ] `storage/logs/laravel.log` existe
  - [ ] Sin errores críticos
  - [ ] Información relevante registrada

- [ ] **Database logs**
  - [ ] Migraciones registradas
  - [ ] Queries ejecútadas correctamente

## 🚀 Preparación para Producción

- [ ] **Cambios .env**
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_ENV=production`
  - [ ] `DB_PASSWORD` asegurada
  - [ ] `APP_KEY` generada

- [ ] **Assets compilados**
  - [ ] `npm run build` ejecutado
  - [ ] `public/build` creado
  - [ ] Archivos minificados

- [ ] **Caché optimizado**
  - [ ] `php artisan config:cache`
  - [ ] `php artisan route:cache`
  - [ ] `php artisan view:cache`

- [ ] **Storage link**
  - [ ] `php artisan storage:link` ejecutado
  - [ ] `public/storage` disponible

- [ ] **Permissions**
  - [ ] `storage/` writable (755)
  - [ ] `bootstrap/cache/` writable (755)

## ✅ Checklist Final

**Sistema Completamente Funcional**
- [ ] Login funciona
- [ ] Dashboard muestra estadísticas
- [ ] Todos los módulos CRUD operativos
- [ ] Seguridad y roles implementados
- [ ] Migraciones ejecutadas
- [ ] Seeders poblados
- [ ] Assets compilados
- [ ] Sin errores en logs
- [ ] Ready para testing
- [ ] Ready para producción

---

## 📊 Estado Actual

- **Fase**: Completada - Phase 1
- **Módulos Implementados**: 7 controladores
- **Vistas Creadas**: 20+ archivos Blade
- **Rutas Configuradas**: 40+ rutas protegidas
- **Migraciones**: 20 tablas
- **Modelos**: 21 modelos con relaciones
- **Documentación**: 5 archivos completos

## 🎯 Próximos Pasos

1. **Testing Manual** - Ejecutar todos los casos de prueba
2. **Deployment** - Llevar a servidor de staging
3. **MQTT Integration** - Implementar conexión a broker
4. **Alert System** - Alertas por threshold
5. **API REST** - Endpoints para móvil
6. **Reporting** - Gráficos y reportes PDF

---

**Última actualización**: Enero 2025  
**Versión**: 1.0 - Phase 1 Complete
