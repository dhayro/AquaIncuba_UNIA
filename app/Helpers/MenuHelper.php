<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MenuHelper
{
    /**
     * Obtener menús principales (nivel 0) para el usuario actual con permisos
     */
    public static function getMainMenus()
    {
        $user = Auth::user();
        $empresaId = $user->id_empresa ?? null;
        
        // Obtener todos los menús nivel 0
        $mainMenus = Menu::where('id_empresa', $empresaId)
            ->where('nivel', 0)
            ->orderBy('orden', 'asc')
            ->get();

        // Filtrar solo los grupos que tienen al menos un submenú permitido
        return $mainMenus->filter(function($menu) use ($user, $empresaId) {
            $submenus = Menu::where('id_empresa', $empresaId)
                ->where('id_padre', $menu->id)
                ->where('nivel', 1)
                ->get();

            // Si el grupo no tiene submenús, no lo mostrar
            if ($submenus->isEmpty()) {
                return false;
            }

            // Verificar si el usuario tiene permisos para al menos un submenú
            foreach ($submenus as $submenu) {
                if (self::userHasMenuPermission($user, $submenu)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Obtener submenús de un menú padre filtrando por permisos
     */
    public static function getSubmenus($parentId)
    {
        $user = Auth::user();
        $empresaId = $user->id_empresa ?? null;
        
        $submenus = Menu::where('id_empresa', $empresaId)
            ->where('id_padre', $parentId)
            ->where('nivel', 1)
            ->orderBy('orden', 'asc')
            ->get();

        // Filtrar solo los submenús para los cuales el usuario tiene permisos
        return $submenus->filter(function($submenu) use ($user) {
            return self::userHasMenuPermission($user, $submenu);
        });
    }

    /**
     * Verificar si el usuario tiene permiso para un menú específico
     */
    private static function userHasMenuPermission($user, $menu)
    {
        // Obtener el rol del usuario para esta empresa
        $usuarioRol = DB::table('roles_usuarios')
            ->where('id_usuario', $user->id)
            ->where('id_empresa', $user->id_empresa)
            ->first();

        if (!$usuarioRol) {
            return false;
        }

        // Verificar si el rol tiene permiso para este menú
        $permiso = DB::table('permisos_menus_roles')
            ->where('id_rol', $usuarioRol->id_rol)
            ->where('id_menu', $menu->id)
            ->first();

        return $permiso !== null && $permiso->puede_ver == 1;
    }

    /**
     * Obtener la URL de ruta basada en el nombre del menú
     */
    public static function getRouteFromMenu($menu)
    {
        if (!$menu->url) {
            return null;
        }

        // Mapeo de URLs a rutas nombradas
        $routeMap = [
            '/dashboard' => 'dashboard',
            '/admin/incubators' => 'incubadoras.index',
            '/admin/sensors' => 'sensores.index',
            '/admin/users' => 'usuarios.index',
            '/roles' => 'roles.index',
            '/admin/roles' => 'roles.index',
            '/admin/menu-permissions' => 'menu-permissions.index',
            '/settings/company' => 'empresa.show',
            '/estudios' => 'estudios.index',
            '/parametros' => 'parametros.index',
            '/muestras-estudio' => 'muestras-estudio.index',
            '/datos-crudos' => 'datos-crudos.index',
            '/datos-procesados' => 'datos-procesados.index',
            '/conclusiones' => 'conclusiones.index',
            '/lecturas' => 'lecturas.index',
            '/alertas' => 'alertas.index',
            '/dispositivos-mqtt' => 'dispositivos-mqtt.index',
            '/temas-mqtt' => 'temas-mqtt.index',
            '/logs-mqtt' => 'logs-mqtt.index',
            '/mqtt/configuracion' => 'mqtt.configuracion',
            '/settings/profile' => 'profile.show',
            '/settings/system' => 'system.settings',
        ];

        return $routeMap[$menu->url] ?? null;
    }

    /**
     * Obtener el icono según el nombre del menú
     */
    public static function getIconFromMenu($menu)
    {
        $iconMap = [
            'Dashboard' => 'feather feather-home',
            'DASHBOARD' => 'feather feather-home',
            'ADMINISTRACIÓN' => 'feather feather-lock',
            'Incubadoras' => 'feather feather-droplet',
            'Sensores' => 'feather feather-activity',
            'Usuarios' => 'feather feather-users',
            'Roles y Permisos' => 'feather feather-lock',
            'Menús y Permisos' => 'feather feather-menu',
            'ESTUDIOS' => 'feather feather-bar-chart-2',
            'Calidad de Agua' => 'feather feather-droplets',
            'Parámetros' => 'feather feather-layers',
            'Muestras' => 'feather feather-layers',
            'MONITOREO' => 'feather feather-activity',
            'Lecturas' => 'feather feather-zap',
            'Alertas' => 'feather feather-alert-circle',
            'Dispositivos' => 'feather feather-wifi',
            'Temas MQTT' => 'feather feather-settings',
            'Logs MQTT' => 'feather feather-server',
            'CONFIGURACIÓN' => 'feather feather-settings',
            'Configuración MQTT' => 'feather feather-settings',
            'Empresa' => 'feather feather-briefcase',
            'Perfil de Usuario' => 'feather feather-user',
            'Sistema' => 'feather feather-settings',
        ];

        return $iconMap[$menu->nombre] ?? 'feather feather-menu';
    }

    /**
     * Obtener la URL actual para comparar con menús activos
     */
    public static function getCurrentRoute()
    {
        return \request()->route()?->getName();
    }

    /**
     * Convertir nombre del menú a catName para el sidebar
     */
    public static function getCatNameFromMenu($menu)
    {
        $catNameMap = [
            'DASHBOARD' => 'dashboard',
            'ADMINISTRACIÓN' => 'admin',
            'ESTUDIOS' => 'estudios',
            'MONITOREO' => 'monitoreo',
            'CONFIGURACIÓN' => 'configuracion',
            'Infraestructura' => 'infraestructura',
            'Menús y Permisos' => 'menuspermisos',
        ];

        return $catNameMap[$menu->nombre] ?? strtolower(str_replace(' ', '', $menu->nombre));
    }

    /**
     * Verificar si la ruta actual pertenece al grupo de menú
     */
    public static function isCurrentRouteInMenu($menu, $submenus)
    {
        $currentPath = \request()->getPathInfo();
        $currentRouteName = \request()->route()?->getName() ?? '';
        
        // Obtener el prefijo del menú
        $menuPrefix = self::getMenuPrefix($menu);
        
        // VERIFICACIÓN 1: Por nombre de ruta (funciona incluso sin submenús definidos)
        // Si el menú tiene prefijo "roles" y la ruta es "roles.permisos.edit" → Match
        if ($menuPrefix && strpos($currentRouteName, $menuPrefix . '.') === 0) {
            return true;
        }
        
        // VERIFICACIÓN 2: Por URL exacta del menú
        if ($menu->url) {
            $menuUrl = rtrim($menu->url, '/');
            $currentPathClean = rtrim($currentPath, '/');
            
            if ($currentPathClean === $menuUrl || strpos($currentPathClean, $menuUrl . '/') === 0) {
                return true;
            }
        }
        
        // VERIFICACIÓN 3: Por submenús (si existen)
        if (!empty($submenus)) {
            foreach ($submenus as $submenu) {
                if ($submenu->url) {
                    $submenuUrl = rtrim($submenu->url, '/');
                    $currentPathClean = rtrim($currentPath, '/');
                    
                    if ($currentPathClean === $submenuUrl || strpos($currentPathClean, $submenuUrl . '/') === 0) {
                        return true;
                    }
                }
                
                $routeName = self::getRouteFromMenu($submenu);
                if ($routeName && \request()->routeIs(str_replace('.', '.*', $routeName))) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Extraer el prefijo de una ruta para comparación
     */
    private static function getMenuPrefix($menu)
    {
        if ($menu->url) {
            $url = rtrim($menu->url, '/');
            // Obtener la última parte después del último /
            $parts = explode('/', trim($url, '/'));
            $lastPart = end($parts);
            return !empty($lastPart) ? $lastPart : null;
        }
        return null;
    }
}
