<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Parche para eventos pasivos - Debe cargar PRIMERO -->
    <script>
        (function() {
          let passiveSupported = false;
          try {
            const options = {
              get passive() {
                passiveSupported = true;
                return false;
              }
            };
            window.addEventListener('test', null, options);
            window.removeEventListener('test', null, options);
          } catch(err) {
            passiveSupported = false;
          }

          if (!passiveSupported) {
            return;
          }

          const originalAddEventListener = EventTarget.prototype.addEventListener;
          // Solo aplicar passive a bibliotecas específicas conocidas que no necesitan preventDefault
          const passiveLibraryPatterns = [
            'perfectScrollbar',
            'waves',
            'perfect-scrollbar'
          ];

          EventTarget.prototype.addEventListener = function(type, listener, options) {
            // Solo hacer pasivo si:
            // 1. Es un evento de scroll/wheel/touch
            // 2. Y el listener proviene de una librería conocida que usa passive
            const scrollLikeEvents = ['scroll', 'wheel', 'touchmove', 'touchstart', 'touchend', 'mousewheel', 'DOMMouseScroll'];
            
            if (scrollLikeEvents.includes(type) && typeof options !== 'object') {
              // Si es boolean o undefined, convertir a objeto con passive: false por defecto
              // para permitir preventDefault
              options = { passive: false, capture: options === true };
            } else if (scrollLikeEvents.includes(type) && typeof options === 'object' && options) {
              // Si ya es un objeto y no especifica passive, dejar como está (por defecto será false)
              if (!('passive' in options)) {
                options.passive = false;
              }
            }
            return originalAddEventListener.call(this, type, listener, options);
          };
        })();
    </script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>
        @isset($title)
            @if ($title !== '')
                {{$title}} | Multipurpose Bootstrap Dashboard Template
            @else
                CORK Admin | Multipurpose Bootstrap Dashboard Template
            @endif
        @endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{Vite::asset('resources/images/favicon.ico')}}"/>
    @vite(['resources/scss/layouts/semi-dark-menu/light/loader.scss'])
    @vite(['resources/scss/layouts/semi-dark-menu/dark/loader.scss'])
    @vite(['resources/layouts/semi-dark-menu/loader.js'])

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/src/bootstrap/css/bootstrap.min.css')}}">
    @vite(['resources/scss/light/assets/main.scss'])
    @vite(['resources/scss/dark/assets/main.scss'])
    @vite(['resources/scss/light/plugins/perfect-scrollbar/perfect-scrollbar.scss'])
    @vite(['resources/scss/dark/plugins/perfect-scrollbar/perfect-scrollbar.scss'])
    <link rel="stylesheet" href="{{asset('plugins/src/waves/waves.min.css')}}">
    @vite(['resources/scss/layouts/semi-dark-menu/light/structure.scss'])
    @vite(['resources/scss/layouts/semi-dark-menu/dark/structure.scss'])
    <link rel="stylesheet" href="{{asset('plugins/src/highlight/styles/monokai-sublime.css')}}">
    
    <style>
        /* Asegurar visualización correcta del sidebar y contenido */
        .main-container {
            display: flex;
            gap: 0;
        }
        
        .sidebar-wrapper {
            flex-shrink: 0;
            width: 260px;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            overflow-x: auto;
        }
    </style>
    
    <style>
        body:not(.dark) .logo-light {
            display: block;
        }
        body:not(.dark) .logo-dark {
            display: none;
        }
        body.dark .logo-light {
            display: none;
        }
        body.dark .logo-dark {
            display: block;
        }
    </style>
   

    @isset($scrollspy)
        @if ($scrollspy)
            @vite(['resources/scss/light/assets/scrollspyNav.scss'])
            @vite(['resources/scss/dark/assets/scrollspyNav.scss'])
        @endif
    @endisset
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body class="
    {{ Request::routeIs('error404') ? 'error text-center' : '' }}
    {{ Request::routeIs('maintenance') ? 'maintanence text-center' : '' }}
    {{ 
        (Request::routeIs('boxedSignIn') || 
        Request::routeIs('boxedSignUp') || 
        Request::routeIs('boxedLockscreen') || 
        Request::routeIs('boxedPasswordReset') || 
        Request::routeIs('boxed2sv')) ? 'form' : '' 
    }}

    {{ 
        (Request::routeIs('coverSignIn') || 
        Request::routeIs('coverSignUp') || 
        Request::routeIs('coverLockscreen') || 
        Request::routeIs('coverPasswordReset') || 
        Request::routeIs('cover2sv')) ? 'form' : '' 
    }}
    {{ Request::routeIs('collapsed') ? 'alt-menu' : '' }}
    
    
" layout="{{ Request::routeIs('boxed') ? 'boxed' : 'full-width' }}" >
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->
    
    @if (isset($simplePage) && $simplePage)

        @yield('content')
        
    @else

    @if (!Request::routeIs('blank'))
        <!--  BEGIN NAVBAR  -->
        @include('layouts.navbar')
        <!--  END NAVBAR  -->
    @endif

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">

                <div class="overlay"></div>
                <div class="search-overlay"></div>

                @if (!Request::routeIs('blank'))
                    <!--  BEGIN SIDEBAR  -->
                    @include('layouts.sidebar')
                    <!--  END SIDEBAR  -->                    
                @endif

                <!--  BEGIN CONTENT AREA  -->
                <div id="content" class="main-content {{ Request::routeIs('blank') ? 'ms-0 mt-0' : '' }}">

                    @if (isset($scrollspy) && $scrollspy)
                        <div class="container">
                            <div class="container">                
                                <div class="middle-content container-xxl p-0">
        
                                    <!--  BEGIN BREADCRUMBS  -->
                                    {{-- @include('layouts.secondaryNav') --}}
                                    <!--  END BREADCRUMBS  -->
                                    
                                    <!--  BEGIN CONTENT  -->
                                    @yield('content')
                                    <!--  END CONTENT  -->

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="layout-px-spacing">
                            <div class="middle-content {{ Request::routeIs('boxed') ? 'container-xxl' : '' }} p-0">
                                @if (!Request::routeIs('blank'))
                                    <!--  BEGIN BREADCRUMBS  -->
                                    {{-- @include('layouts.secondaryNav') --}}
                                    <!--  END BREADCRUMBS  -->
                                @endif
                                                        
                                <!--  BEGIN CONTENT  -->
                                @yield('content')
                                <!--  END CONTENT  -->
                            </div>

                        </div>
                    @endif
                    
                    @if (!Request::routeIs('blank'))
                        <!--  BEGIN FOOTER  -->
                        @include('layouts.footer')
                        <!--  END FOOTER  -->
                    @endif
                </div>
                <!--  END CONTENT AREA  -->

            </div>
            <!-- END MAIN CONTAINER -->
            
    @endif

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('plugins/src/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/src/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('plugins/src/mousetrap/mousetrap.min.js')}}"></script>
    <script src="{{asset('plugins/src/waves/waves.min.js')}}"></script>
    <script src="{{asset('plugins/src/highlight/highlight.pack.js')}}"></script>
    @if (!isset($simplePage) || !$simplePage)
        @vite(['resources/layouts/semi-dark-menu/app.js'])
    @endif
    
    @if (isset($scrollspy) && $scrollspy)
        @vite(['resources/js/scrollspyNav.js'])
    @endif
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @yield('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>