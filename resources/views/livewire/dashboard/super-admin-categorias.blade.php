<div class="min-h-screen bg-gray-50">
      <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx {{$cambiarVistaCategorias ? 'bx-category' : 'bx-sitemap'}}  text-white text-xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{$cambiarVistaCategorias ? 'Gestión de Categorías' : 'Gestión de Subcategorías'}}</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($deleteSub || $deleteCat)
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
                    
                    @if ($cambiarVistaCategorias)
                        <p class="text-gray-700 mb-6">
                            ¿Estás seguro de que deseas eliminar esta categoía? Se perderán todos los datos asociados.
                        </p>
                        <p class="text-sm text-gray-400 mb-6">Categoría: {{$deleteCat->getCategoriaFormattedAttribute()}}</p>
                    @else
                        <p class="text-gray-700 mb-6">
                            ¿Estás seguro de que deseas eliminar esta subcategoria? Se perderán todos los datos asociados.
                        </p>
                        <p class="text-sm text-gray-400 mb-6">Subcategoria: {{ $deleteSub->getSubcategoriaFormattedAttribute() }} - {{$deleteSub->getCategoriaFormattedAttribute()}}</p>
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
            <!-- subcategoria List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div>
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 max-lg:order-2">
                <div class="w-full grid grid-cols-2">
                    <div class="w-auto flex items-center justify-center">
                        <button wire:click="cambiarVista(0)" class="text-md lg:text-xl font-semibold w-full py-2 rounded-tl-xl cursor-pointer"
                        :class="{
                            'bg-white': @json(!$cambiarVistaCategorias),
                            'text-zinc-600 bg-gray-100 inset-shadow-xl hover:bg-gray-200 transition-colors': @json($cambiarVistaCategorias)
                        }">
                            <i class='bx bx-sitemap text-blue-600 mr-2'></i>
                            Subcategorías
                        </button>
                    </div>
                    <div class="w-auto flex items-center justify-center">
                        <button wire:click="cambiarVista(1)" class="text-md lg:text-xl font-semibold w-full py-2 ounded-tr-xl cursor-pointer"
                        :class="{
                            'bg-white': @json($cambiarVistaCategorias),
                            'text-zinc-600 bg-gray-100 inset-shadow-xl hover:bg-gray-200 transition-colors': @json(!$cambiarVistaCategorias),
                        }">
                            <i class='bx bx-category text-blue-600 mr-2'></i>
                            Categorías
                        </button>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="relative w-full sm:w-auto">
                            @if($cambiarVistaCategorias)
                                <input type="text" wire:model.live.debounce.300ms="searchCat" placeholder="Buscar categoría..." id="searh-categorias"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            @else
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar subategoría..." id="searh-subcategorias"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto max-lg:pb-4">
                        @if ($cambiarVistaCategorias)
                            <table class="w-full divide-y divide-gray-200" for="pagination-categorias">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            wire:click="orden('categoria')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-category mr-2'></i>
                                                    Categoría
                                                </div>
                                                @if ($sortCat == 'categoria')
                                                    @if ($directionCat == 'asc')
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
                                    @foreach($categoriasRender as $categoriaRender)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold w-20 truncate" title="{{ $categoriaRender->getCategoriaFormattedAttribute() }}">
                                                {{ $categoriaRender->getCategoriaFormattedAttribute() }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- View Button -->
                                                    <button wire:click="view({{ $categoriaRender->id}})" 
                                                    class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                                    title="Ver detalles">
                                                        <i class='bx bx-search-alt-2 text-xl'></i>
                                                    </button>
                                                    
                                                    <!-- Edit Button -->
                                                    <button wire:click="edit({{ $categoriaRender->id }})" 
                                                    class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                                    title="Editar">
                                                        <i class='bx bx-edit text-xl'></i>
                                                    </button>
                                                
                                                    <!-- Delete Button -->
                                                    <button wire:click="confirmDelete({{ $categoriaRender->id }})"
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
                            @if($categoriasRender->isEmpty() && $categoriasRender->currentPage() == 1)
                                <div class="text-center py-8">
                                    <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay categorías</h3>
                                    <p class="text-gray-500">
                                        No se encontraron registros de categorías en el sistema
                                    </p>
                                </div>
                            @else
                                <div class="mx-5" id="pagination-categorias">
                                    {{ $categoriasRender->links() }}
                                </div>
                            @endif
                            
                        @else
                            <table class="w-full divide-y divide-gray-200" for="pagination-sub_scategorias">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                            wire:click="orden('subcategoria')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-sitemap mr-2'></i>
                                                    Subcategoría
                                                </div>
                                                @if ($sort == 'subcategoria')
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
                                            wire:click="orden('categoria')">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <i class='bx bx-category mr-2'></i>
                                                    Categoría
                                                </div>
                                                @if ($sort == 'categoria')
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
                                    @foreach($subcategoriasRender as $subcategoriaRender)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold w-20 truncate" title="{{ $subcategoriaRender->getSubcategoriaFormattedAttribute() }}">
                                                {{ $subcategoriaRender->getSubcategoriaFormattedAttribute() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-clip w-20 truncate" title={{ $subcategoriaRender->getCategoriaFormattedAttribute() }}">
                                                {{ $subcategoriaRender->getCategoriaFormattedAttribute() }}
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- View Button -->
                                                    <button wire:click="view({{ $subcategoriaRender->id}})" 
                                                    class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors focus:outline-none"
                                                    title="Ver detalles">
                                                        <i class='bx bx-search-alt-2 text-xl'></i>
                                                    </button>
                                                    
                                                    <!-- Edit Button -->
                                                    <button wire:click="edit({{ $subcategoriaRender->id }})" 
                                                    class="p-2 rounded-full text-gray-600 hover:text-indigo-900 hover:bg-gray-100 transition-colors focus:outline-none"
                                                    title="Editar">
                                                        <i class='bx bx-edit text-xl'></i>
                                                    </button>
                                                
                                                    <!-- Delete Button -->
                                                    <button wire:click="confirmDelete({{ $subcategoriaRender->id }})"
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
                            @if($subcategoriasRender->isEmpty() && $subcategoriasRender->currentPage() == 1)
                                <div class="text-center py-8">
                                    <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay subcategorías</h3>
                                    <p class="text-gray-500">
                                        No se encontraron registros de subcategorías en el sistema
                                    </p>
                                </div>
                            @else
                                <div class="mx-5" id="pagination-sub_scategorias">
                                    {{ $subcategoriasRender->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
            </div>
            <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 max-lg:order-1">
                <div class="lg:p-8">
                    
                    @if((!$cambiarVistaCategorias && $activeTab === 'create') || ($cambiarVistaCategorias && $activeTabCat === 'create'))
                        <!-- Form Header -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    @if ($cambiarVistaCategorias && $editingCat)
                                        <button type="button" wire:click="resetFormCat"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @elseif($editingSub)
                                        <button type="button" wire:click="resetForm"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class='bx bx-arrow-back'></i>
                                        </button>
                                    @endif
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx 
                                                @if ($cambiarVistaCategorias)
                                                    {{ $editingCat ? 'bx-edit' : 'bx-plus'}}
                                                @else
                                                    {{ $editingSub ? 'bx-edit' : 'bx-plus' }}
                                                @endif
                                                 text-blue-600 text-xl'></i>
                                        </div>
                                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                            @if ($cambiarVistaCategorias)
                                                {{ $editingCat ? 'Editar Categoría' : 'Nueva Categoría'}}
                                            @else
                                                {{ $editingSub ? 'Editar Subcategoría' : 'Nueva Subcategoría' }}
                                            @endif
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($cambiarVistaCategorias)
                                        @if($editingCat)
                                            <div class="px-2 lg:px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-xs lg:text-sm font-medium">
                                                <i class='bx bx-edit lg:mr-1'></i>
                                                <span class="max-lg:hidden">Editando</span>
                                                
                                            </div>
                                        @else
                                            <div class="px-2 lg:px-4 py-2 bg-green-100 text-green-800 rounded-full text-xs lg:text-sm font-medium">
                                                <i class='bx bx-plus lg:mr-1'></i>
                                                <span class="max-lg:hidden">Creando</span>
                                                
                                            </div>
                                        @endif
                                    @else
                                        @if($editingSub)
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
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="submit" class="space-y-8">
                            <div class="space-y-6">
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx {{$cambiarVistaCategorias ? 'bx-category' : 'bx-sitemap'}} text-blue-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-md lg:text-lg font-bold text-gray-900">Nombre de la {{ $cambiarVistaCategorias ? 'Categoría' : 'Subcategoría' }}</h3>
                                        </div>
                                    </div>
                                        @if($cambiarVistaCategorias)
                                            <input type="text" wire:model.live="categoria.categoria" id="text-categoria" maxlength="50"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                placeholder="Nombre para la categoria" required>
                                        @else
                                            <input type="text" wire:model.live="subcategoria.subcategoria" id="text-subcategoria" maxlength="50"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                placeholder="Nombre para la subcategoria" required>
                                        @endif
                                        
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class='bx bx-info-circle mr-1'></i>
                                                <span>Caracteres: {{ $cambiarVistaCategorias ? strlen($categoria['categoria']) : strlen($subcategoria['subcategoria']) }}/50</span>
                                            </div>
                                            <div class="flex items-center">
                                                @if($subcategoria['subcategoria'] && !$cambiarVistaCategorias)
                                                    @if(strlen($subcategoria['subcategoria']) <= 50 && strlen($subcategoria['subcategoria']) > 0)
                                                        <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                        <span class="text-green-600 text-sm font-medium">Válida</span>
                                                    @elseif(strlen($subcategoria['subcategoria']) >= 51)
                                                        <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                        <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                    @endif
                                                @elseif($categoria['categoria'] && $cambiarVistaCategorias)
                                                    @if(strlen($categoria['categoria']) <= 50 && strlen($categoria['categoria']) > 0)
                                                        <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                        <span class="text-green-600 text-sm font-medium">Válida</span>
                                                    @elseif(strlen($categoria['categoria']) >= 51)
                                                        <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                        <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                    @if (!$cambiarVistaCategorias)
                                        @error('subcategoria.subcategoria') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @else
                                        @error('categoria.categoria') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @endif
                                    
                                    @if($cambiarVistaCategorias)
                                    <textarea wire:model.live="categoria.descripcion" rows="8" id="textarea-categoria" maxlength="1000"
                                            class="w-full p-4 mt-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Describa de que se trata la categoría" required></textarea>
                                    @else
                                    <textarea wire:model.live="subcategoria.descripcion" rows="8" id="textarea-subcategoria" maxlength="1000"
                                            class="w-full p-4 mt-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Describa de que se trata la categoría" required></textarea>

                                    @endif

                                    
                                    <div class="flex justify-between items-center mt-4">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class='bx bx-info-circle mr-1'></i>
                                            <span>Caracteres: {{ $cambiarVistaCategorias ? strlen($categoria['descripcion']) : strlen($subcategoria['descripcion']) }}/1000 (mínimo 10)</span>
                                        </div>
                                        <div class="flex items-center">
                                            @if($subcategoria['descripcion'] && !$cambiarVistaCategorias)
                                                @if(strlen($subcategoria['descripcion']) <= 1000  && strlen($subcategoria['descripcion']) >= 10)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @elseif(strlen($subcategoria['descripcion']) >= 1001)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @elseif(strlen($subcategoria['descripcion']) <= 9 && strlen($subcategoria['descripcion']) >= 1)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy Corto</span>
                                                @endif
                                            @elseif($categoria['descripcion'] && $cambiarVistaCategorias)
                                                @if(strlen($categoria['descripcion']) <= 1000 && strlen($categoria['descripcion']) >= 10)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @elseif(strlen($categoria['descripcion']) >= 1001)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @elseif(strlen($categoria['descripcion']) <= 9 && strlen($categoria['descripcion']) >= 1)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy Corto</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if (!$cambiarVistaCategorias)
                                        @error('subcategoria.descripcion') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @else
                                        @error('categoria.descripcion') 
                                            <div class="flex items-center text-red-600 text-sm mt-2">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    @endif

                                    @if (!$cambiarVistaCategorias)
                                        <div class="mt-4">
                                            <div class="flex items-center mb-4">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class='bx bx-category text-blue-600 text-xl'></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-md lg:text-lg font-bold text-gray-900">Categoría</h3>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">Seleccione la categoría al que le pernecera la subcategoría</p>
                                            <select wire:model.live="subcategoria.categoria" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                                <option value="" disabled selected>Seleccione una categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{$categoria->categoria}}">{{$categoria->getCategoriaFormattedAttribute()}}</option>
                                                @endforeach
                                            </select>
                                            @error('subcategoria.categoria') 
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
                                        @if($cambiarVistaCategorias)
                                            <button type="button" wire:click="resetFormCat" class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
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
                                                @if ($cambiarVistaCategorias)
                                                    {{ $editingCat ? 'bx-save' : 'bx-check'}}
                                                @else
                                                    {{ $editingSub ? 'bx-save' : 'bx-check' }}
                                                @endif
                                                mr-2'>
                                        </i>
                                        @if ($cambiarVistaCategorias)
                                            {{ $editingCat ? 'Actualizar Categoría' : 'Crear Categoría'}}
                                        @else
                                            {{ $editingSub ? 'Actualizar Subcategoría' : 'Crear Subcategoría' }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                                    </div>
                    @endif

                    @if((!$cambiarVistaCategorias && $activeTab === 'show') || ($cambiarVistaCategorias && $activeTabCat === 'show'))
                        <div class="pb-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button type="button" wire:click="{{$cambiarVistaCategorias ? 'resetFormCat' : 'resetForm'}}"
                                        class="inline-flex items-center px-4 py-2  border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                        <i class='bx bx-arrow-back'></i>
                                    </button>
                                    <h2 class="text-lg lg:text-2xl font-bold text-gray-900">
                                    Detalles de la {{$cambiarVistaCategorias ? 'Categoría' : 'Subcategoría'}}
                                    </h2>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button wire:click="edit({{$cambiarVistaCategorias ? $showCat->id : $showSub->id}})" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Editar Subcategoría">
                                        <i class='bx bx-edit text-xl'></i>
                                    </button>
                                    <button wire:click="confirmDelete({{$cambiarVistaCategorias ? $showCat->id : $showSub->id}})" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Eliminar Subcategoría">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="lg:p-6 space-y-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{$cambiarVistaCategorias ? 'Categoría' : 'Subcategoría'}}</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $cambiarVistaCategorias ? $showCat->getCategoriaFormattedAttribute() : $showSub->getSubcategoriaFormattedAttribute() }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900 font-medium">{{ $cambiarVistaCategorias ? $showCat->descripcion : $showSub->descripcion}}</p>
                                    </div>
                                </div>
                                @if(!$cambiarVistaCategorias)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900 font-medium">{{ $showSub->getCategoriaFormattedAttribute() }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Creación</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $cambiarVistaCategorias ? $showCat->created_at->format('d/m/Y') : $showSub->created_at->format('d/m/Y')}}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Editada</label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $cambiarVistaCategorias ? $showCat->updated_at->format('d/m/Y') : $showSub->updated_at->format('d/m/Y')}}</p>
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

