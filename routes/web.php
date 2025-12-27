<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\MenuPermissionController;
use App\Http\Controllers\Admin\IncubadoraController;
use App\Http\Controllers\Admin\SensorController;
use App\Http\Controllers\Admin\EstudioCalidadAguaController;
use App\Http\Controllers\Admin\LecturaSensorController;
use App\Http\Controllers\Admin\AlertaMqttController;
use App\Http\Controllers\Admin\ConfiguracionMqttController;
use App\Http\Controllers\Admin\DispositivoMqttController;
use App\Http\Controllers\Admin\TemaMqttController;
use App\Http\Controllers\Admin\LogMqttController;
use App\Http\Controllers\Admin\DatoCrudoEstudioController;
use App\Http\Controllers\Admin\DatoProcessadoEstudioController;
use App\Http\Controllers\Admin\ConclusionEstudioController;
use App\Http\Controllers\Admin\ParametroEstudioController;
use App\Http\Controllers\Admin\MuestraEstudioController;

/**
 * =======================
 *   Authentication Routes
 * =======================
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/**
 * =======================
 *    Redirect Root
 * =======================
 */
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

/**
 * =======================
 *    Protected Routes
 * =======================
 */
Route::middleware('auth')->group(function () {
    
    /**
     * Dashboard
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Administración - Usuarios
     */
    Route::get('/usuarios/data', [UsuarioController::class, 'getUsuariosData'])->name('usuarios.get-data');
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

    /**
     * Administración - Empresa
     */
    Route::get('/empresa', [EmpresaController::class, 'show'])->name('empresa.show');
    Route::get('/empresa/editar', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');

    /**
     * Administración - Roles y Permisos
     */
    Route::get('/roles/data', [RolController::class, 'getRolesData'])->name('roles.get-data');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/{rol}/permisos', [RolController::class, 'editPermisos'])->name('roles.permisos.edit');
    Route::put('/roles/{rol}/permisos', [RolController::class, 'actualizarPermisos'])->name('roles.permisos.update');

    /**
     * Administración - Menús y Permisos
     */
    Route::prefix('admin/menu-permissions')->name('menu-permissions.')->group(function () {
        Route::get('/', [MenuPermissionController::class, 'index'])->name('index');
        Route::get('/user/{user}/roles', [MenuPermissionController::class, 'showUserPermissions'])->name('user-roles');
        Route::put('/user/{user}/roles', [MenuPermissionController::class, 'updateUserRoles'])->name('update-user-roles');
        Route::get('/role/{role}/permissions', [MenuPermissionController::class, 'showRolePermissions'])->name('role-permissions');
        Route::put('/role/{role}/permissions', [MenuPermissionController::class, 'updateRolePermissions'])->name('update-role-permissions');
        // Rutas para menús
        Route::get('/menus/data', [MenuPermissionController::class, 'getMenusData'])->name('get-menus-data');
        Route::post('/menus', [MenuPermissionController::class, 'storeMenu'])->name('store-menu');
        Route::get('/menus/{menu}', [MenuPermissionController::class, 'getMenu'])->name('get-menu');
        Route::put('/menus/{menu}', [MenuPermissionController::class, 'updateMenu'])->name('update-menu');
        Route::delete('/menus/{menu}', [MenuPermissionController::class, 'destroyMenu'])->name('destroy-menu');
    });

    /**
     * Administración - Incubadoras
     */
    Route::resource('incubadoras', IncubadoraController::class)->names([
        'index' => 'incubadoras.index',
        'create' => 'incubadoras.create',
        'store' => 'incubadoras.store',
        'edit' => 'incubadoras.edit',
        'update' => 'incubadoras.update',
        'destroy' => 'incubadoras.destroy',
    ]);
    Route::get('/incubadoras/{incubadora}/sensores', [IncubadoraController::class, 'asignarSensores'])->name('incubadoras.sensores');
    Route::put('/incubadoras/{incubadora}/sensores', [IncubadoraController::class, 'guardarSensores'])->name('incubadoras.sensores.save');

    /**
     * Administración - Sensores
     */
    Route::resource('sensores', SensorController::class)->names([
        'index' => 'sensores.index',
        'create' => 'sensores.create',
        'store' => 'sensores.store',
        'edit' => 'sensores.edit',
        'update' => 'sensores.update',
        'destroy' => 'sensores.destroy',
    ]);

    /**
     * Administración - Estudios de Calidad de Agua
     */
    Route::resource('estudios', EstudioCalidadAguaController::class)->names([
        'index' => 'estudios.index',
        'create' => 'estudios.create',
        'store' => 'estudios.store',
        'show' => 'estudios.show',
        'edit' => 'estudios.edit',
        'update' => 'estudios.update',
        'destroy' => 'estudios.destroy',
    ]);

    /**
     * Administración - Lecturas de Sensores
     */
    Route::resource('lecturas', LecturaSensorController::class)->names([
        'index' => 'lecturas.index',
        'create' => 'lecturas.create',
        'store' => 'lecturas.store',
        'show' => 'lecturas.show',
        'edit' => 'lecturas.edit',
        'update' => 'lecturas.update',
        'destroy' => 'lecturas.destroy',
    ]);

    /**
     * Administración - Alertas MQTT
     */
    Route::resource('alertas', AlertaMqttController::class)->names([
        'index' => 'alertas.index',
        'create' => 'alertas.create',
        'store' => 'alertas.store',
        'edit' => 'alertas.edit',
        'update' => 'alertas.update',
        'destroy' => 'alertas.destroy',
    ]);
    Route::put('/alertas/{alerta}/toggle', [AlertaMqttController::class, 'toggle'])->name('alertas.toggle');

    /**
     * Administración - Configuración MQTT
     */
    Route::get('/mqtt/configuracion', [ConfiguracionMqttController::class, 'index'])->name('mqtt.configuracion');
    Route::get('/mqtt/editar', [ConfiguracionMqttController::class, 'edit'])->name('mqtt.edit');
    Route::put('/mqtt/actualizar', [ConfiguracionMqttController::class, 'update'])->name('mqtt.update');
    Route::post('/mqtt/test', [ConfiguracionMqttController::class, 'testConnection'])->name('mqtt.test');

    /**
     * Administración - Dispositivos MQTT
     */
    Route::resource('dispositivos-mqtt', DispositivoMqttController::class)->names([
        'index' => 'dispositivos-mqtt.index',
        'create' => 'dispositivos-mqtt.create',
        'store' => 'dispositivos-mqtt.store',
        'show' => 'dispositivos-mqtt.show',
        'edit' => 'dispositivos-mqtt.edit',
        'update' => 'dispositivos-mqtt.update',
        'destroy' => 'dispositivos-mqtt.destroy',
    ]);

    /**
     * Administración - Temas MQTT
     */
    Route::resource('temas-mqtt', TemaMqttController::class)->names([
        'index' => 'temas-mqtt.index',
        'create' => 'temas-mqtt.create',
        'store' => 'temas-mqtt.store',
        'show' => 'temas-mqtt.show',
        'edit' => 'temas-mqtt.edit',
        'update' => 'temas-mqtt.update',
        'destroy' => 'temas-mqtt.destroy',
    ]);

    /**
     * Administración - Logs MQTT
     */
    Route::resource('logs-mqtt', LogMqttController::class)->only(['index', 'show'])->names([
        'index' => 'logs-mqtt.index',
        'show' => 'logs-mqtt.show',
    ]);
    Route::post('/logs-mqtt/export', [LogMqttController::class, 'export'])->name('logs-mqtt.export');
    Route::post('/logs-mqtt/clean', [LogMqttController::class, 'clean'])->name('logs-mqtt.clean');

    /**
     * Administración - Datos Crudos de Estudio
     */
    Route::resource('datos-crudos', DatoCrudoEstudioController::class)->names([
        'index' => 'datos-crudos.index',
        'create' => 'datos-crudos.create',
        'store' => 'datos-crudos.store',
        'show' => 'datos-crudos.show',
        'edit' => 'datos-crudos.edit',
        'update' => 'datos-crudos.update',
        'destroy' => 'datos-crudos.destroy',
    ]);

    /**
     * Administración - Datos Procesados de Estudio
     */
    Route::resource('datos-procesados', DatoProcessadoEstudioController::class)->names([
        'index' => 'datos-procesados.index',
        'create' => 'datos-procesados.create',
        'store' => 'datos-procesados.store',
        'show' => 'datos-procesados.show',
        'edit' => 'datos-procesados.edit',
        'update' => 'datos-procesados.update',
        'destroy' => 'datos-procesados.destroy',
    ]);

    /**
     * Administración - Conclusiones de Estudio
     */
    Route::resource('conclusiones', ConclusionEstudioController::class)->names([
        'index' => 'conclusiones.index',
        'create' => 'conclusiones.create',
        'store' => 'conclusiones.store',
        'show' => 'conclusiones.show',
        'edit' => 'conclusiones.edit',
        'update' => 'conclusiones.update',
        'destroy' => 'conclusiones.destroy',
    ]);
    Route::get('/conclusiones/{conclusion}/pdf', [ConclusionEstudioController::class, 'exportPdf'])->name('conclusiones.pdf');

    /**
     * Administración - Parámetros de Estudio
     */
    Route::resource('parametros', ParametroEstudioController::class)->names([
        'index' => 'parametros.index',
        'create' => 'parametros.create',
        'store' => 'parametros.store',
        'show' => 'parametros.show',
        'edit' => 'parametros.edit',
        'update' => 'parametros.update',
        'destroy' => 'parametros.destroy',
    ]);

    /**
     * Administración - Muestras de Estudio
     */
    Route::resource('muestras', MuestraEstudioController::class)->names([
        'index' => 'muestras-estudio.index',
        'create' => 'muestras-estudio.create',
        'store' => 'muestras-estudio.store',
        'show' => 'muestras-estudio.show',
        'edit' => 'muestras-estudio.edit',
        'update' => 'muestras-estudio.update',
        'destroy' => 'muestras-estudio.destroy',
    ]);

});

/**
 * =======================
 *          Apps
 * =======================
 */
Route::prefix('app')->group(function () {
    Route::get('/calendar', function () {
        return view('admin/apps/calendar',
            [
                'catName' => 'app',
                'title' => 'Javascript Calendar',
                "breadcrumbs" => ["Apps", "Calendar"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('calendar');
    Route::get('/chat', function () {
        return view('admin/apps/chat',
            [
                'catName' => 'app',
                'title' => 'Chat Application',
                "breadcrumbs" => ["Apps", "Chat"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('chat');
    Route::get('/contacts', function () {
        return view('admin/apps/contacts',
            [
                'catName' => 'app',
                'title' => 'Contact Profile',
                "breadcrumbs" => ["Apps", "Contact"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('contacts');
    Route::get('/mailbox', function () {
        return view('admin/apps/mailbox',
            [
                'catName' => 'app',
                'title' => 'Mailbox',
                "breadcrumbs" => ["Apps", "analytics"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('mailbox');
    Route::get('/notes', function () {
        return view('admin/apps/notes',
            [
                'catName' => 'app',
                'title' => 'Notes',
                "breadcrumbs" => ["Apps", "analytics"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('notes');
    Route::get('/scrumboard', function () {
        return view('admin/apps/scrumboard',
            [
                'catName' => 'app',
                'title' => 'Scrum Task Board',
                "breadcrumbs" => ["Apps", "Scrumboard"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('scrumboard');
    Route::get('/todolist', function () {
        return view('admin/apps/todo-list',
            [
                'catName' => 'app',
                'title' => 'Todo List',
                "breadcrumbs" => ["Apps", "Todo List"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('todolist');

    // Apps => Invoice
    Route::prefix('invoice')->group(function () {
        Route::get('/create', function () {
            return view('admin/apps/invoice/create',
                [
                    'catName' => 'app',
                    'submenu' => 'invoice',
                    'title' => 'Invoice Add',
                    "breadcrumbs" => ["Apps", "Invoice", "Create"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('icreate');
        Route::get('/edit', function () {
            return view('admin/apps/invoice/edit',
                [
                    'catName' => 'app',
                    'submenu' => 'invoice',
                    'title' => 'Invoice Edit',
                    "breadcrumbs" => ["Apps", "Invoice", "Edit"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('iedit');
        Route::get('/list', function () {
            return view('admin/apps/invoice/list',
                [
                    'catName' => 'app',
                    'submenu' => 'invoice',
                    'title' => 'Invoice List',
                    "breadcrumbs" => ["Apps", "Invoice", "List"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('ilist');
        Route::get('/preview', function () {
            return view('admin/apps/invoice/preview',
                [
                    'catName' => 'app',
                    'submenu' => 'invoice',
                    'title' => 'Invoice Preview',
                    "breadcrumbs" => ["Apps", "Invoice", "Preview"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('ipreview');
    });

    // Apps => Ecommerce
    Route::prefix('ecommerce')->group(function () {
        Route::get('/create', function () {
            return view('admin/apps/ecommerce/create',
                [
                    'catName' => 'app',
                    'submenu' => 'ecommerce',
                    'title' => 'Ecommerce Create',
                    "breadcrumbs" => ["Apps", "Ecommerce", "Create"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bcreate');
        Route::get('/edit', function () {
            return view('admin/apps/ecommerce/edit',
                [
                    'catName' => 'app',
                    'submenu' => 'ecommerce',
                    'title' => 'Ecommerce Edit',
                    "breadcrumbs" => ["Apps", "Ecommerce", "Edit"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bedit');
        Route::get('/list', function () {
            return view('admin/apps/ecommerce/list',
                [
                    'catName' => 'app',
                    'submenu' => 'ecommerce',
                    'title' => 'Ecommerce List',
                    "breadcrumbs" => ["Apps", "Ecommerce", "List"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('blist');
        Route::get('/product', function () {
            return view('admin/apps/ecommerce/product',
                [
                    'catName' => 'app',
                    'submenu' => 'ecommerce',
                    'title' => 'Ecommerce Product Details',
                    "breadcrumbs" => ["Apps", "Ecommerce", "Product"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bproduct');
        Route::get('/shop', function () {
            return view('admin/apps/ecommerce/shop',
                [
                    'catName' => 'app',
                    'submenu' => 'ecommerce',
                    'title' => 'Ecommerce Shop',
                    "breadcrumbs" => ["Apps", "Ecommerce", "Shop"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bshop');
    });

    // Apps => Blog
    Route::prefix('blog')->group(function () {
        Route::get('/create', function () {
            return view('admin/apps/blog/create',
                [
                    'catName' => 'app',
                    'submenu' => 'blog',
                    'title' => 'Blog Create',
                    "breadcrumbs" => ["Apps", "Blog", "Create"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bcreate');
        Route::get('/edit', function () {
            return view('admin/apps/blog/edit',
                [
                    'catName' => 'app',
                    'submenu' => 'blog',
                    'title' => 'Blog Edit',
                    "breadcrumbs" => ["Apps", "Blog", "Edit"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bedit');
        Route::get('/list', function () {
            return view('admin/apps/blog/list',
                [
                    'catName' => 'app',
                    'submenu' => 'blog',
                    'title' => 'Blog List',
                    "breadcrumbs" => ["Apps", "Blog", "List"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('blist');
        Route::get('/grid', function () {
            return view('admin/apps/blog/grid',
                [
                    'catName' => 'app',
                    'submenu' => 'blog',
                    'title' => 'Blog',
                    "breadcrumbs" => ["Apps", "Blog", "Grid"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bgrid');
        Route::get('/post', function () {
            return view('admin/apps/blog/post',
                [
                    'catName' => 'app',
                    'submenu' => 'blog',
                    'title' => 'Post Content',
                    "breadcrumbs" => ["Apps", "Blog", "Post"],
                    'scrollspy' => 0,
                    'simplePage' => 0
                ]
            );
        })->name('bpost');
    });
});


/**
 * =======================
 *          Components
 * =======================
 */
Route::prefix('component')->group(function () {
    Route::get('/tabs', function () {
        return view('admin/components/tabs',
            [
                'catName' => 'component',
                'title' => 'Tabs',
                "breadcrumbs" => ["Component", "Tabs"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('tabs');
    Route::get('/accordions', function () {
        return view('admin/components/accordions',
            [
                'catName' => 'component',
                'title' => 'Accordions',
                "breadcrumbs" => ["Component", "Accordions"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('accordions');
    Route::get('/modals', function () {
        return view('admin/components/modals',
            [
                'catName' => 'component',
                'title' => 'Modals',
                "breadcrumbs" => ["Component", "Modals"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('modals');
    Route::get('/cards', function () {
        return view('admin/components/cards',
            [
                'catName' => 'component',
                'title' => 'Card',
                "breadcrumbs" => ["Component", "Cards"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('cards');
    Route::get('/carousel', function () {
        return view('admin/components/carousel',
            [
                'catName' => 'component',
                'title' => 'Bootstrap Carousel',
                "breadcrumbs" => ["Component", "Carousel"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('carousel');
    Route::get('/splide', function () {
        return view('admin/components/splide',
            [
                'catName' => 'component',
                'title' => 'Splide',
                "breadcrumbs" => ["Component", "Splide"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('splide');
    Route::get('/sweet-alerts', function () {
        return view('admin/components/sweetalerts',
            [
                'catName' => 'component',
                'title' => 'SweetAlert',
                "breadcrumbs" => ["Component", "Sweetalerts"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('sweetAlert');
    Route::get('/timeline', function () {
        return view('admin/components/timeline',
            [
                'catName' => 'component',
                'title' => 'Timeline',
                "breadcrumbs" => ["Component", "Timeline"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('timeline');
    Route::get('/notifications', function () {
        return view('admin/components/notifications',
            [
                'catName' => 'component',
                'title' => 'Snackbar',
                "breadcrumbs" => ["Component", "Notifications"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('notifications');
    Route::get('/media-objects', function () {
        return view('admin/components/media-object',
            [
                'catName' => 'component',
                'title' => 'Media Object',
                "breadcrumbs" => ["Component", "Media Objects"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('mediaObject');
    Route::get('/list-group', function () {
        return view('admin/components/list-group',
            [
                'catName' => 'component',
                'title' => 'List Group',
                "breadcrumbs" => ["Component", "List Group"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('listGroup');
    Route::get('/pricing-tables', function () {
        return view('admin/components/pricing-tables',
            [
                'catName' => 'component',
                'title' => 'Pricing Tables',
                "breadcrumbs" => ["Component", "Pricing Tables"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('pricingTable');
    Route::get('/lightbox', function () {
        return view('admin/components/lightbox',
            [
                'catName' => 'component',
                'title' => 'Lightbox',
                "breadcrumbs" => ["Component", "Lightbox"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('lightbox');
    Route::get('/drag-drop', function () {
        return view('admin/components/drag-drop',
            [
                'catName' => 'component',
                'title' => 'Dragula Drag and Drop',
                "breadcrumbs" => ["Component", "Drag and Drop"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('dragDrop');
    Route::get('/font-icons', function () {
        return view('admin/components/font-icon',
            [
                'catName' => 'component',
                'title' => 'Fonticon Icon',
                "breadcrumbs" => ["Component", "Font Icons"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('fontIcons');
    Route::get('/flag-icons', function () {
        return view('admin/components/flag-icon',
            [
                'catName' => 'component',
                'title' => 'SVG Flag Icons',
                "breadcrumbs" => ["Component", "Flag Icons"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('flagIcons');
});


/**
 * =======================
 *          Elements
 * =======================
 */
Route::prefix('element')->group(function () {
    Route::get('/alerts', function () {
        return view(
            'admin/elements/alerts',
            [
                'catName' => 'element',
                'title' => 'Alerts',
                "breadcrumbs" => ["Element", "Alerts"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('alerts');
    Route::get('/avatar', function () {
        return view('admin/elements/avatar',
            [
                'catName' => 'element',
                'title' => 'Avatar',
                "breadcrumbs" => ["Element", "Avatar"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('avatar');
    Route::get('/badges', function () {
        return view('admin/elements/badges',
            [
                'catName' => 'element',
                'title' => 'Badge',
                "breadcrumbs" => ["Element", "Badge"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('badges');
    Route::get('/breadcrumbs', function () {
        return view('admin/elements/breadcrumbs',
            [
                'catName' => 'element',
                'title' => 'Breadcrumb',
                "breadcrumbs" => ["Element", "Breadcrumb"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('breadcrumbs');
    Route::get('/button-groups', function () {
        return view('admin/elements/button-groups',
            [
                'catName' => 'element',
                'title' => 'Button Group',
                "breadcrumbs" => ["Element", "Button Group"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('buttonGroups');
    Route::get('/buttons', function () {
        return view('admin/elements/buttons',
            [
                'catName' => 'element',
                'title' => 'Bootstrap Buttons',
                "breadcrumbs" => ["Element", "Bootstrap Buttons"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('buttons');
    Route::get('/color-library', function () {
        return view('admin/elements/color-lib',
            [
                'catName' => 'element',
                'title' => 'Color Library',
                "breadcrumbs" => ["Element", "Color Library"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('colorLibrary');
    Route::get('/dropdown', function () {
        return view('admin/elements/dropdown',
            [
                'catName' => 'element',
                'title' => 'Dropdown',
                "breadcrumbs" => ["Element", "Dropdown"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('dropdown');
    Route::get('/infobox', function () {
        return view('admin/elements/infobox',
            [
                'catName' => 'element',
                'title' => 'Infobox',
                "breadcrumbs" => ["Element", "Infobox"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('infobox');
    Route::get('/loader', function () {
        return view('admin/elements/loader',
            [
                'catName' => 'element',
                'title' => 'Loaders',
                "breadcrumbs" => ["Element", "Loaders"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('loader');
    Route::get('/pagination', function () {
        return view('admin/elements/pagination',
            [
                'catName' => 'element',
                'title' => 'Pagination',
                "breadcrumbs" => ["Element", "Pagination"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('pagination');
    Route::get('/popovers', function () {
        return view('admin/elements/popovers',
            [
                'catName' => 'element',
                'title' => 'Popovers',
                "breadcrumbs" => ["Element", "Popovers"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('popovers');
    Route::get('/progressbar', function () {
        return view('admin/elements/progress-bar',
            [
                'catName' => 'element',
                'title' => 'Bootstrap Progress Bar',
                "breadcrumbs" => ["Element", "Bootstrap Progress Bar"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('progressbar');
    Route::get('/search', function () {
        return view('admin/elements/search',
            [
                'catName' => 'element',
                'title' => 'Search',
                "breadcrumbs" => ["Element", "Search"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('search');
    Route::get('/tooltips', function () {
        return view('admin/elements/tooltips',
            [
                'catName' => 'element',
                'title' => 'Tooltips',
                "breadcrumbs" => ["Element", "Tooltips"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('tooltips');
    Route::get('/treeview', function () {
        return view('admin/elements/treeview',
            [
                'catName' => 'element',
                'title' => 'Tree View',
                "breadcrumbs" => ["Element", "Tree View"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('treeview');
    Route::get('/typography', function () {
        return view('admin/elements/typography',
            [
                'catName' => 'element',
                'title' => 'Typography',
                "breadcrumbs" => ["Element", "Typography"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('typography');
});


/**
 * =======================
 *          Maps
 * =======================
 */
Route::get('/maps', function () {
    return view('admin/maps',
        [
            'catName' => 'maps',
            'title' => 'jVector Maps',
            "breadcrumbs" => ["Maps"],
            'scrollspy' => 1,
            'simplePage' => 0
        ]
    );
})->name('maps');


/**
 * =======================
 *          Charts
 * =======================
 */
Route::get('/charts', function () {
    return view('admin/charts',
        [
            'catName' => 'charts',
            'title' => 'Apex Chart',
            "breadcrumbs" => ["User Interface", "Chart"],
            'scrollspy' => 1,
            'simplePage' => 0
        ]
    );
})->name('charts');


/**
 * =======================
 *          Widgets
 * =======================
 */
Route::get('/widgets', function () {
    return view('admin/widgets',
        [
            'catName' => 'widgets',
            'title' => 'Widgets',
            "breadcrumbs" => ["User Interface", "Widgets"],
            'scrollspy' => 0,
            'simplePage' => 0
        ]
    );
})->name('widgets');


/**
 * =======================
 *          Tables
 * =======================
 */
Route::get('/tables', function () {
    return view('admin/tables',
        [
            'catName' => 'tables',
            'title' => 'Bootstrap Tables',
            "breadcrumbs" => ["Tables", "Bootstrap"],
            'scrollspy' => 1,
            'simplePage' => 0
        ]
    );
})->name('tables');


/**
 * =======================
 *          Tables => Datatable
 * =======================
 */
Route::prefix('datatable')->group(function () {
    Route::get('/basic', function () {
        return view('admin/datatables/basic',
            [
                'catName' => 'datatable',
                'title' => 'DataTables Basic',
                "breadcrumbs" => ["DataTables", "Basic"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('basic');    
    Route::get('/striped', function () {
        return view('admin/datatables/striped',
            [
                'catName' => 'datatable',
                'title' => 'DataTables Striped',
                "breadcrumbs" => ["DataTables", "Striped"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('striped');    
    Route::get('/custom', function () {
        return view('admin/datatables/custom',
            [
                'catName' => 'datatable',
                'title' => 'Custom DataTables',
                "breadcrumbs" => ["DataTables", "Custom"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('custom');    
    Route::get('/miscellaneous', function () {
        return view('admin/datatables/miscellaneous',
            [
                'catName' => 'datatable',
                'title' => 'Miscellaneous DataTables',
                "breadcrumbs" => ["DataTables", "Miscellaneous"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('miscellaneous');    
});


/**
 * =======================
 *          Forms
 * =======================
 */
Route::prefix('form')->group(function () {
    Route::get('/auto-complete', function () {
        return view('admin/forms/auto-complete',
            [
                'catName' => 'form',
                'title' => 'AutoComplete',
                "breadcrumbs" => ["Form", "AutoComplete"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('autoComplete');
    Route::get('/basic', function () {
        return view('admin/forms/basic',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Forms',
                "breadcrumbs" => ["Form", "Basic"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('basic');
    Route::get('/checkbox', function () {
        return view('admin/forms/checkbox',
            [
                'catName' => 'form',
                'title' => 'Checkbox',
                "breadcrumbs" => ["Form", "Checkbox"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('checkbox');
    Route::get('/clipboard', function () {
        return view('admin/forms/clipboard',
            [
                'catName' => 'form',
                'title' => 'Clipboard',
                "breadcrumbs" => ["Form", "Clipboard"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('clipboard');
    Route::get('/date-time-picker', function () {
        return view('admin/forms/date-time-picker',
            [
                'catName' => 'form',
                'title' => 'Date and Time Picker',
                "breadcrumbs" => ["Form", "Date Time Picker"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('dateTimePicker');
    Route::get('/file-upload', function () {
        return view('admin/forms/file-upload',
            [
                'catName' => 'form',
                'title' => 'File Upload',
                "breadcrumbs" => ["Form", "File Upload"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('fileUpload');
    Route::get('/input-group', function () {
        return view('admin/forms/input-group',
            [
                'catName' => 'form',
                'title' => 'Input Group',
                "breadcrumbs" => ["Form", "Input Group"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('inputGroup');
    Route::get('/input-mask', function () {
        return view('admin/forms/input-mask',
            [
                'catName' => 'form',
                'title' => 'Input Mask',
                "breadcrumbs" => ["Form", "Input Mask"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('inputMask');
    Route::get('/layouts', function () {
        return view('admin/forms/layouts',
            [
                'catName' => 'form',
                'title' => 'Form Layouts',
                "breadcrumbs" => ["Form", "Layouts"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('layouts');
    Route::get('/markdown-editor', function () {
        return view('admin/forms/markdown-editor',
            [
                'catName' => 'form',
                'title' => 'Markdown Editor',
                "breadcrumbs" => ["Form", "Markdown"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('markdownEditor');
    Route::get('/maxlength', function () {
        return view('admin/forms/maxlength',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Maxlength',
                "breadcrumbs" => ["Form", "Maxlength"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('maxlength');
    Route::get('/quill-editor', function () {
        return view('admin/forms/quill-editor',
            [
                'catName' => 'form',
                'title' => 'Quill Editor',
                "breadcrumbs" => ["Form", "Quill"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('quillEditor');
    Route::get('/radio', function () {
        return view('admin/forms/radio',
            [
                'catName' => 'form',
                'title' => 'Radio',
                "breadcrumbs" => ["Form", "Radio"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('radio');
    Route::get('/slider', function () {
        return view('admin/forms/slider',
            [
                'catName' => 'form',
                'title' => 'Range Slider',
                "breadcrumbs" => ["Form", "Range Slider"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('slider');
    Route::get('/switches', function () {
        return view('admin/forms/switches',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Toggle',
                "breadcrumbs" => ["Form", "Toggle"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('switches');
    Route::get('/tagify', function () {
        return view('admin/forms/tagify',
            [
                'catName' => 'form',
                'title' => 'Tagify',
                "breadcrumbs" => ["Form", "Tagify"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('tagify');
    Route::get('/tom-select', function () {
        return view('admin/forms/tom-select',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Select',
                "breadcrumbs" => ["Form", "Tom Select"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('tomSelect');
    Route::get('/touchspin', function () {
        return view('admin/forms/touch-spin',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Touchspin',
                "breadcrumbs" => ["Form", "Touchspin"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('touchspin');
    Route::get('/validation', function () {
        return view('admin/forms/validation',
            [
                'catName' => 'form',
                'title' => 'Bootstrap Form Validation',
                "breadcrumbs" => ["Form", "Validation"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('validation');
    Route::get('/wizards', function () {
        return view('admin/forms/wizards',
            [
                'catName' => 'form',
                'title' => 'Wizards',
                "breadcrumbs" => ["Form", "Wizards"],
                'scrollspy' => 1,
                'simplePage' => 0
            ]
        );
    })->name('wizards');
});


/**
 * =======================
 *          Layout
 * =======================
 */
Route::prefix('layout')->group(function () {
    Route::get('/blank', function () {
        return view('admin/layouts/blank',
            [
                'catName' => 'layout',
                'title' => 'Blank Page',
                "breadcrumbs" => ["Layout", "Blank"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('blank');    
    Route::get('/empty', function () {
        return view('admin/layouts/empty',
            [
                'catName' => 'layout',
                'title' => 'Empty Page',
                "breadcrumbs" => ["Layout", "Empty"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('empty');
    Route::get('/boxed', function () {
        return view('admin/layouts/boxed',
            [
                'catName' => 'layout',
                'title' => 'Boxed',
                "breadcrumbs" => ["Layout", "Boxed"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('boxed');
    Route::get('/collapsible', function () {
        return view('admin/layouts/collapsed',
            [
                'catName' => 'layout',
                'title' => 'Collapsible Menu',
                "breadcrumbs" => ["Layout", "Collapsible"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('collapsed');
});


/**
 * =======================
 *          Users
 * =======================
 */
Route::prefix('user')->group(function () {
    Route::get('/account-setting', function () {
        return view('admin/users/account-settings',
            [
                'catName' => 'user',
                'title' => 'Account Settings',
                "breadcrumbs" => ["User", "Account Settings"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('accountSetting');    
    Route::get('/profile', function () {
        return view('admin/users/profile',
            [
                'catName' => 'user',
                'title' => 'User Profile',
                "breadcrumbs" => ["User", "Profile"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('profile');
});


/**
 * =======================
 *          Pages
 * =======================
 */
Route::prefix('pages')->group(function () {
    Route::get('/knowledge-base', function () {
        return view('admin/pages/knowledge-base',
            [
                'catName' => 'page',
                'title' => 'Knowledge Base',
                "breadcrumbs" => ["Pages", "Knowledge Base"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('knowledgeBase');
    Route::get('/faq', function () {
        return view('admin/pages/faq',
            [
                'catName' => 'page',
                'title' => 'FAQs',
                "breadcrumbs" => ["Pages", "FAQs"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('faq');
    Route::get('/contact-us', function () {
        return view('admin/pages/contact-form',
            [
                'catName' => 'page',
                'title' => 'Contact Us',
                "breadcrumbs" => ["Pages", "Contact Us"],
                'scrollspy' => 0,
                'simplePage' => 0
            ]
        );
    })->name('contactForm');
    Route::get('/404', function () {
        return view('admin/pages/404',
            [
                'catName' => 'page',
                'title' => '404',
                "breadcrumbs" => ["Pages", "404"],
                'scrollspy' => 0,
                'simplePage' => 1
            ]
        );
    })->name('error404');
    Route::get('/maintenance', function () {
        return view('admin/pages/maintenance',
            [
                'catName' => 'page',
                'title' => 'Maintenence',
                "breadcrumbs" => ["Pages", "Maintenence"],
                'scrollspy' => 0,
                'simplePage' => 1
            ]
        );
    })->name('maintenance');
});


/**
 * =======================
 *          Auth
 * =======================
 */
Route::prefix('authentication')->group(function () {

    Route::prefix('boxed')->group(function () {
        Route::get('/sign-in', function () {
            // return 'boxed-sign-in';
            return view('admin/auth/boxed/sign-in',
                [
                    'catName' => 'auth',
                    'title' => 'Sign In Boxed',
                    "breadcrumbs" => ["Authentication", "Sign In"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('boxedSignIn');    
        Route::get('/sign-up', function () {
            // return 'boxed-sign-up';
            return view('admin/auth/boxed/sign-up',
                [
                    'catName' => 'auth',
                    'title' => 'Sign Up Boxed',
                    "breadcrumbs" => ["Authentication", "Sign Up"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('boxedSignUp');
        Route::get('/lockscreen', function () {
            // return 'boxed-lockscreen';
            return view('admin/auth/boxed/unlock',
                [
                    'catName' => 'auth',
                    'title' => 'LockScreen Boxed',
                    "breadcrumbs" => ["Authentication", "LockScreen"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('boxedLockscreen');
        Route::get('/password-reset', function () {
            // return 'boxed-password-reset';
            return view('admin/auth/boxed/reset',
                [
                    'catName' => 'auth',
                    'title' => 'Password Reset Boxed',
                    "breadcrumbs" => ["Authentication", "Password Reset"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('boxedPasswordReset');
        Route::get('/2-step-verification', function () {
            // return 'boxed-2-step-verification';
            return view('admin/auth/boxed/2-step',
                [
                    'catName' => 'auth',
                    'title' => '2 Step Verification Boxed',
                    "breadcrumbs" => ["Authentication", "2 Step Verification"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('boxed2sv');
    });


    Route::prefix('cover')->group(function () {
        Route::get('/sign-in', function () {
            // return 'cover-sign-in';
            return view('admin/auth/cover/sign-in',
                [
                    'catName' => 'auth',
                    'title' => 'Sign In Cover',
                    "breadcrumbs" => ["Authentication", "Sign In"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('coverSignIn');    
        Route::get('/sign-up', function () {
            // return 'cover-sign-up';
            return view('admin/auth/cover/sign-up',
                [
                    'catName' => 'auth',
                    'title' => 'Sign Up Cover',
                    "breadcrumbs" => ["Authentication", "Sign Up"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('coverSignUp');
        Route::get('/lockscreen', function () {
            // return 'cover-lockscreen';
            return view('admin/auth/cover/unlock',
                [
                    'catName' => 'auth',
                    'title' => 'LockScreen Cover',
                    "breadcrumbs" => ["Authentication", "LockScreen"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('coverLockscreen');
        Route::get('/password-reset', function () {
            // return 'cover-password-reset';
            return view('admin/auth/cover/reset',
                [
                    'catName' => 'auth',
                    'title' => 'Password Reset Cover',
                    "breadcrumbs" => ["Authentication", "Password Reset"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('coverPasswordReset');
        Route::get('/2-step-verification', function () {
            // return 'cover-2-step-verification';
            return view('admin/auth/cover/2-step',
                [
                    'catName' => 'auth',
                    'title' => '2 Step Verification Cover',
                    "breadcrumbs" => ["Authentication", "2 Step Verification"],
                    'scrollspy' => 0,
                    'simplePage' => 1
                ]
            );
        })->name('cover2sv');
    });
    
});