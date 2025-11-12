<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Historial de Solicitudes</title>
    {{-- Puedes incluir estilos CSS básicos si es necesario --}}
    <style>
        body { 
            font-family: sans-serif;

        }
        .titulo { 
            color: #333; 
            text-align: center;
            margin-top: 100px; 
        }

        .fecha{
            color: #525252;
            text-align: center;
        }

        .grafico { 
            max-width: 800px; 
            margin: 20px auto; 
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

        /* 1. Contenedor Principal (Simula el Grid) */
        .grid-pdf-container { 
            width: 100%;
        }

        .col-3-pdf {
            float: left; 
            
            width: 30%; 
            
            padding: 5px;
            margin-right: 3%;
            
            border: 1px solid #e5e7eb;
            box-sizing: border-box;
        }
        
        .last-col-pdf {
            margin-right: 0;
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
        
    <h1 class="titulo">Historial General de las Solicitudes</h1>

    <p class="fecha">
        {{$selectedPeriodChart === 'today' ? 'del día de hoy' : 
        ($selectedPeriodChart === 'last_7_days' ? 'últimos 7 días' :
        ($selectedPeriodChart === 'last_30_days' ? 'últimos 30 días' :
        ($selectedPeriodChart === 'this_month' ? 'este mes' : 
        ($selectedPeriodChart === 'this_year' ? 'este año' : 'N/A'))))}}
    </p>

    <div class="grafico">
        <img src="{{ $chartDataUrl }}" style="width: 100%; height: auto;">
    </div>
        
    <div class="grid-pdf-container">

        <div class="col-3-pdf">
            <h4 style="margin-top: 0; font-size: 16px;">Esta Semana</h4>
            <div style="display: flex;">
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Solicitudes:</strong> {{ $solicitudesSemana->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Pendientes:</strong> {{ $solicitudesSemana->where('estatus', 1)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Aprobadas:</strong> {{ $solicitudesSemana->where('estatus', 2)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Rechazadas:</strong> {{ $solicitudesSemana->where('estatus', 3)->count() }}</p>
            </div>
        </div>

        <div class="col-3-pdf">
            <h4 style="margin-top: 0; font-size: 16px;">Este Mes</h4>
            <div style="display: flex;">
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Solicitudes:</strong> {{ $solicitudesMes->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Pendientes:</strong> {{ $solicitudesMes->where('estatus', 1)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Aprobadas:</strong> {{ $solicitudesMes->where('estatus', 2)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Rechazadas:</strong> {{ $solicitudesMes->where('estatus', 3)->count() }}</p>
            </div>
        </div>

        <div class="col-3-pdf last-col-pdf">
            <h4 style="margin-top: 0; font-size: 16px;">Este Año</h4>
            <div style="display: flex;">
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Solicitudes:</strong> {{ $solicitudesAno->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Pendientes:</strong> {{ $solicitudesAno->where('estatus', 1)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Aprobadas:</strong> {{ $solicitudesAno->where('estatus', 2)->count() }}</p>
                <p style="font-size: 15px; margin-bottom: 0;"><strong>Total Rechazadas:</strong> {{ $solicitudesAno->where('estatus', 3)->count() }}</p>
            </div>
        </div>

    </div>

    <p style="color:#3333335b; padding-top:400px; text-align: center;">Reporte generado por el sistema de gestión de solicitudes del CMBEY</p>
    </body>
</html>