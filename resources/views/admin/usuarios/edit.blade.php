@extends('layouts.app')

@section('styles')
@vite(['resources/scss/light/assets/forms/switches.scss'])
@vite(['resources/scss/dark/assets/forms/switches.scss'])
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Editar Usuario: {{ $usuario->nombre }}</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errores:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $usuario->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" 
                                   value="{{ old('correo', $usuario->correo) }}" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" name="contraseña" class="form-control @error('contraseña') is-invalid @enderror">
                            <small class="form-text text-muted">Dejar en blanco para mantener la actual</small>
                            @error('contraseña')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="contraseña_confirmation" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <div class="list-group">
                                @foreach($roles as $rol)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $rol->id }}" 
                                               id="rol_{{ $rol->id }}" {{ in_array($rol->id, $rolesAsignados) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rol_{{ $rol->id }}">
                                            <strong>{{ ucfirst($rol->nombre) }}</strong>
                                            <small class="text-muted">{{ $rol->descripcion }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
