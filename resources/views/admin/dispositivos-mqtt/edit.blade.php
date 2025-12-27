@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Dispositivo MQTT</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dispositivos-mqtt.update', $dispositivoMqtt) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" placeholder="Ej: Dispositivo MQTT 01" required 
                            value="{{ old('nombre', $dispositivoMqtt->nombre) }}">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="id_dispositivo">ID Dispositivo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('id_dispositivo') is-invalid @enderror" 
                            id="id_dispositivo" name="id_dispositivo" placeholder="Ej: mqtt-device-001" required 
                            value="{{ old('id_dispositivo', $dispositivoMqtt->id_dispositivo) }}">
                        @error('id_dispositivo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tipo_dispositivo">Tipo de Dispositivo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tipo_dispositivo') is-invalid @enderror" 
                            id="tipo_dispositivo" name="tipo_dispositivo" placeholder="Ej: Sensor, Actuador, etc." required 
                            value="{{ old('tipo_dispositivo', $dispositivoMqtt->tipo_dispositivo) }}">
                        @error('tipo_dispositivo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tema_mqtt">Tema MQTT <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tema_mqtt') is-invalid @enderror" 
                            id="tema_mqtt" name="tema_mqtt" placeholder="Ej: aqua/sensores/temp" required 
                            value="{{ old('tema_mqtt', $dispositivoMqtt->tema_mqtt) }}">
                        @error('tema_mqtt')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="ubicacion">Ubicación (opcional)</label>
                        <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                            id="ubicacion" name="ubicacion" placeholder="Ej: Laboratorio 1, Tanque A, etc." 
                            value="{{ old('ubicacion', $dispositivoMqtt->ubicacion) }}">
                        @error('ubicacion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="esta_activo" name="esta_activo" 
                                value="1" {{ old('esta_activo', $dispositivoMqtt->esta_activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="esta_activo">
                                Dispositivo Activo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="{{ route('dispositivos-mqtt.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
