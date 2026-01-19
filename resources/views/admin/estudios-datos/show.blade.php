@extends('layouts.app')

@section('styles')
@vite(['resources/scss/light/assets/components/modal.scss'])
@vite(['resources/scss/dark/assets/components/modal.scss'])

<style>
    /* Estilos mejorados con colores más limpios */
    body {
        background-color: #f8f9fa;
    }

    .page-wrapper {
        background-color: #f8f9fa;
    }

    .sensor-card {
        height: 100%;
        min-height: 480px;
        display: flex;
        flex-direction: column;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .sensor-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border-color: #667eea;
    }

    .sensor-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        padding: 1.25rem;
    }

    /* Grid responsivo */
    .sensor-grid {
        display: grid;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Desktop: 3 columnas */
    @media (min-width: 1400px) {
        .sensor-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Tablet grande: 2 columnas */
    @media (min-width: 992px) and (max-width: 1399px) {
        .sensor-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Tablet: 2 columnas */
    @media (min-width: 768px) and (max-width: 991px) {
        .sensor-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Mobile: 1 columna */
    @media (max-width: 767px) {
        .sensor-grid {
            grid-template-columns: 1fr;
        }
        
        .sensor-card {
            min-height: auto;
        }
    }

    .card-header {
        border-radius: 0;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .card-header h6 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .card-header small {
        display: block;
        font-size: 0.8rem;
        opacity: 0.85;
        margin-top: 0.35rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .badge:hover {
        opacity: 0.85;
        transform: scale(1.08);
    }

    /* Modal mejorado */
    .modal-header {
        background: #3498db;
        color: white;
        border-radius: 0.5rem 0.5rem 0 0;
        border: none;
        padding: 1.25rem;
    }

    .modal-body {
        overflow-y: auto;
        max-height: calc(100vh - 200px);
        background-color: #f8f9fa;
    }

    .tab-content {
        border: 1px solid #e0e0e0;
        border-top: none;
        border-radius: 0 0 0.5rem 0.5rem;
        padding: 1.25rem;
        background-color: white;
    }

    .nav-tabs {
        border-bottom: 2px solid #e0e0e0;
        background-color: #f8f9fa;
        border-radius: 0 0 0 0;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #666;
        transition: all 0.3s;
        padding: 0.85rem 1.25rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #3498db;
        color: #3498db;
        background-color: white;
    }

    .nav-tabs .nav-link.active {
        background-color: white;
        border-bottom-color: #3498db;
        color: #3498db;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table {
        background-color: white;
    }

    .table-light {
        background-color: #f8f9fa;
    }

    .incubadora-section {
        margin-bottom: 2.5rem;
    }

    .incubadora-header {
        background: #2c3e50;
        color: white;
        padding: 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .incubadora-header h4 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: white;
    }

    /* Headers de sensores - AZUL UNIFORME */
    .card-header {
        border-radius: 0;
        padding: 1rem;
        background: #3498db;
        color: white;
        border: none;
    }

    .card-header h6 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .card-header small {
        display: block;
        font-size: 0.8rem;
        opacity: 0.85;
        margin-top: 0.35rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .stat-label {
        font-size: 0.85rem;
        opacity: 0.85;
        margin-bottom: 0.5rem;
        letter-spacing: 0.2px;
    }

    /* Ultima lectura - BLANCO */
    .ultima-lectura-box {
        background: white;
        color: #333;
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        margin: -1.25rem -1.25rem 0.75rem -1.25rem;
        border: 1px solid #e0e0e0;
        border-bottom: 3px solid #3498db;
    }

    .ultima-lectura-box .valor-grande {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        color: #3498db;
    }

    .ultima-lectura-box .label {
        font-size: 0.8rem;
        opacity: 0.85;
        color: #666;
    }

    /* Progress bar - AZUL */
    .progress {
        background-color: #ecf0f1;
        height: 24px;
        border-radius: 0.5rem;
    }

    .progress-bar {
        background: #3498db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.5rem;
    }

    /* Canvas responsivo */
    canvas {
        max-height: 300px;
    }

    .page-title {
        margin-bottom: 0;
        color: #333;
    }

    .page-header {
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 1.5rem 0;
    }

    .page-body {
        padding: 2rem 0;
    }

    /* Badges mejorados */
    .badge.bg-secondary {
        background-color: #6c757d !important;
        color: white;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
        color: white;
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #333;
    }

    /* Container padding */
    .container-xl {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    /* Text colors */
    .text-muted {
        color: #999 !important;
    }

    /* Fondos suaves */
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center mb-3">
                <div class="col">
                    <a href="{{ route('estudios-datos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark font-monospace fs-6">
                        {{ $estudio->codigo_estudio }}
                    </span>
                </div>
            </div>

            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <i class="fas fa-flask"></i> {{ $estudio->nombre }}
                    </h2>
                </div>
                <div class="col-auto">
                    @switch($estudio->estado)
                        @case('en_progreso')
                            <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> En Progreso</span>
                            @break
                        @case('finalizado')
                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Finalizado</span>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Información del Estudio - BLANCO Y LIMPIO -->
            <div class="row mb-4 g-3">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="stat-box">
                        <div class="stat-label">📅 Fecha Inicio</div>
                        <div class="stat-value" style="color: #667eea;">{{ $estudio->fecha_inicio->format('d/m') }}</div>
                        <small style="color: #999;">{{ $estudio->fecha_inicio->format('Y') }}</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="stat-box">
                        <div class="stat-label">📅 Fecha Fin</div>
                        <div class="stat-value" style="color: #764ba2;">{{ $estudio->fecha_fin ? $estudio->fecha_fin->format('d/m') : 'N/A' }}</div>
                        <small style="color: #999;">{{ $estudio->fecha_fin ? $estudio->fecha_fin->format('Y') : '' }}</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="stat-box">
                        <div class="stat-label">🔲 Incubadoras</div>
                        <div class="stat-value" style="color: #667eea;">{{ $estudio->incubadoras->count() }}</div>
                        <small style="color: #999;">Activas</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="stat-box">
                        <div class="stat-label">⚙️ Estado</div>
                        <div class="stat-value" style="font-size: 1.3rem; color: #764ba2;">{{ ucfirst(str_replace('_', ' ', $estudio->estado)) }}</div>
                    </div>
                </div>
            </div>

            <!-- Datos por Incubadora -->
            @forelse($incubadorasData as $data)
                <div class="incubadora-section">
                    <div class="incubadora-header">
                        <h4>
                            <i class="fas fa-cube"></i> {{ $data['incubadora']->nombre }}
                        </h4>
                    </div>

                    <!-- Grid de Sensores -->
                    <div class="sensor-grid">
                        @foreach($data['sensores'] as $sensor)
                            <div class="sensor-card">
                                <div class="card-header">
                                    <h6>
                                        <i class="fas fa-temperature-high"></i> {{ $sensor['nombre'] }}
                                    </h6>
                                    <small>{{ $sensor['parametro'] }} ({{ $sensor['unidad'] }})</small>
                                </div>

                                <div class="sensor-content">
                                    <!-- Último Valor Grande -->
                                    <div class="ultima-lectura-box">
                                        <div class="label">Última Lectura</div>
                                        @if($sensor['ultima_lectura_valor'] !== null)
                                            <div class="valor-grande">{{ number_format($sensor['ultima_lectura_valor'], 2) }}</div>
                                            <small style="opacity: 0.85;">{{ $sensor['ultima_lectura'] ? $sensor['ultima_lectura']->format('H:i:s') : 'N/A' }}</small>
                                        @else
                                            <div class="valor-grande" style="font-size: 1.5rem;">SIN DATO</div>
                                        @endif
                                    </div>

                                    <!-- Estadísticas en grid 2x2 - BLANCOS -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="stat-box light">
                                                <small class="stat-label">Mínimo</small>
                                                <div class="stat-value" style="font-size: 1.2rem; color: #0d6efd;">
                                                    @if($sensor['valor_minimo'] !== null)
                                                        {{ number_format($sensor['valor_minimo'], 2) }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box light">
                                                <small class="stat-label">Máximo</small>
                                                <div class="stat-value" style="font-size: 1.2rem; color: #dc3545;">
                                                    @if($sensor['valor_maximo'] !== null)
                                                        {{ number_format($sensor['valor_maximo'], 2) }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Promedio -->
                                    <div class="stat-box light mb-3">
                                        <small class="stat-label">Promedio</small>
                                        <div class="stat-value" style="font-size: 1.2rem; color: #198754;">
                                            @if($sensor['valor_promedio'] !== null)
                                                {{ number_format($sensor['valor_promedio'], 2) }}
                                            @else
                                                —
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Total de Lecturas - CLICKEABLE -->
                                    <div class="text-center mb-3" style="cursor: pointer;" 
                                         data-bs-toggle="modal" 
                                         data-bs-target="#modal-{{ $data['incubadora']->id }}-{{ $loop->index }}"
                                         title="Click para ver datos completos">
                                        <span class="badge bg-secondary me-2" style="cursor: pointer;">
                                            <i class="fas fa-database"></i> {{ $sensor['total_lecturas'] }}
                                        </span>
                                        <span class="badge bg-success" style="cursor: pointer;">
                                            <i class="fas fa-check"></i> {{ $sensor['lecturas_validas'] }}
                                        </span>
                                    </div>

                                    <!-- Mini Gráfico -->
                                    <div class="mt-auto pt-2">
                                        <canvas id="chart-{{ $data['incubadora']->id }}-{{ $loop->index }}" height="80"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL para datos completos -->
                            <div class="modal fade" id="modal-{{ $data['incubadora']->id }}-{{ $loop->index }}" tabindex="-1">
                                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div>
                                                <h5 class="modal-title mb-0">
                                                    <i class="fas fa-chart-bar"></i> {{ $sensor['nombre'] }} - {{ $sensor['parametro'] }}
                                                </h5>
                                                <small style="color: white; opacity: 0.85;">{{ $data['incubadora']->nombre }}</small>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Tabs para ver tabla o gráfico -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-tabla-{{ $data['incubadora']->id }}-{{ $loop->index }}" role="tab">
                                                        <i class="fas fa-table"></i> Tabla Completa ({{ $sensor['total_lecturas'] }} registros)
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#tab-grafico-{{ $data['incubadora']->id }}-{{ $loop->index }}" role="tab">
                                                        <i class="fas fa-chart-line"></i> Gráfico de Progreso
                                                    </a>
                                                </li>
                                            </ul>

                                            <!-- Contenido de tabs -->
                                            <div class="tab-content mt-0">
                                                <!-- TAB TABLA -->
                                                <div class="tab-pane fade show active" id="tab-tabla-{{ $data['incubadora']->id }}-{{ $loop->index }}">
                                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                                        <table class="table table-sm table-hover">
                                                            <thead class="table-light sticky-top">
                                                                <tr>
                                                                    <th style="width: 20%;">Fecha</th>
                                                                    <th style="width: 15%;">Hora</th>
                                                                    <th style="width: 15%;">Valor</th>
                                                                    <th style="width: 50%;">Progreso</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody-{{ $data['incubadora']->id }}-{{ $loop->index }}">
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted">
                                                                        <i class="fas fa-spinner fa-spin"></i> Cargando datos...
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!-- TAB GRÁFICO -->
                                                <div class="tab-pane fade" id="tab-grafico-{{ $data['incubadora']->id }}-{{ $loop->index }}">
                                                    <div style="position: relative; height: 400px; width: 100%;">
                                                        <canvas id="full-chart-{{ $data['incubadora']->id }}-{{ $loop->index }}"></canvas>
                                                    </div>
                                                    <div class="mt-3 p-3 bg-light rounded">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i>
                                                            El gráfico muestra la evolución temporal de los valores. 
                                                            Pasa el cursor sobre los puntos para ver detalles.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> No hay datos disponibles para este estudio
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Datos para los gráficos mini
    const chartsData = {!! json_encode($incubadorasData) !!};
    const estudioId = {{ $estudio->id }};

    chartsData.forEach((data, incIdx) => {
        data.sensores.forEach((sensor, sensorIdx) => {
            // Mini gráfico en la tarjeta
            const chartId = `chart-${data.incubadora.id}-${sensorIdx}`;
            const ctx = document.getElementById(chartId);
            
            if (ctx) {
                const minVal = sensor.valor_minimo || 0;
                const maxVal = sensor.valor_maximo || 10;
                const promVal = sensor.valor_promedio || 5;
                const ultimaVal = sensor.ultima_lectura_valor || 0;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mín', 'Prom', 'Máx', 'Últ'],
                        datasets: [{
                            label: sensor.parametro,
                            data: [minVal, promVal, maxVal, ultimaVal],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#667eea',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Event listener para modal - cargar datos cuando se abre
            const modalId = `modal-${data.incubadora.id}-${sensorIdx}`;
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.addEventListener('show.bs.modal', function() {
                    cargarDatosCompletos(data.incubadora.id, sensorIdx, sensor.nombre, sensor.parametro);
                });
            }
        });
    });

    // Función para cargar datos históricos completos
    function cargarDatosCompletos(incubadoraId, sensorIdx, sensorNombre, parametro) {
        const tbodyId = `tbody-${incubadoraId}-${sensorIdx}`;
        const tbody = document.getElementById(tbodyId);
        
        // Mostrar carga
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>';

        // Codificar el nombre del sensor correctamente (incluyendo +, espacios, etc)
        const sensorEncoded = encodeURIComponent(sensorNombre);
        const parametroEncoded = parametro ? encodeURIComponent(parametro) : '';

        // Llamar a la API para obtener datos históricos del parámetro específico
        const url = parametroEncoded 
            ? `/api/estudios/${estudioId}/sensor-datos/${incubadoraId}/${sensorEncoded}/${parametroEncoded}`
            : `/api/estudios/${estudioId}/sensor-datos/${incubadoraId}/${sensorEncoded}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.lecturas && data.lecturas.length > 0) {
                    // Llenar tabla
                    let html = '';
                    let valoresValidos = data.lecturas.map(l => l.valor).filter(v => v !== null);
                    let minVal = valoresValidos.length > 0 ? Math.min(...valoresValidos) : 0;
                    let maxVal = valoresValidos.length > 0 ? Math.max(...valoresValidos) : 100;

                    data.lecturas.forEach((lectura, idx) => {
                        const fecha = new Date(lectura.created_at);
                        const fechaFormato = fecha.toLocaleDateString('es-PE', { timeZone: 'America/Lima' });
                        const horaFormato = fecha.toLocaleTimeString('es-PE', { timeZone: 'America/Lima' });
                        
                        const valor = lectura.valor !== null ? parseFloat(lectura.valor).toFixed(2) : 'SIN DATO';
                        
                        // Calcular porcentaje de progreso (0-100%)
                        let porcentaje = 0;
                        if (lectura.valor !== null && minVal !== maxVal) {
                            porcentaje = Math.max(0, Math.min(100, ((lectura.valor - minVal) / (maxVal - minVal)) * 100));
                        } else if (lectura.valor !== null) {
                            porcentaje = 50; // Si min == max, mostrar 50%
                        }

                        const rowClass = lectura.valor === null ? 'table-danger' : '';

                        html += `
                            <tr class="${rowClass}">
                                <td><small>${fechaFormato}</small></td>
                                <td><small>${horaFormato}</small></td>
                                <td><strong>${valor}</strong></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: ${porcentaje}%; background-color: #3498db;" 
                                             aria-valuenow="${porcentaje}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            ${Math.round(porcentaje)}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                    tbody.innerHTML = html;

                    // Crear gráfico de progreso con el nombre del parámetro
                    crearGraficoCompleto(incubadoraId, sensorIdx, data.lecturas, sensorNombre, data.parametro);
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay datos disponibles</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar datos</td></tr>';
            });
    }

    // Función para crear gráfico completo
    function crearGraficoCompleto(incubadoraId, sensorIdx, lecturas, sensorNombre, nombreParametro) {
        const chartId = `full-chart-${incubadoraId}-${sensorIdx}`;
        const ctx = document.getElementById(chartId);
        
        if (!ctx) return;

        // Preparar datos para gráfico
        const labels = lecturas.map(l => {
            const fecha = new Date(l.created_at);
            return fecha.toLocaleTimeString('es-PE', { 
                timeZone: 'America/Lima',
                hour: '2-digit',
                minute: '2-digit'
            });
        });

        const datos = lecturas.map(l => l.valor !== null ? parseFloat(l.valor).toFixed(2) : null);

        // Destruir gráfico anterior si existe
        if (window[`chart_${chartId}`]) {
            window[`chart_${chartId}`].destroy();
        }

        // Crear nuevo gráfico
        const ctx_element = ctx.getContext('2d');
        window[`chart_${chartId}`] = new Chart(ctx_element, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: nombreParametro || sensorNombre,
                    data: datos,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#667eea',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    spanGaps: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return `Hora: ${context[0].label}`;
                            },
                            label: function(context) {
                                return `Valor: ${context.parsed.y !== null ? context.parsed.y : 'SIN DATO'}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        title: {
                            display: true,
                            text: 'Valor'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        title: {
                            display: true,
                            text: 'Tiempo'
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
