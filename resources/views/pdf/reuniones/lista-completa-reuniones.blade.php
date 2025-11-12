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
                <th style="width: 5%; border: 1px solid #000; padding: 5px; text-align: left;">ID</th>
                <th style="width: 12%; border: 1px solid #000; padding: 5px; text-align: left;">Título</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Descripción</th>
                <th style="width: 8%; border: 1px solid #000; padding: 5px; text-align: left;">Estatus</th>
                <th style="width: 8%; border: 1px solid #000; padding: 5px; text-align: left;">Fecha</th>
                <th style="width: 8%; border: 1px solid #000; padding: 5px; text-align: left;">Hora</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Tipo Reunión</th>
                <th style="width: 12%; border: 1px solid #000; padding: 5px; text-align: left;">Solicitudes</th>
                <th style="width: 12%; border: 1px solid #000; padding: 5px; text-align: left;">Concejales</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Instituciones</th>
                <th style="width: 5%; border: 1px solid #000; padding: 5px; text-align: left;">Resolución</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reuniones as $reunion)
                <tr>
                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ $reunion->id }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ Str::title($reunion->titulo) }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ Str::limit($reunion->descripcion ?? 'N/A', 80) }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px;">
                        {{ Str::title(optional($reunion->estatusRelacion)->estatus ?? 'N/A') }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ $reunion->fecha_reunion->format('d/m/Y') }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ Carbon::parse($reunion->hora_reunion)->format('g:i a') }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                        {{ optional($reunion->tipoReunionRelacion)->titulo ?? 'N/A' }}
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        @if($reunion->solicitudes->isNotEmpty())
                            @foreach($reunion->solicitudes as $index => $solicitud)
                                {{ $solicitud->solicitud_id }} - {{ Str::limit($solicitud->titulo, 30) }}
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        @if($reunion->concejales->isNotEmpty())
                            @foreach($reunion->concejales as $concejal)
                                {{ optional($concejal->persona)->nombre ?? 'N/A' }} {{ optional($concejal->persona)->apellido ?? '' }}
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        @if($reunion->instituciones->isNotEmpty())
                            {{ $reunion->instituciones->pluck('titulo')->implode(', ') }}
                        @else
                            N/A
                        @endif
                    </td>

                    <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                        {{ Str::limit($reunion->resolución ?? 'N/A', 50) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="page-break-after: always;"></div>
