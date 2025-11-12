<div class="min-h-screen bg-gray-50">
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx {{$cambiarVistaParroquia ? 'bx-buildings' : 'bx-group'}}  text-white text-xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{$cambiarVistaParroquia ? 'Gestión de Parroquia' : 'Gestión de Comunidades'}}</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($deleteCom || $deletePar)
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
                    
                    @if ($cambiarVistaParroquia)
                        <p class="text-gray-700 mb-6">
                            ¿Estás seguro de que deseas eliminar esta parroquia? Se perderán todos los datos asociados.
                        </p>
                        <p class="text-sm text-gray-400 mb-6">Parroquia: {{$deletePar->getParroquiaFormattedAttribute()}}</p>
                    @else
                        <p class="text-gray-700 mb-6">
                            ¿Estás seguro de que deseas eliminar esta comunidad? Se perderán todos los datos asociados.
                        </p>
                        <p class="text-sm text-gray-400 mb-6">Comunidad: {{ $deleteCom->comunidad }} - {{$deleteCom->getParroquiaFormattedAttribute()}}</p>
                    @endif
                    
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cancelDelete" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="deleteDefinitive" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- comunidad List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 lg:order-2">
                <div class="w-full grid grid-cols-2">
                    <div class="w-auto flex items-center justify-center">
                        <button wire:click="cambiarVista(0)" class="text-md lg:text-xl max-md:text-md font-semibold w-full py-2 px-full rounded-tl-xl cursor-pointer"
                        :class="{
                            'bg-white': @json(!$cambiarVistaParroquia),
                            'text-zinc-600 bg-gray-100 inset-shadow-xl hover:bg-gray-200 transition-colors': @json($cambiarVistaParroquia)
                        }">
                            <i class='bx bx-group text-blue-600 mr-2'></i>
                            Comunidades
                        </button>
                    </div>
                    <div class="w-auto flex items-center justify-center">
                        <button wire:click="cambiarVista(1)" class="text-md lg:text-xl max-md:text-md font-semibold w-full py-2 px-full rounded-tr-xl cursor-pointer"
                        :class="{
                            'bg-white': @json($cambiarVistaParroquia),
                            'text-zinc-600 bg-gray-100 inset-shadow-xl hover:bg-gray-200 transition-colors': @json(!$cambiarVistaParroquia),
                        }">
                            <i class='bx bx-buildings text-blue-600 mr-2'></i>
                            Parroquias
                        </button>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="relative w-full sm:w-auto">
                            @if($cambiarVistaParroquia)
                                <input type="text" wire:model.live.debounce.300ms="searchPar" placeholder="Buscar parroquia..." id="searh-parroquia"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            @else
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar comunidad..." id="searh-comunidad"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto max-lg:pb-4">
                        @if ($cambiarVistaParroquia)
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            wire:click="orden('parroquia')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-category mr-2'></i>
                                                    Parroquia
                                                </div>
                                                @if ($sortPar == 'parroquia')
                                                    @if ($directionPar == 'asc')
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
                                    @foreach($parroquiasRender as $parroquiaRender)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold w-20 truncate" title="{{ $parroquiaRender->getParroquiaFormattedAttribute() }}">
                                                {{ $parroquiaRender->getParroquiaFormattedAttribute() }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- View Button -->
                                                    <button wire:click="view({{ $parroquiaRender->id}})" 
                                                    class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                                    title="Ver detalles">
                                                        <i class='bx bx-search-alt-2 text-xl'></i>
                                                    </button>
                                                    
                                                    <!-- Edit Button -->
                                                    <button wire:click="edit({{ $parroquiaRender->id }})" 
                                                    class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                                    title="Editar">
                                                        <i class='bx bx-edit text-xl'></i>
                                                    </button>
                                                
                                                    <!-- Delete Button -->
                                                    <button wire:click="confirmDelete({{ $parroquiaRender->id }})"
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
                            @if($parroquiasRender->isEmpty() && $parroquiasRender->currentPage() == 1)
                                <div class="text-center py-8">
                                    <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay parroquias</h3>
                                    <p class="text-gray-500">
                                        No se encontraron registros de parroquias en el sistema
                                    </p>
                                </div>
                            @else
                                <div class="mx-5">
                                    {{ $parroquiasRender->links() }}
                                </div>
                            @endif
                            
                        @else
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            wire:click="orden('comunidad')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-sitemap mr-2'></i>
                                                    Comunidades
                                                </div>
                                                @if ($sort == 'comunidad')
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
                                            wire:click="orden('parroquia')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-category mr-2'></i>
                                                    Parroquia
                                                </div>
                                                @if ($sort == 'parroquia')
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
                                    @foreach($comunidadesRender as $comunidadRender)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold w-20 truncate" title="{{ $comunidadRender->comunidad }}">
                                                {{ $comunidadRender->comunidad }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-clip w-20 truncate" title="{{ $comunidadRender->getParroquiaFormattedAttribute() }}">
                                                {{ $comunidadRender->getParroquiaFormattedAttribute() }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- View Button -->
                                                    <button wire:click="view({{ $comunidadRender->id}})" 
                                                    class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                                    title="Ver detalles">
                                                        <i class='bx bx-search-alt-2 text-xl'></i>
                                                    </button>
                                                    
                                                    <!-- Edit Button -->
                                                    <button wire:click="edit({{ $comunidadRender->id }})" 
                                                    class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                                    title="Editar">
                                                        <i class='bx bx-edit text-xl'></i>
                                                    </button>
                                                
                                                    <!-- Delete Button -->
                                                    <button wire:click="confirmDelete({{ $comunidadRender->id }})"
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
                            @if($comunidadesRender->isEmpty() && $comunidadesRender->currentPage() == 1)
                                <div class="text-center py-8">
                                    <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay comunidades</h3>
                                    <p class="text-gray-500">
                                        No se encontraron registros de comunidades en el sistema
                                    </p>
                                </div>
                            @else
                                <div class="mx-5">
                                    {{ $comunidadesRender->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
            </div>
            <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:order-1">
                <div class="lg:p-8">
                    
                    @if((!$cambiarVistaParroquia && $activeTab === 'create') || ($cambiarVistaParroquia && $activeTabPar === 'create'))
                        <!-- Form Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    @if ($cambiarVistaParroquia && $editingPar)
                                        <button type="button" wire:click="resetFormPar"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @elseif($editingCom)
                                        <button type="button" wire:click="resetForm"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx 
                                                @if ($cambiarVistaParroquia)
                                                    {{ $editingPar ? 'bx-edit' : 'bx-plus'}}
                                                @else
                                                    {{ $editingCom ? 'bx-edit' : 'bx-plus' }}
                                                @endif
                                                 text-blue-600 text-xl'></i>
                                        </div>
                                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                            @if ($cambiarVistaParroquia)
                                                {{ $editingPar ? 'Editar Parroquia' : 'Nueva Parroquia'}}
                                            @else
                                                {{ $editingCom ? 'Editar Comunidad' : 'Nueva Comunidad' }}
                                            @endif
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($cambiarVistaParroquia)
                                        @if($editingPar)
                                            <div class="px-2 lg:px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                <i class='bx bx-edit lg:mr-1'></i>
                                                <span class="max-lg:hidden">Editando</span>
                                            </div>
                                        @else
                                            <div class="px-2 lg:px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <i class='bx bx-plus lg:mr-1'></i>
                                                <span class="max-lg:hidden">Creando</span>
                                            </div>
                                        @endif
                                    @else
                                        @if($editingCom)
                                            <div class="px-2 lg:px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                <i class='bx bx-edit lg:mr-1'></i>
                                                <span class="max-lg:hidden">Editando</span>
                                            </div>
                                        @else
                                            <div class="px-2 lg:px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <i class='bx bx-plus lg:mr-1'></i>
                                                <span class="max-lg:hidden">Creando</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="submit" class="space-y-8">
                            <div class="space-y-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx {{$cambiarVistaParroquia ? 'bx-buildings' : 'bx-group'}}  text-blue-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">Nombre de la {{ $cambiarVistaParroquia ? 'Parroquia' : 'Comunidad' }}</h3>
                                        </div>
                                    </div>
                                    @if($cambiarVistaParroquia)
                                        <input type="text" wire:model.live="parroquia.parroquia" id="text-parroquia" maxlength="50"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Nombre para la parroquia" required>
                                    @else
                                        <input type="text" wire:model.live="comunidad.comunidad" id="text-comunidad" maxlength="50"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Nombre para la comunidad" required>

                                    @endif
                                    <div class="flex justify-between items-center mt-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class='bx bx-info-circle mr-1'></i>
                                            <span>Caracteres: {{ $cambiarVistaParroquia ? strlen($parroquia['parroquia']) : strlen($comunidad['comunidad']) }}/50</span>
                                        </div>
                                        <div class="flex items-center">
                                            @if($comunidad['comunidad'] && !$cambiarVistaParroquia)
                                                @if(strlen($comunidad['comunidad']) <= 50 && strlen($comunidad['comunidad']) > 0)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @elseif(strlen($comunidad['comunidad']) >= 51)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @endif
                                            @elseif($parroquia['parroquia'] && $cambiarVistaParroquia)
                                                @if(strlen($parroquia['parroquia']) <= 50 && strlen($parroquia['parroquia']) > 0)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @elseif(strlen($parroquia['parroquia']) >= 51)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @if (!$cambiarVistaParroquia)
                                        @error('comunidad.comunidad') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @else
                                        @error('parroquia.parroquia') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @endif

                                    @if (!$cambiarVistaParroquia)
                                        <div class="mt-4">
                                            <div class="flex items-center mb-4">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class='bx bx-buildings text-blue-600 text-xl'></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900">Parroquia</h3>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">Seleccione la parroquia al que le pernecera la comunidad</p>
                                            <select wire:model.live="comunidad.parroquia" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                                <option value="" disabled selected>Seleccione una parroquia</option>
                                                @foreach ($parroquias as $parroquia)
                                                    <option value="{{$parroquia->parroquia}}">{{$parroquia->getParroquiaFormattedAttribute()}}</option>
                                                @endforeach
                                            </select>
                                            @error('comunidad.parroquia') 
                                                <div class="flex items-center text-red-600 text-sm mt-1">
                                                    <i class='bx bx-error-circle mr-1'></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                            {{--  BOTON CREATE/EDIT --}}
                                <div class="flex flex-col sm:flex-row items-center pt-8 border-t border-gray-200 space-y-4 sm:space-y-0 justify-between">
                                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                        @if($cambiarVistaParroquia)
                                            <button type="button" wire:click="resetFormPar" class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                                <i class='bx bx-refresh'></i>
                                            </button>
                                        @else
                                            <button type="button" wire:click="resetForm" class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                                <i class='bx bx-refresh'></i>
                                            </button>
                                        @endif
                                    </div>
                                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
                                        <i class='bx 
                                                @if ($cambiarVistaParroquia)
                                                    {{ $editingPar ? 'bx-save' : 'bx-check'}}
                                                @else
                                                    {{ $editingCom ? 'bx-save' : 'bx-check' }}
                                                @endif
                                                mr-2'>
                                        </i>
                                        @if ($cambiarVistaParroquia)
                                            {{ $editingPar ? 'Actualizar Parroquia' : 'Crear Parroquia'}}
                                        @else
                                            {{ $editingCom ? 'Actualizar Comunidad' : 'Crear Comunidad' }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    @if((!$cambiarVistaParroquia && $activeTab === 'show') || ($cambiarVistaParroquia && $activeTabPar === 'show'))
                        <div class="pb-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button type="button" wire:click="{{$cambiarVistaParroquia ? 'resetFormPar' : 'resetForm'}}"
                                        class="inline-flex items-center px-4 py-2  border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                        <i class='bx bx-arrow-back'></i>
                                    </button>
                                    <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                    Detalles de la {{$cambiarVistaParroquia ? 'Parroquia' : 'Comunidad'}}
                                    </h2>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button wire:click="edit({{$cambiarVistaParroquia ? $showPar->id : $showCom->id}})" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Editar Comunidad">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>
                                    <button wire:click="confirmDelete({{$cambiarVistaParroquia ? $showPar->id : $showCom->id}})" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Eliminar Comunidad">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="lg:p-6 space-y-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{$cambiarVistaParroquia ? 'Parroquia' : 'Comunidad'}}</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $cambiarVistaParroquia ? $showPar->getParroquiaFormattedAttribute() : $showCom->comunidad }}</p>
                                    </div>
                                </div>
                                @if(!$cambiarVistaParroquia)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Parroquia</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900 font-medium">{{ $showCom->getParroquiaFormattedAttribute() }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Creación</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $cambiarVistaParroquia ? $showPar->created_at->format('d/m/Y') : $showCom->created_at->format('d/m/Y')}}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Editada</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $cambiarVistaParroquia ? $showPar->updated_at->format('d/m/Y') : $showCom->updated_at->format('d/m/Y')}}</p>
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
</div>
