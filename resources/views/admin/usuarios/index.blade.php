@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/src/table/datatable/datatables.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css">
@vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/light/plugins/table/datatable/custom_dt_custom.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/dt-global_style.scss'])
@vite(['resources/scss/dark/plugins/table/datatable/custom_dt_custom.scss'])
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
    <h2>Gestión de Usuarios</h2>
</div>

<div class="row layout-spacing">
    <div class="col-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-header d-flex justify-content-center align-items-center">
                <h4 style="flex: 1;">Lista de Usuarios del Sistema</h4>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUsuarioModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Crear Usuario
                </button>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive-wrapper">
                    <table id="usuarios-table" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Correo</th>
                            <th width="180" class="text-center">Acciones</th>
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

@include('admin.usuarios.usuario-modals')

@endsection

@section('scripts')
<!-- jQuery (requerido por DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS v2.3.6 -->
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
<script>
    let table = null;
    let retries = 0;
    const maxRetries = 10;
    
    function initUsuarioHandlers() {
        if ($.fn.dataTable.isDataTable('#usuarios-table')) {
            table = $('#usuarios-table').DataTable();
            setupUsuarioFormHandlers();
            return;
        }
        
        if (retries < maxRetries) {
            retries++;
            setTimeout(initUsuarioHandlers, 100);
        }
    }

    function setupUsuarioFormHandlers() {
        // ==================== CREAR USUARIO ====================
        document.getElementById('createUsuarioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.querySelector('#createUsuarioModal button[type="submit"]');
            const errorAlert = document.getElementById('createErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
            errorAlert.classList.add('d-none');

            const formData = new FormData(this);

            fetch('/usuarios', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la tabla manteniendo la página actual
                    const currentPage = table.page();
                    table.ajax.reload(function() {
                        table.page(currentPage).draw(false);
                    });

                    document.getElementById('createUsuarioForm').reset();
                    
                    // Cerrar modal correctamente
                    const modalElement = document.getElementById('createUsuarioModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    // Remover backdrop oscuro si queda visible
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Usuario creado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al crear el usuario';
                    errorAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorAlert.textContent = 'Error en la solicitud: ' + error.message;
                errorAlert.classList.remove('d-none');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Crear Usuario';
            });
        });

        // ==================== EDITAR USUARIO ====================
        $(document).on('click', '.btn-edit-usuario', function() {
            const usuarioId = $(this).data('usuario-id');
            fetch(`/usuarios/${usuarioId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editUsuarioId').value = data.id;
                    document.getElementById('editNombre').value = data.nombre;
                    document.getElementById('editCorreo').value = data.correo;
                });
        });

        document.getElementById('editUsuarioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const usuarioId = document.getElementById('editUsuarioId').value;
            const submitBtn = document.querySelector('#editUsuarioModal button[type="submit"]');
            const errorAlert = document.getElementById('editErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
            errorAlert.classList.add('d-none');

            const data = {
                nombre: document.getElementById('editNombre').value,
                correo: document.getElementById('editCorreo').value,
                contraseña: document.getElementById('editContraseña').value || null
            };

            fetch(`/usuarios/${usuarioId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const currentPage = table.page();
                    table.ajax.reload(function() {
                        table.page(currentPage).draw(false);
                    });

                    document.getElementById('editUsuarioForm').reset();
                    
                    const modalElement = document.getElementById('editUsuarioModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Usuario actualizado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al actualizar el usuario';
                    errorAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorAlert.textContent = 'Error en la solicitud: ' + error.message;
                errorAlert.classList.remove('d-none');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Guardar Cambios';
            });
        });

        // ==================== ELIMINAR USUARIO ====================
        $(document).on('click', '.btn-delete-usuario', function() {
            const usuarioId = $(this).data('usuario-id');
            document.getElementById('deleteUsuarioId').value = usuarioId;
        });

        document.getElementById('deleteUsuarioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const usuarioId = document.getElementById('deleteUsuarioId').value;
            const submitBtn = document.querySelector('#deleteUsuarioModal button[type="submit"]');
            const errorAlert = document.getElementById('deleteErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
            errorAlert.classList.add('d-none');

            fetch(`/usuarios/${usuarioId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const currentPage = table.page();
                    table.ajax.reload(function() {
                        table.page(currentPage).draw(false);
                    });

                    const modalElement = document.getElementById('deleteUsuarioModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Usuario eliminado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al eliminar el usuario';
                    errorAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorAlert.textContent = 'Error en la solicitud: ' + error.message;
                errorAlert.classList.remove('d-none');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Eliminar Usuario';
            });
        });
    }

    function showSuccessAlert(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'floating-alert alert alert-light-success alert-dismissible fade show';
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <div>
                <strong>¡Éxito!</strong>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(function() {
            alertDiv.classList.add('hide');
            setTimeout(function() {
                alertDiv.remove();
            }, 300);
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Destruir instancias anteriores si existen
        if ($.fn.dataTable.isDataTable('#usuarios-table')) {
            $('#usuarios-table').DataTable().destroy();
        }

        // Configuración de DataTable para Usuarios
        $('#usuarios-table').DataTable({
            "ajax": {
                "url": "{{ route('usuarios.get-data') }}",
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
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
            "responsive": true,
            "columnDefs": [
                {
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": 0,
                    "orderable": true,
                    "searchable": true
                },
                {
                    "targets": 1,
                    "orderable": false,
                    "searchable": true
                }
            ],
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                   "<'table-responsive'tr>" +
                   "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>"
        });

        initUsuarioHandlers();
    });
</script>
<script type="module" src="{{asset('plugins/src/table/datatable/datatables.js')}}"></script>
@endsection
