@extends('layouts.app')

@section('styles')
{{-- @vite(['resources/scss/light/assets/tables/datatable.scss']) --}}
{{-- @vite(['resources/scss/dark/assets/tables/datatable.scss']) --}}
@endsection

@section('content')

<!-- ✅ TARJETA DE BIENVENIDA Y FLUJO RÁPIDO -->
<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="card" style="border-top: 4px solid #0d6efd;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div style="font-size: 2.5rem;">👋</div>
                    </div>
                    <div class="col">
                        <h5 class="card-title mb-1">¡Bienvenido a AquaIncuba UNIA!</h5>
                        <p class="card-text text-muted mb-3">Sistema de Monitoreo de Calidad de Agua en Incubadoras</p>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle p-2 me-2" style="font-size: 1.1rem;">1</span>
                                    <div>
                                        <strong>Crear Estudio</strong><br>
                                        <small class="text-muted">Inicia un nuevo ciclo de monitoreo</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle p-2 me-2" style="font-size: 1.1rem;">2</span>
                                    <div>
                                        <strong>Ver Muestras</strong><br>
                                        <small class="text-muted">Se crean automáticamente</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle p-2 me-2" style="font-size: 1.1rem;">3</span>
                                    <div>
                                        <strong>Registrar Lecturas</strong><br>
                                        <small class="text-muted">Ingresa tus mediciones</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('estudios.index') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Crear mi Primer Estudio
                            </a>
                            <a href="{{ route('lecturas.index') }}" class="btn btn-outline-primary btn-sm ms-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                Ver Lecturas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row layout-top-spacing">
    <!-- Estadísticas Principales -->
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-icon bg-primary text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        </div>
                        <h6 class="card-title mt-3 mb-0">Incubadoras</h6>
                        <h2 class="card-text mt-2">{{ $totalIncubadoras }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-icon bg-success text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                        <h6 class="card-title mt-3 mb-0">Sensores</h6>
                        <h2 class="card-text mt-2">{{ $totalSensores }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-icon bg-info text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4-2h2v20h-2zm4 4h2v16h-2z"/></svg>
                        </div>
                        <h6 class="card-title mt-3 mb-0">Estudios Activos</h6>
                        <h2 class="card-text mt-2">{{ $estudiosActivos->count() }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-icon bg-warning text-white rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <h6 class="card-title mt-3 mb-0">Usuarios</h6>
                        <h2 class="card-text mt-2">{{ $totalUsuarios }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incubadoras Activas -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Incubadoras Activas</h5>
            </div>
            <div class="card-body">
                @if($incubadorasActivas->count() > 0)
                    <div class="list-group">
                        @foreach($incubadorasActivas as $incubadora)
                            <a href="{{ route('incubadoras.edit', $incubadora->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $incubadora->nombre }}</h6>
                                    <small class="text-muted">{{ $incubadora->sensores->count() }} sensores</small>
                                </div>
                                <p class="mb-1"><small class="text-muted">{{ $incubadora->codigo }}</small></p>
                                @if($incubadora->temperatura_optima)
                                    <small class="text-muted">T°: {{ $incubadora->temperatura_optima }}°C</small>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay incubadoras registradas</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Estudios Activos -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Estudios en Progreso</h5>
            </div>
            <div class="card-body">
                @if($estudiosActivos->count() > 0)
                    <div class="list-group">
                        @foreach($estudiosActivos as $estudio)
                            <a href="{{ route('estudios.show', $estudio->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $estudio->nombre }}</h6>
                                    <span class="badge bg-success">Activo</span>
                                </div>
                                <p class="mb-1"><small class="text-muted">{{ $estudio->incubadoras->pluck('nombre')->join(', ') ?? 'N/A' }}</small></p>
                                <small class="text-muted">Inicio: {{ $estudio->fecha_inicio->format('d/m/Y') }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay estudios activos</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Lecturas Recientes -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Lecturas Recientes de Sensores</h5>
            </div>
            <div class="card-body">
                @if($lecturasRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Incubadora</th>
                                    <th>Sensor</th>
                                    <th>Valor Crudo</th>
                                    <th>Valor Procesado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lecturasRecientes as $lectura)
                                    <tr>
                                        <td>{{ $lectura->incubadora->nombre }}</td>
                                        <td>{{ $lectura->sensor->nombre }}</td>
                                        <td>{{ $lectura->valor_crudo }}</td>
                                        <td>{{ $lectura->valor_procesado ?? 'N/A' }}</td>
                                        <td><small class="text-muted">{{ $lectura->created_at->format('d/m/Y H:i') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No hay lecturas registradas</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- @vite(['resources/js/apps/datatables.js']) --}}
@endsection
