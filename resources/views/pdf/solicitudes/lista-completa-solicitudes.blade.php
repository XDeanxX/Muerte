@php
use Carbon\Carbon;
@endphp

<div style="margin-bottom: 25px;">
    <h3 style="color: #555; border-bottom: 1px solid #ccc; padding-bottom: 5px; font-size: 14px;">
        Bloque de Registros #{{ $page }}
    </h3>
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 10px;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 9%; border: 1px solid #000; padding: 5px; text-align: left;">N° Ticket</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Título</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Descripción</th>
                <th style="width: 12%; border: 1px solid #000; padding: 5px; text-align: left;">Estatus</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Categoría</th>
                <th style="width: 15%; border: 1px solid #000; padding: 5px; text-align: left;">Tipo de Solicitud</th>
                <th style="width: 24%; border: 1px solid #000; padding: 5px; text-align: left;">Ubicación</th>
                <th style="width: 13%; border: 1px solid #000; padding: 5px; text-align: left;">Solicitante</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Fecha Creada</th>
                <th style="width: 9%; border: 1px solid #000; padding: 5px; text-align: left;">Visita</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitudes as $solicitud)
                <tr>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">{{ $solicitud->solicitud_id }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ Str::title($solicitud->titulo) }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{$solicitud->descripcion}}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px;">
                        {{$solicitud->getEstatusFormattedAttribute()}}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{$solicitud->subcategoriaRelacion->getCategoriaFormattedAttribute()}}, {{$solicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute()}}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        {{ $solicitud->getTipoSolicitudFormattedAttribute() }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px;">
                        {{ $solicitud->pais }} {{ $solicitud->estado_region }}, {{ $solicitud->municipio }}, {{ Str::title($solicitud->comunidadRelacion->parroquia)}}, 
                        {{Str::title($solicitud->comunidad)}}, {{$solicitud->direccion_detallada}}.
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; overflow: hidden;">
                        {{$solicitud->persona->nombre . ' ' . $solicitud->persona->segundo_nombre . ' ' . $solicitud->persona->apellido . ' ' . $solicitud->persona->segundo_apellido}} 
                        <br>
                        {{$solicitud->persona->nacionalidadTransform() . '-' . $solicitud->persona->cedula}}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        {{ $solicitud->fecha_creacion->format('d-m-Y H:i')  }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        {{ ($solicitud->asignada_visita ? 'Asignada' : 'No Asignada')}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<p style="color:#3333335b; margin-top: 50px; text-align: center;">Reporte generado por el sistema de gestión de solicitudes del CMBEY</p>

<div style="page-break-after: always;"></div>