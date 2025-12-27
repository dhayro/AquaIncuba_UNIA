@extends('layouts.app')

@section('content')

<div class="row layout-top-spacing">
    <div class="col-lg-8 mx-auto">
        <div class="statistic-box">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Crear Nueva Incubadora</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Errores:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('incubadoras.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nombre de la Incubadora</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" 
                                   value="{{ old('codigo') }}" required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Volumen (litros)</label>
                                    <input type="number" name="volumen_litros" class="form-control @error('volumen_litros') is-invalid @enderror" 
                                           value="{{ old('volumen_litros') }}" min="0.1" step="0.1">
                                    @error('volumen_litros')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Temperatura Óptima (°C)</label>
                                    <input type="number" name="temperatura_optima" class="form-control @error('temperatura_optima') is-invalid @enderror" 
                                           value="{{ old('temperatura_optima') }}" step="0.1">
                                    @error('temperatura_optima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">pH Óptimo</label>
                                    <input type="number" name="ph_optimo" class="form-control @error('ph_optimo') is-invalid @enderror" 
                                           value="{{ old('ph_optimo') }}" min="0" max="14" step="0.1">
                                    @error('ph_optimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Oxígeno Disuelto Óptimo (mg/L)</label>
                                    <input type="number" name="oxigeno_disuelto_optimo" class="form-control @error('oxigeno_disuelto_optimo') is-invalid @enderror" 
                                           value="{{ old('oxigeno_disuelto_optimo') }}" step="0.1">
                                    @error('oxigeno_disuelto_optimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('incubadoras.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Incubadora</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
