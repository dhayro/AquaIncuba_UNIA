# Guía de Testing - AquaIncuba UNIA

## 🧪 Testing Manual

### Prerequisitos
- Servidor Laravel ejecutándose (`php artisan serve`)
- Base de datos migrada y semeada
- Navegador web actualizado

### 1. Pruebas de Autenticación

#### Test 1.1: Login Exitoso
```
1. Ir a http://localhost:8000/login
2. Ingresar:
   Email: admin@aquaincuba.com
   Contraseña: password123
3. Presionar "Iniciar Sesión"
✓ Resultado esperado: Redirección a /dashboard
✓ Elemento: Navbar muestra "Administrador Sistema"
```

#### Test 1.2: Login Fallido
```
1. Ir a http://localhost:8000/login
2. Ingresar:
   Email: admin@aquaincuba.com
   Contraseña: contraseña_incorrecta
3. Presionar "Iniciar Sesión"
✓ Resultado esperado: Mensaje de error "Credenciales inválidas"
✓ Permanece en página de login
```

#### Test 1.3: Acceso sin Autenticación
```
1. Abrir nueva pestaña/sesión privada
2. Ir a http://localhost:8000/dashboard
✓ Resultado esperado: Redirección a /login
```

#### Test 1.4: Logout
```
1. Estar autenticado
2. Presionar botón de logout (esquina superior)
3. Click en "Cerrar Sesión"
✓ Resultado esperado: Sesión destruida, redirección a login
```

### 2. Pruebas de Dashboard

#### Test 2.1: Cargar Dashboard
```
1. Autenticarse como admin
2. Dashboard carga automáticamente
✓ Verificar:
   - Cards mostrando estadísticas (4 cards: Incubadoras, Sensores, Estudios, Usuarios)
   - Números correctos (si no hay datos, debe mostrar 0)
   - Lista de incubadoras activas
   - Estudios en progreso
   - Últimas lecturas
```

### 3. Pruebas de Gestión de Usuarios

#### Test 3.1: Listar Usuarios
```
1. Ir a /usuarios
2. Ver tabla con usuarios
✓ Verificar:
   - Tabla paginada
   - Columnas: #, Nombre, Correo, Roles, Acciones
   - Los 3 usuarios seedeados aparecen (admin, juan, maria)
   - Botones Editar y Eliminar funcionan
```

#### Test 3.2: Crear Usuario
```
1. Ir a /usuarios → Nuevo Usuario
2. Completar formulario:
   Nombre: Juan Test
   Correo: juan.test@aquaincuba.com
   Contraseña: Password123
   Confirmar: Password123
   Roles: Operador (checkbox)
3. Guardar
✓ Verificar:
   - Mensaje de éxito "Usuario creado exitosamente"
   - Aparece en lista
   - Puede loguearse con credenciales nuevas
```

#### Test 3.3: Editar Usuario
```
1. Ir a /usuarios
2. Click Editar en un usuario
3. Cambiar nombre a "Juan Modificado"
4. Guardar
✓ Verificar:
   - Mensaje de éxito
   - Nombre actualizado en lista
```

#### Test 3.4: Eliminar Usuario
```
1. Ir a /usuarios
2. Click Eliminar en un usuario NO ADMIN
3. Confirmar en popup
✓ Verificar:
   - Usuario desaparece de lista
   - No se puede loguearse más
```

### 4. Pruebas de Gestión de Incubadoras

#### Test 4.1: Crear Incubadora
```
1. Ir a /incubadoras → Nueva Incubadora
2. Completar:
   Nombre: Incubadora A
   Código: INC-A-001
   Volumen: 500
   Temp Óptima: 28
   pH Óptimo: 7.5
   O2 Óptimo: 6
   Descripción: Incubadora para camarones
3. Guardar
✓ Verificar:
   - Aparece en lista /incubadoras
   - Muestra 0 sensores asignados
```

#### Test 4.2: Asignar Sensores
```
1. Ir a /incubadoras
2. Click en botón de sensores (icono)
3. Marcar sensores disponibles
4. Guardar
✓ Verificar:
   - Badge de sensores actualiza
   - En list aparece "2 sensores" (ej)
```

### 5. Pruebas de Gestión de Sensores

#### Test 5.1: Crear Sensor
```
1. Ir a /sensores → Nuevo Sensor
2. Completar:
   Nombre: Termómetro #1
   Código: SENSOR-TEMP-001
   Tipo: Temperatura
   Unidad: °C
   Rango Mín: 15
   Rango Máx: 35
   Factor Calibración: 1.0
3. Guardar
✓ Verificar:
   - Aparece en tabla /sensores
   - Tipo muestra "Temperatura"
```

### 6. Pruebas de Gestión de Estudios

#### Test 6.1: Crear Estudio
```
1. Ir a /estudios → Nuevo Estudio
2. Completar:
   Incubadora: Seleccionar Incubadora A
   Nombre: Estudio Inicial
   Descripción: Análisis de parámetros
   Fecha Inicio: Fecha actual
   Número Muestras: 5
3. Guardar
✓ Verificar:
   - Aparece en lista /estudios con estado "Activo"
   - Al ver detalles, muestra 5 muestras vacías
```

### 7. Pruebas de Gestión de Roles

#### Test 7.1: Crear Rol
```
1. Ir a /roles → Nuevo Rol
2. Completar:
   Nombre: supervisor
   Descripción: Supervisa operaciones
3. Guardar
✓ Verificar:
   - Aparece en lista
```

#### Test 7.2: Asignar Permisos
```
1. Ir a /roles
2. Click Permisos en rol "supervisor"
3. Marcar permisos en menús:
   Dashboard: Ver ✓
   Incubadoras: Ver ✓, Crear ✓, Editar ✓
4. Guardar
✓ Verificar:
   - Mensaje de éxito
   - Permisos guardados correctamente
```

### 8. Pruebas de Seguridad

#### Test 8.1: Control de Acceso por Empresa
```
1. Crear dos empresas (si es posible)
2. Usuario A en Empresa 1
3. Usuario B en Empresa 2
4. Usuario A intenta acceder a /usuarios
✓ Verificar:
   - Solo ve usuarios de su empresa
   - No puede acceder a datos de Empresa 2
```

#### Test 8.2: Protección de Rutas
```
1. Logout
2. Ir a /incubadoras (sin autenticarse)
✓ Verificar:
   - Redirección a /login (Middleware auth)
```

#### Test 8.3: Verificación de Permisos
```
1. Loguearse con usuario "Revisor"
2. Intentar ir a /usuarios
✓ Verificar:
   - Si no tiene permiso, no aparece en menú
   - O muestra error 403
```

## 🤖 Unit Tests (PHPUnit)

### Estructura de Tests
```
tests/
├── Unit/
│   ├── Models/
│   │   ├── EmpresaTest.php
│   │   ├── UsuarioTest.php
│   │   └── IncubadoraTest.php
│   └── Controllers/
│       ├── LoginControllerTest.php
│       └── UsuarioControllerTest.php
└── Feature/
    ├── AuthenticationTest.php
    ├── UsuarioManagementTest.php
    └── IncubadoraManagementTest.php
```

### Ejecutar Tests
```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test tests/Feature/AuthenticationTest.php

# Con coverage
php artisan test --coverage
```

### Ejemplo: Test de Login
```php
// tests/Feature/AuthenticationTest.php

public function test_usuario_puede_loguearse()
{
    $response = $this->post('/login', [
        'correo' => 'admin@aquaincuba.com',
        'contraseña' => 'password123',
    ]);
    
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($this->admin());
}

public function test_usuario_no_autenticado_redirige_a_login()
{
    $response = $this->get('/dashboard');
    
    $response->assertRedirect('/login');
}
```

## 📋 Checklist de Validación

### Sistema
- [ ] Laravel servidor ejecutándose sin errores
- [ ] Base de datos conectada y migrada
- [ ] Assets compilados (CSS/JS)
- [ ] Log de errores vacío o con advertencias menores

### Autenticación
- [ ] Login funciona con credenciales correctas
- [ ] Login falla con credenciales incorrectas
- [ ] Logout destruye la sesión
- [ ] Rutas protegidas requieren autenticación

### CRUD Operations
- [ ] Crear registros (usuarios, incubadoras, sensores)
- [ ] Listar registros con paginación
- [ ] Editar registros existentes
- [ ] Eliminar registros con confirmación

### Validaciones
- [ ] Email debe ser válido
- [ ] Contraseña mínimo 8 caracteres
- [ ] Campos requeridos validan
- [ ] Números aceptan rangos correctos
- [ ] Mensajes de error clara

### Seguridad
- [ ] Usuarios solo ven datos de su empresa
- [ ] Permisos se aplican correctamente
- [ ] Contraseñas se hashean
- [ ] CSRF tokens en todos los formularios

### UI/UX
- [ ] Formularios son responsive
- [ ] Tablas paginadas funcionan
- [ ] Alertas de éxito/error claras
- [ ] Navegación funciona correctamente
- [ ] Logo se muestra en empresa

## 🐛 Errores Comunes y Soluciones

| Error | Causa | Solución |
|-------|-------|----------|
| 404 Not Found | Ruta no existe | Verificar rutas en web.php |
| Class not found | Modelo no importado | Usar `use App\Models\...` |
| SQLSTATE error | Migración no ejecutada | `php artisan migrate` |
| TokenMismatchException | CSRF falta | Agregar `@csrf` en forms |
| 403 Forbidden | Permisos insuficientes | Verificar roles/permisos |
| Email already exists | Validación unique fallando | Verificar constraint BD |
| File upload failed | Permisos directorio | `chmod 755 storage` |

## 📊 Cobertura de Código

**Objetivo**: 80% de cobertura

```bash
php artisan test --coverage --coverage-html=coverage

# Abrir coverage/index.html en navegador
```

### Áreas Críticas a Testear
1. **Autenticación**: 100%
2. **Autorización**: 100%
3. **Validaciones**: 90%
4. **Modelos**: 85%
5. **Controladores**: 75%

## 🎬 Escenarios de Uso Real

### Escenario 1: Setup Inicial
```
1. Usuario admin loguea por primera vez
2. Ve dashboard vacío (0 incubadoras, 0 sensores)
3. Va a Empresa → Edita y sube logo
4. Crea 2 incubadoras
5. Crea 5 sensores
6. Asigna sensores a incubadoras
7. Crea primer estudio
8. Sistema listo para recibir datos MQTT
```

### Escenario 2: Gestión de Roles
```
1. Admin crea rol "operador_temp"
2. Asigna permisos solo a Dashboard, Incubadoras, Sensores
3. Crea usuario con este rol
4. Usuario loguea y solo ve menú restringido
5. Intenta acceder a /usuarios → Error 403
6. Admin modifica permisos en rol
7. Usuario refrescar y ve nuevos permisos
```

### Escenario 3: Estudio Completo
```
1. Operador crea estudio con 10 muestras
2. Ingresa datos crudos manualmente
3. Sistema procesa datos
4. Genera conclusión automática
5. Revisor accede y descarga reporte
```

## ✅ Validación Final

**Ejecutar antes de deployment:**

```bash
# 1. Verificar tests
php artisan test

# 2. Verificar código
php artisan code:analyse

# 3. Limpiar
php artisan cache:clear
php artisan config:clear

# 4. Compilar assets
npm run build

# 5. Verificar logs
tail -f storage/logs/laravel.log
```

---

**Versión**: 1.0
**Última actualización**: Enero 2025
