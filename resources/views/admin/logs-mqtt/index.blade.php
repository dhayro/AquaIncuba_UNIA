@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Logs MQTT</h4>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bx bx-filter me-2"></i>Filtros
            </button>
            <form action="{{ route('logs-mqtt.export') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="bx bx-download me-2"></i>Exportar CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Filtros activos -->
    @if (request()->filled('dispositivo_id') || request()->filled('tema_id') || request()->filled('desde') || request()->filled('hasta'))
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Mostrando resultados filtrados</span>
            <a href="{{ route('logs-mqtt.index') }}" class="btn btn-sm btn-outline-info">Limpiar filtros</a>
        </div>
    @endif

    @if ($logs->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Dispositivo</th>
                            <th>Tema</th>
                            <th>Valor</th>
                            <th>Fecha/Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($logs as $log)
                            <tr>
                                <td><strong>{{ $log->tema->dispositivo->nombre }}</strong></td>
                                <td>
                                    {{ $log->tema->nombre }}<br>
                                    <code style="font-size: 0.75rem;">{{ $log->tema->ruta }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $log->valor }} {{ $log->tema->unidad }}</span>
                                </td>
                                <td>{{ $log->fecha_lectura->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('logs-mqtt.show', $log) }}" class="btn btn-sm btn-icon btn-text-primary" title="Ver detalles">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay logs MQTT registrados.</p>
        </div>
    @endif

    <!-- Modal de Filtros -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtrar Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('logs-mqtt.index') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="dispositivo_id">Dispositivo</label>
                            <select class="form-select" id="dispositivo_id" name="dispositivo_id">
                                <option value="">Todos los dispositivos</option>
                                @foreach ($dispositivos as $dispositivo)
                                    <option value="{{ $dispositivo->id }}" {{ request('dispositivo_id') == $dispositivo->id ? 'selected' : '' }}>
                                        {{ $dispositivo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="tema_id">Tema</label>
                            <select class="form-select" id="tema_id" name="tema_id">
                                <option value="">Todos los temas</option>
                                @foreach ($temas as $tema)
                                    <option value="{{ $tema->id }}" {{ request('tema_id') == $tema->id ? 'selected' : '' }}>
                                        {{ $tema->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="desde">Desde</label>
                                <input type="date" class="form-control" id="desde" name="desde" value="{{ request('desde') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="hasta">Hasta</label>
                                <input type="date" class="form-control" id="hasta" name="hasta" value="{{ request('hasta') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para limpiar logs antiguos -->
    <div class="modal fade" id="cleanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Limpiar Logs Antiguos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('logs-mqtt.clean') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Elimina todos los logs más antiguos de X días.</p>
                        <div class="mb-3">
                            <label class="form-label" for="dias">Días</label>
                            <input type="number" class="form-control" id="dias" name="dias" min="1" max="365" value="30" required>
                        </div>
                        <p class="text-warning"><small><i class="bx bx-info-circle"></i> Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar Logs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
