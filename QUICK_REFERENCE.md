# 🎯 Quick Reference Card - AquaIncuba UNIA

## 📋 Referencia Rápida para Desarrolladores

### 🚀 Inicio Rápido (5 minutos)

```bash
# 1. Instalar dependencias
composer install && npm install

# 2. Configurar entorno
cp .env.example .env
php artisan key:generate

# 3. Configurar BD en .env
# DB_DATABASE=aquaincuba_unia
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Migrar y seedear
php artisan migrate --seed

# 5. Compilar assets
npm run build

# 6. Iniciar servidor
php artisan serve
```

**Login con**: admin@aquaincuba.com / password123

---

## 🗂️ Estructura de Directorios

```
app/
  ├── Http/Controllers/
  │   ├── LoginController
  │   ├── DashboardController
  │   └── Admin/
  │       ├── UsuarioController
  │       ├── RolController
  │       ├── EmpresaController
  │       ├── IncubadoraController
  │       ├── SensorController
  │       └── EstudioCalidadAguaController
  └── Models/ (21 modelos)

resources/views/
  ├── auth/ (login)
  ├── admin/ (dashboard + CRUD)
  │   ├── usuarios/
  │   ├── roles/
  │   ├── empresa/
  │   ├── incubadoras/
  │   ├── sensores/
  │   └── estudios/
  └── layouts/

database/
  ├── migrations/ (20 tablas)
  ├── seeders/ (datos test)
  └── factories/

routes/web.php (40+ rutas)
```

---

## 🎮 Operaciones CRUD Comunes

### Crear un nuevo modelo con CRUD completo

```bash
# Crear modelo, migración, controlador, factory
php artisan make:model NombreModelo -mcf --resource

# Agregar ruta en routes/web.php
Route::resource('nombres-modelos', NombreModeloController::class);

# Crear vistas en resources/views/admin/nombres-modelos/
# - index.blade.php
# - create.blade.php
# - edit.blade.php
```

### Estructura de un Controller

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Models\Incubadora;
use Illuminate\Http\Request;

class IncubadoraController extends Controller
{
    public function index()
    {
        $incubadoras = Incubadora::where('id_empresa', 
            auth()->user()->id_empresa)->paginate(15);
        return view('admin.incubadoras.index', compact('incubadoras'));
    }

    public function create()
    {
        return view('admin.incubadoras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|unique:incubadoras',
            'volumen' => 'required|numeric|min:0',
        ]);

        Incubadora::create([
            'nombre' => $request->nombre,
            'id_empresa' => auth()->user()->id_empresa,
            // ...
        ]);

        return redirect()->route('incubadoras.index')
            ->with('success', 'Creado exitosamente');
    }

    // ... edit, update, destroy
}
```

---

## 🗄️ Consultas Útiles

### Obtener datos de usuario actual

```php
auth()->user()                           // Usuario autenticado
auth()->user()->empresa                  // Su empresa
auth()->user()->rolesUsuarios            // Sus roles
auth()->check()                          // ¿Autenticado?
auth()->id()                             // ID del usuario
```

### Scopings por empresa

```php
// En el controller
$empresa = auth()->user()->id_empresa;

// Listar solo datos de la empresa
Incubadora::where('id_empresa', $empresa)->get();

// O con relación
auth()->user()->empresa->incubadoras;
```

### Relaciones en Eloquent

```php
// One-to-Many
$empresa->usuarios;
$empresa->incubadoras;

// Many-to-Many
$incubadora->sensores;
$sensor->incubadoras;

// Through
$incubadora->sensores()->through('incubadora_sensor');

// HasMany Through
$empresa->sensores()->hasManyThrough(Sensor::class, Incubadora::class);
```

---

## 📝 Validación en Formularios

### Server-side (PHP)

```php
$validated = $request->validate([
    'nombre' => 'required|string|min:3|max:100',
    'correo' => 'required|email|unique:usuarios',
    'contraseña' => 'required|min:8|confirmed',
    'fecha' => 'required|date',
    'numero' => 'required|numeric|min:0|max:1000',
]);
```

### Client-side (Blade)

```blade
<input type="text" name="nombre" required>
<input type="email" name="correo" required>
<input type="password" name="contraseña" required minlength="8">

@error('nombre')
    <span class="text-danger">{{ $message }}</span>
@enderror
```

---

## 🎨 Vistas Blade - Patrones Comunes

### Loop con alternativa vacía

```blade
@forelse($usuarios as $usuario)
    <tr>
        <td>{{ $usuario->nombre }}</td>
        <td>{{ $usuario->correo }}</td>
    </tr>
@empty
    <tr><td colspan="2">Sin usuarios</td></tr>
@endforelse
```

### Condicional de permiso

```blade
@if(auth()->user()->puede_crear_usuarios)
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        Crear usuario
    </a>
@endif
```

### Formulario con CSRF y método spoofing

```blade
<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf
    <input type="text" name="nombre" value="{{ old('nombre') }}">
    <button type="submit">Guardar</button>
</form>

<!-- Para PUT/DELETE -->
<form action="{{ route('usuarios.update', $usuario) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- campos -->
</form>
```

### Modal Bootstrap

```blade
<button type="button" class="btn btn-info" data-bs-toggle="modal" 
    data-bs-target="#detalleModal{{ $item->id }}">
    Ver Detalles
</button>

<div class="modal fade" id="detalleModal{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detalles</h5>
            </div>
            <div class="modal-body">
                <!-- contenido -->
            </div>
        </div>
    </div>
</div>
```

---

## 🔐 Seguridad - Patrones

### Verificar autorización

```php
// En controller
if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
    abort(403, 'No autorizado');
}

// O usar middleware personalizado
Route::middleware(['auth', 'permission:editar_incubadoras'])
    ->group(function () {
        // rutas protegidas
    });
```

### Hashear contraseña

```php
use Illuminate\Support\Facades\Hash;

// Al crear
$usuario->contraseña = Hash::make($request->contraseña);
$usuario->save();

// Al verificar
if (Hash::check($request->contraseña, $usuario->contraseña)) {
    // contraseña correcta
}
```

---

## 📊 Migraciones - Referencia

### Crear tabla

```php
Schema::create('incubadoras', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('codigo')->unique();
    $table->foreignId('id_empresa')->constrained('empresas');
    $table->decimal('volumen', 10, 2);
    $table->decimal('temp_optima', 5, 2)->nullable();
    $table->timestamps();
    
    $table->index('id_empresa');
    $table->index('codigo');
});
```

### Relaciones comunes

```php
// Foreign key
$table->foreignId('id_empresa')->constrained('empresas');

// Unique
$table->unique('codigo');
$table->unique(['id_empresa', 'codigo']);

// Index
$table->index('status');

// Timestamps
$table->timestamps();        // created_at, updated_at
$table->softDeletes();       // deleted_at

// Enum
$table->enum('status', ['activo', 'inactivo']);
```

---

## 📝 Seeders - Crear datos de prueba

```php
<?php
namespace Database\Seeders;

use App\Models\Incubadora;
use Illuminate\Database\Seeder;

class IncubadoraSeeder extends Seeder
{
    public function run()
    {
        Incubadora::factory()
            ->count(5)
            ->for(Empresa::first())
            ->create();

        // O manual
        Incubadora::create([
            'nombre' => 'Incubadora Principal',
            'codigo' => 'INC-001',
            'id_empresa' => 1,
            'volumen' => 500,
        ]);
    }
}
```

---

## 🧪 Testing - Estructura

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;

class UsuarioTest extends TestCase
{
    public function test_puede_crear_usuario()
    {
        $response = $this->post('/usuarios', [
            'nombre' => 'Test',
            'correo' => 'test@test.com',
            'contraseña' => 'password123',
            'contraseña_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('usuarios', [
            'correo' => 'test@test.com',
        ]);
    }
}
```

---

## 💻 Comandos Artisan Útiles

```bash
# Rutas
php artisan route:list
php artisan route:list --name=usuarios

# BD
php artisan migrate
php artisan migrate:refresh --seed
php artisan tinker

# Caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Modelos
php artisan make:model Nombre -m
php artisan make:migration create_table --create=table

# Debug
php artisan about
php artisan serve --port=8001
```

---

## 🔗 Rutas Clave

| Ruta | Descripción |
|------|-------------|
| `/login` | Formulario login |
| `/logout` | Cerrar sesión |
| `/dashboard` | Panel principal |
| `/usuarios` | Lista de usuarios |
| `/roles` | Gestión de roles |
| `/empresa` | Configuración |
| `/incubadoras` | CRUD incubadoras |
| `/sensores` | CRUD sensores |
| `/estudios` | CRUD estudios |

---

## 📚 Documentación Rápida

| Documento | Para | Tiempo |
|-----------|------|--------|
| GUIA_RAPIDA.md | Instalar | 5 min |
| DOCUMENTACION.md | Entender BD | 30 min |
| ARQUITECTURA.md | Extender | 20 min |
| TESTING.md | Probar | 25 min |
| COMANDOS_UTILES.md | Referencia CLI | 5 min |

---

## 🐛 Troubleshooting Rápido

| Problema | Solución |
|----------|----------|
| "Class not found" | `composer dump-autoload` |
| "No routes" | `php artisan route:clear` |
| "View not found" | `php artisan view:clear` |
| "Cache issue" | `php artisan cache:clear` |
| "Permission denied" | `chmod 755 storage/` |
| "DB connection error" | Verificar `.env` y MySQL |
| "Asset 404" | `npm run build` |

---

## ✅ Checklist Pre-Commit

- [ ] Código formateado (sin trailing spaces)
- [ ] Sin variables sin usar
- [ ] Validación en ambos lados (cliente/servidor)
- [ ] Mensajes de error claros
- [ ] Logs apropiados
- [ ] Tests pasando
- [ ] Sin console.log()
- [ ] Seguridad validada (auth, scoping)

---

## 📱 Breakpoints Bootstrap 5

```
xs: < 576px (móviles)
sm: ≥ 576px (tablets)
md: ≥ 768px (tablets grandes)
lg: ≥ 992px (desktops)
xl: ≥ 1200px (desktops grandes)
xxl: ≥ 1400px
```

**Uso**: `d-none d-md-block` (ocultar en móvil, mostrar en tablet+)

---

## 🎯 Mejores Prácticas

✅ **Hacer:**
- Validación en server siempre
- Usar Eloquent para queries
- Scoping por empresa en CRUD
- Hashing para contraseñas
- Timestamps en migraciones
- Mensajes claros al usuario
- Documentar código complejo

❌ **No hacer:**
- Queries raw SQL
- Lógica en vistas
- Contraseñas en logs
- Confiar solo en client-side
- Debug información en producción
- Código duplicado
- Dependencias sin usar

---

## 📞 Help & Support

**Para ayuda rápida, consulta:**
- `DOCUMENTACION.md` - Detalles técnicos
- `ARQUITECTURA.md` - Cómo funciona
- `COMANDOS_UTILES.md` - Comandos CLI
- `TESTING.md` - Cómo probar

**Credenciales test:**
```
Email: admin@aquaincuba.com
Pass: password123
```

---

**Última actualización**: Enero 2025  
**Versión**: 1.0  
**Creado para**: Desarrollo rápido de AquaIncuba UNIA

*Imprime esta tarjeta y tenla a mano* 📋
