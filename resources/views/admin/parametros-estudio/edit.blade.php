@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Parámetro de Estudio</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('parametros.update', $parametro) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="codigo">Código <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                            id="codigo" name="codigo" disabled
                            value="{{ old('codigo', $parametro->codigo) }}">
                        @error('codigo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" required 
                            value="{{ old('nombre', $parametro->nombre) }}">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="unidad">Unidad <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unidad') is-invalid @enderror" 
                            id="unidad" name="unidad" required 
                            value="{{ old('unidad', $parametro->unidad) }}">
                        @error('unidad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="tipo_medicion">Tipo de Medición <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo_medicion') is-invalid @enderror" 
                            id="tipo_medicion" name="tipo_medicion" required>
                            <option value="">Selecciona tipo</option>
                            <option value="ambas" {{ old('tipo_medicion', $parametro->tipo_medicion) == 'ambas' ? 'selected' : '' }}>Ambas</option>
                            <option value="temperatura" {{ old('tipo_medicion', $parametro->tipo_medicion) == 'temperatura' ? 'selected' : '' }}>Temperatura</option>
                            <option value="ph" {{ old('tipo_medicion', $parametro->tipo_medicion) == 'ph' ? 'selected' : '' }}>pH</option>
                        </select>
                        @error('tipo_medicion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="decimales">Decimales (opcional)</label>
                        <input type="number" class="form-control @error('decimales') is-invalid @enderror" 
                            id="decimales" name="decimales" min="0" max="10" 
                            value="{{ old('decimales', $parametro->decimales) }}">
                        @error('decimales')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="minimo_optimo">Mínimo Óptimo (opcional)</label>
                        <input type="number" class="form-control @error('minimo_optimo') is-invalid @enderror" 
                            id="minimo_optimo" name="minimo_optimo" step="0.01" 
                            value="{{ old('minimo_optimo', $parametro->minimo_optimo) }}">
                        @error('minimo_optimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="maximo_optimo">Máximo Óptimo (opcional)</label>
                        <input type="number" class="form-control @error('maximo_optimo') is-invalid @enderror" 
                            id="maximo_optimo" name="maximo_optimo" step="0.01" 
                            value="{{ old('maximo_optimo', $parametro->maximo_optimo) }}">
                        @error('maximo_optimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="minimo_critico">Mínimo Crítico (opcional)</label>
                        <input type="number" class="form-control @error('minimo_critico') is-invalid @enderror" 
                            id="minimo_critico" name="minimo_critico" step="0.01" 
                            value="{{ old('minimo_critico', $parametro->minimo_critico) }}">
                        @error('minimo_critico')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="maximo_critico">Máximo Crítico (opcional)</label>
                        <input type="number" class="form-control @error('maximo_critico') is-invalid @enderror" 
                            id="maximo_critico" name="maximo_critico" step="0.01" 
                            value="{{ old('maximo_critico', $parametro->maximo_critico) }}">
                        @error('maximo_critico')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="{{ route('parametros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
