@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">{{ $temaMqtt->tema }}</h4>
            <p class="text-muted mb-0">Tema MQTT</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('temas-mqtt.edit', $temaMqtt) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('temas-mqtt.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información del Tema</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Tema</label>
                            <p class="mb-0"><strong>{{ $temaMqtt->tema }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Tipo de Dato</label>
                            <p class="mb-0"><code>{{ $temaMqtt->tipo_dato }}</code></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Unidad</label>
                            <p class="mb-0">{{ $temaMqtt->unidad ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Estado</label>
                            <p class="mb-0">
                                @if ($temaMqtt->esta_activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Valor Mínimo</label>
                            <p class="mb-0">{{ $temaMqtt->valor_minimo ?? 'Sin límite' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Valor Máximo</label>
                            <p class="mb-0">{{ $temaMqtt->valor_maximo ?? 'Sin límite' }}</p>
                        </div>
                    </div>

                    @if ($temaMqtt->descripcion)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Descripción</label>
                                <p class="mb-0">{{ $temaMqtt->descripcion }}</p>
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
                        <small>{{ $temaMqtt->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $temaMqtt->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
