@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Cargar Datos Crudos</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('datos-crudos.store') }}" method="POST">
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
                        <label class="form-label" for="numero_muestra">Número de Muestra <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('numero_muestra') is-invalid @enderror" 
                            id="numero_muestra" name="numero_muestra" min="1" required 
                            value="{{ old('numero_muestra') }}">
                        @error('numero_muestra')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="temperatura">Temperatura (°C)</label>
                        <input type="number" class="form-control @error('temperatura') is-invalid @enderror" 
                            id="temperatura" name="temperatura" step="0.01" min="0" max="50" 
                            value="{{ old('temperatura') }}">
                        @error('temperatura')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="ph">pH</label>
                        <input type="number" class="form-control @error('ph') is-invalid @enderror" 
                            id="ph" name="ph" step="0.1" min="0" max="14" 
                            value="{{ old('ph') }}">
                        @error('ph')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="conductividad">Conductividad</label>
                        <input type="number" class="form-control @error('conductividad') is-invalid @enderror" 
                            id="conductividad" name="conductividad" step="0.01" min="0" 
                            value="{{ old('conductividad') }}">
                        @error('conductividad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="oxigeno_disuelto">Oxígeno Disuelto (mg/L)</label>
                        <input type="number" class="form-control @error('oxigeno_disuelto') is-invalid @enderror" 
                            id="oxigeno_disuelto" name="oxigeno_disuelto" step="0.01" min="0" 
                            value="{{ old('oxigeno_disuelto') }}">
                        @error('oxigeno_disuelto')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="turbidez">Turbidez (NTU)</label>
                        <input type="number" class="form-control @error('turbidez') is-invalid @enderror" 
                            id="turbidez" name="turbidez" step="0.01" min="0" 
                            value="{{ old('turbidez') }}">
                        @error('turbidez')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="observaciones">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                            id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Datos</button>
                        <a href="{{ route('datos-crudos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
