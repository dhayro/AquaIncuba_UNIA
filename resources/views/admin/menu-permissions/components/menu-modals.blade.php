<!-- Modal Crear Menú -->
<div class="modal fade" id="createMenuModal" tabindex="-1" role="dialog" aria-labelledby="createMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMenuLabel">Crear Nuevo Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createMenuForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="nombre" class="form-label">Nombre del Menú *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="text" class="form-control" id="url" name="url" placeholder="/admin/ejemplo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="icono" class="form-label">Ícono (clase CSS)</label>
                            <input type="text" class="form-control" id="icono" name="icono" placeholder="feather feather-home">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_padre" class="form-label">Menú Padre</label>
                            <select class="form-select" id="id_padre" name="id_padre">
                                <option value="">— Menú Principal —</option>
                                @forelse($menus->where('nivel', 0) as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->nombre }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="orden" class="form-label">Orden de Visualización *</label>
                            <input type="number" class="form-control" id="orden" name="orden" value="1" required>
                        </div>
                    </div>
                    <div id="createErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="submitCreateBtn">Crear Menú</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Menú -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuLabel">Editar Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMenuForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_menu_id" name="menu_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit_nombre" class="form-label">Nombre del Menú *</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_url" class="form-label">URL</label>
                            <input type="text" class="form-control" id="edit_url" name="url" placeholder="/admin/ejemplo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_icono" class="form-label">Ícono (clase CSS)</label>
                            <input type="text" class="form-control" id="edit_icono" name="icono" placeholder="feather feather-home">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_id_padre" class="form-label">Menú Padre</label>
                            <select class="form-select" id="edit_id_padre" name="id_padre">
                                <option value="">— Menú Principal —</option>
                                @forelse($menus->where('nivel', 0) as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->nombre }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_orden" class="form-label">Orden de Visualización *</label>
                            <input type="number" class="form-control" id="edit_orden" name="orden" required>
                        </div>
                    </div>
                    <div id="editErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning" id="submitEditBtn">Actualizar Menú</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Menú -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" role="dialog" aria-labelledby="deleteMenuLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMenuLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteMenuForm">
                @csrf
                @method('DELETE')
                <input type="hidden" id="delete_menu_id" name="menu_id">
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar este menú? Esta acción no se puede deshacer.</p>
                    <p class="text-danger"><strong>Nota:</strong> Si el menú tiene submenús, estos también serán eliminados.</p>
                    <div id="deleteErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="submitDeleteBtn">Eliminar Menú</button>
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
    
    function initMenuHandlers() {
        if ($.fn.dataTable.isDataTable('#menus-table')) {
            table = $('#menus-table').DataTable();
            setupMenuFormHandlers();
            return;
        }
        
        if (retries < maxRetries) {
            retries++;
            setTimeout(initMenuHandlers, 100);
        }
    }
    
    function setupMenuFormHandlers() {
        // Actualizar lista de menús padre cuando se abre el modal de crear
        const createMenuModal = document.getElementById('createMenuModal');
        if (createMenuModal) {
            createMenuModal.addEventListener('show.bs.modal', function() {
                updateCreateMenuParents();
            });
        }
        
        // ==================== CREAR MENÚ ====================
        document.getElementById('createMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitCreateBtn');
            const errorAlert = document.getElementById('createErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
            errorAlert.classList.add('d-none');

            const formData = new FormData(this);

            fetch('/admin/menu-permissions/menus', {
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

                    document.getElementById('createMenuForm').reset();
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createMenuModal'));
                    modal.hide();

                    showSuccessAlert('Menú creado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al crear el menú';
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
                submitBtn.innerHTML = 'Crear Menú';
            });
        });

        // ==================== EDITAR MENÚ ====================
        document.getElementById('editMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const menuId = document.getElementById('edit_menu_id').value;
            const submitBtn = document.getElementById('submitEditBtn');
            const errorAlert = document.getElementById('editErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
            errorAlert.classList.add('d-none');

            const formData = new FormData(this);

            fetch(`/admin/menu-permissions/menus/${menuId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editMenuModal'));
                    modal.hide();

                    showSuccessAlert('Menú actualizado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al actualizar el menú';
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
                submitBtn.innerHTML = 'Actualizar Menú';
            });
        });

        // ==================== ELIMINAR MENÚ ====================
        document.getElementById('deleteMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const menuId = document.getElementById('delete_menu_id').value;
            const submitBtn = document.getElementById('submitDeleteBtn');
            const errorAlert = document.getElementById('deleteErrorAlert');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Eliminando...';
            errorAlert.classList.add('d-none');

            const formData = new FormData(this);

            fetch(`/admin/menu-permissions/menus/${menuId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'DELETE'
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

                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteMenuModal'));
                    modal.hide();

                    showSuccessAlert('Menú eliminado correctamente');
                } else {
                    errorAlert.textContent = data.message || 'Error al eliminar el menú';
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
                submitBtn.innerHTML = 'Eliminar Menú';
            });
        });

        // Event listeners para botones dinámicos
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit-menu')) {
                const menuId = e.target.closest('.btn-edit-menu').getAttribute('data-menu-id');
                loadMenuEdit(menuId);
            }
            if (e.target.closest('.btn-delete-menu')) {
                const menuId = e.target.closest('.btn-delete-menu').getAttribute('data-menu-id');
                setDeleteMenuId(menuId);
            }
        });
    }

    window.loadMenuEdit = function(menuId) {
        fetch(`/admin/menu-permissions/menus/${menuId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_menu_id').value = data.id;
                document.getElementById('edit_nombre').value = data.nombre;
                document.getElementById('edit_url').value = data.url || '';
                document.getElementById('edit_icono').value = data.icono || '';
                document.getElementById('edit_orden').value = data.orden;
                
                // Cargar lista de menús padre disponibles (excluyendo el menú actual y sus submenús)
                loadAvailableParents(menuId, data.id_padre);
            })
            .catch(error => console.error('Error:', error));
    };

    window.loadAvailableParents = function(currentMenuId, selectedParentId) {
        // Obtener todos los menús principales disponibles desde la tabla ya cargada
        const mainMenuSelect = document.getElementById('edit_id_padre');
        
        // Limpiar el select
        mainMenuSelect.innerHTML = '<option value="">— Menú Principal —</option>';
        
        // Obtener todos los menús de la tabla actual
        const rows = table.rows({ search: 'applied' }).data();
        const usedParents = new Set();
        
        rows.each(function(rowData) {
            const parentName = rowData[3]; // Columna "Padre"
            if (parentName && parentName !== '—') {
                usedParents.add(parentName);
            }
        });
        
        // Construir opciones filtrando menús principales (excluyendo el actual)
        const processedMenus = new Set();
        rows.each(function(rowData) {
            const menuName = rowData[0]; // Nombre del menú
            const menuParent = rowData[3]; // Padre
            const menuId = rowData[6]; // ID (columna oculta)
            
            // Si es menú principal (padre = "—") y no es el menú actual
            if (menuParent === '—' && menuId != currentMenuId && !processedMenus.has(menuId)) {
                processedMenus.add(menuId);
                // Extraer el nombre limpio (sin HTML)
                const cleanName = menuName.replace(/<[^>]*>/g, '').trim();
                
                const option = document.createElement('option');
                option.value = menuId;
                option.textContent = cleanName;
                if (menuId == selectedParentId) {
                    option.selected = true;
                }
                mainMenuSelect.appendChild(option);
            }
        });
    };

    window.updateCreateMenuParents = function() {
        // Actualizar lista de menús padre para el modal de crear
        const createMenuSelect = document.getElementById('id_padre');
        
        // Limpiar y reconstruir el select
        createMenuSelect.innerHTML = '<option value="">— Menú Principal —</option>';
        
        // Obtener todos los menús de la tabla actual
        const rows = table.rows({ search: 'applied' }).data();
        const processedMenus = new Set();
        
        rows.each(function(rowData) {
            const menuName = rowData[0]; // Nombre del menú
            const menuParent = rowData[3]; // Padre
            const menuId = rowData[6]; // ID (columna oculta)
            
            // Si es menú principal (padre = "—")
            if (menuParent === '—' && !processedMenus.has(menuId)) {
                processedMenus.add(menuId);
                // Extraer el nombre limpio (sin HTML)
                const cleanName = menuName.replace(/<[^>]*>/g, '').trim();
                
                const option = document.createElement('option');
                option.value = menuId;
                option.textContent = cleanName;
                createMenuSelect.appendChild(option);
            }
        });
    };

    window.setDeleteMenuId = function(menuId) {
        document.getElementById('delete_menu_id').value = menuId;
    };

    function showSuccessAlert(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'floating-alert alert alert-light-success alert-dismissible fade show';
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <div>
                <strong>¡Éxito!</strong>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 300);"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto-dismiss después de 4 segundos
        setTimeout(function() {
            if (alertDiv.parentElement) {
                alertDiv.classList.add('hide');
                setTimeout(function() {
                    if (alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, 300);
            }
        }, 4000);
    }
    
    initMenuHandlers();
});
</script>
