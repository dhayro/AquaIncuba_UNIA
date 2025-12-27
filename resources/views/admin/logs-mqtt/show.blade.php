@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Detalles del Log MQTT</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('logs-mqtt.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Log</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Dispositivo</label>
                            <p class="mb-0"><strong>{{ $logMqtt->tema->dispositivo->nombre }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Tema</label>
                            <p class="mb-0"><strong>{{ $logMqtt->tema->nombre }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Ruta MQTT</label>
                            <p class="mb-0"><code>{{ $logMqtt->tema->ruta }}</code></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Valor</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">
                                    {{ $logMqtt->valor }} {{ $logMqtt->tema->unidad }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Tipo de Dato</label>
                            <p class="mb-0"><strong>{{ ucfirst(str_replace('_', ' ', $logMqtt->tema->tipo_dato)) }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Fecha/Hora de Lectura</label>
                            <p class="mb-0"><strong>{{ $logMqtt->fecha_lectura->format('d/m/Y H:i:s') }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Payload Completo</label>
                            <pre class="bg-light p-3 rounded"><code>{{ $logMqtt->payload }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Tema</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Nombre</label>
                        <strong>{{ $logMqtt->tema->nombre }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Dispositivo</label>
                        <strong>{{ $logMqtt->tema->dispositivo->nombre }}</strong>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Estado</label>
                        <span class="badge {{ $logMqtt->tema->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $logMqtt->tema->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información de Registro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Registrado</label>
                        <small>{{ $logMqtt->created_at->format('d/m/Y H:i:s') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
