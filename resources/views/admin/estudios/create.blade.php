@extends('layouts.app')

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nuevo Estudio</h5>
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

                    <form method="POST" action="{{ route('estudios.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Incubadora</label>
                            <select name="id_incubadora" class="form-select @error('id_incubadora') is-invalid @enderror" required>
                                <option value="">Seleccionar incubadora...</option>
                                @foreach($incubadoras as $incubadora)
                                    <option value="{{ $incubadora->id }}" {{ old('id_incubadora') == $incubadora->id ? 'selected' : '' }}>
                                        {{ $incubadora->nombre }} ({{ $incubadora->sensores->count() }} sensores)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_incubadora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre del Estudio</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                           value="{{ old('fecha_inicio') }}" required>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha Fin (opcional)</label>
                                    <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                           value="{{ old('fecha_fin') }}">
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Número de Muestras</label>
                            <input type="number" name="numero_muestras" class="form-control @error('numero_muestras') is-invalid @enderror" 
                                   value="{{ old('numero_muestras', 10) }}" min="1" max="100" required>
                            @error('numero_muestras')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('estudios.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Estudio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
