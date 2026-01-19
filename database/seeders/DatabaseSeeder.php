<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\RolUsuario;
use App\Models\Menu;
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

        // Crear maestros globales (sin dependencias de empresa)
        $this->seedUnidadesMedida();
        $this->seedTiposSensores();

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

        // Crear dispositivos MQTT (depende de empresa)
        $this->call(DispositivoMqttSeeder::class);

        // Crear incubadoras PRIMERO (depende de empresa)
        $this->call(IncubadoraSeeder::class);

        // Crear los 12 sensores exactos del PLC V4 (depende de empresa e incubadoras)
        $this->call(SensorPLCSeeder::class);

        // Crear estudios de calidad de agua (depende de incubadoras)
        $this->call(EstudioCalidadAguaSeeder::class);

        // Crear muestras de estudio (depende de estudios)
        $this->call(MuestraEstudioSeeder::class);

        // Crear lecturas de sensores para pruebas (depende de sensores)
        $this->call(LecturaSensorSeeder::class);

        // Crear logs MQTT para testing (depende de dispositivos)
        $this->call(LogMqttSeeder::class);

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
                ['nombre' => 'Usuarios', 'url' => '/usuarios', 'orden' => 1, 'icono' => 'feather feather-users'],
                ['nombre' => 'Menús y Permisos', 'url' => '/menu-permissions', 'orden' => 2, 'icono' => 'feather feather-menu'],
                ['nombre' => 'Roles y Permisos', 'url' => '/roles', 'orden' => 3, 'icono' => 'feather feather-lock'],
                ['nombre' => 'Unidades de Medida', 'url' => '/unidades-medida', 'orden' => 4, 'icono' => 'feather feather-ruler'],
                ['nombre' => 'Tipos de Sensores', 'url' => '/tipos-sensores', 'orden' => 5, 'icono' => 'feather feather-layers'],
                ['nombre' => 'Sensores', 'url' => '/sensores', 'orden' => 6, 'icono' => 'feather feather-activity'],
                ['nombre' => 'Incubadoras', 'url' => '/incubadoras', 'orden' => 7, 'icono' => 'feather feather-droplet'],
            ],
            'ESTUDIOS' => [
                ['nombre' => 'Calidad de Agua', 'url' => '/estudios', 'orden' => 1, 'icono' => 'feather feather-droplets'],
                ['nombre' => 'Ver Datos', 'url' => '/estudios-datos', 'orden' => 2, 'icono' => 'feather feather-database'],
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
            'Tipos de Sensores',
            'Unidades de Medida',
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

    /**
     * Crear unidades de medida maestras
     */
    private function seedUnidadesMedida(): void
    {
        $this->command->info('📏 Creando unidades de medida...');

        $unidades = [
            // Temperatura
            ['nombre' => 'Grados Celsius', 'simbolo' => '°C', 'descripcion' => 'Temperatura en grados Celsius'],
            ['nombre' => 'Grados Fahrenheit', 'simbolo' => '°F', 'descripcion' => 'Temperatura en grados Fahrenheit'],
            ['nombre' => 'Kelvin', 'simbolo' => 'K', 'descripcion' => 'Temperatura en Kelvin'],
            
            // pH
            ['nombre' => 'pH', 'simbolo' => 'pH', 'descripcion' => 'Escala de pH (0-14)'],
            
            // Concentración
            ['nombre' => 'Miligramos por Litro', 'simbolo' => 'mg/L', 'descripcion' => 'Concentración en miligramos por litro'],
            ['nombre' => 'Partes por Millón', 'simbolo' => 'ppm', 'descripcion' => 'Concentración en partes por millón'],
            ['nombre' => 'Gramos por Litro', 'simbolo' => 'g/L', 'descripcion' => 'Concentración en gramos por litro'],
            
            // Conductividad
            ['nombre' => 'Milisiemens por Centímetro', 'simbolo' => 'mS/cm', 'descripcion' => 'Conductividad eléctrica en milisiemens por centímetro'],
            ['nombre' => 'Microsiemens por Centímetro', 'simbolo' => 'µS/cm', 'descripcion' => 'Conductividad eléctrica en microsiemens por centímetro'],
            
            // Turbidez
            ['nombre' => 'Unidades de Turbidez Nefelométrica', 'simbolo' => 'NTU', 'descripcion' => 'Unidades de turbidez nefelométrica'],
            ['nombre' => 'Unidades de Turbidez Formazina', 'simbolo' => 'FTU', 'descripcion' => 'Unidades de turbidez formazina'],
            
            // Salinidad
            ['nombre' => 'Partes por Mil', 'simbolo' => 'ppt', 'descripcion' => 'Salinidad en partes por mil'],
            ['nombre' => 'PSU', 'simbolo' => 'PSU', 'descripcion' => 'Practical Salinity Unit'],
            
            // Presión
            ['nombre' => 'Bares', 'simbolo' => 'bar', 'descripcion' => 'Presión en bares'],
            ['nombre' => 'Atmósferas', 'simbolo' => 'atm', 'descripcion' => 'Presión en atmósferas'],
            ['nombre' => 'Pascales', 'simbolo' => 'Pa', 'descripcion' => 'Presión en Pascales'],
            
            // Volumen
            ['nombre' => 'Litros', 'simbolo' => 'L', 'descripcion' => 'Volumen en litros'],
            ['nombre' => 'Mililitros', 'simbolo' => 'mL', 'descripcion' => 'Volumen en mililitros'],
            ['nombre' => 'Metros Cúbicos', 'simbolo' => 'm³', 'descripcion' => 'Volumen en metros cúbicos'],
            
            // Oxígeno Disuelto
            ['nombre' => 'Miligramos de Oxígeno por Litro', 'simbolo' => 'mg O₂/L', 'descripcion' => 'Oxígeno disuelto en agua en miligramos por litro'],
            ['nombre' => 'Porcentaje de Saturación de Oxígeno', 'simbolo' => '%DO', 'descripcion' => 'Porcentaje de saturación de oxígeno disuelto'],
            ['nombre' => 'Partes por Millón de Oxígeno', 'simbolo' => 'ppm O₂', 'descripcion' => 'Concentración de oxígeno disuelto en partes por millón'],
            
            // Radiación (Luz)
            ['nombre' => 'Microeinsteins por Metro Cuadrado por Segundo', 'simbolo' => 'µmol/(m².s)', 'descripcion' => 'Radiación fotosintética activa (PAR)'],
            ['nombre' => 'Luxes', 'simbolo' => 'lux', 'descripcion' => 'Unidad de iluminancia'],
            ['nombre' => 'Microvatios por Centímetro Cuadrado', 'simbolo' => 'µW/cm²', 'descripcion' => 'Densidad de flujo radiante ultravioleta'],
            
            // Alcalinidad
            ['nombre' => 'Miliequivalentes por Litro', 'simbolo' => 'meq/L', 'descripcion' => 'Alcalinidad total en miliequivalentes'],
            ['nombre' => 'Milimoles por Litro de CaCO₃', 'simbolo' => 'mmol/L CaCO₃', 'descripcion' => 'Alcalinidad expresada como carbonato de calcio'],
            
            // Dureza
            ['nombre' => 'Grados de Dureza Alemana', 'simbolo' => '°dH', 'descripcion' => 'Dureza total en grados alemanes'],
            ['nombre' => 'Grados de Dureza Francesa', 'simbolo' => '°fH', 'descripcion' => 'Dureza total en grados franceses'],
            ['nombre' => 'Partes por Millón de CaCO₃', 'simbolo' => 'ppm CaCO₃', 'descripcion' => 'Dureza total expresada como carbonato de calcio'],
            
            // Nutrientes
            ['nombre' => 'Miligramos de Nitrógeno por Litro', 'simbolo' => 'mg N/L', 'descripcion' => 'Concentración de nitrógeno total'],
            ['nombre' => 'Miligramos de Fósforo por Litro', 'simbolo' => 'mg P/L', 'descripcion' => 'Concentración de fósforo total'],
        ];

        foreach ($unidades as $unidad) {
            DB::table('unidades_medida')->updateOrInsert(
                ['nombre' => $unidad['nombre']],
                array_merge($unidad, ['activo' => true])
            );
        }

        $this->command->line("  ✓ " . count($unidades) . " unidades de medida creadas");
    }

    /**
     * Crear tipos de sensores maestros
     */
    private function seedTiposSensores(): void
    {
        $this->command->info('🔌 Creando tipos de sensores...');

        $tipos = [
            [
                'nombre' => 'Temperatura',
                'descripcion' => 'Sensor para medir temperatura del agua',
            ],
            [
                'nombre' => 'pH',
                'descripcion' => 'Sensor para medir el pH del agua',
            ],
            [
                'nombre' => 'Oxígeno Disuelto',
                'descripcion' => 'Sensor para medir oxígeno disuelto en el agua',
            ],
            [
                'nombre' => 'Conductividad',
                'descripcion' => 'Sensor para medir conductividad eléctrica del agua',
            ],
            [
                'nombre' => 'Turbidez',
                'descripcion' => 'Sensor para medir turbidez del agua',
            ],
            [
                'nombre' => 'Salinidad',
                'descripcion' => 'Sensor para medir salinidad del agua',
            ],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_sensores')->updateOrInsert(
                ['nombre' => $tipo['nombre']],
                array_merge($tipo, ['activo' => true])
            );
        }

        $this->command->line("  ✓ " . count($tipos) . " tipos de sensores creados");
    }
}
