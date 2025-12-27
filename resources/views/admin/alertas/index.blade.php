@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Alertas MQTT</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('alertas.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Nueva Alerta
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Sensor</th>
                        <th>Rango</th>
                        <th>Notificación</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alertas as $alerta)
                        <tr>
                            <td>
                                <strong>{{ $alerta->nombre }}</strong>
                                <br/>
                                <small class="text-muted">{{ $alerta->descripcion }}</small>
                            </td>
                            <td>{{ $alerta->sensor->nombre ?? 'N/A' }}</td>
                            <td>
                                @if ($alerta->valor_minimo && $alerta->valor_maximo)
                                    {{ $alerta->valor_minimo }} - {{ $alerta->valor_maximo }}
                                @elseif ($alerta->valor_minimo)
                                    Min: {{ $alerta->valor_minimo }}
                                @elseif ($alerta->valor_maximo)
                                    Max: {{ $alerta->valor_maximo }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($alerta->tipo_notificacion) }}</span>
                            </td>
                            <td class="text-center">
                                @if ($alerta->activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-danger">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" 
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <form action="{{ route('alertas.toggle', $alerta) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-toggle-left me-1"></i> 
                                                {{ $alerta->activa ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                        <a class="dropdown-item" href="{{ route('alertas.edit', $alerta) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <form action="{{ route('alertas.destroy', $alerta) }}" method="POST" 
                                            style="display:inline;" 
                                            onsubmit="return confirm('¿Eliminar alerta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted">No hay alertas configuradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $alertas->links() }}
    </div>
</div>
@endsection
