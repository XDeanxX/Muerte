<!DOCTYPE html>
<html>

<head>
    <title>Detalles de la Solicitud - {{ $solicitud->solicitud_id }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* CSS INCRUSTADO PARA EL PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .logo-container {
            float: left;/
            width: 200px;
        }

        .info-container {
            float: right;
            text-align: right;/
            font-size: 14px;
            margin-top: 10px;
        }
        
        .info-container p {
            margin: 0;
            line-height: 1.4;
            color: #838383;
        }

        .header {
            text-align: center;
            padding: 15px;
            background-color: #f3f4f6;
            /* gray-100 */
            border-bottom: 2px solid #3b82f6;
            /* blue-500 */
            height: 100px;
        }

        .content {
            padding: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #e5e7eb;
            /* gray-200 */
            color: #1f2937;
            /* gray-800 */
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            color: #6b7280;
            /* gray-500 */
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="header-container">
            <div class="logo-container">
                <img src="{{ public_path('img/logotipo.png') }}" alt="Logo de mi Aplicación" style="width: 200px;">
            </div>

            <div class="info-container">
                <p>Reporte Generado el: {{now()->format('d/m/Y')}}</p>
                <p>Por: {{ Auth::user()->persona->nombre}} {{Auth::user()->persona->apellido}}</p>
                <p>{{Auth::user()->persona->nacionalidad === 1 ? 'V' : 'E'}}-{{Auth::user()->persona->cedula}}</p>
            </div>
        </div>
    </div>

    <div class="content bg-blue-600">
        <h2>Información</h2>

        <table class="data-table">
            <tr>
                <th>N° de Ticket</th>
                <td>{{ $solicitud->solicitud_id }}</td>
            </tr>
            <tr>
                <th>Estatus</th>
                <td>{{ $solicitud->getEstatusFormattedAttribute() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Título</th>
                <td>{{ $solicitud->titulo ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td>{{ $solicitud->descripcion ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Ubicación</th>
                <td>{{ $solicitud->comunidadRelacion->getParroquiaFormattedAttribute() ?? 'N/A' }} - {{ $solicitud->comunidadRelacion->getComunidadFormattedAttribute() ?? 'N/A' }}  - {{ $solicitud->direccion_detallada ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Categoría</th>
                <td>{{ $solicitud->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'N/A' }} - {{ $solicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tipo de Solicitud</th>
                <td>{{ $solicitud->getTipoSolicitudFormattedAttribute() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Solicitante</th>
                <td>{{ $solicitud->persona->nombre ?? 'N/A' }} {{ $solicitud->persona->apellido ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Cédula</th>
                <td>{{ $solicitud->persona->nacionalidad ?? 'N/A' }}-{{ $solicitud->persona->cedula ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Fecha Solicitada</th>
                <td>{{ $solicitud->fecha_creacion->format('d/m/Y H:i') ?? 'N/A' }}</td>
            </tr>
        </table>

    </div>
    <p style="color:#3333335b; padding-top: 320px; text-align: center;">Reporte generado por el sistema de gestión de solicitudes del CMBEY</p>
</body>

</html>