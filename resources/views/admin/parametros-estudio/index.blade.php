@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Parámetros de Estudio</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('parametros.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nuevo Parámetro
            </a>
        </div>
    </div>

    @if ($parametros->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Unidad</th>
                            <th>Tipo de Medición</th>
                            <th>Rango Óptimo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($parametros as $parametro)
                            <tr>
                                <td>
                                    <code>{{ $parametro->codigo }}</code>
                                </td>
                                <td>
                                    <strong>{{ $parametro->nombre }}</strong>
                                </td>
                                <td>{{ $parametro->unidad }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $parametro->tipo_medicion)) }}</span>
                                </td>
                                <td>
                                    @if ($parametro->minimo_optimo !== null && $parametro->maximo_optimo !== null)
                                        {{ $parametro->minimo_optimo }} - {{ $parametro->maximo_optimo }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('parametros.show', $parametro) }}">
                                                <i class="bx bx-show me-2"></i>Ver
                                            </a>
                                            <a class="dropdown-item" href="{{ route('parametros.edit', $parametro) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <form action="{{ route('parametros.destroy', $parametro) }}" method="POST" class="d-inline">
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
            {{ $parametros->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay parámetros registrados. <a href="{{ route('parametros.create') }}">Crear uno</a></p>
        </div>
    @endif
</div>
@endsection
