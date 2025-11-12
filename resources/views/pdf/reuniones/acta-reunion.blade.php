<!DOCTYPE html>
<html>
<head>
    <title>Acta de Reunión - {{ $reunion->titulo }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #f3f4f6;
            border-bottom: 3px solid #3b82f6;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 22px;
        }

        .header p {
            margin: 3px 0;
            color: #6b7280;
            font-size: 12px;
        }

        .content {
            padding: 30px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #eff6ff;
            color: #1e40af;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            border-left: 4px solid #3b82f6;
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px;
            width: 30%;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .info-value {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e5e7eb;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: bold;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .check-yes {
            color: #059669;
            font-weight: bold;
        }

        .check-no {
            color: #dc2626;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .status-aprobada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .resolution-box {
            background-color: #fefce8;
            border: 2px solid #facc15;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .resolution-box h4 {
            margin: 0 0 10px 0;
            color: #713f12;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 2px solid #000;
            width: 60%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ACTA DE REUNIÓN</h1>
        <p style="font-size: 16px; font-weight: bold; margin-top: 10px;">{{ $reunion->titulo }}</p>
        <p>Sistema Municipal CMBEY</p>
        <p style="margin-top: 10px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="content">
        <!-- Información General -->
        <div class="section">
            <div class="section-title">INFORMACIÓN GENERAL DE LA REUNIÓN</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Título:</div>
                    <div class="info-value">{{ $reunion->titulo }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo de Reunión:</div>
                    <div class="info-value">{{ optional($reunion->tipoReunionRelacion)->titulo ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha Programada:</div>
                    <div class="info-value">{{ $reunion->fecha_reunion->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Hora Programada:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Duración Estimada:</div>
                    <div class="info-value">{{ $reunion->duracion_reunion ?? 'No especificada' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Duración Real:</div>
                    <div class="info-value"><strong>{{ $reunion->duracion_real ?? 'No registrada' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Hora de Finalización Real:</div>
                    <div class="info-value"><strong>{{ $reunion->hora_finalizacion_real ? \Carbon\Carbon::parse($reunion->hora_finalizacion_real)->format('g:i a') : 'No registrada' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Estatus:</div>
                    <div class="info-value">{{ ucfirst(optional($reunion->estatusRelacion)->estatus ?? 'N/A') }}</div>
                </div>
            </div>
            
            @if($reunion->descripcion)
            <div style="margin-top: 15px;">
                <strong>Descripción:</strong>
                <p style="margin: 5px 0; padding: 10px; background-color: #f9fafb; border-left: 3px solid #3b82f6;">
                    {{ $reunion->descripcion }}
                </p>
            </div>
            @endif
        </div>

        <!-- Asistencia de Solicitantes -->
        @if($reunion->solicitudes->isNotEmpty())
        <div class="section">
            <div class="section-title">ASISTENCIA DE SOLICITANTES</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Nombre</th>
                        <th style="width: 40%;">Solicitud</th>
                        <th style="width: 20%; text-align: center;">Asistió</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reunion->solicitudes as $solicitud)
                    <tr>
                        <td>{{ optional($solicitud->persona)->nombre ?? 'N/A' }} {{ optional($solicitud->persona)->apellido ?? '' }}</td>
                        <td>{{ $solicitud->titulo }}</td>
                        <td style="text-align: center;">
                            @if($solicitud->pivot->asistencia_solicitante)
                                <span class="check-yes">✓ SÍ</span>
                            @else
                                <span class="check-no">✗ NO</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Asistencia de Concejales -->
        @if($reunion->concejales->isNotEmpty())
        <div class="section">
            <div class="section-title">ASISTENCIA DE CONCEJALES</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70%;">Nombre</th>
                        <th style="width: 30%; text-align: center;">Asistió</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reunion->concejales as $concejal)
                    <tr>
                        <td>{{ optional($concejal->persona)->nombre ?? 'N/A' }} {{ optional($concejal->persona)->apellido ?? '' }}</td>
                        <td style="text-align: center;">
                            @if($concejal->pivot->asistencia)
                                <span class="check-yes">✓ SÍ</span>
                            @else
                                <span class="check-no">✗ NO</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Asistencia de Instituciones -->
        @if($reunion->instituciones->isNotEmpty())
        <div class="section">
            <div class="section-title">ASISTENCIA DE INSTITUCIONES</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70%;">Institución</th>
                        <th style="width: 30%; text-align: center;">Asistió</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reunion->instituciones as $institucion)
                    <tr>
                        <td>{{ $institucion->titulo }}</td>
                        <td style="text-align: center;">
                            @if($institucion->pivot->asistencia)
                                <span class="check-yes">✓ SÍ</span>
                            @else
                                <span class="check-no">✗ NO</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Decisiones sobre Solicitudes -->
        @if($reunion->solicitudes->isNotEmpty())
        <div class="section">
            <div class="section-title">DECISIONES SOBRE SOLICITUDES</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Solicitud</th>
                        <th style="width: 25%; text-align: center;">Decisión</th>
                        <th style="width: 25%; text-align: center;">Asignada a Visitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reunion->solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->titulo }}</td>
                        <td style="text-align: center;">
                            @if($solicitud->pivot->estatus_decision)
                                <span class="status-badge status-{{ $solicitud->pivot->estatus_decision }}">
                                    {{ strtoupper($solicitud->pivot->estatus_decision) }}
                                </span>
                            @else
                                <span class="status-badge status-pendiente">PENDIENTE</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($solicitud->asignada_visita)
                                <span class="check-yes">✓ SÍ</span>
                            @else
                                <span class="check-no">✗ NO</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Resolución -->
        @if($reunion->resolución)
        <div class="section">
            <div class="section-title">RESOLUCIÓN Y CONCLUSIONES</div>
            <div class="resolution-box">
                <h4>Resolución de la Reunión:</h4>
                <p style="text-align: justify; line-height: 1.6; margin: 0;">
                    {{ $reunion->resolución }}
                </p>
            </div>
        </div>
        @endif

        <!-- Firma (si aplica) -->
        <div class="signature-section">
            <p style="text-align: center; font-size: 11px; color: #6b7280;">
                Este documento constituye el acta oficial de la reunión celebrada.
            </p>
            <div style="text-align: center; margin-top: 60px;">
                <div class="signature-line" style="margin: 0 auto;">
                    <p style="margin-top: 5px; font-weight: bold;">Firma del Responsable</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Sistema Municipal CMBEY - Acta de Reunión</p>
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
