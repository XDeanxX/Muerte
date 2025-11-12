

<div>
    <style>
    /* ... (Tus estilos se mantienen) ... */
    .birthday-today-row {
        background-color: #fffbeb !important; /* Fondo amarillo muy claro para hoy */
        border-left: 4px solid #f59e0b; /* Borde naranja */
    }
    .badge-hoy {
        background-color: #f59e0b; /* Naranja */ 
        color: white;
    }
    .badge-proximo {
        background-color: #bfdbfe; /* Azul claro */
        color: #1e40af; /* Azul oscuro */
    }
    </style>

    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx bx-user-circle text-white text-xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Trabajadores</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-4">
                    @if($cambiarVista === 'list')
                        <button wire:click="create('create')" {{-- el wire:click es una etiqueta livewire para llamar la fucnion create--}}
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class='bx bx-plus mr-2'></i>
                            Nueva Trabajador
                        </button>
                    @else
                        <button wire:click="regresarAlListado" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-list-ul mr-2'></i>
                            Volver al Listado
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if($cambiarVista === 'list' || $cambiarVista === 'destroy')
            <div class="bg-white rounded-xl shadow-xl p-2 mb-4">
                <div class="flex max-lg:flex-col lg:items-center lg:justify-between gap-4">
                    <div class="relative w-full sm:w-auto">
                        <input type="text" wire:model.live.debounce.300ms="buscador" placeholder="Buscar solicitud..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full lg:w-80">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{route('dashboard.superadmin.cargos')}}"
                                class="inline-flex items-center px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class='bx bx-briefcase mr-2'></i>
                            Cargos
                        </a>
                        <button wire:click="exportarTodosPdf" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                        title="Exportar todos los trabajadores a PDF">
                        <i class='bx bxs-file-pdf text-2xl'></i>
                        </button>
                        <button wire:click="exportTodosExcel" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                         title="Exportar a Excel">
                        <i class='bx bxs-file text-2xl'></i>
                       </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                        Lista de trabajadores 
                    </h2>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table id="trabajadoresTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class='bx bx-map-pin mr-2 text-base'></i> NOMBRE COMPLETO
                                    </div>
                                </th>
                                
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class='bx bx-id-card mr-2 text-base'></i> CÉDULA
                                    </div>
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-on-mobile">
                                    <div class="flex items-center">
                                        <i class='bx bx-phone mr-2 text-base'></i> TELÉFONO
                                    </div>
                                </th>
                                
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class='bx bx-briefcase mr-2 text-base'></i> CARGO
                                    </div>
                                </th>

                                 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class='bx bx-building-house mr-2 text-base'></i> ZONA
                                    </div>
                                </th>
                                
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class='bx bx-calendar mr-2 text-base'></i> CUMPLEAÑOS
                                    </div>
                                </th>
                                
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class='bx bx-cog mr-2 text-base'></i> ACCIONES
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($trabajadores as $trabajador)
                                @php
                                    $persona = $trabajador->persona;
                                    // La línea que calcula la fecha de nacimiento no necesita cambios
                                    $fechaNacimiento = $persona && $persona->nacimiento ? \Carbon\Carbon::parse($persona->nacimiento) : null;
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
                                    } elseif ($diasRestantes <= 30) {
                                        $rowClass = 'bg-blue-50';
                                    }
                                    
                                    $nombre_completo = trim($persona->nombre . ' ' . $persona->segundo_nombre . ' ' . $persona->apellido . ' ' . $persona->segundo_apellido);
                                @endphp
                            
                                <tr class="hover:bg-gray-50 transition {{ $rowClass }}">
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if($diasRestantes === 0)
                                            <span class="text-yellow-700 font-bold">🎂 {{ $nombre_completo }}</span>
                                        @elseif($diasRestantes <= 7)
                                            <span class="text-blue-700">🎁 {{ $nombre_completo }}</span>
                                        @else
                                            {{ $nombre_completo }}
                                        @endif
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $persona->nacionalidad === 1 ? 'V' : 'E' }}-{{ $persona->cedula }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 hide-on-mobile">
                                        {{ $persona->telefono ?? '—' }}
                                    </td>

                                    {{-- CARGO --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 bg-blue-200 text-blue-700 rounded-full text-xs font-medium">
                                            {{ $trabajador->cargo->descripcion ?? 'Sin Cargo' }}
                                        </span>
                                    </td>

                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 hide-on-mobile">
                                            {{ $trabajador->zona_trabajo}}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex items-center gap-2">
                                            @if($fechaNacimiento)
                                                <i class='bx bx-calendar-event text-base'></i> {{ $fechaNacimiento->format('d/m/Y') }}
                                            @else
                                                —
                                            @endif

                                            @if($diasRestantes === 0)
                                                <span class="px-2 py-0.5 text-xs rounded-full badge-hoy">¡HOY!</span>
                                            @elseif($diasRestantes <= 30)
                                                <span class="px-2 py-0.5 text-xs rounded-full badge-proximo">
                                                    En {{ $diasRestantes }} días
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-1">
                                            {{-- Ver Detalles (Ojo/Visor) --}}
                                            <button wire:click="show('show', {{$trabajador->persona_cedula}})"
                                               class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors"
                                               title="Ver detalles">
                                                <i class='bx bx-show text-xl'></i>
                                            </button>

                                            {{-- Editar (Lápiz) --}}
                                            <button wire:click="edit('edit', {{$trabajador->persona_cedula}})"
                                               class="p-2 rounded-full text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 transition-colors"
                                               title="Editar">
                                                <i class='bx bx-edit text-xl'></i>
                                            </button>

                                            <button wire:click="destroy('destroy', {{$trabajador->persona_cedula}})"
                                                class="p-2 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors"
                                                title="Eliminar">
                                                <i class='bx bx-trash text-xl'></i>
                                            </button>
                                            
                                            <button wire:click="exportPdf({{$trabajador->persona_cedula}})"
                                                class="p-2 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors"
                                                title="Descargar Ficha">
                                                <i class='bx bx-download text-xl'></i> 
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class='bx bx-user-x text-6xl mb-3'></i>
                                            <p class="text-lg font-medium">No se encontraron trabajadores</p>
                                            <p class="text-sm">Intenta ajustar tu búsqueda o crea un nuevo trabajador</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $trabajadores->links() }}
                    </div>
                </div>
            </div>
        @endif

        @if($cambiarVista === 'create')
            <livewire:dashboard.trabajadores.create>
        @endif

        @if($cambiarVista === 'edit')
            <livewire:dashboard.trabajadores.edit :persona_cedula="$persona_cedula">
        @endif

        @if($cambiarVista === 'show')
            <livewire:dashboard.trabajadores.show :persona_cedula="$persona_cedula">
        @endif

        @if($cambiarVista === 'destroy')
            <div class="fixed inset-0 bg-black/10 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-trash text-red-600 text-xl'></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Confirmar Eliminación</h3>
                                <p class="text-sm text-gray-600">Esta acción no se puede deshacer</p>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 mb-6">
                            ¿Estás seguro de que deseas eliminar esta trabajador? Se perderán todos los datos asociados.
                        </p>
                        <p class="text-sm text-gray-400 mb-6">Trabajador: {{ $persona_cedula->persona->nombre }} {{ $persona_cedula->persona->apellido }} {{ $persona_cedula->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{ $persona_cedula->persona->cedula }}, {{ $persona_cedula->cargo->descripcion }}</p>
                        
                        <div class="flex justify-end space-x-4">
                            <button wire:click="regresarAlListado" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button wire:click="destroyDefinitivo({{$persona_cedula->persona_cedula}})" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div id="cumpleanos-toast" class="fixed top-2 right-5 z-50 p-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-xl shadow-xl border-yellow-300 border-2" style="display: none; opacity: 0; transform: translateX(100%); transition: all 0.5s ease-in-out;">
        <div class="flex items-center">
            <i class="fas fa-birthday-cake text-3xl mr-3 text-white birthday-icon-pulse"></i>
            <div class="flex-1">
                <p class="font-bold text-lg" id="toast-title">¡Feliz Cumpleaños!</p>
                <p class="text-sm" id="toast-message"></p>
            </div>
            <button onclick="hideToast()" class="ml-4 text-white hover:text-yellow-200 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
        <div class="absolute -bottom-1 -left-1 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
    </div>
</div>
