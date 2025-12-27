@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Temas MQTT</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('temas-mqtt.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nuevo Tema
            </a>
        </div>
    </div>

    @if ($temas->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tema</th>
                            <th>Descripción</th>
                            <th>Tipo de Dato</th>
                            <th>Unidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($temas as $tema)
                            <tr>
                                <td>
                                    <strong>{{ $tema->tema }}</strong>
                                    @if ($tema->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($tema->descripcion, 40) }}</small>
                                    @endif
                                </td>
                                <td><small>{{ $tema->descripcion ?? '—' }}</small></td>
                                <td><code>{{ $tema->tipo_dato }}</code></td>
                                <td>{{ $tema->unidad ?? '—' }}</td>
                                <td>
                                    @if ($tema->esta_activo)
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
                                            <a class="dropdown-item" href="{{ route('temas-mqtt.show', $tema) }}">
                                                <i class="bx bx-show me-2"></i>Ver
                                            </a>
                                            <a class="dropdown-item" href="{{ route('temas-mqtt.edit', $tema) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <form action="{{ route('temas-mqtt.destroy', $tema) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Estás seguro?');">
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

        <div class="d-flex justify-content-center mt-4">
            {{ $temas->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay temas MQTT registrados. <a href="{{ route('temas-mqtt.create') }}">Crear uno</a></p>
        </div>
    @endif
</div>
@endsection
