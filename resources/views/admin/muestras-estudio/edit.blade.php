@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Muestra</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('muestras.update', $muestra) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Estudio</label>
                        <input type="text" class="form-control" disabled 
                            value="{{ $muestra->estudio->nombre }} - {{ $muestra->estudio->incubadora->nombre }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="numero_muestra">Número de Muestra <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('numero_muestra') is-invalid @enderror" 
                            id="numero_muestra" name="numero_muestra" min="1" required 
                            value="{{ old('numero_muestra', $muestra->numero_muestra) }}">
                        @error('numero_muestra')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="estado_muestra">Estado de Muestra <span class="text-danger">*</span></label>
                        <select class="form-select @error('estado_muestra') is-invalid @enderror" 
                            id="estado_muestra" name="estado_muestra" required>
                            <option value="">Selecciona un estado</option>
                            <option value="recibida" {{ old('estado_muestra', $muestra->estado_muestra) == 'recibida' ? 'selected' : '' }}>Recibida</option>
                            <option value="procesando" {{ old('estado_muestra', $muestra->estado_muestra) == 'procesando' ? 'selected' : '' }}>Procesando</option>
                            <option value="completada" {{ old('estado_muestra', $muestra->estado_muestra) == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="descartada" {{ old('estado_muestra', $muestra->estado_muestra) == 'descartada' ? 'selected' : '' }}>Descartada</option>
                        </select>
                        @error('estado_muestra')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="fecha_recoleccion">Fecha de Recolección <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_recoleccion') is-invalid @enderror" 
                            id="fecha_recoleccion" name="fecha_recoleccion" required 
                            value="{{ old('fecha_recoleccion', $muestra->fecha_recoleccion->format('Y-m-d')) }}">
                        @error('fecha_recoleccion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="hora_recoleccion">Hora de Recolección (opcional)</label>
                        <input type="time" class="form-control @error('hora_recoleccion') is-invalid @enderror" 
                            id="hora_recoleccion" name="hora_recoleccion" 
                            value="{{ old('hora_recoleccion', $muestra->hora_recoleccion) }}">
                        @error('hora_recoleccion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="ubicacion_recoleccion">Ubicación de Recolección (opcional)</label>
                        <input type="text" class="form-control @error('ubicacion_recoleccion') is-invalid @enderror" 
                            id="ubicacion_recoleccion" name="ubicacion_recoleccion" 
                            value="{{ old('ubicacion_recoleccion', $muestra->ubicacion_recoleccion) }}">
                        @error('ubicacion_recoleccion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="condiciones_ambientales">Condiciones Ambientales (opcional)</label>
                        <textarea class="form-control @error('condiciones_ambientales') is-invalid @enderror" 
                            id="condiciones_ambientales" name="condiciones_ambientales" rows="3">{{ old('condiciones_ambientales', $muestra->condiciones_ambientales) }}</textarea>
                        @error('condiciones_ambientales')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="{{ route('muestras.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
