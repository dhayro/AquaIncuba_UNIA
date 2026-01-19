@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <i class="fas fa-database"></i> Datos de Estudios
                    </h2>
                    <div class="text-muted mt-1">Consulta datos de estudios finalizados o en progreso</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Buscador -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('estudios-datos.buscar') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="text" name="codigo" class="form-control" placeholder="Ej: EST-UNIA-2026-003" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </form>
                            @if(session('error'))
                                <div class="alert alert-danger mt-2 mb-0">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Estudios -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Estudios Disponibles</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Código</th>
                                        <th style="width: 30%;">Nombre</th>
                                        <th style="width: 15%;">Estado</th>
                                        <th style="width: 15%;">Incubadoras</th>
                                        <th style="width: 20%;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($estudios as $estudio)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark font-monospace">
                                                    {{ $estudio->codigo_estudio }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $estudio->nombre }}</strong>
                                            </td>
                                            <td>
                                                @switch($estudio->estado)
                                                    @case('en_progreso')
                                                        <span class="badge bg-warning">En Progreso</span>
                                                        @break
                                                    @case('finalizado')
                                                        <span class="badge bg-success">Finalizado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($estudio->estado) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $estudio->incubadoras->count() }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('estudios-datos.show', $estudio->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Ver Datos
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                No hay estudios disponibles
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($estudios->hasPages())
                            <div class="card-footer d-flex align-items-center">
                                {{ $estudios->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
