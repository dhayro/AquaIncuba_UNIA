@extends('layouts.app')

@section('styles')
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">{{ $estudio->nombre }}</h5>
                        <small class="text-muted">Incubadora: {{ $estudio->incubadora->nombre }} | Inicio: {{ $estudio->fecha_inicio->format('d/m/Y') }}</small>
                    </div>
                    <div>
                        <a href="{{ route('estudios.edit', $estudio->id) }}" class="btn btn-warning btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Editar
                        </a>
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <h6 class="mb-3">Muestras del Estudio</h6>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Muestra</th>
                                    <th>Datos Crudos</th>
                                    <th>Datos Procesados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($muestras as $muestra)
                                    <tr>
                                        <td>{{ $muestra->id }}</td>
                                        <td><strong>Muestra #{{ $muestra->numero_muestra }}</strong></td>
                                        <td>
                                            @if($muestra->datosCrudos->count() > 0)
                                                <span class="badge bg-success">{{ $muestra->datosCrudos->count() }}</span>
                                            @else
                                                <span class="badge bg-secondary">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($muestra->datosProcesados->count() > 0)
                                                <span class="badge bg-info">{{ $muestra->datosProcesados->count() }}</span>
                                            @else
                                                <span class="badge bg-secondary">0</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#muestra{{ $muestra->id }}">
                                                Ver Detalles
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay muestras registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales para detalles de muestras -->
@foreach($muestras as $muestra)
<div class="modal fade" id="muestra{{ $muestra->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Muestra #{{ $muestra->numero_muestra }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Datos Crudos</h6>
                @if($muestra->datosCrudos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Parámetro</th>
                                    <th>Valor</th>
                                    <th>Sensor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($muestra->datosCrudos as $dato)
                                    <tr>
                                        <td>{{ $dato->parametro->nombre }}</td>
                                        <td>{{ $dato->valor_crudo }}</td>
                                        <td>{{ $dato->sensor->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Sin datos crudos registrados</p>
                @endif

                <h6 class="mt-4">Datos Procesados</h6>
                @if($muestra->datosProcesados->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Parámetro</th>
                                    <th>Valor Procesado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($muestra->datosProcesados as $dato)
                                    <tr>
                                        <td>{{ $dato->parametro->nombre }}</td>
                                        <td>{{ $dato->valor_procesado }}</td>
                                        <td>
                                            @if($dato->dentro_rango)
                                                <span class="badge bg-success">Dentro de Rango</span>
                                            @else
                                                <span class="badge bg-danger">Fuera de Rango</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Sin datos procesados registrados</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
@endsection
