@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Configuración MQTT</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('mqtt.edit') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-edit"></i> Editar
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if ($configuracion)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nombre:</strong></label>
                                <p>{{ $configuracion->nombre }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Estado:</strong></label>
                                @if ($configuracion->activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-danger">Inactiva</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Host:</strong></label>
                                <p><code>{{ $configuracion->host }}</code></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Puerto:</strong></label>
                                <p><code>{{ $configuracion->puerto }}</code></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Usuario:</strong></label>
                                <p>{{ $configuracion->usuario ?: 'No configurado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Última actualización:</strong></label>
                                <p>{{ $configuracion->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <form action="{{ route('mqtt.test') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-info">
                                <i class="bx bx-wifi"></i> Probar Conexión
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <p>No hay configuración MQTT. <a href="{{ route('mqtt.edit') }}">Crear configuración</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Información</h5>
                    <p class="small">
                        La configuración MQTT permite conectar el sistema a un broker MQTT para recibir datos de sensores 
                        en tiempo real.
                    </p>
                    <hr/>
                    <p class="small mb-0">
                        <strong>Puerto por defecto:</strong> 1883<br/>
                        <strong>Puerto seguro (TLS):</strong> 8883
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
