<x-layouts.rbac>
    @section('title', 'Detalles de la Reunión')

    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-calendar-event text-white text-xl'></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Detalles de la Reunión</h1>
                                <p class="text-sm text-gray-600">{{ $reunion->titulo }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard.reuniones.edit', $reunion) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class='bx bx-edit mr-2'></i>
                            Editar
                        </a>
                        <a href="{{ route('dashboard.reuniones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-6">
                <!-- Información Principal -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            <i class='bx bx-info-circle text-indigo-600 mr-2'></i>
                            Información Principal
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-text text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Título</h3>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $reunion->titulo }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-calendar text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Fecha y Hora</h3>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $reunion->fecha_reunion->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $reunion->fecha_reunion->diffForHumans() }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-file-blank text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Solicitud Asociada</h3>
                                </div>
                                @if($reunion->solicitud)
                                    <p class="text-lg font-semibold text-gray-900">{{ $reunion->solicitud->titulo }}</p>
                                    <p class="text-sm text-gray-500">ID: {{ $reunion->solicitud->solicitud_id }}</p>
                                @else
                                    <p class="text-gray-500 italic">No asociada</p>
                                @endif
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-buildings text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Institución</h3>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">{{ $reunion->institucion->titulo ?? 'No especificada' }}</p>
                            </div>
                        </div>
                        
                        @if($reunion->descripcion)
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-detail text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Descripción</h3>
                                </div>
                                <p class="text-gray-900 leading-relaxed">{{ $reunion->descripcion }}</p>
                            </div>
                        @endif
                        
                        @if($reunion->ubicacion)
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class='bx bx-map text-gray-500 mr-2'></i>
                                    <h3 class="text-sm font-medium text-gray-700">Ubicación</h3>
                                </div>
                                <p class="text-gray-900">{{ $reunion->ubicacion }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Asistentes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">
                                <i class='bx bx-group text-indigo-600 mr-2'></i>
                                Asistentes
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $reunion->asistentes->count() }} personas
                            </span>
                        </div>
                        
                        @if($reunion->asistentes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($reunion->asistentes as $asistente)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class='bx bx-user text-blue-600'></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $asistente->nombre }} {{ $asistente->apellido }}</p>
                                                <p class="text-xs text-gray-500">Cédula: {{ $asistente->cedula }}</p>
                                            </div>
                                        </div>
                                        @if($asistente->pivot->es_concejal)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class='bx bx-star mr-1'></i>
                                                Concejal
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class='bx bx-user-x text-4xl text-gray-400 mb-4'></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay asistentes registrados</h3>
                                <p class="text-gray-500">Edite la reunión para agregar asistentes.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.rbac>