<div class="min-h-screen bg-gray-50">
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx bx-book-content text-white text-xl'></i>
                        </div>
                        <div>   
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Concejales</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($deleteConcejal)
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
                        ¿Estás seguro de que deseas eliminar este concejal? Se perderán todos los datos asociados.
                    </p>
                    <p class="text-sm text-gray-400 mb-6">Concejal: {{ $deleteConcejal->persona->nombre }} {{ $deleteConcejal->persona->apellido }}
                        - {{ $deleteConcejal->cargo_concejal }}
                    </p>
                    
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cancelDelete" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="deleteConcejalDefinitive" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- caocejal List -->
        @if($openForm === 'list')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 lg:order-2">
                <div class="">
                    <div class="flex max-lg:flex-col lg:items-center lg:justify-between mb-6">
                        <h2 class="text-md lg:text-xl max-md:text-md font-semibold text-gray-900">
                            <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                            Todas los Concejales
                            
                        </h2>
                        <div class="relative w-full sm:w-auto max-lg:mt-4">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar concejal..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto max-lg:pb-4">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    {{-- wire:click="orden('persona.nombre')" --}}>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class='bx bx-user mr-2'></i>
                                            Nombre y Apellido
                                        </div>
                                        @if ($sort == 'persona.nombre')
                                            @if ($direction == 'asc')
                                                <i class='bx bx-caret-up mr-2'></i>
                                            @else
                                                <i class='bx bx-caret-down mr-2'></i>
                                            @endif
                                        @else
                                            <i class='bx bx-carets-up-down mr-2'></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    wire:click="orden('cargo_concejal')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class='bx bx-book-content mr-2'></i>
                                            Cargo
                                        </div>
                                        @if ($sort == 'cargo_concejal')
                                            @if ($direction == 'asc')
                                                <i class='bx bx-caret-up mr-2'></i>
                                            @else
                                                <i class='bx bx-caret-down mr-2'></i>
                                            @endif
                                        @else
                                            <i class='bx bx-carets-up-down mr-2'></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex justify-end items-center">
                                        <i class='bx bx-cog mr-2'></i>
                                        Acciones
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($concejalsRender as $concejalRender)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 flex flex-col whitespace-nowrap text-sm text-gray-900">
                                        <p class=" font-bold">{{ $concejalRender->persona->nombre }} {{ $concejalRender->persona->apellido }}</p>
                                        <p class="text-gray-700">{{ $concejalRender->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{$concejalRender->persona->cedula}}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        {{ $concejalRender->cargo_concejal }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- View Button -->
                                            <button wire:click="viewConcejal({{ $concejalRender->persona_cedula }})" 
                                            class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                            title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <button wire:click="editConcejal({{ $concejalRender->persona_cedula }})" 
                                            class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                            title="Editar">
                                                <i class='bx bx-edit text-xl'></i>
                                            </button>
                                        
                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete({{ $concejalRender->persona_cedula }})"
                                            class="p-2 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors focus:outline-none"
                                            title="Eliminar">
                                                <i class='bx bx-trash text-xl'></i>
                                            </button>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($concejalsRender->isEmpty() && $concejalsRender->currentPage() == 1)
                        <div class="text-center py-8">
                            <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay concejales</h3>
                            <p class="text-gray-500">
                                No se encontraron registros de concejales en el sistema
                            </p>
                        </div>
                    @else
                        <div class="mx-5">
                            {{ $concejalsRender->links() }}
                        </div>
                    @endif
                </div>
            </div>
            </div>
            <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:order-1">
                <div class="lg:p-8">
                    
                    @if($activeTab === 'create')
                        <!-- Form Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    @if ($editingConcejal)
                                        <button type="button" wire:click="resetForm"
                                            class="inline-flex items-center px-4 py-2  border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx {{ $editingConcejal ? 'bx-edit' : 'bx-plus' }}  text-blue-600 text-xl'></i>
                                        </div>
                                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                            {{ $editingConcejal ? 'Editar Concejal' : 'Nueva Concejal' }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($editingConcejal)
                                        <div class="px-2 lg:px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-xs lg:text-sm font-medium">
                                            <i class='bx bx-edit lg:mr-1 max-lg:text-xl'></i>
                                            <span class="max-lg:hidden">Editando</span>
                                            
                                        </div>
                                    @else
                                        <div class="px-2 lg:px-4 py-2 bg-green-100 text-green-800 rounded-full text-xs lg:text-sm font-medium">
                                            <i class='bx bx-plus lg:mr-1 max-lg:text-xl'></i>
                                            <span class="max-lg:hidden">Creando</span>
                                            
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="submit" class="space-y-8">
                            <div class="space-y-6">
                                @if ($editingConcejal)
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre y Apellido</label>
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <p class="text-gray-900 font-medium">{{ $persona['nombre'] }} {{$persona['apellido']}}</p>
                                            </div>
                                        </div>
                                        <div class="">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Cédula</label>
                                            <div class="p-3 bg-gray-50 rounded-lg">
                                                <p class="text-gray-900 font-medium">{{ $persona['nacionalidad'] === 1 ? 'V' : 'E' }}-{{$persona['cedula']}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <div class="flex items-center mb-4">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class='bx bx-book-content text-blue-600 text-xl'></i>
                                            </div>
                                            <div>
                                                <h3 class="text-md lg:text-lg font-bold text-gray-900">Cargo del Concejal</h3>
                                            </div>
                                        </div>
                                        <input type="text" wire:model.live="concejal.cargo_concejal" id="input-concejal-cargo_concejal" maxlength="100"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                placeholder="Nombre para el cargo" required>
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class='bx bx-info-circle mr-1'></i>
                                                <span>Caracteres: {{ strlen($concejal['cargo_concejal']) }}/100</span>
                                            </div>
                                            <div class="flex items-center">
                                                @if($concejal['cargo_concejal'])
                                                    @if(strlen($concejal['cargo_concejal']) <= 100)
                                                        <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                        <span class="text-green-600 text-sm font-medium">Válida</span>
                                                    @else
                                                        <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                        <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        @error('concejal.cargo_concejal') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                @else

                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <div class="flex items-center mb-4">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class='bx bx-search text-blue-600 text-xl'></i>
                                            </div>
                                            <div>
                                                <h3 class="text-md lg:text-lg font-bold text-gray-900">Buscar Cédula</h3>
                                            </div>
                                        </div>
                                        <input type="text" wire:model.live.debounce.300ms="buscarCedula" id="input-concejal-buscarCedula" maxlength="20"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                placeholder="Buscar cédula" required>
                                        @if ($mensajeCedula === 1)
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-5 mt-4">                                                
                                                <h4 class="font-semibold text-yellow-900 mb-2 flex items-center">
                                                    <i class='bx bx-info-circle mr-2'></i>
                                                    La persona ya es un concejal!
                                                </h4>
                                                <ul class="text-sm text-yellow-800 space-y-1 ml-6">
                                                    <li><strong>Datos:</strong></li>
                                                    <li>• {{ $persona['nombre'] }} {{$persona['apellido']}}</li>
                                                    <li>• {{ $persona['nacionalidad'] === 1 ? 'V' : 'E' }}-{{$persona['cedula']}}</li>
                                                </ul>
                                            </div>
                                        @elseif($mensajeCedula === 2)
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-5 mt-4">                                                
                                                <h4 class="font-semibold text-green-900 mb-2 flex items-center">
                                                    <i class='bx bx-info-circle mr-2'></i>
                                                    La persona existe! Asigne el cargo.
                                                </h4>
                                                <ul class="text-sm text-green-800 space-y-1 ml-6">
                                                    <li><strong>Datos:</strong></li>
                                                    <li>• {{ $persona['nombre'] }} {{$persona['apellido']}}</li>
                                                    <li>• {{ $persona['nacionalidad'] === 1 ? 'V' : 'E' }}-{{$persona['cedula']}}</li>
                                                </ul>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg mt-6">
                                                <div class="flex items-center mb-4">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class='bx bx-book-content  text-blue-600 text-xl'></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-md lg:text-lg font-bold text-gray-900">Cargo del Concejal</h3>
                                                    </div>
                                                </div>
                                                <input type="text" wire:model.live="concejal.cargo_concejal" id="input-concejal-cargo_concejal-create" maxlength="100"
                                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Nombre para el cargo" required>
                                                <div class="flex justify-between items-center mt-4">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class='bx bx-info-circle mr-1'></i>
                                                        <span>Caracteres: {{ strlen($concejal['cargo_concejal']) }}/100</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        @if($concejal['cargo_concejal'])
                                                            @if(strlen($concejal['cargo_concejal']) <= 100)
                                                                <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                                <span class="text-green-600 text-sm font-medium">Válida</span>
                                                            @else
                                                                <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                                <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                @error('concejal.cargo_concejal') 
                                                    <div class="flex items-center text-red-600 text-sm mt-2">
                                                        <i class='bx bx-error-circle mr-1'></i>
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @elseif($mensajeCedula === 3)
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-5 mt-4">                                                
                                                <h4 class="font-semibold text-red-900 mb-2 flex items-center">
                                                    <i class='bx bx-info-circle mr-2'></i>
                                                    La persona no se encuentra registrada en el sistema. Desea registrar a la persona?
                                                </h4>
                                            </div>
                                            <button wire:click="openFormPersona()" 
                                                class="w-full mt-4 px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
                                                Registrar Persona
                                            </button>
                                        @else
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                                                <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                                    <i class='bx bx-info-circle mr-2'></i>
                                                    ¿Qué sucederá?
                                                </h4>
                                                <ul class="text-sm text-blue-800 space-y-1 ml-6">
                                                    <li>• Si la persona <strong>existe y es concejal</strong> se notificará</li>
                                                    <li>• Si la persona <strong>existe y no es concejal</strong> se le asignara un cargo</li>
                                                    <li>• Si la persona <strong>no existe</strong> se registrara los datos de la persona</li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    
                                @endif

                            {{--  BOTON CREATE/EDIT --}}
                                @if($mensajeCedula === 2 || $editingConcejal)
                                    <div class="flex flex-col sm:flex-row items-center pt-8 border-t border-gray-200 space-y-4 sm:space-y-0 justify-between">
                                        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                            @if(!$editingConcejal)
                                                <button type="button" wire:click="resetForm" 
                                                        class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                                    <i class='bx bx-refresh'></i>
                                                </button>
                                            @endif
                                        </div>
                                        <button type="submit" 
                                                class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
                                            <i class='bx {{ $editingConcejal ? 'bx-save' : 'bx-check' }} mr-2'></i>
                                            {{ $editingConcejal ? 'Actualizar Concejal' : 'Crear Concejal' }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    @endif

                    @if ($activeTab === 'show')
                        <div class="pb-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button type="button" wire:click="resetForm"
                                        class="inline-flex items-center px-4 py-2  border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                        <i class='bx bx-arrow-back'></i>
                                    </button>
                                    <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                    Detalles del Concejal
                                    </h2>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button wire:click="editConcejal('{{$showConcejal->persona_cedula}}')" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Editar Concejal">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>
                                    <button wire:click="confirmDelete('{{$showConcejal->persona_cedula}}')" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Eliminar Concejal">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="lg:p-6 space-y-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre y Apellido</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $showConcejal->persona->nombre }} {{$showConcejal->persona->apellido}}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cédula</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $showConcejal->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{$showConcejal->persona->cedula}}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo del Concejal</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $showConcejal->cargo_concejal }}</p>
                                    </div>
                                </div>
                                <div class="flex grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Creación</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $showConcejal->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Editada</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $showConcejal->updated_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($openForm === 'create')
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class='bx bx-user text-blue-600 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Datos Personales
                    </h2>
                    <p class="text-sm text-gray-600">
                        Completa la información de la persona
                    </p>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">
            {{-- Nombres --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="concejal_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Primer Nombre *
                    </label>
                    <input type="text" id="concejal_nombre" wire:model="persona.nombre" required
                        placeholder="Ej: Juan"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.nombre') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_segundo_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Segundo Nombre
                    </label>
                    <input type="text" id="concejal_segundo_nombre" wire:model="persona.segundo_nombre"
                        placeholder="Ej: Carlos"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.segundo_nombre') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.segundo_nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_apellido" class="block text-sm font-medium text-gray-700 mb-2">
                        Primer Apellido *
                    </label>
                    <input type="text" id="concejal_apellido" wire:model="persona.apellido" required
                        placeholder="Ej: Rodríguez"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.apellido') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_segundo_apellido" class="block text-sm font-medium text-gray-700 mb-2">
                        Segundo Apellido
                    </label>
                    <input type="text" id="concejal_segundo_apellido" wire:model="persona.segundo_apellido"
                        placeholder="Ej: Pérez"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.segundo_apellido') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.segundo_apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Identificación --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Nacionalidad *
                    </label>
                    <select id="concejal_nacionalidad" wire:model="persona.nacionalidad" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.nacionalidad') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        @foreach ($nacionalidad as $nac)
                            <option value="{{ $nac->id }}">{{ $nac->Nacionalidad }}</option>
                        @endforeach
                    </select>
                    @error('persona.nacionalidad') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_cedula-readonly" class="block text-sm font-medium text-gray-700 mb-2">
                        Cédula *
                    </label>
                    <input type="text" id="concejal_cedula-readonly" wire:model="persona.cedula" readonly
                        class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed font-mono">
                    @error('persona.cedula') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_genero" class="block text-sm font-medium text-gray-700 mb-2">
                        Género *
                    </label>
                    <select id="concejal_genero" wire:model="persona.genero" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.genero') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        @foreach ($genero as $gen)
                            <option value="{{ $gen->id }}">{{ $gen->genero }}</option>
                        @endforeach
                    </select>
                    @error('persona.genero') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Contacto --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="concejal_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico *
                    </label>
                    <input type="email" id="concejal_email" wire:model="persona.email" required
                        placeholder="ejemplo@correo.com"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.email') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="concejal_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" id="concejal_telefono" wire:model="persona.telefono"
                        placeholder="0412-1234567"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('persona.telefono') border-red-500 @else border-gray-300 @enderror">
                    @error('persona.telefono') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Dirección --}}
            <div>
                <label for="concejal_direccion" class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección
                </label>
                <textarea id="concejal_direccion" wire:model="persona.direccion" rows="3"
                    placeholder="Dirección completa..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="concejal_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                        Cargo *
                    </label>
                    <input type="text" id="concejal_cargo" wire:model="concejal.cargo_concejal" required
                        placeholder="Administración"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                            @error('concejal.cargo_concejal') border-red-500 @else border-gray-300 @enderror">
                    @error('concejal.cargo_concejal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between pt-6 border-gray-300 border-t ">
                <button type="button" 
                    wire:click="closeFormPersona"
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <i class='bx bx-left-arrow-alt mr-2'></i>
                    Anterior
                </button>

                <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
                    Registrar Concejal
                    <i class='bx bx-right-arrow-alt ml-2'></i>
                </button>
            </div>
        </form>
    </div>
    @endif
</div>