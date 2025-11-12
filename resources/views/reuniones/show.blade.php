<x-layouts.rbac>
    @section('title', 'Detalles de la Reunión')

    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-calendar-event text-white text-2xl'></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Detalles de la Reunión</h1>
                                <p class="text-sm text-gray-600">{{ $reunion->titulo }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @php
                            $fechaHoraReunion = \Carbon\Carbon::parse($reunion->fecha_reunion->format('Y-m-d') . ' ' . $reunion->hora_reunion);
                            $reunionPasada = $fechaHoraReunion->isPast();
                            $estatusFinalizado = App\Models\Estatus::where('sector_sistema', 'reuniones')
                                                                    ->where('estatus', 'finalizada')
                                                                    ->value('estatus_id');
                            $estaFinalizada = ($reunion->estatus == $estatusFinalizado);
                        @endphp

                        @if(Auth::user()->isSuperAdministrador())
                            @if($reunionPasada && !$estaFinalizada)
                                <a href="{{ route('dashboard.reuniones.finalize', $reunion) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-md hover:shadow-lg">
                                    <i class='bx bx-check-circle mr-2'></i>
                                    Finalizar Reunión
                                </a>
                            @endif

                            <a href="{{ route('dashboard.reuniones.edit', $reunion) }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                                <i class='bx bx-edit mr-2'></i>
                                Editar
                            </a>
                        @endif
                        
                        <a href="{{ route('dashboard.reuniones.index') }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <i class='bx bx-check text-white text-lg'></i>
                        </div>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                            <i class='bx bx-error text-white text-lg'></i>
                        </div>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <i class='bx bx-info-circle text-white text-lg'></i>
                        </div>
                        <span class="text-blue-800 font-medium">{{ session('info') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Export Buttons for Finalized Meetings -->
        @if($estaFinalizada)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class='bx bx-download text-blue-600 text-2xl mr-3'></i>
                            <div>
                                <h3 class="font-semibold text-gray-900">Descargar Documentos</h3>
                                <p class="text-sm text-gray-600">Exporta el acta de la reunión finalizada</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dashboard.reuniones.export.acta', $reunion) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md"
                               title="Descargar Acta en PDF">
                                <i class='bx bxs-file-pdf text-red-600 text-2xl mr-2'></i>
                                <span class="font-medium text-gray-700">Acta PDF</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-6">
                
                <!-- Card 1: Información Principal -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class='bx bx-info-circle text-blue-600'></i>
                            </div>
                            Información Principal
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Fecha -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-calendar text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Fecha</h3>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $reunion->fecha_reunion->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $reunion->fecha_reunion->diffForHumans() }}</p>
                            </div>
                            
                            <!-- Hora -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-time text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Hora</h3>
                                </div>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $reunion->hora_reunion ? \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a') : 'No especificado' }}
                                </p>
                            </div>
                            
                            <!-- Tipo de Reunión -->
                            @if($reunion->tipo_reunion)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-map text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Tipo de Reunión</h3>
                                </div>
                                <p class="text-base font-semibold text-gray-900">{{ Str::title($reunion->tituloTipoReunion()) }}</p>
                            </div>
                            @endif
                            
                            <!-- Estatus -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-info-square text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Estatus</h3>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $reunion->tituloEstatus() }}
                                </span>
                            </div>

                            <!-- Duración Estimada -->
                            @if($reunion->duracion_reunion)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-timer text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Duración Estimada</h3>
                                </div>
                                <p class="text-base font-semibold text-gray-900">{{ $reunion->duracion_reunion }}</p>
                            </div>
                            @endif
                            
                            <!-- Duración Real (solo si está finalizada) -->
                            @if($estaFinalizada && $reunion->duracion_real)
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-timer text-green-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-green-700">Duración Real</h3>
                                </div>
                                <p class="text-base font-semibold text-green-900">{{ $reunion->duracion_real }}</p>
                            </div>
                            @endif
                            
                            <!-- Hora de Finalización Real (solo si está finalizada) -->
                            @if($estaFinalizada && $reunion->hora_finalizacion_real)
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-time-five text-green-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-green-700">Hora de Finalización Real</h3>
                                </div>
                                <p class="text-base font-semibold text-green-900">
                                    {{ \Carbon\Carbon::parse($reunion->hora_finalizacion_real)->format('g:i a') }}
                                </p>
                            </div>
                            @endif
                            
                            <!-- Instituciones Responsables -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 md:col-span-2">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-buildings text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Instituciones Responsables</h3>
                                </div>
                                @if($reunion->instituciones->count() > 0)
                                    <div class="space-y-2 mt-2">
                                        @foreach ($reunion->instituciones as $institucion)
                                            <div class="flex items-center justify-between">
                                                <p class="text-base font-semibold text-gray-900">{{ $institucion->titulo ?? 'No especificada' }}</p>
                                                @if ($reunion->estatus === 10 && $institucion->pivot->asistencia)
                                                    <span class="bg-green-100 py-1 px-2 text-xs rounded-xl text-green-700">Asistió</span>
                                                @elseif($reunion->estatus === 10 && !$institucion->pivot->asistencia)
                                                    <span class="bg-gray-100 py-1 px-2 text-xs rounded-xl text-gray-600">No Asistió</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">No hay instituciones asignadas</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        @if($reunion->descripcion)
                            <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-detail text-blue-600 mr-2 text-xl'></i>
                                    <h3 class="text-sm font-medium text-blue-900">Descripción y Objetivos</h3>
                                </div>
                                <p class="text-gray-800 leading-relaxed">{{ $reunion->descripcion }}</p>
                            </div>
                        @endif
                        
                        <!-- Resolución (solo si está finalizada) -->
                        @if($estaFinalizada && $reunion->resolución)
                            <div class="mt-6 bg-yellow-50 rounded-lg p-4 border-2 border-yellow-300">
                                <div class="flex items-center mb-3">
                                    <i class='bx bx-check-circle text-yellow-600 mr-2 text-2xl'></i>
                                    <h3 class="text-base font-bold text-yellow-900">Resolución y Conclusiones de la Reunión</h3>
                                </div>
                                <p class="text-gray-800 leading-relaxed text-justify">{{ $reunion->resolución }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 2: Concejales Participantes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-group text-green-600'></i>
                                </div>
                                Concejales Participantes
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $reunion->concejales->count() }} concejale(s)
                            </span>
                        </div>
                        
                        @if($reunion->concejales->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($reunion->concejales as $concejal)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                <i class='bx bx-user text-green-600 text-xl'></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $concejal->persona->nombre }} {{ $concejal->persona->apellido }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">C.I: {{ number_format($concejal->persona_cedula, 0, '.', '.') }}</p>
                                                @if ($reunion->estatus === 10)
                                                    <div class="mt-2">
                                                        @if($concejal->pivot->asistencia)
                                                            <span class="bg-green-100 py-1 px-2 text-xs rounded-xl text-green-700">Asistió</span>
                                                        @else
                                                            <span class="bg-gray-100 py-1 px-2 text-xs rounded-xl text-gray-600">No Asistió</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class='bx bx-user-x text-4xl text-gray-400 mb-4'></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay concejales asignados</h3>
                                <p class="text-gray-500">Esta reunión no tiene concejales participantes.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 3: Registro de Citación -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-user-voice text-purple-600'></i>
                                </div>
                                Registro de Citación
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ $reunion->solicitudes->count() }} persona(s) citada(s)
                            </span>
                        </div>
                        
                        @if($reunion->solicitudes->count() > 0)
                            <div class="bg-purple-50 rounded-lg p-3 mb-4">
                                <p class="text-sm text-purple-800 flex items-center">
                                    <i class='bx bx-info-circle mr-2'></i>
                                    <span>Personas citadas según las solicitudes asociadas a esta reunión</span>
                                </p>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($reunion->solicitudes as $solicitud)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                                <i class='bx bx-user text-purple-600 text-2xl'></i>
                                            </div>
                                            <div class="flex-1">
                                                @if($solicitud->persona)
                                                    <h3 class="text-base font-bold text-gray-900">
                                                        {{ $solicitud->persona->nombre }} 
                                                        {{ $solicitud->persona->segundo_nombre ?? '' }} 
                                                        {{ $solicitud->persona->apellido }} 
                                                        {{ $solicitud->persona->segundo_apellido ?? '' }}
                                                    </h3>
                                                    <div class="mt-2 space-y-1">
                                                        <p class="text-sm text-gray-600">
                                                            <i class='bx bx-id-card mr-1 text-gray-400'></i>
                                                            <span class="font-medium">Cédula:</span> {{ number_format($solicitud->persona->cedula, 0, '.', '.') }}
                                                        </p>
                                                        @if($solicitud->persona->telefono)   
                                                            <p class="text-sm text-gray-600">
                                                                <i class='bx bx-phone mr-1 text-gray-400'></i>
                                                                <span class="font-medium">Teléfono:</span> {{ $solicitud->persona->telefono }}
                                                            </p>
                                                        @endif
                                                        @if($solicitud->persona->email)
                                                            <p class="text-sm text-gray-600">
                                                                <i class='bx bx-envelope mr-1 text-gray-400'></i>
                                                                <span class="font-medium">Email:</span> {{ $solicitud->persona->email }}
                                                            </p>
                                                        @endif
                                                        <p class="text-sm text-purple-700 mt-2">
                                                            <i class='bx bx-file-blank mr-1'></i>
                                                            <span class="font-medium">Solicitud:</span> {{ $solicitud->titulo }}
                                                            <span class="text-xs text-gray-500">({{ $solicitud->solicitud_id }})</span>
                                                        </p>
                                                        <p class="text-sm text-blue-700">
                                                            <i class='bx bx-time mr-1'></i>
                                                            <span class="font-medium">Hora de Citación:</span> 
                                                            {{ $reunion->hora_reunion ? \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a') : 'No especificado' }}
                                                            el {{ $reunion->fecha_reunion->format('d/m/Y') }}
                                                        </p>
                                                    </div>
                                                    @if ($reunion->estatus === 10)
                                                        <div class="mt-3">
                                                            @if($solicitud->pivot->asistencia_solicitante)
                                                                <span class="bg-green-100 py-1 px-3 text-sm rounded-xl text-green-700 font-medium">Asistió</span>
                                                            @else
                                                                <span class="bg-gray-100 py-1 px-3 text-sm rounded-xl text-gray-600 font-medium">No Asistió</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <h3 class="text-base font-bold text-gray-900">Solicitante sin datos personales</h3>
                                                    <p class="text-sm text-purple-700 mt-2">
                                                        <i class='bx bx-file-blank mr-1'></i>
                                                        <span class="font-medium">Solicitud:</span> {{ $solicitud->titulo }}
                                                        <span class="text-xs text-gray-500">({{ $solicitud->solicitud_id }})</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class='bx bx-user-x text-4xl text-gray-400 mb-4'></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay personas citadas</h3>
                                <p class="text-gray-500">No se han asignado solicitudes a esta reunión.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 4: Solicitudes Asociadas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class='bx bx-file-blank text-orange-600'></i>
                                </div>
                                Solicitudes Asociadas
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                {{ $reunion->solicitudes->count() }} solicitud(es)
                            </span>
                        </div>
                        
                        @if($reunion->solicitudes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($reunion->solicitudes as $solicitud)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <!-- Indicador de Derecho de Palabra -->
                                                <div class="flex items-center mb-2">
                                                    <div class="w-3 h-3 rounded-full {{ $solicitud->derecho_palabra ? 'bg-blue-500' : 'bg-gray-400' }} mr-2" 
                                                         title="{{ $solicitud->derecho_palabra ? 'Solicitó Derecho de Palabra' : 'No solicitó Derecho de Palabra' }}"></div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $solicitud->titulo }}</p>
                                                </div>
                                                
                                                <!-- Información del Solicitante -->
                                                @if($solicitud->persona)
                                                    <p class="text-xs text-gray-700 mt-2">
                                                        <i class='bx bx-user mr-1 text-blue-600'></i>
                                                        <span class="font-medium">Solicitante:</span> 
                                                        {{ $solicitud->persona->nombre }} {{ $solicitud->persona->apellido }}
                                                    </p>
                                                @endif
                                                
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class='bx bx-id-card mr-1'></i>
                                                    ID: {{ $solicitud->solicitud_id }}
                                                </p>
                                                
                                                @if($solicitud->pivot->fecha_hora_citacion)
                                                    <p class="text-xs text-gray-600 mt-2">
                                                        <i class='bx bx-time mr-1'></i>
                                                        Citación: {{ \Carbon\Carbon::parse($solicitud->pivot->fecha_hora_citacion)->format('d/m/Y g:i a') }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Mostrar Asistencia si la reunión está finalizada -->
                                                @if($estaFinalizada)
                                                    <div class="mt-2">
                                                        @if($solicitud->pivot->asistencia_solicitante)
                                                            <span class="bg-green-100 py-1 px-2 text-xs rounded-xl text-green-700 font-medium">Asistió</span>
                                                        @else
                                                            <span class="bg-gray-100 py-1 px-2 text-xs rounded-xl text-gray-600 font-medium">No Asistió</span>
                                                        @endif
                                                        
                                                        @if($solicitud->pivot->estatus_decision)
                                                            <span class="ml-2 py-1 px-2 text-xs rounded-xl font-medium
                                                                {{ $solicitud->pivot->estatus_decision == 'aprobada' ? 'bg-green-100 text-green-700' : '' }}
                                                                {{ $solicitud->pivot->estatus_decision == 'rechazada' ? 'bg-red-100 text-red-700' : '' }}
                                                                {{ $solicitud->pivot->estatus_decision == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                                                {{ ucfirst($solicitud->pivot->estatus_decision) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                {{ $solicitud->getEstatusFormattedAttribute() ?? 'Sin estatus' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class='bx bx-file-blank text-4xl text-gray-400 mb-4'></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes asociadas</h3>
                                <p class="text-gray-500">Esta reunión no tiene solicitudes vinculadas.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.rbac>