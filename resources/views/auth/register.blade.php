<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('img/isotipo.png') }}">
    <title>CMBEY - Registro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="font-roboto antialiased">
    <div class="full-screen-container bg-gradient-to-b from-blue-300 via-blue-500 to-blue-900">
        @livewire('auth.register-form')
    </div>
    @livewireScripts
</body>

</html>