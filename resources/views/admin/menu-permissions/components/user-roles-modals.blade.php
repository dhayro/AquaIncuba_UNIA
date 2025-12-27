@foreach($usuarios as $usuario)
    <!-- Modal Roles Usuario -->
    <div class="modal fade" id="rolesModal{{ $usuario->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Roles de {{ $usuario->nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @forelse($usuario->roles as $rol)
                        <div class="mb-3 p-3 border rounded" style="background-color: #f8f9fa; border-left: 4px solid #0d6efd;">
                            <h6 class="text-primary fw-bold mb-1">{{ $rol->nombre }}</h6>
                            <p class="text-muted mb-0 small">{{ $rol->descripcion ?? 'Sin descripción' }}</p>
                        </div>
                    @empty
                        <div class="alert alert-light-warning text-center py-4">
                            <p class="text-muted mb-0">Este usuario no tiene roles asignados</p>
                        </div>
                    @endforelse
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('menu-permissions.user-roles', $usuario->id) }}" class="btn btn-primary">Editar Roles</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
