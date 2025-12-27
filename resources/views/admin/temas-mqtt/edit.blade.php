@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Tema MQTT</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('temas-mqtt.update', $temaMqtt) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tema">Tema <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tema') is-invalid @enderror" 
                            id="tema" name="tema" placeholder="Ej: temperatura_agua" required 
                            value="{{ old('tema', $temaMqtt->tema) }}">
                        @error('tema')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tipo_dato">Tipo de Dato <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tipo_dato') is-invalid @enderror" 
                            id="tipo_dato" name="tipo_dato" placeholder="Ej: float, integer, string" required 
                            value="{{ old('tipo_dato', $temaMqtt->tipo_dato) }}">
                        @error('tipo_dato')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="unidad">Unidad (opcional)</label>
                        <input type="text" class="form-control @error('unidad') is-invalid @enderror" 
                            id="unidad" name="unidad" placeholder="Ej: °C, %, pH" 
                            value="{{ old('unidad', $temaMqtt->unidad) }}">
                        @error('unidad')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="descripcion">Descripción (opcional)</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" name="descripcion" placeholder="Descripción" 
                            value="{{ old('descripcion', $temaMqtt->descripcion) }}">
                        @error('descripcion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="valor_minimo">Valor Mínimo (opcional)</label>
                        <input type="number" step="any" class="form-control @error('valor_minimo') is-invalid @enderror" 
                            id="valor_minimo" name="valor_minimo" placeholder="Ej: 15.5" 
                            value="{{ old('valor_minimo', $temaMqtt->valor_minimo) }}">
                        @error('valor_minimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="valor_maximo">Valor Máximo (opcional)</label>
                        <input type="number" step="any" class="form-control @error('valor_maximo') is-invalid @enderror" 
                            id="valor_maximo" name="valor_maximo" placeholder="Ej: 35.5" 
                            value="{{ old('valor_maximo', $temaMqtt->valor_maximo) }}">
                        @error('valor_maximo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="esta_activo" name="esta_activo" 
                                value="1" {{ old('esta_activo', $temaMqtt->esta_activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="esta_activo">
                                Tema Activo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="{{ route('temas-mqtt.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
