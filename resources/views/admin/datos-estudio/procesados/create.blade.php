@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Crear Dato Procesado</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('datos-procesados.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="id_estudio">Estudio <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_estudio_calidad_agua') is-invalid @enderror" 
                            id="id_estudio" name="id_estudio_calidad_agua" required>
                            <option value="">Selecciona un estudio</option>
                            @foreach ($estudios as $estudio)
                                <option value="{{ $estudio->id }}" {{ old('id_estudio_calidad_agua') == $estudio->id ? 'selected' : '' }}>
                                    {{ $estudio->nombre }} - {{ $estudio->incubadora->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_estudio_calidad_agua')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="parametro">Parámetro <span class="text-danger">*</span></label>
                        <select class="form-select @error('parametro') is-invalid @enderror" 
                            id="parametro" name="parametro" required>
                            <option value="">Selecciona un parámetro</option>
                            <option value="temperatura" {{ old('parametro') == 'temperatura' ? 'selected' : '' }}>Temperatura</option>
                            <option value="ph" {{ old('parametro') == 'ph' ? 'selected' : '' }}>pH</option>
                            <option value="conductividad" {{ old('parametro') == 'conductividad' ? 'selected' : '' }}>Conductividad</option>
                            <option value="oxigeno_disuelto" {{ old('parametro') == 'oxigeno_disuelto' ? 'selected' : '' }}>Oxígeno Disuelto</option>
                            <option value="turbidez" {{ old('parametro') == 'turbidez' ? 'selected' : '' }}>Turbidez</option>
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
                            value="{{ old('promedio') }}">
                        @error('promedio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="desviacion_estandar">Desviación Estándar (σ)</label>
                        <input type="number" class="form-control @error('desviacion_estandar') is-invalid @enderror" 
                            id="desviacion_estandar" name="desviacion_estandar" step="0.01" min="0" 
                            value="{{ old('desviacion_estandar') }}">
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
                            value="{{ old('minimo') }}">
                        @error('minimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="maximo">Máximo <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('maximo') is-invalid @enderror" 
                            id="maximo" name="maximo" step="0.01" required 
                            value="{{ old('maximo') }}">
                        @error('maximo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="notas">Notas de Procesamiento</label>
                        <textarea class="form-control @error('notas_procesamiento') is-invalid @enderror" 
                            id="notas" name="notas_procesamiento" rows="3">{{ old('notas_procesamiento') }}</textarea>
                        @error('notas_procesamiento')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Dato</button>
                        <a href="{{ route('datos-procesados.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
