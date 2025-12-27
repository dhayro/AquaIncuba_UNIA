@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Conclusión #{{ $conclusionEstudio->id }}</h4>
            <p class="text-muted mb-0">Conclusión del Estudio de Calidad del Agua</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('conclusiones-estudio.edit', $conclusionEstudio) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('conclusiones-estudio.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información de la Conclusión</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Calidad del Agua</label>
                            <p class="mb-0">
                                @php
                                    $colorCalidad = match($conclusionEstudio->calidad_agua) {
                                        'excelente' => 'success',
                                        'buena' => 'info',
                                        'aceptable' => 'warning',
                                        'pobre' => 'danger',
                                        'muy_pobre' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $colorCalidad }}">
                                    {{ ucfirst(str_replace('_', ' ', $conclusionEstudio->calidad_agua)) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Apto para Consumo</label>
                            <p class="mb-0">
                                @if ($conclusionEstudio->apto_consumo)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Resumen de Conclusiones</label>
                            <div class="alert alert-light">
                                <p class="mb-0">{{ $conclusionEstudio->resumen }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted mb-1">Hallazgos Principales</label>
                            <div class="alert alert-light">
                                <p class="mb-0">{{ $conclusionEstudio->hallazgos }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($conclusionEstudio->recomendaciones)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Recomendaciones</label>
                                <div class="alert alert-light">
                                    <p class="mb-0">{{ $conclusionEstudio->recomendaciones }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($conclusionEstudio->observaciones)
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-muted mb-1">Observaciones Adicionales</label>
                                <div class="alert alert-light">
                                    <p class="mb-0">{{ $conclusionEstudio->observaciones }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Parámetros Evaluados</h5>
                </div>
                <div class="card-body">
                    @if ($conclusionEstudio->parametros->count())
                        <div class="table-responsive text-nowrap">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parámetro</th>
                                        <th>Valor</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conclusionEstudio->parametros as $parametro)
                                        <tr>
                                            <td><strong>{{ $parametro->nombre }}</strong></td>
                                            <td><code>{{ $parametro->valor }} {{ $parametro->unidad }}</code></td>
                                            <td>
                                                @if ($parametro->cumple_normativa)
                                                    <span class="badge bg-success">Conforme</span>
                                                @else
                                                    <span class="badge bg-danger">No Conforme</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No hay parámetros asociados a esta conclusión.</p>
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
                        <strong>{{ $conclusionEstudio->estudio->nombre }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Incubadora</label>
                        <small>{{ $conclusionEstudio->estudio->incubadora->nombre }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Responsable</label>
                        <small>{{ $conclusionEstudio->estudio->usuario->nombre }}</small>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Datos del Análisis</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Datos crudos registrados</label>
                        <strong>{{ $conclusionEstudio->estudio->datosCrudos->count() }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Datos procesados</label>
                        <strong>{{ $conclusionEstudio->estudio->datosProcessados->count() }}</strong>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Muestras recolectadas</label>
                        <strong>{{ $conclusionEstudio->estudio->muestras->count() }}</strong>
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
                        <small>{{ $conclusionEstudio->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $conclusionEstudio->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Firmado por</label>
                        <small>{{ $conclusionEstudio->firmado_por ?? 'No firmado' }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
