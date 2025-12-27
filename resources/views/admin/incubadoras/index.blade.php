@extends('layouts.app')

@section('styles')
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Gestión de Incubadoras</h5>
                    <a href="{{ route('incubadoras.create') }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Nueva Incubadora
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Volumen</th>
                                    <th>Sensores</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incubadoras as $incubadora)
                                    <tr>
                                        <td>{{ $incubadora->id }}</td>
                                        <td><strong>{{ $incubadora->nombre }}</strong></td>
                                        <td><code>{{ $incubadora->codigo }}</code></td>
                                        <td>{{ $incubadora->volumen_litros ? $incubadora->volumen_litros . ' L' : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $incubadora->sensores->count() }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('incubadoras.sensores', $incubadora->id) }}" class="btn btn-sm btn-info" title="Asignar Sensores">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><path d="M16.5 7.5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"></path><path d="M2 12a10 10 0 1 1 20 0 10 10 0 0 1-20 0z"></path></svg>
                                            </a>
                                            <a href="{{ route('incubadoras.edit', $incubadora->id) }}" class="btn btn-sm btn-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Editar
                                            </a>
                                            <form method="POST" action="{{ route('incubadoras.destroy', $incubadora->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay incubadoras registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $incubadoras->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@endsection
