@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Lecturas de Sensores</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('lecturas.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Nueva Lectura
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
                        <th>Sensor</th>
                        <th>Incubadora</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lecturas as $lectura)
                        <tr>
                            <td>
                                <strong>{{ $lectura->sensor->nombre ?? 'N/A' }}</strong>
                                <br/>
                                <small class="text-muted">{{ $lectura->sensor->tipo ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $lectura->incubadora->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $lectura->valor }} {{ $lectura->sensor->unidad ?? '' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $lectura->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" 
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('lecturas.edit', $lectura) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <form action="{{ route('lecturas.destroy', $lectura) }}" method="POST" 
                                            style="display:inline;" 
                                            onsubmit="return confirm('¿Eliminar lectura?');">
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
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted">No hay lecturas registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $lecturas->links() }}
    </div>
</div>
@endsection
