<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte Completo de Trabajadores</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; word-wrap: break-word; }
        th { background-color: #f0f0f0; }

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
    </style>
</head>
<body>

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

    <h1 style="color: #333; text-align: center; margin-top: 100px; ">Reporte Completo de Trabajadores</h1>

    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre Completo</th>
                <th>Nacionalidad</th>
                <th>Género</th>
                <th>Fecha Nacimiento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Cargo</th>
                <th>Zona de trabajo</th>
                <th>Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trabajadores as $trabajador)
                <tr>
                    <td>{{ $trabajador->persona->cedula }}</td>
                    <td>{{ $trabajador->persona->nombre }} {{ $trabajador->persona->segundo_nombre }} {{ $trabajador->persona->apellido }} {{ $trabajador->persona->segundo_apellido }}</td>
                    <td>{{ $trabajador->persona->nacionalidad ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->genero ?? 'No registrado' }}</td>
                    <td>{{ optional($trabajador->persona->nacimiento)->format('d/m/Y') ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->telefono ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->email ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->direccion ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->cargo->descripcion ?? 'Sin cargo' }}</td>
                    <td>{{ $trabajador->zona_trabajo ?? 'No asignada' }}</td>
                    <td>{{ optional($trabajador->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="color:#3333335b; margin-top: 50px; text-align: center;">Reporte generado por el sistema de gestión de solicitudes del CMBEY</p>
</body>
</html>
