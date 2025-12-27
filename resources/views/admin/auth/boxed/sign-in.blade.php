@extends('layouts.app')

@section('styles')
{{-- Style Here --}}
@vite(['resources/scss/light/assets/authentication/auth-boxed.scss'])
@vite(['resources/scss/dark/assets/authentication/auth-boxed.scss'])
@endsection

@section('content')
{{-- Content Here --}}
<div class="auth-container d-flex">

    <div class="container mx-auto align-self-center">

        <div class="row">

            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                <div class="card mt-3 mb-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h2>Login - AquaIncuba</h2>
                                <p>Ingresa tu correo y contraseña para acceder al sistema</p>
                            </div>

                            @if ($errors->any())
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <strong>Error:</strong>
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.post') }}" class="col-12">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" 
                                           value="{{ old('correo') }}" required>
                                    @error('correo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Contraseña</label>
                                        <input type="password" name="contraseña" class="form-control @error('contraseña') is-invalid @enderror" required>
                                        @error('contraseña')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" name="remember" id="form-check-default">
                                            <label class="form-check-label" for="form-check-default">
                                                Recuérdame
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-secondary w-100">INGRESAR</button>
                                    </div>
                                </div>
                            </form>

                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <p class="mb-0 text-muted small">
                                        Sistema de Gestión de Incubadoras - AquaIncuba UNIA
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>

</div>
@endsection

@section('scripts')
{{-- Scripts Here --}}
@endsection