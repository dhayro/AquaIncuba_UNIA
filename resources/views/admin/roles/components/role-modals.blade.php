<!-- Modal Crear Rol -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleLabel">Crear Nuevo Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="nombre" class="form-label">Nombre del Rol *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del rol..."></textarea>
                        </div>
                    </div>
                    <div id="createErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitCreateBtn">Crear Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleLabel">Editar Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_role_id" name="role_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit_nombre" class="form-label">Nombre del Rol *</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3" placeholder="Descripción del rol..."></textarea>
                        </div>
                    </div>
                    <div id="editErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning" id="submitEditBtn">Actualizar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Rol -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoleLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteRoleForm">
                @csrf
                @method('DELETE')
                <input type="hidden" id="delete_role_id" name="role_id">
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar este rol?</p>
                    <p class="text-danger"><strong>Nota:</strong> Se eliminarán todos los permisos asociados a este rol.</p>
                    <div id="deleteErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="submitDeleteBtn">Eliminar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let table;
    let retries = 0;
    const maxRetries = 10;
    
    function initRoleHandlers() {
        if ($.fn.dataTable.isDataTable('#roles-table')) {
            table = $('#roles-table').DataTable();
            setupRoleFormHandlers();
            return;
        }
        
        if (retries < maxRetries) {
            retries++;
            setTimeout(initRoleHandlers, 100);
        }
    }

    function setupRoleFormHandlers() {
        // ==================== CREAR ROL ====================
        document.getElementById('createRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitCreateBtn');
            const errorAlert = document.getElementById('createErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
            errorAlert.classList.add('d-none');

            const formData = new FormData(this);

            fetch('/roles', {
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

                    document.getElementById('createRoleForm').reset();
                    
                    // Cerrar modal correctamente
                    const modalElement = document.getElementById('createRoleModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    // Remover backdrop oscuro si queda visible
                    setTimeout(() => {
                        // Remover todos los backdrops
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        // Remover clase modal-open del body
                        document.body.classList.remove('modal-open');
                        // Limpiar inline styles que puedan estar bloqueando
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Rol creado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al crear el rol';
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
                submitBtn.innerHTML = 'Crear Rol';
            });
        });

        // ==================== EDITAR ROL ====================
        document.getElementById('editRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const roleId = document.getElementById('edit_role_id').value;
            const submitBtn = document.getElementById('submitEditBtn');
            const errorAlert = document.getElementById('editErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
            errorAlert.classList.add('d-none');

            const data = {
                nombre: document.getElementById('edit_nombre').value,
                descripcion: document.getElementById('edit_descripcion').value
            };

            fetch(`/roles/${roleId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la tabla manteniendo la página actual
                    const currentPage = table.page();
                    table.ajax.reload(function() {
                        table.page(currentPage).draw(false);
                    });

                    // Cerrar modal correctamente
                    const modalElement = document.getElementById('editRoleModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    // Remover backdrop oscuro si queda visible
                    setTimeout(() => {
                        // Remover todos los backdrops
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        // Remover clase modal-open del body
                        document.body.classList.remove('modal-open');
                        // Limpiar inline styles que puedan estar bloqueando
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Rol actualizado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al actualizar el rol';
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
                submitBtn.innerHTML = 'Actualizar Rol';
            });
        });

        // ==================== ELIMINAR ROL ====================
        document.getElementById('deleteRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const roleId = document.getElementById('delete_role_id').value;
            const submitBtn = document.getElementById('submitDeleteBtn');
            const errorAlert = document.getElementById('deleteErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
            errorAlert.classList.add('d-none');

            fetch(`/roles/${roleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la tabla manteniendo la página actual
                    const currentPage = table.page();
                    table.ajax.reload(function() {
                        table.page(currentPage).draw(false);
                    });

                    // Cerrar modal correctamente
                    const modalElement = document.getElementById('deleteRoleModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                    
                    // Remover backdrop oscuro si queda visible
                    setTimeout(() => {
                        // Remover todos los backdrops
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        // Remover clase modal-open del body
                        document.body.classList.remove('modal-open');
                        // Limpiar inline styles que puedan estar bloqueando
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }, 300);

                    showSuccessAlert('Rol eliminado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al eliminar el rol';
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
                submitBtn.innerHTML = 'Eliminar Rol';
            });
        });

        // Event listeners para botones dinámicos
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit-role')) {
                const roleId = e.target.closest('.btn-edit-role').getAttribute('data-role-id');
                loadRoleEdit(roleId);
            }
            if (e.target.closest('.btn-delete-role')) {
                const roleId = e.target.closest('.btn-delete-role').getAttribute('data-role-id');
                setDeleteRoleId(roleId);
            }
        });
    }

    window.loadRoleEdit = function(roleId) {
        fetch(`/roles/${roleId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_role_id').value = data.id;
                document.getElementById('edit_nombre').value = data.nombre;
                document.getElementById('edit_descripcion').value = data.descripcion || '';
                
                // Abrir el modal después de cargar los datos
                const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
                editRoleModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos del rol');
            });
    };

    window.setDeleteRoleId = function(roleId) {
        document.getElementById('delete_role_id').value = roleId;
    };

    // Limpiar modales cuando se cierren
    const createRoleModal = document.getElementById('createRoleModal');
    const editRoleModal = document.getElementById('editRoleModal');
    const deleteRoleModal = document.getElementById('deleteRoleModal');

    const cleanupModal = () => {
        setTimeout(() => {
            // Remover todos los backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            // Remover clase modal-open del body
            document.body.classList.remove('modal-open');
            // Limpiar inline styles que puedan estar bloqueando
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 100);
    };

    if (createRoleModal) {
        createRoleModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('createRoleForm').reset();
            document.getElementById('createErrorAlert').classList.add('d-none');
            cleanupModal();
        });
    }

    if (editRoleModal) {
        editRoleModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('editRoleForm').reset();
            document.getElementById('editErrorAlert').classList.add('d-none');
            cleanupModal();
        });
    }

    if (deleteRoleModal) {
        deleteRoleModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('deleteRoleForm').reset();
            document.getElementById('deleteErrorAlert').classList.add('d-none');
            cleanupModal();
        });
    }

    initRoleHandlers();
});
</script>
