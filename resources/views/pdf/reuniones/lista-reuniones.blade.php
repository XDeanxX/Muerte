<!DOCTYPE html>
<html>
<head>
    <title>Listado de Reuniones - Sistema Municipal CMBEY</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .header {
            text-align: center;
            padding: 15px;
            background-color: #f3f4f6;
            border-bottom: 3px solid #3b82f6;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 20px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 12px;
        }

        .content {
            padding: 20px;
        }

        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 10px;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }

        .data-table th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: bold;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-finalizada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            padding: 10px 0;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Listado de Reuniones</h1>
        <p>Sistema Municipal CMBEY</p>
        <p style="font-size: 10px; margin-top: 5px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="content">
        <div class="info-box">
            <strong>Total de Reuniones:</strong> {{ $reuniones->count() }}
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Título</th>
                    <th style="width: 10%;">Fecha</th>
                    <th style="width: 8%;">Hora</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 10%;">Estatus</th>
                    <th style="width: 8%;">Solicitudes</th>
                    <th style="width: 8%;">Concejales</th>
                    <th style="width: 8%;">Instituciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reuniones as $reunion)
                <tr>
                    <td>{{ $reunion->id }}</td>
                    <td>{{ $reunion->titulo }}</td>
                    <td>{{ $reunion->fecha_reunion->format('d/m/Y') }}</td>
                    <td>{{ $reunion->hora_reunion }}</td>
                    <td>{{ optional($reunion->tipoReunionRelacion)->titulo ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge {{ str_contains(strtolower(optional($reunion->estatusRelacion)->estatus ?? ''), 'finalizada') ? 'status-finalizada' : 'status-pendiente' }}">
                            {{ ucfirst(optional($reunion->estatusRelacion)->estatus ?? 'N/A') }}
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $reunion->solicitudes->count() }}</td>
                    <td style="text-align: center;">{{ $reunion->concejales->count() }}</td>
                    <td style="text-align: center;">{{ $reunion->instituciones->count() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #6b7280;">No hay reuniones registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($reuniones->isNotEmpty())
        <div style="margin-top: 30px; padding: 15px; background-color: #f9fafb; border-radius: 8px;">
            <h3 style="margin-top: 0; color: #1f2937; font-size: 12px;">Resumen Estadístico</h3>
            <table style="width: 100%; font-size: 10px;">
                <tr>
                    <td style="padding: 5px;"><strong>Total de Reuniones:</strong></td>
                    <td style="padding: 5px;">{{ $reuniones->count() }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><strong>Total de Solicitudes Vinculadas:</strong></td>
                    <td style="padding: 5px;">{{ $reuniones->sum(function($r) { return $r->solicitudes->count(); }) }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><strong>Total de Concejales Participantes:</strong></td>
                    <td style="padding: 5px;">{{ $reuniones->sum(function($r) { return $r->concejales->count(); }) }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><strong>Total de Instituciones Involucradas:</strong></td>
                    <td style="padding: 5px;">{{ $reuniones->sum(function($r) { return $r->instituciones->count(); }) }}</td>
                </tr>
            </table>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema Municipal CMBEY - Gestión de Reuniones</p>
        <p class="page-number"></p>
    </div>
</body>
</html>
