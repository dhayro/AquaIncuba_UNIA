@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Muestras de Estudio</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('muestras-estudio.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nueva Muestra
            </a>
        </div>
    </div>

    @if ($muestras->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Muestra #</th>
                            <th>Estudio</th>
                            <th>Incubadora</th>
                            <th>Fecha Recolección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($muestras as $muestra)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">#{{ $muestra->numero_muestra }}</span>
                                </td>
                                <td><strong>{{ $muestra->estudio->nombre }}</strong></td>
                                <td>{{ $muestra->estudio->incubadora->nombre }}</td>
                                <td>{{ $muestra->fecha_recoleccion->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $estadoColors = [
                                            'recibida' => 'info',
                                            'procesando' => 'warning',
                                            'completada' => 'success',
                                            'descartada' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $estadoColors[$muestra->estado_muestra] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $muestra->estado_muestra)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('muestras-estudio.show', $muestra) }}">
                                                <i class="bx bx-show me-2"></i>Ver
                                            </a>
                                            <a class="dropdown-item" href="{{ route('muestras-estudio.edit', $muestra) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <form action="{{ route('muestras-estudio.destroy', $muestra) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Eliminar?');">
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
            {{ $muestras->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay muestras registradas. <a href="{{ route('muestras-estudio.create') }}">Crear una</a></p>
        </div>
    @endif
</div>
@endsection
