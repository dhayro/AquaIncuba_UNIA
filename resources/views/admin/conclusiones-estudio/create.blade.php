@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Nueva Conclusión</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('conclusiones.store') }}" method="POST">
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
                        <label class="form-label" for="titulo">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                            id="titulo" name="titulo" placeholder="Ej: Conclusiones del análisis de calidad de agua" required 
                            value="{{ old('titulo') }}">
                        @error('titulo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="contenido">Contenido <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('contenido') is-invalid @enderror" 
                            id="contenido" name="contenido" rows="6" placeholder="Describe los hallazgos principales..." required>{{ old('contenido') }}</textarea>
                        @error('contenido')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted d-block mt-1">Mínimo 20 caracteres</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="calidad_agua">Calidad de Agua <span class="text-danger">*</span></label>
                        <select class="form-select @error('calidad_agua') is-invalid @enderror" 
                            id="calidad_agua" name="calidad_agua" required>
                            <option value="">Selecciona un nivel</option>
                            <option value="excelente" {{ old('calidad_agua') == 'excelente' ? 'selected' : '' }}>Excelente</option>
                            <option value="buena" {{ old('calidad_agua') == 'buena' ? 'selected' : '' }}>Buena</option>
                            <option value="aceptable" {{ old('calidad_agua') == 'aceptable' ? 'selected' : '' }}>Aceptable</option>
                            <option value="deficiente" {{ old('calidad_agua') == 'deficiente' ? 'selected' : '' }}>Deficiente</option>
                            <option value="muy_deficiente" {{ old('calidad_agua') == 'muy_deficiente' ? 'selected' : '' }}>Muy Deficiente</option>
                        </select>
                        @error('calidad_agua')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="recomendaciones">Recomendaciones (opcional)</label>
                        <textarea class="form-control @error('recomendaciones') is-invalid @enderror" 
                            id="recomendaciones" name="recomendaciones" rows="4" placeholder="Sugiere acciones para mejorar la calidad del agua...">{{ old('recomendaciones') }}</textarea>
                        @error('recomendaciones')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Crear Conclusión</button>
                        <a href="{{ route('conclusiones.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
