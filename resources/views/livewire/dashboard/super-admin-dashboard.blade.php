<div class="min-h-screen" id='init'>
    <style>
        /* ... (Tus estilos se mantienen) ... */
        .birthday-today-row {
            background-color: #fffbeb !important;
            /* Fondo amarillo muy claro para hoy */
            border-left: 4px solid #f59e0b;
            /* Borde naranja */
        }

        .badge-hoy {
            background-color: #f59e0b;
            /* Naranja */
            color: white;
        }

        .badge-proximo {
            background-color: #bfdbfe;
            /* Azul claro */
            color: #1e40af;
            /* Azul oscuro */
        }
    </style>
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 id="main-card" class="text-3xl font-bold text-gray-900">
                Panel de SuperAdministrador
            </h1>
            <p class="mt-2 text-gray-600">Bienvenido, {{ auth()->user()->persona->nombre }} - Control Total del Sistema
            </p>
        </div>

        <div>
            @if ($showSecurityNotification)
            <div
                class="bg-orange-50 border border-orange-400 text-orange-900 p-6 rounded-2xl shadow-xl relative mb-8 flex items-start space-x-4">

                <div class="flex-shrink-0 mt-1">
                    <i class='bx bxs-error-alt text-4xl text-orange-600'></i>
                </div>

                <div class="flex-1">
                    <strong class="font-extrabold block text-xl mb-2 leading-tight">
                    </strong>
                    <p class="block text-lg sm:inline leading-relaxed">
                        No has establecido tus preguntas de seguridad. Esta configuración es vital para recuperar tu
                        cuenta.
                        <a href="{{ route('dashboard.seguridad') }}"
                            class="font-bold underline text-orange-700 hover:text-orange-900 transition-colors ml-1">
                            Ve a configurarlas ahora
                        </a>.
                    </p>
                </div>

                <span
                    class="absolute top-3 right-3 p-1 cursor-pointer rounded-full hover:bg-orange-100 transition-colors"
                    wire:click="$set('showSecurityNotification', false)" title="Ocultar esta notificación">
                    <i class='bx bx-x text-2xl text-orange-500'></i>
                </span>
            </div>
            @endif

        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8" id='countCards'>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg" id='request'>
                <div class="flex items-center">
                    <i class='bx bx-file-blank text-3xl mr-4 text-gray-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes_general->count() }}</h3>
                        <p class="text-blue-100">Total Solicitudes</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg"
                id='requestApproved'>
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-3xl mr-4 text-green-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes_general->where('estatus', 2)->count()
                            }}</h3>
                        <p class="text-blue-100">Aprobadas</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 rounded-xl p-6 text-white shadow-lg"
                id='requestStanding'>
                <div class="flex items-center">
                    <i class='bx bx-time-five text-3xl mr-4 text-orange-500'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes_general->where('estatus', 1)->count()
                            }}</h3>
                        <p class="text-blue-100 ">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-800 to-blue-900 rounded-xl p-6 text-white shadow-lg"
                id='visitsInProgess'>
                <div class="flex items-center">
                    <i class='bx bx-calendar-check text-3xl mr-4 text-teal-300'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $visitas->count() }}</h3>
                        <p class="text-blue-100">Visitas en curso</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-900 to-blue-950 rounded-xl p-6 text-white shadow-lg" id='totalUsers'>
                <div class="flex items-center">
                    <i class='bx bx-user text-3xl mr-4 text-neutral-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $usuarios_general->count() }}</h3>
                        <p class="text-blue-100">Total Usuarios</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Management Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <button wire:click="setActiveTab('usuarios')"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-group text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Gestión de Usuarios</h3>
                    <p class="text-sm text-gray-600 mt-1">Administrar usuarios y roles</p>
                </div>
            </button>

            <button wire:click="setActiveTab('solicitudes')"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-file-blank text-blue-600 text-3xl mr-4'></i>
                    <h3 class="font-bold text-gray-900">Solicitudes Globales</h3>
                    <p class="text-sm text-gray-600 mt-1">Control total de solicitudes</p>
                </div>
            </button>

            <button wire:click="setActiveTab('reportes')"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-bar-chart-alt-2 text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Reportes Avanzados</h3>
                    <p class="text-sm text-gray-600 mt-1">Analíticas y estadísticas</p>
                </div>
            </button>

            <button wire:click="setActiveTab('cumpleañeros')"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-cake text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Cumpleañeros</h3>
                    <p class="text-sm text-gray-600 mt-1">Proximo Cumpleañeros</p>
                </div>
            </button>
        </div>
        <!-- Tabs Content -->
        <div class="container mx-auto px-4 py-8">

            <div class="border-b border-gray-200 overflow-y-hidden lg:overflow-x-scroll">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="$set('activeTab', 'dashboard')" class="{{ $activeTab === 'dashboard' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                       whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        General
                    </button>
                    <button wire:click="$set('activeTab', 'solicitudes')" class="{{ $activeTab === 'solicitudes' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                       whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        Solicitudes
                    </button>
                    <button wire:click="$set('activeTab', 'usuarios')" class="{{ $activeTab === 'usuarios' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                       whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        Usuarios
                    </button>
                    <button wire:click="$set('activeTab', 'reportes')" class="{{ $activeTab === 'reportes' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                       whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        Reportes
                    </button>
                    <button wire:click="$set('activeTab', 'cumpleañeros')" class="{{ $activeTab === 'cumpleañeros' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} 
                       whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150">
                        Cumpleañeros
                    </button>
                </nav>
            </div>

            @if($activeTab === 'dashboard' || !$activeTab)
            <div class="mt-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Vista General del Sistema</h1>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class='bx bx-user-plus text-blue-600 mr-2'></i>
                            Usuarios Recientes
                        </h2>
                        <div class="space-y-4">
                            @foreach($usuarios_recientes as $usuario)
                            <div
                                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class='bx bx-user text-blue-600 text-sm'></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">
                                            {{ $usuario->persona->nombre ?? 'N/A' }} {{ $usuario->persona->apellido ??
                                            '' }}
                                        </h3>
                                        <p class="text-xs text-gray-600">{{ $usuario->getRoleName() ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $usuario->role === 1 ? 'Super' : ($usuario->role === 2 ? 'Admin' : 'User') }}
                                </span>
                            </div>
                            @endforeach
                            {{$usuarios_recientes->links(data: ['pageName' => 'UsuariosRecientes'])}}
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class='bx bx-list-check mr-2 text-blue-600'></i>
                            Solicitudes Pendientes
                        </h2>
                        <div class="space-y-4">
                            @foreach($solicitudes_pendientes as $solicitud)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                title="{{$solicitud->titulo}}">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class='bx bx-file-blank text-blue-600 text-sm'></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">{{ Str::limit($solicitud->titulo,
                                            20) }}</h3>
                                        <p class="text-xs text-gray-600">{{ $solicitud->persona->nombre ?? 'Usuario' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $solicitud->getEstatusFormattedAttribute() }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        {{$solicitudes_pendientes->links()}}
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class='bx bx-stats text-blue-600 mr-2'></i>
                            Estadísticas del Sistema
                        </h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-800">Total Usuarios</span>
                                <span class="text-lg font-bold text-blue-600">{{ $usuarios->count() ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-800">Total Solicitudes</span>
                                <span class="text-lg font-bold text-blue-600">{{ $solicitudes->count() ?? 0}}</span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-800">Comunidades</span>
                                <span class="text-lg font-bold text-blue-600">{{ $comunidad->count() ?? 0 }}</span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-800">Parroquias</span>
                                <span class="text-lg font-bold text-blue-600">{{ $parroquia->count() ?? 0 }}</span>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-blue-800">Cargos</span>
                                <span class="text-lg font-bold text-blue-600">{{ $cargo->count() ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($activeTab === 'solicitudes')
            <div class="mt-8 bg-white rounded-xl shadow-2xl p-6 md:p-8 border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
                        <i class='bx bx-cog text-blue-600 mr-3 text-3xl'></i>
                        Gestión Global de Solicitudes
                    </h2>
                    <div class="relative w-full sm:w-1/3 min-w-[200px]">
                        <input wire:model.live="search" type="text" placeholder="Buscar..."
                            class="w-full p-2 pl-8 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm">
                        <i class='bx bx-search absolute left-2 top-2.5 text-gray-400 text-lg'></i>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-inner">
                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-blue-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Ticket
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Título
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Usuario
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Categoría
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Tipo
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($solicitudes as $solicitud)
                            <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $solicitud->solicitud_id ?? 'Ticket-' . $solicitud->id }}
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700">
                                    {{ Str::limit($solicitud->titulo, 30) }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $solicitud->persona->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ Str::title($solicitud->subcategoriaRelacion->categoria) ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ $solicitud->tipo_solicitud === 'individual' ? 'Individual' : 'Colectivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm">
                                    <select
                                        wire:change="updateSolicitudStatus('{{ $solicitud->solicitud_id }}', $event.target.value)"
                                        class="text-xs border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 py-1 px-2 font-semibold
                                        {{$solicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800' : ($solicitud->estatus === 2 ? 'bg-green-100 text-green-800' : ($solicitud->estatus === 3 ? 'bg-red-100 text-red-800' : ''))}}
                                        ">
                                        @foreach ($estatusSolicitud as $estatus)
                                        <option value="{{$estatus->estatus_id}}" {{ $estatus->estatus_id ===
                                            $solicitud->estatus
                                            ?'selected' : '' }}>{{$estatus->getEstatusFormattedAttribute()}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 p-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                        {!! $solicitudes->links(data: ['pageName' => 'solicitudPage']) !!}
                    </div>
                </div>
            </div>
            @endif

            @if($activeTab === 'usuarios')
            <div class="mt-8 bg-white rounded-xl shadow-2xl p-6 md:p-8 border border-gray-100">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">

                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
                        <i class='bx bx-group text-blue-600 mr-3 text-3xl'></i>
                        Gestión de Usuarios
                    </h2>

                    <div class="relative w-full sm:w-1/3 min-w-[200px]">
                        <input wire:model.live="search" type="text" placeholder="Buscar..."
                            class="w-full p-2 pl-8 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm">
                        <i class='bx bx-search absolute left-2 top-2.5 text-gray-400 text-lg'></i>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-inner">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Cédula
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Teléfono
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                    Rol
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($usuarios as $usuario)
                            <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $usuario->persona_cedula }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $usuario->persona->nombre ?? 'N/A' }} {{ $usuario->persona->apellido ?? '' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $usuario->persona->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $usuario->persona->telefono ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm">
                                    <select
                                        wire:change="changeUserRole({{ $usuario->persona_cedula }}, $event.target.value)"
                                        class="text-xs border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 py-1 px-2 bg-white text-gray-700 font-semibold">
                                        <option value="1" {{ $usuario->role === 1 ? 'selected' : ''}}>SuperAdministrador
                                        </option>
                                        <option value="2" {{ $usuario->role === 2 ? 'selected' : '' }}>Administrador
                                        </option>
                                        <option value="3" {{ $usuario->role === 3 ? 'selected' : '' }}>Usuario</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                    {!! $usuarios->links(data: ['pageName'=>'usuarioPage']) !!}
                </div>
            </div>
        </div>
        @endif

        @if($activeTab === 'reportes')
        <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class='bx bx-bar-chart-alt-2 text-blue-600 mr-3'></i>
                Reportes Avanzados
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Solicitudes por Categoría</h4>
                    <div class="space-y-3">
                        @foreach ($categorias as $categoria)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{$categoria->getCategoriaFormattedAttribute()}}</span>
                            <span class="text-sm font-medium text-blue-600">{{ $categoria->solicitudes->count()}}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Usuarios por Rol</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">SuperAdministradores:</span>
                            <span class="text-sm font-medium text-blue-600">{{ $usuarios_general->where('role',
                                1)->count()
                                }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Administradores:</span>
                            <span class="text-sm font-medium text-blue-600">{{ $usuarios_general->where('role',
                                2)->count()
                                }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Usuarios:</span>
                            <span class="text-sm font-medium text-blue-600">{{ $usuarios_general->where('role',
                                3)->count()
                                }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Actividad Reciente (Solicitudes)</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Solicitudes Hoy:</span>
                            <span class="text-sm font-medium text-blue-600">{{
                                $solicitudes_general->where('fecha_creacion',
                                '>=', today())->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Solicitudes Esta Semana:</span>
                            <span class="text-sm font-medium text-blue-600">{{
                                $solicitudes_general->where('fecha_creacion',
                                '>=', now()->startOfWeek())->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Solicitudes Este Mes:</span>
                            <span class="text-sm font-medium text-blue-600">{{
                                $solicitudes_general->where('fecha_creacion',
                                '>=', now()->startOfMonth())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($activeTab === 'cumpleañeros')
        <div class="mt-8 bg-white rounded-xl shadow-2xl p-6 md:p-8 border border-gray-100">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-4">

                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
                        <i class='bx bx-cake text-blue-600 mr-3 text-3xl'></i>
                        Trabajadores Cumpleañeros
                    </h2>
                    <p class="text-sm text-gray-600 ml-2">Las 5 personas más cercanas a la fecha</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-inner">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                Cédula
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                Nombre
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                Cargo
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-blue-800">
                                Nacimiento
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($cumpleaneros->take(5) as $cumpleanero)
                        @php
                        $persona = $cumpleanero->persona;
                        // La línea que calcula la fecha de nacimiento no necesita cambios
                        $fechaNacimiento = $persona && $persona->nacimiento ?
                        \Carbon\Carbon::parse($persona->nacimiento) : null;
                        $hoy = \Carbon\Carbon::now();
                        $diasRestantes = 9999;

                        if ($fechaNacimiento) {
                        $cumpleEsteAno = $fechaNacimiento->copy()->year($hoy->year);

                        // Si el cumpleaños ya pasó este año y no es hoy, se mueve al próximo año
                        if ($cumpleEsteAno->isPast() && !$cumpleEsteAno->isToday()) {
                        $cumpleEsteAno->addYear();
                        }

                        // --- LA CORRECCIÓN ESTÁ AQUÍ ---
                        // Usamos intval() para convertir el resultado flotante a un entero.
                        $diasRestantes = intval($hoy->diffInDays($cumpleEsteAno));
                        // O podrías usar floor($hoy->diffInDays($cumpleEsteAno)) para el mismo efecto.
                        }

                        $rowClass = '';
                        if ($diasRestantes === 0) {
                        $rowClass = 'birthday-today-row';
                        } elseif ($diasRestantes <= 30) { $rowClass='bg-blue-50' ; } $nombre_completo=trim($persona->
                            nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->apellido . ' ' .
                            $persona->segundo_apellido);
                            @endphp
                            <tr class="hover:bg-blue-50 transition duration-150 ease-in-out {{ $rowClass }}">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cumpleanero->persona_cedula }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    @if($diasRestantes === 0)
                                    <span class="text-yellow-700 font-bold">🎂
                                        {{ $cumpleanero->persona->nombre ?? 'N/A' }} {{ $cumpleanero->persona->apellido
                                        ?? '' }}</span>
                                    @elseif($diasRestantes <= 7) <span class="text-blue-700">🎁
                                        {{ $cumpleanero->persona->nombre ?? 'N/A' }} {{ $cumpleanero->persona->apellido
                                        ?? '' }}</span>
                                        @else

                                        {{ $cumpleanero->persona->nombre ?? 'N/A' }} {{ $cumpleanero->persona->apellido
                                        ?? '' }}
                                        @endif
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $cumpleanero->cargo['descripcion'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $cumpleanero->persona->nacimiento->format('d/m/Y') ?? 'N/A' }}
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
<div x-data="{ show: true }" class="z-50">
    <buttom @click="iniciarTour()" x-show="show"
        class="fixed bottom-6 right-6  text-gray-700 text-4xl rounded-full shadow-xl flex items-center justify-center cursor-pointer z-50 transition-transform transform hover:scale-110">
        <i class='bx bxs-help-circle'></i>
    </buttom>
</div>

<script>
    function iniciarTour() {
            
            const driver = window.driver.js.driver;

            const driverObj = driver({

                animate: true,
                showButtons: ['next', 'previous', 'close'],
                prevBtnText: 'Anterior',   
                nextBtnText: 'Siguiente', 
                doneBtnText: 'Finalizar',
                steps: [
                    { element: '#init', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: '¡Bienvenido al perfil de secretaria!', 
                        description: 'En esta seccion encontraras un resumen de la informacion mas vital del sistema', 
                        side: 'left' } 
                    },
                    
                    { element: '#countCards', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Contadores', 
                        description: 'Esta seccion resumira en contadores la informacion', 
                        side: 'left' } 
                    },

                      { element: '#request', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Solicitudes totales', 
                        description: 'Mostrando la totalidad de las solicitudes', 
                        side: 'left' } 
                    },

                     { element: '#requestApproved', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Solicitudes Aprobadas', 
                        description: 'Totalidades de las solicitudes aprobadas ', 
                        side: 'left' } 
                    },

                    { element: '#requestStanding', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Solicitudes Pendientes', 
                        description: 'Totalidad de las solicitudes pendientes', 
                        side: 'left' } 
                    },
                    
                    { element: '#visitsInProgess', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Visitas en progreso', 
                        description: 'Totalidad de las visitas en progreso', 
                        side: 'left' } 
                    },

                    { element: '#totalUsers', popover: {
                        popoverClass: 'mi-popover-tour',
                        title: 'Usuarios totales', 
                        description: 'Totalidad de los usuarios registrados ', 
                        side: 'left' } 
                    },
                        
                ]
            });

            driverObj.drive(); 
        }
</script>
</div>