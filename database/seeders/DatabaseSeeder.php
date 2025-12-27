<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\RolUsuario;
use App\Models\Menu;
use App\Models\ParametroEstudio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Para hacer refresh completo:
     * php artisan migrate:refresh --seed
     * 
     * Para resetear solo el seeder:
     * php artisan db:seed
     */
    public function run(): void
    {
        // Limpiar datos en orden inverso de dependencias (si es refresh)
        if ($this->isRefreshing()) {
            $this->cleanData();
        }

        // Crear empresa (sin dependencias)
        $empresa = $this->seedEmpresa();

        // Crear roles (depende de empresa para id_empresa)
        $roles = $this->seedRoles($empresa);

        // Crear usuarios (depende de empresa)
        $usuarios = $this->seedUsuarios($empresa);

        // Asignar roles a usuarios (depende de usuarios y roles)
        $this->seedRolesUsuarios($usuarios, $empresa);

        // Crear menús (depende de empresa)
        $this->seedMenus($empresa);

        // Asignar permisos a roles (depende de roles y menús)
        $this->seedPermisosMenus($empresa);

        // Crear parámetros de estudio (depende de empresa)
        $this->seedParametrosEstudio($empresa);

        $this->command->info('✅ Seeder completado exitosamente');
    }

    /**
     * Determinar si estamos haciendo un refresh
     */
    private function isRefreshing(): bool
    {
        return DB::table('usuarios')->count() > 0;
    }

    /**
     * Limpiar datos en orden inverso de dependencias
     */
    private function cleanData(): void
    {
        $this->command->warn('🗑️  Limpiando datos existentes...');

        // Orden inverso de creación (respetando claves foráneas)
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Tablas de relación y datos dependientes
            DB::table('permisos_menus_roles')->truncate();
            DB::table('roles_usuarios')->truncate();
            
            // Datos principales
            DB::table('menus')->truncate();
            DB::table('parametros_estudio')->truncate();
            DB::table('usuarios')->truncate();
            DB::table('roles')->truncate();
            DB::table('empresas')->truncate();
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('✅ Datos limpiados correctamente');
        } catch (\Exception $e) {
            $this->command->error('❌ Error al limpiar datos: ' . $e->getMessage());
        }
    }

    /**
     * Crear roles base del sistema
     */
    private function seedRoles(Empresa $empresa): array
    {
        $this->command->info('📋 Creando roles...');

        $roles = [
            ['nombre' => 'administrador', 'descripcion' => 'Administrador del sistema con acceso total'],
            ['nombre' => 'operador', 'descripcion' => 'Operador que gestiona incubadoras y sensores'],
            ['nombre' => 'revisor', 'descripcion' => 'Revisor de estudios de calidad de agua'],
        ];

        $rolesCreados = [];
        foreach ($roles as $rol) {
            $rol['id_empresa'] = $empresa->id;
            $rolesCreados[] = Rol::create($rol);
            $this->command->line("  ✓ Rol '{$rol['nombre']}' creado para {$empresa->nombre}");
        }
        
        return $rolesCreados;
    }

    /**
     * Crear empresa principal
     */
    private function seedEmpresa(): Empresa
    {
        $this->command->info('🏢 Creando empresa...');

        $empresa = Empresa::create([
            'rfc' => 'TEST000000ABC',
            'nombre' => 'AquaIncuba UNIA',
            'correo' => 'contacto@aquaincuba.com',
            'telefono' => '+34-900-123456',
            'direccion' => 'Calle Principal 123',
            'ciudad' => 'Madrid',
            'estado' => 'Madrid',
            'codigo_postal' => '28001',
            'descripcion' => 'Sistema de incubadoras con control de calidad de agua',
        ]);

        $this->command->line("  ✓ Empresa '{$empresa->nombre}' creada");
        return $empresa;
    }

    /**
     * Crear usuarios de prueba
     */
    private function seedUsuarios(Empresa $empresa): array
    {
        $this->command->info('👥 Creando usuarios...');

        $usuarios = [
            [
                'nombre' => 'Admin',
                'correo' => 'admin@aquaincuba.com',
                'contraseña' => Hash::make('password123'),
                'correo_verificado_en' => now(),
                'id_empresa' => $empresa->id,
            ],
            [
                'nombre' => 'Operador 1',
                'correo' => 'operador@aquaincuba.com',
                'contraseña' => Hash::make('password123'),
                'correo_verificado_en' => now(),
                'id_empresa' => $empresa->id,
            ],
            [
                'nombre' => 'Revisor 1',
                'correo' => 'revisor@aquaincuba.com',
                'contraseña' => Hash::make('password123'),
                'correo_verificado_en' => now(),
                'id_empresa' => $empresa->id,
            ],
        ];

        $usuariosCreados = [];
        foreach ($usuarios as $usuario) {
            $u = Usuario::create($usuario);
            $usuariosCreados[] = $u;
            $this->command->line("  ✓ Usuario '{$usuario['nombre']}' ({$usuario['correo']}) creado");
        }

        return $usuariosCreados;
    }

    /**
     * Asignar roles a usuarios
     */
    private function seedRolesUsuarios(array $usuarios, Empresa $empresa): void
    {
        $this->command->info('🔗 Asignando roles a usuarios...');

        $roles = Rol::all();

        // Admin tiene rol administrador
        $adminRol = $roles->firstWhere('nombre', 'administrador');
        if ($adminRol && isset($usuarios[0])) {
            RolUsuario::create([
                'id_usuario' => $usuarios[0]->id,
                'id_rol' => $adminRol->id,
                'id_empresa' => $empresa->id,
            ]);
            $this->command->line("  ✓ Rol 'administrador' asignado a '{$usuarios[0]->nombre}'");
        }

        // Operador tiene rol operador
        $operadorRol = $roles->firstWhere('nombre', 'operador');
        if ($operadorRol && isset($usuarios[1])) {
            RolUsuario::create([
                'id_usuario' => $usuarios[1]->id,
                'id_rol' => $operadorRol->id,
                'id_empresa' => $empresa->id,
            ]);
            $this->command->line("  ✓ Rol 'operador' asignado a '{$usuarios[1]->nombre}'");
        }

        // Revisor tiene rol revisor
        $revisorRol = $roles->firstWhere('nombre', 'revisor');
        if ($revisorRol && isset($usuarios[2])) {
            RolUsuario::create([
                'id_usuario' => $usuarios[2]->id,
                'id_rol' => $revisorRol->id,
                'id_empresa' => $empresa->id,
            ]);
            $this->command->line("  ✓ Rol 'revisor' asignado a '{$usuarios[2]->nombre}'");
        }
    }

    /**
     * Crear estructura de menús
     */
    private function seedMenus(Empresa $empresa): void
    {
        $this->command->info('📑 Creando estructura de menús...');

        // Crear todos los menús principales (nivel 0)
        $groups = [
            'DASHBOARD' => ['orden' => 1, 'es_colapsible' => false],
            'ADMINISTRACIÓN' => ['orden' => 2, 'es_colapsible' => true],
            'ESTUDIOS' => ['orden' => 3, 'es_colapsible' => true],
            'MONITOREO' => ['orden' => 4, 'es_colapsible' => true],
            'CONFIGURACIÓN' => ['orden' => 5, 'es_colapsible' => true],
        ];

        $groupIds = [];
        foreach ($groups as $nombre => $data) {
            $group = Menu::create([
                'nombre' => $nombre,
                'nivel' => 0,
                'orden' => $data['orden'],
                'es_colapsible' => $data['es_colapsible'],
                'id_empresa' => $empresa->id,
            ]);
            $groupIds[$nombre] = $group->id;
            $this->command->line("  ✓ Grupo '{$nombre}' creado");
        }

        // Crear los submenús (nivel 1)
        $items = [
            'DASHBOARD' => [
                ['nombre' => 'Dashboard', 'url' => '/dashboard', 'orden' => 1, 'icono' => 'feather feather-home'],
            ],
            'ADMINISTRACIÓN' => [
                ['nombre' => 'Incubadoras', 'url' => '/admin/incubators', 'orden' => 1, 'icono' => 'feather feather-droplet'],
                ['nombre' => 'Sensores', 'url' => '/admin/sensors', 'orden' => 2, 'icono' => 'feather feather-activity'],
                ['nombre' => 'Usuarios', 'url' => '/admin/users', 'orden' => 3, 'icono' => 'feather feather-users'],
                ['nombre' => 'Roles y Permisos', 'url' => '/admin/roles', 'orden' => 4, 'icono' => 'feather feather-lock'],
                ['nombre' => 'Menús y Permisos', 'url' => '/admin/menu-permissions', 'orden' => 5, 'icono' => 'feather feather-menu'],
            ],
            'ESTUDIOS' => [
                ['nombre' => 'Calidad de Agua', 'url' => '/estudios', 'orden' => 1, 'icono' => 'feather feather-droplets'],
                ['nombre' => 'Parámetros', 'url' => '/parametros', 'orden' => 2, 'icono' => 'feather feather-layers'],
            ],
            'MONITOREO' => [
                ['nombre' => 'Lecturas', 'url' => '/lecturas', 'orden' => 1, 'icono' => 'feather feather-zap'],
                ['nombre' => 'Alertas', 'url' => '/alertas', 'orden' => 2, 'icono' => 'feather feather-alert-circle'],
                ['nombre' => 'Dispositivos', 'url' => '/dispositivos', 'orden' => 3, 'icono' => 'feather feather-wifi'],
                ['nombre' => 'Temas MQTT', 'url' => '/temas-mqtt', 'orden' => 4, 'icono' => 'feather feather-settings'],
                ['nombre' => 'Logs MQTT', 'url' => '/logs-mqtt', 'orden' => 5, 'icono' => 'feather feather-server'],
            ],
            'CONFIGURACIÓN' => [
                ['nombre' => 'Empresa', 'url' => '/settings/company', 'orden' => 1, 'icono' => 'feather feather-briefcase'],
                ['nombre' => 'Perfil de Usuario', 'url' => '/settings/profile', 'orden' => 2, 'icono' => 'feather feather-user'],
                ['nombre' => 'Sistema', 'url' => '/settings/system', 'orden' => 3, 'icono' => 'feather feather-settings'],
            ],
        ];

        foreach ($items as $groupName => $menuItems) {
            $parentId = $groupIds[$groupName];
            foreach ($menuItems as $item) {
                Menu::create(array_merge($item, [
                    'nivel' => 1,
                    'id_padre' => $parentId,
                    'id_empresa' => $empresa->id,
                ]));
                $this->command->line("    ✓ Menú '{$item['nombre']}' creado");
            }
        }
    }

    /**
     * Crear parámetros de estudio
     */
    private function seedParametrosEstudio(Empresa $empresa): void
    {
        $this->command->info('⚙️  Creando parámetros de estudio...');

        $parametros = [
            [
                'codigo' => 'TEMP',
                'nombre' => 'Temperatura',
                'unidad' => '°C',
                'tipo_medicion' => 'automatica',
                'minimo_optimo' => 25.0,
                'maximo_optimo' => 30.0,
                'minimo_critico' => 15.0,
                'maximo_critico' => 35.0,
                'decimales' => 2,
            ],
            [
                'codigo' => 'PH',
                'nombre' => 'pH',
                'unidad' => '',
                'tipo_medicion' => 'automatica',
                'minimo_optimo' => 6.8,
                'maximo_optimo' => 7.5,
                'minimo_critico' => 6.0,
                'maximo_critico' => 8.5,
                'decimales' => 2,
            ],
            [
                'codigo' => 'DISS_OXY',
                'nombre' => 'Oxígeno Disuelto',
                'unidad' => 'ppm',
                'tipo_medicion' => 'automatica',
                'minimo_optimo' => 6.0,
                'maximo_optimo' => 8.5,
                'minimo_critico' => 4.0,
                'maximo_critico' => 10.0,
                'decimales' => 2,
            ],
            [
                'codigo' => 'TURB',
                'nombre' => 'Turbidez',
                'unidad' => 'NTU',
                'tipo_medicion' => 'automatica',
                'minimo_optimo' => 0.0,
                'maximo_optimo' => 2.0,
                'minimo_critico' => 0.0,
                'maximo_critico' => 5.0,
                'decimales' => 2,
            ],
            [
                'codigo' => 'COND',
                'nombre' => 'Conductividad',
                'unidad' => 'μS/cm',
                'tipo_medicion' => 'automatica',
                'minimo_optimo' => 100.0,
                'maximo_optimo' => 500.0,
                'minimo_critico' => 50.0,
                'maximo_critico' => 1000.0,
                'decimales' => 1,
            ],
        ];

        foreach ($parametros as $parametro) {
            ParametroEstudio::create(array_merge($parametro, [
                'id_empresa' => $empresa->id,
            ]));
            $this->command->line("  ✓ Parámetro '{$parametro['nombre']}' ({$parametro['codigo']}) creado");
        }
    }

    /**
     * Asignar permisos de menús a roles
     */
    private function seedPermisosMenus(Empresa $empresa): void
    {
        $this->command->info('🔐 Asignando permisos de menús a roles...');

        // Obtener todos los roles y menús
        $roles = Rol::all();
        $menus = Menu::where('id_empresa', $empresa->id)->where('nivel', 1)->get();

        // Obtener el rol administrador
        $adminRol = $roles->firstWhere('nombre', 'administrador');

        // El administrador tiene acceso a todos los menús
        foreach ($menus as $menu) {
            DB::table('permisos_menus_roles')->insert([
                'id_rol' => $adminRol->id,
                'id_menu' => $menu->id,
                'puede_ver' => true,
                'puede_crear' => true,
                'puede_editar' => true,
                'puede_eliminar' => true,
            ]);
        }
        $this->command->line("  ✓ Permisos totales asignados a rol 'administrador'");

        // Permisos para el operador
        $operadorRol = $roles->firstWhere('nombre', 'operador');
        $operadorMenus = $menus->whereIn('nombre', [
            'Dashboard',
            'Incubadoras',
            'Sensores',
            'Lecturas',
            'Alertas',
            'Dispositivos',
        ])->pluck('id');

        foreach ($operadorMenus as $menuId) {
            DB::table('permisos_menus_roles')->insert([
                'id_rol' => $operadorRol->id,
                'id_menu' => $menuId,
                'puede_ver' => true,
                'puede_crear' => false,
                'puede_editar' => false,
                'puede_eliminar' => false,
            ]);
        }
        $this->command->line("  ✓ Permisos asignados a rol 'operador'");

        // Permisos para el revisor
        $revisorRol = $roles->firstWhere('nombre', 'revisor');
        $revisorMenus = $menus->whereIn('nombre', [
            'Dashboard',
            'Calidad de Agua',
            'Parámetros',
            'Lecturas',
        ])->pluck('id');

        foreach ($revisorMenus as $menuId) {
            DB::table('permisos_menus_roles')->insert([
                'id_rol' => $revisorRol->id,
                'id_menu' => $menuId,
                'puede_ver' => true,
                'puede_crear' => false,
                'puede_editar' => false,
                'puede_eliminar' => false,
            ]);
        }
        $this->command->line("  ✓ Permisos asignados a rol 'revisor'");
    }
}
