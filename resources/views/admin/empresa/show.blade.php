@extends('layouts.app')

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Información de la Empresa</h5>
                    <a href="{{ route('empresa.edit') }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Editar
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($empresa->logo)
                                <img src="{{ Storage::url($empresa->logo) }}" class="img-fluid rounded" alt="{{ $empresa->nombre }}">
                            @else
                                <div class="bg-light p-5 rounded text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td>{{ $empresa->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>RFC:</strong></td>
                                        <td>{{ $empresa->rfc }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Correo:</strong></td>
                                        <td>{{ $empresa->correo }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono:</strong></td>
                                        <td>{{ $empresa->telefono ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dirección:</strong></td>
                                        <td>{{ $empresa->direccion ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ciudad:</strong></td>
                                        <td>{{ $empresa->ciudad ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>{{ $empresa->estado ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Código Postal:</strong></td>
                                        <td>{{ $empresa->codigo_postal ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descripción:</strong></td>
                                        <td>{{ $empresa->descripcion ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
