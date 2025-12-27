@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/src/table/datatable/datatables.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.min.css">
@vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/light/plugins/table/datatable/custom_dt_custom.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/custom_dt_custom.scss'])
@vite(['resources/scss/light/assets/components/carousel.scss'])
@vite(['resources/scss/light/assets/components/modal.scss'])
@vite(['resources/scss/light/assets/components/tabs.scss'])
@vite(['resources/scss/light/assets/elements/alert.scss'])
@vite(['resources/scss/light/assets/elements/floating-alert.scss'])
@vite(['resources/scss/light/assets/elements/table-responsive.scss'])
@vite(['resources/scss/dark/assets/components/carousel.scss'])
@vite(['resources/scss/dark/assets/components/modal.scss'])
@vite(['resources/scss/dark/assets/components/tabs.scss'])
@vite(['resources/scss/dark/assets/elements/alert.scss'])
@vite(['resources/scss/dark/assets/elements/floating-alert.scss'])
@vite(['resources/scss/dark/assets/elements/table-responsive.scss'])
@endsection

@section('content')

<div class="seperator-header layout-top-spacing">
    <h2>Administración de Menús y Permisos</h2>
</div>

@if(session('success'))
    <div class="floating-alert alert alert-light-success alert-dismissible fade show" role="alert" id="successAlert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <div>
            <strong>¡Éxito!</strong>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(function() {
                    alert.classList.add('hide');
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 4000);
            }
        });
    </script>
@endif

<div class="row layout-spacing">
    <!-- Usuarios y sus Roles -->
    <div class="col-lg-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Usuarios y sus Roles</h4>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive-wrapper">
                    <table id="usuarios-table" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Roles</th>
                            <th width="70" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td>
                                    <span class="text-dark-strong">{{ $usuario->nombre }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $usuario->correo }}</small>
                                </td>
                                <td>
                                    @php
                                        $rolesCount = $usuario->roles->count();
                                    @endphp
                                    <button type="button" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#rolesModal{{ $usuario->id }}" style="border: none; cursor: pointer;">
                                        {{ $rolesCount }} {{ $rolesCount == 1 ? 'rol' : 'roles' }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('menu-permissions.user-roles', $usuario->id) }}" class="btn btn-sm btn-outline-primary" title="Editar roles">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles y sus Permisos -->
    <div class="col-lg-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Roles y Permisos de Menú</h4>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive-wrapper">
                    <table id="roles-table" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Menús</th>
                            <th width="70" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $rol)
                            @php
                                $permCount = \App\Models\PermisoMenuRol::where('id_rol', $rol->id)->count();
                            @endphp
                            <tr>
                                <td>
                                    <span class="text-dark-strong">{{ $rol->nombre }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $permCount }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('menu-permissions.role-permissions', $rol->id) }}" class="btn btn-sm btn-outline-success" title="Gestionar permisos">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    No hay roles registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.menu-permissions.components.user-roles-modals')
@include('admin.menu-permissions.components.menu-modals')

<!-- Estructura de Menús -->
<div class="row" style="margin-top: 0;">
    <div class="col-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-header d-flex justify-content-center align-items-center">
                <h4 style="flex: 1;">Estructura de Menús del Sistema</h4>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Crear Menú
                </button>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive-wrapper">
                    <table id="menus-table" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">URL</th>
                            <th width="70" class="text-center">Tipo</th>
                            <th class="text-center">Padre</th>
                            <th width="60" class="text-center">Orden</th>
                            <th width="130" class="text-center">Acciones</th>
                            <th style="display:none;">ID</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- jQuery (requerido por DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS v2.3.6 -->
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
<!-- DataTables RowGroup JS -->
<script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Destruir instancias anteriores si existen
        if ($.fn.dataTable.isDataTable('#usuarios-table')) {
            $('#usuarios-table').DataTable().destroy();
        }
        if ($.fn.dataTable.isDataTable('#roles-table')) {
            $('#roles-table').DataTable().destroy();
        }
        if ($.fn.dataTable.isDataTable('#menus-table')) {
            $('#menus-table').DataTable().destroy();
        }

        // Configuración de DataTable para Usuarios
        $('#usuarios-table').DataTable({
            "language": {
                "oPaginate": {
                    "sFirst": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sLast": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Buscar...",
                "sLengthMenu": "Resultados :  _MENU_"
            },
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
            "responsive": true,
            "columnDefs": [
                {
                    "targets": -1,
                    "orderable": false,
                    "searchable": false
                }
            ],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                   "<'table-responsive'tr>" +
                   "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>"
        });

        // Configuración de DataTable para Roles
        $('#roles-table').DataTable({
            "language": {
                "oPaginate": {
                    "sFirst": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sLast": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Buscar...",
                "sLengthMenu": "Resultados :  _MENU_"
            },
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
            "responsive": true,
            "columnDefs": [
                {
                    "targets": -1,
                    "orderable": false,
                    "searchable": false
                }
            ],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                   "<'table-responsive'tr>" +
                   "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>"
        });

        // Configuración de DataTable para Menús
        $('#menus-table').DataTable({
            "ajax": {
                "url": "{{ route('menu-permissions.get-menus-data') }}",
                "type": "GET",
                "dataSrc": "data"
            },
            "language": {
                "oPaginate": {
                    "sFirst": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    "sLast": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Buscar...",
                "sLengthMenu": "Resultados :  _MENU_"
            },
            "pageLength": 15,
            "lengthMenu": [[10, 15, 25, 50], [10, 15, 25, 50]],
            "responsive": true,
            "processing": true,
            "serverSide": false,
            "order": [[3, 'asc'], [4, 'asc']],
            "columnDefs": [
                {
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": 3,
                    "visible": true
                },
                {
                    "targets": 5,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": 6,
                    "visible": false,
                    "searchable": false
                }
            ],
            "rowGroup": {
                "dataSrc": 3
            },
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                   "<'table-responsive'tr>" +
                   "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>"
        });
    });

</script>
<script type="module" src="{{asset('plugins/src/table/datatable/datatables.js')}}"></script>
@endsection

