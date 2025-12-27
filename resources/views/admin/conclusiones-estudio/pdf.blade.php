<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conclusión Estudio - {{ $conclusionEstudio->estudio->nombre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .study-info {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .study-info h3 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .quality-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
            font-size: 13px;
        }

        .quality-badge.excelente {
            background-color: #28a745;
        }

        .quality-badge.buena {
            background-color: #17a2b8;
        }

        .quality-badge.aceptable {
            background-color: #ffc107;
            color: #333;
        }

        .quality-badge.pobre {
            background-color: #dc3545;
        }

        .quality-badge.muy_pobre {
            background-color: #721c24;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background: #007bff;
            color: white;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 15px;
            font-weight: bold;
        }

        .section-content {
            padding: 0 0 0 0;
            text-align: justify;
            font-size: 13px;
            line-height: 1.8;
        }

        .conclusion-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .conclusion-table th {
            background: #e9ecef;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
            font-weight: bold;
        }

        .conclusion-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }

        .conclusion-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .conformance {
            text-align: center;
            font-weight: bold;
        }

        .conformance.yes {
            color: #28a745;
        }

        .conformance.no {
            color: #dc3545;
        }

        .aptitud {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            border-radius: 4px;
        }

        .aptitud.apto {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .aptitud.no-apto {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
            font-size: 12px;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CONCLUSIÓN DEL ESTUDIO</h1>
            <p>Análisis de Calidad del Agua</p>
        </div>

        <div class="study-info">
            <h3>INFORMACIÓN DEL ESTUDIO</h3>
            <div class="info-row">
                <span class="info-label">Estudio:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Incubadora:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->incubadora->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Responsable:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->usuario->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fecha de Emisión:</span>
                <span class="info-value">{{ $conclusionEstudio->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="aptitud {{ $conclusionEstudio->apto_consumo ? 'apto' : 'no-apto' }}">
            {{ $conclusionEstudio->apto_consumo ? '✓ AGUA APTA PARA CONSUMO' : '✗ AGUA NO APTA PARA CONSUMO' }}
        </div>

        <div class="section">
            <div class="section-title">CALIDAD GENERAL DEL AGUA</div>
            <div style="text-align: center; padding: 15px;">
                <span class="quality-badge {{ $conclusionEstudio->calidad_agua }}">
                    {{ ucfirst(str_replace('_', ' ', $conclusionEstudio->calidad_agua)) }}
                </span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">RESUMEN DE CONCLUSIONES</div>
            <div class="section-content">
                {{ $conclusionEstudio->resumen }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">HALLAZGOS PRINCIPALES</div>
            <div class="section-content">
                {{ $conclusionEstudio->hallazgos }}
            </div>
        </div>

        @if ($conclusionEstudio->recomendaciones)
            <div class="section">
                <div class="section-title">RECOMENDACIONES</div>
                <div class="section-content">
                    {{ $conclusionEstudio->recomendaciones }}
                </div>
            </div>
        @endif

        @if ($conclusionEstudio->parametros->count())
            <div class="section">
                <div class="section-title">PARÁMETROS EVALUADOS</div>
                <table class="conclusion-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Parámetro</th>
                            <th style="width: 30%;">Valor Medido</th>
                            <th style="width: 30%;">Conformidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($conclusionEstudio->parametros as $parametro)
                            <tr>
                                <td>{{ $parametro->nombre }}</td>
                                <td>{{ $parametro->valor }} {{ $parametro->unidad }}</td>
                                <td class="conformance {{ $parametro->cumple_normativa ? 'yes' : 'no' }}">
                                    {{ $parametro->cumple_normativa ? '✓ Conforme' : '✗ No Conforme' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($conclusionEstudio->observaciones)
            <div class="section">
                <div class="section-title">OBSERVACIONES ADICIONALES</div>
                <div class="section-content">
                    {{ $conclusionEstudio->observaciones }}
                </div>
            </div>
        @endif

        <div class="section">
            <div class="section-title">DATOS DEL ANÁLISIS</div>
            <div class="info-row" style="padding: 0 10px;">
                <span class="info-label">Datos crudos registrados:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->datosCrudos->count() }}</span>
            </div>
            <div class="info-row" style="padding: 0 10px;">
                <span class="info-label">Datos procesados:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->datosProcessados->count() }}</span>
            </div>
            <div class="info-row" style="padding: 0 10px;">
                <span class="info-label">Muestras recolectadas:</span>
                <span class="info-value">{{ $conclusionEstudio->estudio->muestras->count() }}</span>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-line">
                <div>{{ $conclusionEstudio->firmado_por ?? 'Responsable' }}</div>
                <div style="font-size: 10px; margin-top: 5px;">Firma Responsable</div>
            </div>
            <div class="signature-line">
                <div style="font-size: 10px;">{{ now()->format('d/m/Y') }}</div>
                <div style="font-size: 10px;">Fecha</div>
            </div>
        </div>

        <div class="footer">
            <p>Este documento es una conclusión oficial generada por el sistema AquaIncuba UNIA.</p>
            <p>Para más información contacte a: {{ $conclusionEstudio->estudio->empresa->nombre }}</p>
        </div>
    </div>
</body>
</html>
