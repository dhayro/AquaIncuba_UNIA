{{-- @extends('layouts.app') --}}

{{-- @section('sidebar') --}}
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{Vite::asset('resources/images/logo.svg')}}" class="logo-light navbar-logo-g" alt="logo">
                        <img src="{{Vite::asset('resources/images/logo.svg')}}" class="logo-dark navbar-logo-g" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="{{ route('dashboard') }}" class="nav-link"> AQUAINCUBA </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                </div>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @php
                use App\Helpers\MenuHelper;
                $mainMenus = MenuHelper::getMainMenus();
                $currentRoute = MenuHelper::getCurrentRoute();
            @endphp

            @forelse($mainMenus as $menu)
                @php
                    $submenus = MenuHelper::getSubmenus($menu->id);
                    $catName = MenuHelper::getCatNameFromMenu($menu);
                    $iconClass = MenuHelper::getIconFromMenu($menu);
                    // Verificar si algún submenú está activo o si estamos en la ruta del menú
                    $hasActiveSubmenu = MenuHelper::isCurrentRouteInMenu($menu, $submenus);
                @endphp

                {{-- Menú colapsible con encabezado y submenús --}}
                <li class="menu {{ $hasActiveSubmenu ? 'active' : '' }}">
                    <a href="#{{ $catName }}" data-bs-toggle="collapse" aria-expanded="{{ $hasActiveSubmenu ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            @switch($iconClass)
                                @case('feather feather-home')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                    @break
                                @case('feather feather-lock')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    @break
                                @case('feather feather-droplet')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-droplet"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                                    @break
                                @case('feather feather-bar-chart-2')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                    @break
                                @case('feather feather-activity')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                    @break
                                @case('feather feather-settings')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m6.08 0l4.24-4.24M1 12h6m6 0h6m-1.78 7.78l-4.24-4.24m-6.08 0l-4.24 4.24"></path></svg>
                                    @break
                                @default
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><circle cx="12" cy="12" r="1"></circle></svg>
                            @endswitch
                            <span>{{ $menu->nombre }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>

                    {{-- Submenús colapsibles --}}
                    <ul class="collapse submenu list-unstyled {{ $hasActiveSubmenu ? 'show' : '' }}" id="{{ $catName }}" data-bs-parent="#accordionExample">
                        @foreach($submenus as $submenu)
                            @php
                                $route = MenuHelper::getRouteFromMenu($submenu);
                                $isActive = $route ? (\request()->routeIs(str_replace('.', '.*', $route)) ? true : false) : false;
                            @endphp
                            <li class="{{ $isActive ? 'active' : '' }}">
                                @if($route && \Route::has($route))
                                    <a href="{{ route($route) }}"> {{ $submenu->nombre }} </a>
                                @else
                                    <a href="{{ $submenu->url }}"> {{ $submenu->nombre }} </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>

            @empty
                <li class="menu menu-heading">
                    <div class="heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <span>Sin acceso</span>
                    </div>
                </li>
            @endforelse

        </ul>

    </nav>

</div>

{{-- @endsection --}}
