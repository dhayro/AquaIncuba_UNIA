@extends('layouts.app')

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nuevo Sensor</h5>
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

                    <form method="POST" action="{{ route('sensores.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nombre del Sensor</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo') }}" required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Sensor</label>
                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="temperatura" {{ old('tipo') === 'temperatura' ? 'selected' : '' }}>Temperatura</option>
                                <option value="ph" {{ old('tipo') === 'ph' ? 'selected' : '' }}>pH</option>
                                <option value="oxigeno_disuelto" {{ old('tipo') === 'oxigeno_disuelto' ? 'selected' : '' }}>Oxígeno Disuelto</option>
                                <option value="turbidez" {{ old('tipo') === 'turbidez' ? 'selected' : '' }}>Turbidez</option>
                                <option value="conductividad" {{ old('tipo') === 'conductividad' ? 'selected' : '' }}>Conductividad</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unidad de Medida</label>
                            <input type="text" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" 
                                   value="{{ old('unidad_medida') }}" required>
                            @error('unidad_medida')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rango Mínimo</label>
                                    <input type="number" name="rango_minimo" class="form-control @error('rango_minimo') is-invalid @enderror" 
                                           value="{{ old('rango_minimo') }}" step="0.1">
                                    @error('rango_minimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rango Máximo</label>
                                    <input type="number" name="rango_maximo" class="form-control @error('rango_maximo') is-invalid @enderror" 
                                           value="{{ old('rango_maximo') }}" step="0.1">
                                    @error('rango_maximo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Factor de Calibración</label>
                            <input type="number" name="factor_calibracion" class="form-control @error('factor_calibracion') is-invalid @enderror" 
                                   value="{{ old('factor_calibracion', 1) }}" step="0.0001">
                            @error('factor_calibracion')
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

                        <div class="text-end">
                            <a href="{{ route('sensores.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Sensor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
