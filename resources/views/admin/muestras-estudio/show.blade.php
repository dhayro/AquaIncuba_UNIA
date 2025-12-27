@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Muestra #{{ $muestra->numero_muestra }}</h4>
            <p class="text-muted mb-0">{{ $muestra->estudio->nombre }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('muestras.edit', $muestra) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('muestras.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información de la Muestra</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Número de Muestra</label>
                            <p class="mb-0"><strong>#{{ $muestra->numero_muestra }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Estado</label>
                            <p class="mb-0">
                                @php
                                    $estadoColors = [
                                        'recibida' => 'info',
                                        'procesando' => 'warning',
                                        'completada' => 'success',
                                        'descartada' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $estadoColors[$muestra->estado_muestra] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $muestra->estado_muestra)) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Fecha de Recolección</label>
                            <p class="mb-0"><strong>{{ $muestra->fecha_recoleccion->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Hora de Recolección</label>
                            <p class="mb-0">
                                @if ($muestra->hora_recoleccion)
                                    <strong>{{ $muestra->hora_recoleccion }}</strong>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($muestra->ubicacion_recoleccion)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Ubicación de Recolección</label>
                                <p class="mb-0"><strong>{{ $muestra->ubicacion_recoleccion }}</strong></p>
                            </div>
                        </div>
                    @endif

                    @if ($muestra->condiciones_ambientales)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Condiciones Ambientales</label>
                                <p class="mb-0">{{ $muestra->condiciones_ambientales }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Estudio Asociado</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Nombre</label>
                        <strong>{{ $muestra->estudio->nombre }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Incubadora</label>
                        <strong>{{ $muestra->estudio->incubadora->nombre }}</strong>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Estado del Estudio</label>
                        <span class="badge bg-primary">{{ ucfirst($muestra->estudio->estado) }}</span>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información de Registro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Creado</label>
                        <small>{{ $muestra->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $muestra->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
