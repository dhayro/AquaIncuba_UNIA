@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Dispositivos MQTT</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('dispositivos-mqtt.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nuevo Dispositivo
            </a>
        </div>
    </div>

    @if ($dispositivos->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>ID Dispositivo</th>
                            <th>Tipo</th>
                            <th>Tema MQTT</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($dispositivos as $dispositivo)
                            <tr>
                                <td>
                                    <strong>{{ $dispositivo->nombre }}</strong>
                                </td>
                                <td><code>{{ $dispositivo->id_dispositivo }}</code></td>
                                <td>{{ $dispositivo->tipo_dispositivo }}</td>
                                <td><small>{{ $dispositivo->tema_mqtt }}</small></td>
                                <td>
                                    @if ($dispositivo->ubicacion)
                                        {{ $dispositivo->ubicacion }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($dispositivo->esta_activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('dispositivos-mqtt.show', $dispositivo) }}">
                                                <i class="bx bx-eye me-2"></i>Ver
                                            </a>
                                            <a class="dropdown-item" href="{{ route('dispositivos-mqtt.edit', $dispositivo) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <form action="{{ route('dispositivos-mqtt.destroy', $dispositivo) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este dispositivo?');">
                                                    <i class="bx bx-trash me-2"></i>Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $dispositivos->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay dispositivos MQTT registrados. <a href="{{ route('dispositivos-mqtt.create') }}">Crear uno</a></p>
        </div>
    @endif
</div>
@endsection
