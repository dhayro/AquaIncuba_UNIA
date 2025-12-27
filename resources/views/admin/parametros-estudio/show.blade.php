@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">{{ $parametro->nombre }}</h4>
            <p class="text-muted mb-0">Código: <code>{{ $parametro->codigo }}</code></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('parametros.edit', $parametro) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('parametros.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Parámetro</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Nombre</label>
                            <p class="mb-0"><strong>{{ $parametro->nombre }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Unidad de Medida</label>
                            <p class="mb-0"><strong>{{ $parametro->unidad }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted mb-1">Tipo de Medición</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $parametro->tipo_medicion)) }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted mb-1">Decimales</label>
                            <p class="mb-0"><strong>{{ $parametro->decimales }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted mb-1">Código</label>
                            <p class="mb-0"><code>{{ $parametro->codigo }}</code></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Rango Óptimo</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted mb-1">Mínimo</label>
                                    <p class="mb-0">
                                        @if ($parametro->minimo_optimo !== null)
                                            <strong>{{ $parametro->minimo_optimo }}</strong>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted mb-1">Máximo</label>
                                    <p class="mb-0">
                                        @if ($parametro->maximo_optimo !== null)
                                            <strong>{{ $parametro->maximo_optimo }}</strong>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6>Rango Crítico</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted mb-1">Mínimo</label>
                                    <p class="mb-0">
                                        @if ($parametro->minimo_critico !== null)
                                            <strong>{{ $parametro->minimo_critico }}</strong>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted mb-1">Máximo</label>
                                    <p class="mb-0">
                                        @if ($parametro->maximo_critico !== null)
                                            <strong>{{ $parametro->maximo_critico }}</strong>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Información de Registro</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Creado</label>
                        <small>{{ $parametro->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $parametro->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
