@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Datos Crudos de Estudio</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('datos-crudos.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nuevo Dato
            </a>
        </div>
    </div>

    @if ($datos->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Estudio</th>
                            <th>Incubadora</th>
                            <th>Muestra</th>
                            <th>Temperatura</th>
                            <th>pH</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($datos as $dato)
                            <tr>
                                <td><strong>{{ $dato->estudio->nombre }}</strong></td>
                                <td>{{ $dato->estudio->incubadora->nombre }}</td>
                                <td>
                                    <span class="badge bg-primary">#{{ $dato->numero_muestra }}</span>
                                </td>
                                <td>
                                    @if ($dato->temperatura)
                                        {{ $dato->temperatura }}°C
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if ($dato->ph)
                                        {{ $dato->ph }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('datos-crudos.edit', $dato) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <form action="{{ route('datos-crudos.destroy', $dato) }}" method="POST" class="d-inline">
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
            {{ $datos->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay datos crudos registrados. <a href="{{ route('datos-crudos.create') }}">Crear uno</a></p>
        </div>
    @endif
</div>
@endsection
