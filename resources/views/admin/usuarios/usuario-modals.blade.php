{{-- Create Usuario Modal --}}
<div class="modal fade" id="createUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="createUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUsuarioLabel">Crear Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="createUsuarioForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createNombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="createNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="createCorreo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="createCorreo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="createContraseña" class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="createContraseña" name="contraseña" required minlength="8">
                        <small class="text-muted">Mínimo 8 caracteres</small>
                    </div>
                    <div id="createErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Usuario Modal --}}
<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="editUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUsuarioLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="editUsuarioForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUsuarioId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCorreo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="editCorreo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editContraseña" class="form-label">Nueva Contraseña <span class="text-muted">(Dejar vacío para no cambiar)</span></label>
                        <input type="password" class="form-control" id="editContraseña" name="contraseña" minlength="8">
                        <small class="text-muted">Mínimo 8 caracteres</small>
                    </div>
                    <div id="editErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Usuario Modal --}}
<div class="modal fade" id="deleteUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="deleteUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger" id="deleteUsuarioLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -0.25em; margin-right: 0.5rem;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Eliminar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="deleteUsuarioForm">
                @csrf
                @method('DELETE')
                <input type="hidden" id="deleteUsuarioId">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este usuario? Esta acción <strong>no se puede deshacer</strong>.</p>
                    <div id="deleteErrorAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>