@extends('layouts.app')

@section('styles')
@vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/light/plugins/table/datatable/custom_dt_custom.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/custom_dt_custom.scss'])
@vite(['semi-dark-menu/resources/scss/light/assets/forms/switches.scss'])
@vite(['semi-dark-menu/resources/scss/dark/assets/forms/switches.scss'])
@vite(['resources/scss/light/assets/components/modal.scss'])
@vite(['resources/scss/dark/assets/components/modal.scss'])
@vite(['resources/scss/light/assets/elements/alert.scss'])
@vite(['resources/scss/light/assets/elements/floating-alert.scss'])
@vite(['resources/scss/light/assets/elements/table-responsive.scss'])
@vite(['resources/scss/dark/assets/components/modal.scss'])
@vite(['resources/scss/dark/assets/elements/alert.scss'])
@vite(['resources/scss/dark/assets/elements/floating-alert.scss'])
@vite(['resources/scss/dark/assets/elements/table-responsive.scss'])
@endsection

@section('content')

<div class="seperator-header layout-top-spacing">
    <h2>Permisos de Rol - {{ ucfirst($rol->nombre) }}</h2>
</div>

<div class="row layout-spacing">
    <div class="col-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Asignar Permisos por Menú</h4>
            </div>
            <div class="widget-content widget-content-area">
                @if (session('success'))
                    <div class="alert alert-light-success alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('roles.permisos.update', $rol->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive-wrapper">
                        <table class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Menú</th>
                                    <th class="text-center" style="background-color: #e3f2fd; color: #1565c0;">
                                        <div class="form-switch-custom form-switch-primary">
                                            <input class="switch-input select-all-column" type="checkbox" 
                                                   id="select_all_ver" data-column="puede_ver">
                                        </div>
                                        <small class="d-block fw-bold">Ver</small>
                                    </th>
                                    <th class="text-center" style="background-color: #f3e5f5; color: #6a1b9a;">
                                        <div class="form-switch-custom form-switch-primary">
                                            <input class="switch-input select-all-column" type="checkbox" 
                                                   id="select_all_crear" data-column="puede_crear">
                                        </div>
                                        <small class="d-block fw-bold">Crear</small>
                                    </th>
                                    <th class="text-center" style="background-color: #e8f5e9; color: #2e7d32;">
                                        <div class="form-switch-custom form-switch-primary">
                                            <input class="switch-input select-all-column" type="checkbox" 
                                                   id="select_all_editar" data-column="puede_editar">
                                        </div>
                                        <small class="d-block fw-bold">Editar</small>
                                    </th>
                                    <th class="text-center" style="background-color: #ffebee; color: #c62828;">
                                        <div class="form-switch-custom form-switch-primary">
                                            <input class="switch-input select-all-column" type="checkbox" 
                                                   id="select_all_eliminar" data-column="puede_eliminar">
                                        </div>
                                        <small class="d-block fw-bold">Eliminar</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $indexCounter = 0; @endphp
                                @foreach($menus as $menuPadre)
                                    @if($menuPadre->id_padre === null)
                                        @php
                                            $permiso = $rol->permisosMenus()->where('id_menu', $menuPadre->id)->first();
                                        @endphp
                                        <tr class="table-primary fw-bold">
                                            <td>
                                                <input type="hidden" name="permisos[{{ $indexCounter }}][menu_id]" value="{{ $menuPadre->id }}">
                                                <strong>{{ $menuPadre->nombre }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-switch-custom form-switch-primary">
                                                    <input class="switch-input" type="checkbox" 
                                                           name="permisos[{{ $indexCounter }}][puede_ver]" 
                                                           value="1" 
                                                           id="ver_{{ $menuPadre->id }}"
                                                           {{ $permiso && $permiso->puede_ver ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-switch-custom form-switch-primary">
                                                    <input class="switch-input" type="checkbox" 
                                                           name="permisos[{{ $indexCounter }}][puede_crear]" 
                                                           value="1" 
                                                           id="crear_{{ $menuPadre->id }}"
                                                           {{ $permiso && $permiso->puede_crear ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-switch-custom form-switch-primary">
                                                    <input class="switch-input" type="checkbox" 
                                                           name="permisos[{{ $indexCounter }}][puede_editar]" 
                                                           value="1" 
                                                           id="editar_{{ $menuPadre->id }}"
                                                           {{ $permiso && $permiso->puede_editar ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-switch-custom form-switch-primary">
                                                    <input class="switch-input" type="checkbox" 
                                                           name="permisos[{{ $indexCounter }}][puede_eliminar]" 
                                                           value="1" 
                                                           id="eliminar_{{ $menuPadre->id }}"
                                                           {{ $permiso && $permiso->puede_eliminar ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                        @php $indexCounter++; @endphp

                                        {{-- Submenús de este padre --}}
                                        @foreach($menus as $submenu)
                                            @if($submenu->id_padre === $menuPadre->id)
                                                @php
                                                    $permisoSub = $rol->permisosMenus()->where('id_menu', $submenu->id)->first();
                                                @endphp
                                                <tr class="table-light">
                                                    <td class="ps-5">
                                                        <input type="hidden" name="permisos[{{ $indexCounter }}][menu_id]" value="{{ $submenu->id }}">
                                                        <span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right me-2" style="display: inline;">
                                                                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>{{ $submenu->nombre }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-switch-custom form-switch-primary">
                                                            <input class="switch-input" type="checkbox" 
                                                                   name="permisos[{{ $indexCounter }}][puede_ver]" 
                                                                   value="1" 
                                                                   id="ver_{{ $submenu->id }}"
                                                                   {{ $permisoSub && $permisoSub->puede_ver ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-switch-custom form-switch-primary">
                                                            <input class="switch-input" type="checkbox" 
                                                                   name="permisos[{{ $indexCounter }}][puede_crear]" 
                                                                   value="1" 
                                                                   id="crear_{{ $submenu->id }}"
                                                                   {{ $permisoSub && $permisoSub->puede_crear ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-switch-custom form-switch-primary">
                                                            <input class="switch-input" type="checkbox" 
                                                                   name="permisos[{{ $indexCounter }}][puede_editar]" 
                                                                   value="1" 
                                                                   id="editar_{{ $submenu->id }}"
                                                                   {{ $permisoSub && $permisoSub->puede_editar ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-switch-custom form-switch-primary">
                                                            <input class="switch-input" type="checkbox" 
                                                                   name="permisos[{{ $indexCounter }}][puede_eliminar]" 
                                                                   value="1" 
                                                                   id="eliminar_{{ $submenu->id }}"
                                                                   {{ $permisoSub && $permisoSub->puede_eliminar ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php $indexCounter++; @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Guardar Permisos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mensaje de éxito desaparecerá automáticamente
        const alert = document.querySelector('.alert-light-success');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('fade');
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 4000);
        }

        // Manejar checkboxes de "Seleccionar Todo" en headers
        document.querySelectorAll('.select-all-column').forEach(selectAllCheckbox => {
            selectAllCheckbox.addEventListener('change', function() {
                const column = this.getAttribute('data-column');
                const isChecked = this.checked;
                
                // Seleccionar todos los inputs en tbody que coincidan con la columna
                document.querySelectorAll(`tbody input[name*="[${column}]"]`).forEach(input => {
                    if (input.classList.contains('switch-input')) {
                        input.checked = isChecked;
                    }
                });
            });
        });

        // Manejar cuando se selecciona un padre - selecciona/deselecciona sus hijos
        document.querySelectorAll('tbody tr.table-primary input[type="checkbox"]').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function() {
                const nameAttr = this.getAttribute('name');
                const match = nameAttr.match(/\[([^\]]+)\]$/);
                if (match) {
                    const column = match[1];
                    const parentRow = this.closest('tr.table-primary');
                    let currentRow = parentRow.nextElementSibling;
                    
                    // Seleccionar/deseleccionar todos los inputs de submenús hasta el próximo padre
                    while (currentRow && currentRow.classList.contains('table-light')) {
                        const childInput = currentRow.querySelector(`input[name*="[${column}]"]`);
                        if (childInput) {
                            childInput.checked = this.checked;
                        }
                        currentRow = currentRow.nextElementSibling;
                    }
                    
                    updateHeaderCheckbox(column);
                }
            });
        });

        // Actualizar estado del header cuando cambian individuales
        document.querySelectorAll('tbody input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const nameAttr = this.getAttribute('name');
                const match = nameAttr.match(/\[([^\]]+)\]$/);
                if (match) {
                    const column = match[1];
                    updateHeaderCheckbox(column);
                    
                    // Si es un hijo, actualizar el estado del padre
                    const row = this.closest('tr');
                    if (row.classList.contains('table-light')) {
                        updateParentCheckbox(row, column);
                    }
                }
            });
        });

        // Función para actualizar header checkbox
        function updateHeaderCheckbox(column) {
            const allInputs = document.querySelectorAll(`tbody input[name*="[${column}]"]`);
            const checkedInputs = document.querySelectorAll(`tbody input[name*="[${column}]"]:checked`);
            
            const selectAllCheckbox = document.querySelector(`input[data-column="${column}"]`);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allInputs.length === checkedInputs.length && allInputs.length > 0;
            }
        }

        // Función para actualizar padre basado en sus hijos
        function updateParentCheckbox(childRow, column) {
            // Encontrar la fila padre
            let parentRow = childRow.previousElementSibling;
            while (parentRow && !parentRow.classList.contains('table-primary')) {
                parentRow = parentRow.previousElementSibling;
            }
            
            if (parentRow) {
                const parentInput = parentRow.querySelector(`input[name*="[${column}]"]`);
                if (parentInput) {
                    // Contar hijos después de esta fila padre
                    let currentRow = parentRow.nextElementSibling;
                    let totalChildren = 0;
                    let checkedChildren = 0;
                    
                    while (currentRow && currentRow.classList.contains('table-light')) {
                        const childInput = currentRow.querySelector(`input[name*="[${column}]"]`);
                        if (childInput) {
                            totalChildren++;
                            if (childInput.checked) {
                                checkedChildren++;
                            }
                        }
                        currentRow = currentRow.nextElementSibling;
                    }
                    
                    // El padre está checked si todos sus hijos están checked
                    parentInput.checked = totalChildren > 0 && totalChildren === checkedChildren;
                }
            }
        }

        // Inicializar estado de checkboxes al cargar
        document.querySelectorAll('.select-all-column').forEach(selectAllCheckbox => {
            const column = selectAllCheckbox.getAttribute('data-column');
            const allInputs = document.querySelectorAll(`tbody input[name*="[${column}]"]`);
            const checkedInputs = document.querySelectorAll(`tbody input[name*="[${column}]"]:checked`);
            
            if (allInputs.length > 0 && allInputs.length === checkedInputs.length) {
                selectAllCheckbox.checked = true;
            }
        });

        // Inicializar estado de padres basado en sus hijos
        document.querySelectorAll('tbody tr.table-primary').forEach(parentRow => {
            const columns = ['puede_ver', 'puede_crear', 'puede_editar', 'puede_eliminar'];
            columns.forEach(column => {
                const parentInput = parentRow.querySelector(`input[name*="[${column}]"]`);
                if (parentInput) {
                    let currentRow = parentRow.nextElementSibling;
                    let totalChildren = 0;
                    let checkedChildren = 0;
                    
                    while (currentRow && currentRow.classList.contains('table-light')) {
                        const childInput = currentRow.querySelector(`input[name*="[${column}]"]`);
                        if (childInput) {
                            totalChildren++;
                            if (childInput.checked) {
                                checkedChildren++;
                            }
                        }
                        currentRow = currentRow.nextElementSibling;
                    }
                    
                    parentInput.checked = totalChildren > 0 && totalChildren === checkedChildren;
                }
            });
        });
    });
</script>
@endsection
