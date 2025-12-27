@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">{{ $dispositivoMqtt->nombre }}</h4>
            <p class="text-muted mb-0">Dispositivo MQTT</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('dispositivos-mqtt.edit', $dispositivoMqtt) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('dispositivos-mqtt.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Dispositivo</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Nombre</label>
                            <p class="mb-0"><strong>{{ $dispositivoMqtt->nombre }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">ID Dispositivo</label>
                            <p class="mb-0"><code>{{ $dispositivoMqtt->id_dispositivo }}</code></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Tipo</label>
                            <p class="mb-0"><strong>{{ $dispositivoMqtt->tipo_dispositivo }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Estado</label>
                            <p class="mb-0">
                                @if ($dispositivoMqtt->esta_activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Tema MQTT</label>
                            <p class="mb-0"><code>{{ $dispositivoMqtt->tema_mqtt }}</code></p>
                        </div>
                    </div>

                    @if ($dispositivoMqtt->ubicacion)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Ubicación</label>
                                <p class="mb-0">{{ $dispositivoMqtt->ubicacion }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información de Registro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Creado</label>
                        <small>{{ $dispositivoMqtt->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Última Lectura</label>
                        @if ($dispositivoMqtt->ultima_lectura)
                            <small>{{ $dispositivoMqtt->ultima_lectura->format('d/m/Y H:i') }}</small>
                        @else
                            <small class="text-muted">Sin datos</small>
                        @endif
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Versión Firmware</label>
                        @if ($dispositivoMqtt->version_firmware)
                            <small>{{ $dispositivoMqtt->version_firmware }}</small>
                        @else
                            <small class="text-muted">No especificada</small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
