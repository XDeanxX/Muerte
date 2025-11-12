<x-layouts.rbac>

    <div class="min-h-screen bg-gray-50">
        <!-- Header Section - Modernized -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-group text-white text-2xl'></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Gestión de Reuniones</h1>
                                <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex max-lg:flex-col lg:items-center gap-4 ">
                        <!-- Botones de acceso rápido - Solo visible para SuperAdministrador -->
                        @if(Auth::user()->isSuperAdministrador())
                            <a href="{{ route('dashboard.superadmin.concejales') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class='bx bx-user-plus mr-2'></i>
                                Registrar Concejal
                            </a>
                            <a href="{{ route('dashboard.superadmin.instituciones') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class='bx bx-building-house mr-2'></i>
                                Registrar Institución
                            </a>
                            <a href="{{ route('dashboard.reuniones.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class='bx bx-plus mr-2 text-lg'></i>
                                Nueva Reunión
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message - Enhanced -->
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

        <!-- Content Section - Enhanced -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Barra de búsqueda -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('dashboard.reuniones.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Buscar por título de reunión..." 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="flex-none md:w-48">
                        <input type="date" 
                               name="fecha" 
                               value="{{ request('fecha') }}"
                               placeholder="Fecha" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <div class="flex-none md:w-48">
                        <select name="tipo" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Todos los tipos</option>
                            <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>Asamblea</option>
                            <option value="2" {{ request('tipo') == '2' ? 'selected' : '' }}>Mesa de Trabajo</option>
                            <option value="3" {{ request('tipo') == '3' ? 'selected' : '' }}>Sesión Solemne</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <!-- Filtro label -->
                        <span class="flex items-center text-sm text-gray-600 font-medium mr-2">Filtro:</span>
                        
                        <!-- PDF Export Button -->
                        <a href="{{ route('dashboard.reuniones.export.pdf') }}" 
                           class="inline-flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md"
                           title="Exportar a PDF">
                            <i class='bx bxs-file-pdf text-red-600 text-xl'></i>
                        </a>
                        
                        <!-- Excel Export Button -->
                        <a href="{{ route('dashboard.reuniones.export.excel') }}" 
                           class="inline-flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md"
                           title="Exportar a Excel">
                            <i class='bx bxs-file text-green-600 text-xl'></i>
                        </a>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                            <i class='bx bx-search mr-2'></i>
                            Buscar
                        </button>
                        @if(request()->hasAny(['search', 'fecha', 'tipo']))
                            <a href="{{ route('dashboard.reuniones.index') }}" 
                               class="px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 flex items-center">
                                <i class='bx bx-x'></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class='bx bx-calendar-event text-blue-600 text-lg'></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                Reuniones Registradas
                            </h2>
                        </div>
                        <div class="bg-gray-50 px-3 py-1 rounded-full">
                            <span class="text-sm text-gray-600 font-medium">
                                Total: {{ $reuniones->total() }} reuniones
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class='bx bx-text text-gray-400'></i>
                                            <span>Reunión</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class='bx bx-file-blank text-gray-400'></i>
                                            <span>Solicitudes Asignadas</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class='bx bx-buildings text-gray-400'></i>
                                            <span>Instituciones Citadas</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class='bx bx-calendar text-gray-400'></i>
                                            <span>Fecha</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class='bx bx-group text-gray-400'></i>
                                            <span>Cocejales Convocados</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-end space-x-2">
                                            <i class='bx bx-cog text-gray-400'></i>
                                            <span>Acciones</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-end space-x-2">
                                            <i class='bx bx-cog text-gray-400'></i>
                                            <span>Estatus</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($reuniones as $reunion)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="space-y-1">
                                                <div class="text-sm font-semibold text-gray-900">{{ $reunion->titulo }}</div>
                                                @if($reunion->descripcion)
                                                    <div class="text-sm text-gray-500 truncate max-w-xs" title="{{ $reunion->descripcion }}">
                                                        {{ Str::limit($reunion->descripcion, 50) }}
                                                    </div>
                                                @endif
                                                @if($reunion->tipo_reunion)
                                                    <div class="flex items-center text-xs text-gray-400">
                                                        <i class='bx bx-map-pin mr-1'></i>
                                                        {{ Str::title($reunion->tipoReunionRelacion->titulo) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="space-y-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $reunion->solicitudes->count() ?? 0 }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            @forelse ($reunion->instituciones as $institucion)
                                                <div class="text-sm font-medium text-gray-900">{{ $institucion->titulo ?? 'N/A' }}</div>
                                            @empty
                                                <div class="text-sm font-medium text-gray-900">N/A</div>
                                            @endforelse
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="space-y-1">
                                                <div class="text-sm font-semibold text-gray-900">{{ $reunion->fecha_reunion->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500 bg-blue-50 px-2 py-0.5 rounded-md inline-block">
                                                    <i class='bx bx-time mr-1'></i>{{ \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="flex flex-col items-start px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                                                    @forelse ($reunion->concejales as $concejal)
                                                        <div>
                                                            <i class='bx bx-user mr-1'></i>
                                                            {{$concejal->persona->nombre}} {{$concejal->persona->apellido}}
                                                        </div>
                                                    @empty
                                                        N/A
                                                    @endforelse
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                
                                                <!-- Ver -->
                                                <a href="{{ route('dashboard.reuniones.show', $reunion) }}" 
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                   title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                                </a>
                                                @if(Auth::user()->isSuperAdministrador())
                                                    <!-- Editar -->
                                                    <a href="{{ route('dashboard.reuniones.edit', $reunion) }}" 
                                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                       title="Editar">
                                                    <i class='bx bx-edit text-xl'></i>
                                                    </a>
                                                @endif
                                                
                                                <!-- Descargar PDF Individual -->
                                                <a href="{{ route('dashboard.reuniones.export.acta', $reunion) }}" 
                                                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                                   title="Descargar PDF">
                                                <i class='bx bx-download text-xl'></i>
                                                </a>
                                                
                                                @if(Auth::user()->isSuperAdministrador())
                                                    <!-- Eliminar -->
                                                    <form action="{{ route('dashboard.reuniones.destroy', $reunion) }}" 
                                                          method="POST" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta reunión?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                                title="Eliminar">
                                                    <i class='bx bx-trash text-xl'></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                         <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="space-y-1">
                                                <div class="text-sm text-zinc-800 bg-gray-100 py-1 px-2 rounded-xl">{{ $reunion->tituloEstatus()}}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                                                    <i class='bx bx-calendar-x text-4xl text-gray-400'></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay reuniones registradas</h3>
                                                <p class="text-gray-500 mb-6 max-w-md">Comience creando una nueva reunión para gestionar las actividades municipales y coordinar con las instituciones.</p>
                                                <a href="{{ route('dashboard.reuniones.create') }}" 
                                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                    <i class='bx bx-plus mr-2 text-lg'></i>
                                                    Crear Primera Reunión
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($reuniones->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                                {{ $reuniones->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.rbac>