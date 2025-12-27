@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Registrar Nueva Lectura</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('lecturas.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="id_sensor">Sensor <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_sensor') is-invalid @enderror" 
                            id="id_sensor" name="id_sensor" required>
                            <option value="">-- Seleccionar Sensor --</option>
                            @foreach ($sensores as $sensor)
                                <option value="{{ $sensor->id }}" {{ old('id_sensor') == $sensor->id ? 'selected' : '' }}>
                                    {{ $sensor->nombre }} ({{ $sensor->tipo }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_sensor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="id_incubadora">Incubadora <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_incubadora') is-invalid @enderror" 
                            id="id_incubadora" name="id_incubadora" required>
                            <option value="">-- Seleccionar Incubadora --</option>
                            @foreach ($incubadoras as $incubadora)
                                <option value="{{ $incubadora->id }}" {{ old('id_incubadora') == $incubadora->id ? 'selected' : '' }}>
                                    {{ $incubadora->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_incubadora')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="valor">Valor <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('valor') is-invalid @enderror" 
                            id="valor" name="valor" step="0.01" required value="{{ old('valor') }}">
                        @error('valor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="timestamp">Timestamp</label>
                        <input type="datetime-local" class="form-control @error('timestamp') is-invalid @enderror" 
                            id="timestamp" name="timestamp" value="{{ old('timestamp') }}">
                        @error('timestamp')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Lectura</button>
                        <a href="{{ route('lecturas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
