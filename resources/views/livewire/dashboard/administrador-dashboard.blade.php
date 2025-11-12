<div class="min-h-screen">
    <!-- Dashboard Content -->
    <div class="p-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Panel de Administrador
            </h1>
            <p class="mt-2 text-gray-600">Bienvenido, {{ auth()->user()->persona->nombre }} - Sistema Municipal CMBEY</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-file-blank text-3xl mr-4 text-gray-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->count() }}</h3>
                        <p class="text-blue-100">Total Solicitudes</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-3xl mr-4 text-green-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->where('estatus', 2)->count() }}</h3>
                        <p class="text-blue-100">Aprobadas</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-time-five text-3xl mr-4 text-yellow-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->where('estatus', 1)->count() }}</h3>
                        <p class="text-blue-100">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-800 to-blue-900 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-calendar-check text-3xl mr-4 text-teal-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $visitas->count() }}</h3>
                        <p class="text-blue-100">Total Visitas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-cog text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Gestión de Solicitudes</h3>
                    <p class="text-sm text-gray-600 mt-1">Revisar solicitudes (Solo Lectura)</p>
                    <button wire:click="setActiveTab('solicitudes')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Ver Solicitudes
                    </button>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-calendar-plus text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Visitas Programadas</h3>
                    <p class="text-sm text-gray-600 mt-1">Revisar visitas municipales</p>
                    <button wire:click="setActiveTab('visitas')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Ver Visitas
                    </button>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-bar-chart-alt-2 text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Reportes</h3>
                    <p class="text-sm text-gray-600 mt-1">Generar reportes estadísticos</p>
                    <button wire:click="setActiveTab('reportes')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Ver Reportes
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Solicitudes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class='bx bx-file-blank text-blue-600 mr-2'></i>
                    Solicitudes Recientes
                </h2>
                <div class="space-y-4">
                    @foreach($solicitudes->take(5) as $solicitud)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                    <i class='bx bx-file-blank text-blue-600'></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ Str::limit($solicitud->titulo, 30) }}</h3>
                                    <p class="text-sm text-gray-600">{{ $solicitud->persona->nombre ?? 'Usuario' }} {{ $solicitud->persona->apellido ?? '' }}</p>
                                    <p class="text-xs text-gray-500">{{ $solicitud->fecha_creacion->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{$solicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800' : ($solicitud->estatus === 2 ? 'bg-green-100 text-green-800' : ($solicitud->estatus === 3 ? 'bg-red-100 text-red-800' : ''))}}    
                            ">
                                {{ $solicitud->getEstatusFormattedAttribute() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Visitas -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class='bx bx-calendar-check text-blue-600 mr-2'></i>
                    Visitas Programadas
                </h2>
                <div class="space-y-4">
                    @if($visitas->count() > 0)
                        @foreach($visitas->take(5) as $visita)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                        <i class='bx bx-calendar-check text-blue-600'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $visita->persona->nombre ?? 'Usuario' }} {{ $visita->persona->apellido ?? '' }}</p>
                                        <div class="flex items-center">                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visita->visita->fecha_inicial)->format('d/m/Y') }}</p> - <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visita->fecha_final)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $visita->visita->estatus->getEstatusFormattedAttribute() }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class='bx bx-calendar-x text-4xl text-gray-400 mb-2'></i>
                            <p class="text-gray-600">No hay visitas programadas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Content -->
        @if($activeTab === 'solicitudes')
            <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-show text-blue-600 mr-3'></i>
                    Todas las Solicitudes (Solo Lectura)
                </h2>
                
                @if($solicitudes->count() > 0)
                    <div class="space-y-4">
                        @foreach($solicitudesDos->take(10) as $solicitud)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $solicitud->titulo }}</h3>
                                            @if($solicitud->solicitud_id)
                                                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                    ID: {{ $solicitud->solicitud_id }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 mb-3">{{ Str::limit($solicitud->descripcion, 150) }}</p>
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                            <span><i class='bx bx-user mr-1'></i>{{ $solicitud->persona->nombre ?? 'Usuario' }} {{ $solicitud->persona->apellido ?? '' }}</span>
                                            <span><i class='bx bx-category mr-1'></i>{{ $solicitud->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin categoría' }}</span>
                                            <span><i class='bx bx-map mr-1'></i>{{ Str::limit($solicitud->direccion_detallada ?? $solicitud->direccion, 30) }}</span>
                                            <span><i class='bx bx-calendar mr-1'></i>{{ $solicitud->fecha_creacion->format('d/m/Y H:i') }} </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{$solicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800' : ($solicitud->estatus === 2 ? 'bg-green-100 text-green-800' : ($solicitud->estatus === 3 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))}}    
                                        ">
                                            {{ $solicitud->getEstatusFormattedAttribute() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class='bx bx-file-blank text-6xl text-gray-400 mb-4'></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes</h3>
                        <p class="text-gray-600">Las solicitudes aparecerán aquí cuando los usuarios las creen</p>
                    </div>
                @endif
                <div class="mt-4">
                    {{ $solicitudesDos->links() }}
                </div>
            </div>
        @endif

        @if($activeTab === 'visitas')
            <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-calendar-check text-blue-600 mr-3'></i>
                    Todas las Visitas
                </h2>
                
                @if($visitas->count() > 0)
                    <div class="space-y-4">
                        @foreach($visitas as $visita)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-600 mb-3">{{ $visita->visita->observacion ?? 'Sin observaciones' }}</p>
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                            <span><i class='bx bx-user mr-1'></i>{{ $visita->persona->nombre ?? 'Usuario' }} {{ $visita->persona->apellido ?? '' }}</span>                                        
                                            <div class="flex items-center">
                                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visita->visita->fecha_inicial)->format('d/m/Y') }}</p> - <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visita->visita->fecha_final)->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $visita->visita->estatus->getEstatusFormattedAttribute() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class='bx bx-calendar-x text-6xl text-gray-400 mb-4'></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay visitas programadas</h3>
                        <p class="text-gray-600">Las visitas aparecerán aquí cuando sean programadas</p>
                    </div>
                @endif
            </div>
        @endif

        @if($activeTab === 'reportes')
            <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-bar-chart-alt-2 text-blue-600 mr-3'></i>
                    Reportes Estadísticos
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Solicitudes por Estado</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Pendientes:</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $solicitudes->where('estatus', 1)->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Aprobadas:</span>
                                <span class="text-sm font-medium text-green-600">{{ $solicitudes->where('estatus', 2)->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Rechazadas:</span>
                                <span class="text-sm font-medium text-red-600">{{ $solicitudes->where('estatus', 3)->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Visitas por Estado</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Programadas:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $visitas->where('estado', 'Programada')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Realizadas:</span>
                                <span class="text-sm font-medium text-green-600">{{ $visitas->where('estado', 'Realizada')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Canceladas:</span>
                                <span class="text-sm font-medium text-red-600">{{ $visitas->where('estado', 'Cancelada')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Resumen General</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Usuarios:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $usuarios->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Categorías:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $categorias->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Actividad Hoy:</span>
                                <span class="text-sm font-medium text-blue-600">{{ $solicitudes->where('created_at', '>=', today())->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>