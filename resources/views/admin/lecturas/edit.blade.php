@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-0">Editar Lectura</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('lecturas.update', $lectura) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Sensor</label>
                        <div class="form-control-plaintext">
                            <strong>{{ $lectura->sensor->nombre }}</strong>
                            <small class="d-block text-muted">({{ $lectura->sensor->tipo }})</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Incubadora</label>
                        <div class="form-control-plaintext">
                            <strong>{{ $lectura->incubadora->nombre }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="valor">Valor <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('valor') is-invalid @enderror" 
                            id="valor" name="valor" step="0.01" required value="{{ old('valor', $lectura->valor) }}">
                        @error('valor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="timestamp">Timestamp</label>
                        <input type="datetime-local" class="form-control @error('timestamp') is-invalid @enderror" 
                            id="timestamp" name="timestamp" 
                            value="{{ old('timestamp', $lectura->timestamp ? $lectura->timestamp->format('Y-m-d\TH:i') : '') }}">
                        @error('timestamp')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('lecturas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
