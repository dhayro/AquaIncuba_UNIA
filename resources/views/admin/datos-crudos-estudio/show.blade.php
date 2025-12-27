@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Dato Crudo #{{ $datoCrudoEstudio->id }}</h4>
            <p class="text-muted mb-0">Dato de Calidad del Agua</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('datos-crudos-estudio.edit', $datoCrudoEstudio) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('datos-crudos-estudio.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Mediciones de Calidad del Agua</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Temperatura (°C)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->temperatura }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">pH</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->ph }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Conductividad (µS/cm)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->conductividad }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Oxígeno Disuelto (mg/L)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->oxigeno_disuelto }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Turbidez (NTU)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->turbidez }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Sólidos Totales (mg/L)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->solidos_totales }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Alcalinidad (mg/L CaCO₃)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->alcalinidad }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Dureza (mg/L CaCO₃)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->dureza }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Cloro Residual (mg/L)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->cloro_residual }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Fluoruro (mg/L)</label>
                            <p class="mb-0"><strong>{{ $datoCrudoEstudio->fluoruro }}</strong></p>
                        </div>
                    </div>

                    @if ($datoCrudoEstudio->observaciones)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Observaciones</label>
                                <p class="mb-0">{{ $datoCrudoEstudio->observaciones }}</p>
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
                        <strong>{{ $datoCrudoEstudio->estudio->nombre }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Incubadora</label>
                        <small>{{ $datoCrudoEstudio->estudio->incubadora->nombre }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Responsable</label>
                        <small>{{ $datoCrudoEstudio->estudio->usuario->nombre }}</small>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Muestra Asociada</h5>
                </div>
                <div class="card-body">
                    @if ($datoCrudoEstudio->muestra)
                        <p class="mb-2">
                            <label class="form-label text-muted mb-1">Número</label>
                            <strong>{{ $datoCrudoEstudio->muestra->numero_muestra }}</strong>
                        </p>
                        <p class="mb-2">
                            <label class="form-label text-muted mb-1">Fecha Recolección</label>
                            <small>{{ $datoCrudoEstudio->muestra->fecha_recoleccion->format('d/m/Y') }}</small>
                        </p>
                        <p class="mb-0">
                            <label class="form-label text-muted mb-1">Estado</label>
                            <span class="badge 
                                @if ($datoCrudoEstudio->muestra->estado_muestra === 'recibida') bg-info
                                @elseif ($datoCrudoEstudio->muestra->estado_muestra === 'procesando') bg-warning
                                @elseif ($datoCrudoEstudio->muestra->estado_muestra === 'completada') bg-success
                                @elseif ($datoCrudoEstudio->muestra->estado_muestra === 'descartada') bg-danger
                                @endif
                            ">
                                {{ ucfirst($datoCrudoEstudio->muestra->estado_muestra) }}
                            </span>
                        </p>
                    @else
                        <p class="text-muted mb-0">Sin muestra asociada</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información de Registro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Creado</label>
                        <small>{{ $datoCrudoEstudio->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $datoCrudoEstudio->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
