<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Completo de Personas</title>
    <style>
        body { font-family: sans-serif; }
        /* Puedes añadir aquí más estilos globales */

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
    <h1 style="color: #333; text-align: center; margin-top: 100px; ">Reporte Completo de Personas</h1>
    
    {!! $chunks !!}
    
</body>
</html>