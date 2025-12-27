@extends('layouts.app')

@section('styles')
<style>
    .statbox {
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    .widget-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e4e6eb;
        background: linear-gradient(135deg, #f5f7fa 0%, #f9f9fc 100%);
    }
    .widget-header h4 {
        color: #212529;
        font-weight: 600;
        margin: 0;
    }
    .widget-content {
        padding: 1.5rem;
    }
    .form-check-label {
        color: #212529;
        margin-bottom: 0;
        cursor: pointer;
        font-weight: 500;
    }
    .form-label {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .card-body p {
        color: #212529;
        margin-bottom: 0;
    }
    .space-y-2 {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .space-y-1 {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .info-box {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e4e6eb;
    }
    .info-box .label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .seperator-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-12">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="page-title">Roles de Usuario: <span class="text-primary">{{ $user->nombre }}</span></h2>
            </div>
            <a href="{{ route('menu-permissions.index') }}" class="btn btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Volver
            </a>
        </div>
    </div>
</div>

<div class="row layout-spacing">
    <div class="col-lg-8">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Asignar Roles a {{ $user->nombre }}</h4>
            </div>
            <div class="widget-content">
                <form action="{{ route('menu-permissions.update-user-roles', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label class="form-label">Selecciona los roles para este usuario</label>
                        <div class="space-y-2">
                            @forelse($allRoles as $role)
                                <div class="form-check p-3 border rounded" style="background-color: #f8f9fa;">
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input" 
                                        id="role_{{ $role->id }}" 
                                        name="roles[]" 
                                        value="{{ $role->id }}"
                                        {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label fw-bold ms-2" for="role_{{ $role->id }}">
                                        {{ $role->nombre }}
                                    </label>
                                    @if($role->descripcion)
                                        <small class="d-block text-muted ms-4 mt-1">{{ $role->descripcion }}</small>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center py-4">No hay roles disponibles</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('menu-permissions.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 4 20 10 14 10"></polyline><polyline points="4 20 4 14 10 14"></polyline><path d="M4 4v7a7 7 0 0 0 12 0V4m0 16h7a7 7 0 0 0-7-7"></path></svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Información del Usuario</h4>
            </div>
            <div class="widget-content">
                <div class="info-box mb-3">
                    <div class="label mb-2">Nombre</div>
                    <p>{{ $user->nombre }}</p>
                </div>

                <div class="info-box mb-3">
                    <div class="label mb-2">Correo Electrónico</div>
                    <p><a href="mailto:{{ $user->correo }}" class="text-primary">{{ $user->correo }}</a></p>
                </div>

                <div class="info-box mb-3">
                    <div class="label mb-2">Empresa</div>
                    <p>{{ $user->empresa->nombre ?? 'No asignada' }}</p>
                </div>

                <div class="info-box">
                    <div class="label mb-2">Roles Actuales</div>
                    <div class="space-y-1">
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->nombre }}</span>
                        @empty
                            <p class="text-muted small mb-0">Sin roles asignados</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
