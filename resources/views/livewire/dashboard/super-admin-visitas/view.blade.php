<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Manejo de Errores (Aplicando estilo de notificación) --}}
    @if ($errorMessage)
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mb-6" role="alert">
        <p class="font-bold">Error al Cargar Visita</p>
        <p>{{ $errorMessage }}</p>
        
    </div>
    @endif

    @if ($visitData)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center">
                        <i class='bx bx-calendar-check text-blue-600 text-4xl'></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-3xl font-bold">
                            Detalle de Visita Asignada
                        </h2>
                        <p class="text-blue-100 text-lg flex items-center mt-1">
                            <i class='bx bx-purchase-tag-alt mr-2'></i>
                            Ticket: {{ $visitData->solicitud_id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">

            <div class="mb-8 flex items-center justify-between border-b pb-4">
                <div class="flex items-center space-x-3">
                    <span
                        class="px-4 py-2 
                        {{ $visitData->estatus->estatus_id === 1 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} 
                        rounded-full text-sm font-semibold flex items-center">
                        <i class='bx bx-loader-circle mr-2'></i>
                        {{ $visitData->estatus->estatus }}
                    </span>
                </div>

                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-500 uppercase mb-1">Período Asignado</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ \Carbon\Carbon::parse($visitData->fecha_inicial)->format('d/m/Y') }} 
                        <span class="text-gray-400 mx-1 font-normal">al</span> 
                        {{ \Carbon\Carbon::parse($visitData->fecha_final)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <div>
                    
                    {{-- Sección Solicitante --}}
                    @if ($visitData->solicitud && $visitData->solicitud->persona)
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class='bx bx-user text-blue-600 mr-2 text-2xl'></i>
                        Información del Solicitante
                    </h3>
                    <div class="space-y-4 mb-8">
                        <div class="py-3 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $visitData->solicitud->persona->nombre }} {{ $visitData->solicitud->persona->apellido }}
                            </p>
                        </div>
                        <div class="py-3 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Cédula</p>
                            <p class="text-base font-semibold text-gray-900 font-mono">
                              v-  {{ $visitData->solicitud->persona->cedula }}
                            </p>
                        </div>
                        <div class="py-3">
                            <p class="text-sm text-gray-500 mb-1">Título de la Solicitud</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $visitData->solicitud->titulo ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Sección Ubicación --}}
                    @if ($visitData->solicitud)
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class='bx bx-map-pin text-blue-600 mr-2 text-2xl'></i>
                        Detalles de Ubicación
                    </h3>
                    <div class="space-y-4">
                         <div class="py-3 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Comunidad</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $visitData->solicitud->comunidad ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="py-3 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Municipio</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $visitData->solicitud->municipio ?? 'Desconocida' }}
                            </p>
                        </div>
                        <div class="py-3">
                            <p class="text-sm text-gray-500 mb-2">Dirección Exacta</p>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-base text-gray-900">
                                    {{ $visitData->solicitud->direccion_detallada ?? 'No especificada' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div>
                    
                    {{-- Sección Equipo Asignado --}}
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class='bx bx-group text-blue-600 mr-2 text-2xl'></i>
                        Personal asignado 
                        <span class="ml-3 text-sm font-normal text-gray-500">({{ $visitData->asistente->count() }} Asistentes)</span>
                    </h3>
                    
                    <ul class="divide-y divide-gray-200 space-y-2 mb-8">
                        @forelse ($visitData->asistente as $asistenteItem)
                            @if ($asistenteItem->User && $asistenteItem->User->usuario)
                            <li class="bg-gray-50 rounded-lg p-4 flex justify-between items-center border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <i class='bx bx-wrench text-blue-500 text-xl'></i>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $asistenteItem->User->nombre }} {{ $asistenteItem->User->apellido }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $asistenteItem->User->usuario->roleModel->rol === 2 ? 'Asistente' : 'Administrador' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-sm font-mono text-gray-600">{{ $asistenteItem->cedula }}</span>
                            </li>
                            @else
                            <li class="p-4 text-sm text-red-500 bg-red-50 rounded-lg border border-red-200">
                                Advertencia: Datos de la persona incompletos para cédula {{ $asistenteItem->cedula }}
                            </li>
                            @endif
                        @empty
                            <li class="p-4 text-gray-500 text-center bg-gray-50 rounded-lg border border-gray-200">No se encontraron técnicos asignados.</li>
                        @endforelse
                    </ul>
                    
                    {{-- Sección Observaciones --}}
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                        <i class='bx bx-message-square-detail text-blue-600 mr-2 text-2xl'></i>
                        Observaciones
                    </h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-base text-gray-800 italic">
                            {{ $visitData->solicitud->observaciones_admin  ?: 'No hay observaciones registradas para esta visita.' }}
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endif
</div>