@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Alerta</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('alertas.update', $alerta) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" required value="{{ old('nombre', $alerta->nombre) }}">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sensor</label>
                        <div class="form-control-plaintext">
                            <strong>{{ $alerta->sensor->nombre }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $alerta->descripcion) }}</textarea>
                        @error('descripcion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="valor_minimo">Valor Mínimo</label>
                        <input type="number" class="form-control @error('valor_minimo') is-invalid @enderror" 
                            id="valor_minimo" name="valor_minimo" step="0.01" 
                            value="{{ old('valor_minimo', $alerta->valor_minimo) }}">
                        @error('valor_minimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="valor_maximo">Valor Máximo</label>
                        <input type="number" class="form-control @error('valor_maximo') is-invalid @enderror" 
                            id="valor_maximo" name="valor_maximo" step="0.01" 
                            value="{{ old('valor_maximo', $alerta->valor_maximo) }}">
                        @error('valor_maximo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tipo_notificacion">Tipo de Notificación <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo_notificacion') is-invalid @enderror" 
                            id="tipo_notificacion" name="tipo_notificacion" required>
                            <option value="email" {{ old('tipo_notificacion', $alerta->tipo_notificacion) == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ old('tipo_notificacion', $alerta->tipo_notificacion) == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="ambos" {{ old('tipo_notificacion', $alerta->tipo_notificacion) == 'ambos' ? 'selected' : '' }}>Ambos</option>
                        </select>
                        @error('tipo_notificacion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="destinatario">Destinatario <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('destinatario') is-invalid @enderror" 
                            id="destinatario" name="destinatario" required 
                            value="{{ old('destinatario', $alerta->destinatario) }}">
                        @error('destinatario')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activa" name="activa" 
                                value="1" {{ old('activa', $alerta->activa) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activa">
                                Alerta Activa
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('alertas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
