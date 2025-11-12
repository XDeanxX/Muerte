<div class="min-h-screen bg-gray-50">
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx bx-building-house text-white text-xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Instituciones</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($deleteInstitucion)
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
                        ¿Estás seguro de que deseas eliminar esta institución? Se perderán todos los datos asociados.
                    </p>
                    <p class="text-sm text-gray-400 mb-6">Institucion: {{ $deleteInstitucion->titulo }}</p>
                    
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cancelDelete" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="deleteInstitucionDefinitive" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- institucion List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 lg:order-2">
                <div class="">
                    <div class="flex max-lg:flex-col lg:items-center lg:justify-between mb-6">
                        <h2 class="text-md lg:text-xl max-md:text-md font-semibold text-gray-900">
                            <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                            Todas las Instituciones
                            
                        </h2>
                        <div class="relative w-full sm:w-auto max-lg:mt-4">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar institución..."
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
                                    wire:click="orden('descripcion')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class='bx bx-info-circle mr-2'></i>
                                            Instituciones
                                        </div>
                                        @if ($sort == 'descripcion')
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
                            @foreach($institucionsRender as $institucionRender)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        {{ $institucionRender->titulo }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- View Button -->
                                            <button wire:click="viewInstitucion({{ $institucionRender->id }})" 
                                            class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                            title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <button wire:click="editInstitucion({{ $institucionRender->id }})" 
                                            class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                            title="Editar">
                                                <i class='bx bx-edit text-xl'></i>
                                            </button>
                                        
                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete({{ $institucionRender->id }})"
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
                    @if($institucionsRender->isEmpty() && $institucionsRender->currentPage() == 1)
                        <div class="text-center py-8">
                            <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay instituciones</h3>
                            <p class="text-gray-500">
                                No se encontraron registros de las instituciones en el sistema
                            </p>
                        </div>
                    @else
                        <div class="mx-5">
                            {{ $institucionsRender->links() }}
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
                                    @if ($editingInstitucion)
                                        <button type="button" wire:click="resetForm"
                                            class="inline-flex items-center px-4 py-2  border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx {{ $editingInstitucion ? 'bx-edit' : 'bx-plus' }}  text-blue-600 text-xl'></i>
                                        </div>
                                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                            {{ $editingInstitucion ? 'Editar Institución' : 'Nueva Institución' }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($editingInstitucion)
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
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx bx-building-house text-blue-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-md lg:text-lg font-bold text-gray-900">Nombre de la Institución</h3>
                                        </div>
                                    </div>
                                    <input type="text" wire:model.live="institucion.titulo" id="input-institucion-titulo" maxlength="100"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Nombre para la institución" required>
                                    <div class="flex justify-between items-center mt-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class='bx bx-info-circle mr-1'></i>
                                            <span>Caracteres: {{ strlen($institucion['titulo']) }}/100</span>
                                        </div>
                                        <div class="flex items-center">
                                            @if($institucion['titulo'])
                                                @if(strlen($institucion['titulo']) <= 100)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @else
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @error('institucion.titulo') 
                                        <div class="flex items-center text-red-600 text-sm mt-2">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <textarea type="text" wire:model.live="institucion.descripcion" id="input-institucion-descripcion" maxlength="100"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors mt-4"
                                            placeholder="Describe de que se trata la institución" required>
                                    </textarea>
                                    <div class="flex justify-between items-center mt-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class='bx bx-info-circle mr-1'></i>
                                            <span>Caracteres: {{ strlen($institucion['descripcion']) }}/100</span>
                                        </div>
                                        <div class="flex items-center">
                                            @if($institucion['descripcion'])
                                                @if(strlen($institucion['descripcion']) <= 100)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @else
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @error('institucion.descripcion') 
                                        <div class="flex items-center text-red-600 text-sm mt-2">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            {{--  BOTON CREATE/EDIT --}}
                                <div class="flex flex-col sm:flex-row items-center pt-8 border-t border-gray-200 space-y-4 sm:space-y-0 justify-between">
                                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                        @if(!$editingInstitucion)
                                            <button type="button" wire:click="resetForm" 
                                                    class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                                <i class='bx bx-refresh'></i>
                                            </button>
                                        @endif
                                    </div>
                                    <button type="submit" 
                                            class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
                                        <i class='bx {{ $editingInstitucion ? 'bx-save' : 'bx-check' }} mr-2'></i>
                                        {{ $editingInstitucion ? 'Actualizar Institución' : 'Crear Institución' }}
                                    </button>
                                </div>
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
                                    Detalles de la Institución
                                    </h2>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button wire:click="editInstitucion('{{$showInstitucion->id}}')" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Editar Institución">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>
                                    <button wire:click="confirmDelete('{{$showInstitucion->id}}')" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Eliminar Institución">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="lg:p-6 space-y-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Institución</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $showInstitucion->titulo }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $showInstitucion->descripcion }}</p>
                                    </div>
                                </div>
                                <div class="flex grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Creación</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $showInstitucion->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Editada</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $showInstitucion->updated_at->format('d/m/Y') }}</p>
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