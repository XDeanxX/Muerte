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
                <th style="width: 9%; border: 1px solid #000; padding: 5px; text-align: left;">Cédula</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Nombres</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Apellidos</th>
                <th style="width: 12%; border: 1px solid #000; padding: 5px; text-align: left;">Teléfono</th>
                <th style="width: 25%; border: 1px solid #000; padding: 5px; text-align: left;">Email</th>
                <th style="width: 15%; border: 1px solid #000; padding: 5px; text-align: left;">Dirección</th>
                <th style="width: 9%; border: 1px solid #000; padding: 5px; text-align: left;">Creación</th>
                <th style="width: 10%; border: 1px solid #000; padding: 5px; text-align: left;">Rol</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
            @php
            $persona = optional($user->persona);
            $rol = method_exists($user, 'getRoleName') ? $user->getRoleName() : 'N/A';
            @endphp
            <tr>
                <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">{{ $user->persona_cedula }}
                </td>

                <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                    {{ $persona->nombre }} {{ $persona->segundo_nombre }}
                </td>

                <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">
                    {{ $persona->apellido }} {{ $persona->segundo_apellido }}
                </td>

                <td style="border: 1px solid #000; padding: 5px;">{{ $persona->telefono ?? 'N/A' }}</td>
                <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word;">{{ $user->persona->email }}</td>
                <td style="border: 1px solid #000; padding: 5px; word-wrap: break-word; font-size: 8px;">
                    {{ $persona->direccion ?? 'N/A' }}
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    {{ $user->created_at ? $user->created_at->format('d-m-Y') : 'N/A' }}
                </td>
                <td style="border: 1px solid #000; padding: 5px; overflow: hidden;">{{ $rol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<p style="color:#3333335b; margin-top: 50px; text-align: center;">Reporte generado por el sistema de gestión de solicitudes del CMBEY</p>
<div style="page-break-after: always;"></div>