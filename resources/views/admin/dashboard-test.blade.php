@extends('layouts.app')

@section('content')
<div class="row layout-top-spacing">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h1>Dashboard de AquaIncuba</h1>
                <p>Este es un test para verificar que la interfaz funciona correctamente.</p>
                <hr>
                <h3>Estadísticas</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Total Incubadoras</h5>
                                <h2>{{ $totalIncubadoras }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Total Sensores</h5>
                                <h2>{{ $totalSensores }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Total Estudios</h5>
                                <h2>{{ $totalEstudios }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Total Usuarios</h5>
                                <h2>{{ $totalUsuarios }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
