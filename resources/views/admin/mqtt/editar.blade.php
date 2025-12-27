@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Configuración MQTT</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('mqtt.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" name="nombre" required 
                            value="{{ old('nombre', $configuracion->nombre ?? 'Configuración MQTT') }}">
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="host">Host/IP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('host') is-invalid @enderror" 
                            id="host" name="host" placeholder="localhost o IP" required 
                            value="{{ old('host', $configuracion->host ?? 'localhost') }}">
                        @error('host')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="puerto">Puerto <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('puerto') is-invalid @enderror" 
                            id="puerto" name="puerto" min="1" max="65535" required 
                            value="{{ old('puerto', $configuracion->puerto ?? 1883) }}">
                        @error('puerto')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted d-block mt-1">1883 (sin TLS) o 8883 (con TLS)</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="usuario">Usuario (opcional)</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                            id="usuario" name="usuario" 
                            value="{{ old('usuario', $configuracion->usuario ?? '') }}">
                        @error('usuario')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="contraseña">Contraseña (opcional)</label>
                        <input type="password" class="form-control @error('contraseña') is-invalid @enderror" 
                            id="contraseña" name="contraseña" 
                            value="{{ old('contraseña', $configuracion->contraseña ?? '') }}">
                        @error('contraseña')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activa" name="activa" 
                                value="1" {{ old('activa', $configuracion->activa ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activa">
                                Configuración Activa
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Una vez activada, el sistema iniciará la conexión MQTT automáticamente.
                        </small>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <strong>Configuración típica:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Host: mosquitto.docker.local o 192.168.1.100</li>
                        <li>Puerto: 1883 (MQTT sin encripción)</li>
                        <li>Usuario: mosquitto_user</li>
                        <li>Topic base: aquaincuba/</li>
                    </ul>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                        <a href="{{ route('mqtt.configuracion') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
