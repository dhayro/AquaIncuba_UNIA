@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Dato Procesado</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('datos-procesados.update', $datoProcessado) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Estudio</label>
                        <input type="text" class="form-control" disabled 
                            value="{{ $datoProcessado->estudio->nombre }} - {{ $datoProcessado->estudio->incubadora->nombre }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="parametro">Parámetro <span class="text-danger">*</span></label>
                        <select class="form-select @error('parametro') is-invalid @enderror" 
                            id="parametro" name="parametro" required>
                            <option value="">Selecciona un parámetro</option>
                            <option value="temperatura" {{ old('parametro', $datoProcessado->parametro) == 'temperatura' ? 'selected' : '' }}>Temperatura</option>
                            <option value="ph" {{ old('parametro', $datoProcessado->parametro) == 'ph' ? 'selected' : '' }}>pH</option>
                            <option value="conductividad" {{ old('parametro', $datoProcessado->parametro) == 'conductividad' ? 'selected' : '' }}>Conductividad</option>
                            <option value="oxigeno_disuelto" {{ old('parametro', $datoProcessado->parametro) == 'oxigeno_disuelto' ? 'selected' : '' }}>Oxígeno Disuelto</option>
                            <option value="turbidez" {{ old('parametro', $datoProcessado->parametro) == 'turbidez' ? 'selected' : '' }}>Turbidez</option>
                        </select>
                        @error('parametro')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="promedio">Promedio <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('promedio') is-invalid @enderror" 
                            id="promedio" name="promedio" step="0.01" required 
                            value="{{ old('promedio', $datoProcessado->promedio) }}">
                        @error('promedio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="desviacion_estandar">Desviación Estándar (σ)</label>
                        <input type="number" class="form-control @error('desviacion_estandar') is-invalid @enderror" 
                            id="desviacion_estandar" name="desviacion_estandar" step="0.01" min="0" 
                            value="{{ old('desviacion_estandar', $datoProcessado->desviacion_estandar) }}">
                        @error('desviacion_estandar')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="minimo">Mínimo <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('minimo') is-invalid @enderror" 
                            id="minimo" name="minimo" step="0.01" required 
                            value="{{ old('minimo', $datoProcessado->minimo) }}">
                        @error('minimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="maximo">Máximo <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('maximo') is-invalid @enderror" 
                            id="maximo" name="maximo" step="0.01" required 
                            value="{{ old('maximo', $datoProcessado->maximo) }}">
                        @error('maximo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="notas">Notas de Procesamiento</label>
                        <textarea class="form-control @error('notas_procesamiento') is-invalid @enderror" 
                            id="notas" name="notas_procesamiento" rows="3">{{ old('notas_procesamiento', $datoProcessado->notas_procesamiento) }}</textarea>
                        @error('notas_procesamiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="{{ route('datos-procesados.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
