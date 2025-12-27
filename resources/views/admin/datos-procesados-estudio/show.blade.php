@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Dato Procesado #{{ $datoProcessadoEstudio->id }}</h4>
            <p class="text-muted mb-0">Análisis Estadístico de Calidad del Agua</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('datos-procesados-estudio.edit', $datoProcessadoEstudio) }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit me-2"></i>Editar
            </a>
            <a href="{{ route('datos-procesados-estudio.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Análisis Estadístico - Temperatura</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Promedio (°C)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->promedio_temperatura }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Desviación Estándar</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->desviacion_temperatura }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Mínimo (°C)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->minimo_temperatura }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Máximo (°C)</label>
                            <p class="mb-0"><strong>{{ $datoProcessadoEstudio->maximo_temperatura }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Análisis Estadístico - pH</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Promedio</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->promedio_ph }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Desviación Estándar</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->desviacion_ph }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Mínimo</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->minimo_ph }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Máximo</label>
                            <p class="mb-0"><strong>{{ $datoProcessadoEstudio->maximo_ph }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Análisis Estadístico - Conductividad</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Promedio (µS/cm)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->promedio_conductividad }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Desviación Estándar</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->desviacion_conductividad }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Mínimo (µS/cm)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->minimo_conductividad }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Máximo (µS/cm)</label>
                            <p class="mb-0"><strong>{{ $datoProcessadoEstudio->maximo_conductividad }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Análisis Estadístico - Oxígeno Disuelto</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Promedio (mg/L)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->promedio_oxigeno }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Desviación Estándar</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->desviacion_oxigeno }}</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Mínimo (mg/L)</label>
                            <p class="mb-2"><strong>{{ $datoProcessadoEstudio->minimo_oxigeno }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Máximo (mg/L)</label>
                            <p class="mb-0"><strong>{{ $datoProcessadoEstudio->maximo_oxigeno }}</strong></p>
                        </div>
                    </div>
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
                        <strong>{{ $datoProcessadoEstudio->estudio->nombre }}</strong>
                    </p>
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Incubadora</label>
                        <small>{{ $datoProcessadoEstudio->estudio->incubadora->nombre }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Responsable</label>
                        <small>{{ $datoProcessadoEstudio->estudio->usuario->nombre }}</small>
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Datos Crudos</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <label class="form-label text-muted mb-1">Total de mediciones</label>
                        <strong>{{ $datoProcessadoEstudio->estudio->datosCrudos->count() }}</strong>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Período de análisis</label>
                        <small>
                            @if ($datoProcessadoEstudio->estudio->datosCrudos->count())
                                {{ $datoProcessadoEstudio->estudio->datosCrudos->min('created_at')->format('d/m/Y') }}
                                a
                                {{ $datoProcessadoEstudio->estudio->datosCrudos->max('created_at')->format('d/m/Y') }}
                            @else
                                Sin datos
                            @endif
                        </small>
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
                        <small>{{ $datoProcessadoEstudio->created_at->format('d/m/Y H:i') }}</small>
                    </p>
                    <p class="mb-0">
                        <label class="form-label text-muted mb-1">Actualizado</label>
                        <small>{{ $datoProcessadoEstudio->updated_at->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
