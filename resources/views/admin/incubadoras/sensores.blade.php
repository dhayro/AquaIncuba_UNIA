@extends('layouts.app')

@section('styles')
@vite(['resources/scss/light/assets/forms/switches.scss'])
@vite(['resources/scss/dark/assets/forms/switches.scss'])
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Asignar Sensores - {{ $incubadora->nombre }}</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('incubadoras.sensores.save', $incubadora->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="list-group">
                            @forelse($sensores as $sensor)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sensores[]" value="{{ $sensor->id }}" 
                                           id="sensor_{{ $sensor->id }}" {{ in_array($sensor->id, $sensoresAsignados) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sensor_{{ $sensor->id }}">
                                        <strong>{{ $sensor->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Código: <code>{{ $sensor->codigo }}</code> | 
                                            Tipo: {{ ucfirst(str_replace('_', ' ', $sensor->tipo)) }} | 
                                            Unidad: {{ $sensor->unidad_medida }}
                                        </small>
                                    </label>
                                </div>
                            @empty
                                <div class="alert alert-info">No hay sensores disponibles</div>
                            @endforelse
                        </div>

                        <div class="text-end mt-3">
                            <a href="{{ route('incubadoras.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Sensores</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
