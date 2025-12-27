@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Conclusiones de Estudio</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('conclusiones.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-2"></i>Nueva Conclusión
            </a>
        </div>
    </div>

    @if ($conclusiones->count())
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Estudio</th>
                            <th>Calidad de Agua</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($conclusiones as $conclusion)
                            <tr>
                                <td>
                                    <strong>{{ $conclusion->titulo }}</strong>
                                    @if ($conclusion->recomendaciones)
                                        <br><small class="text-muted">Recomendaciones: Sí</small>
                                    @endif
                                </td>
                                <td>{{ $conclusion->estudio->nombre }}</td>
                                <td>
                                    @php
                                        $colors = [
                                            'excelente' => 'success',
                                            'buena' => 'info',
                                            'aceptable' => 'warning',
                                            'deficiente' => 'danger',
                                            'muy_deficiente' => 'dark',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $colors[$conclusion->calidad_agua] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $conclusion->calidad_agua)) }}
                                    </span>
                                </td>
                                <td><small>{{ $conclusion->created_at->format('d/m/Y') }}</small></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('conclusiones.show', $conclusion) }}">
                                                <i class="bx bx-show me-2"></i>Ver
                                            </a>
                                            <a class="dropdown-item" href="{{ route('conclusiones.edit', $conclusion) }}">
                                                <i class="bx bx-edit me-2"></i>Editar
                                            </a>
                                            <a class="dropdown-item" href="{{ route('conclusiones.pdf', $conclusion) }}">
                                                <i class="bx bx-file me-2"></i>PDF
                                            </a>
                                            <form action="{{ route('conclusiones.destroy', $conclusion) }}" method="POST" class="d-inline">
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
            {{ $conclusiones->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">No hay conclusiones registradas. <a href="{{ route('conclusiones.create') }}">Crear una</a></p>
        </div>
    @endif
</div>
@endsection
