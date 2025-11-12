<div class="min-h-screen bg-gray-50">
    <style>
        @supports(-webkit-appearance: none) or (-moz-appearance: none) {
            input[type='checkbox'],
            input[type='radio'] {
                --active: #275EFE;
                --active-inner: #fff;
                --focus: 2px rgba(39, 94, 254, .3);
                --border: #BBC1E1;
                --border-hover: #275EFE;
                --background: #fff;
                --disabled: #F6F8FF;
                --disabled-inner: #E1E6F9;
                -webkit-appearance: none;
                -moz-appearance: none;
                height: 21px;
                outline: none;
                display: inline-block;
                vertical-align: top;
                position: relative;
                margin: 0;
                border: 1px solid var(--bc, var(--border));
                background: var(--b, var(--background));
                transition: background .3s, border-color .3s, box-shadow .2s;
                &:after {
                content: '';
                display: block;
                left: 0;
                top: 0;
                position: absolute;
                transition: transform var(--d-t, .3s) var(--d-t-e, ease), opacity var(--d-o, .2s);
                }
                &:checked {
                --b: var(--active);
                --bc: var(--active);
                --d-o: .3s;
                --d-t: .6s;
                --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
                }
                &:disabled {
                --b: var(--disabled);
                cursor: not-allowed;
                opacity: .9;
                &:checked {
                    --b: var(--disabled-inner);
                    --bc: var(--border);
                }
                & + label {
                    cursor: not-allowed;
                }
                }
                &:hover {
                &:not(:checked) {
                    &:not(:disabled) {
                    --bc: var(--border-hover);
                    }
                }
                }
                &:focus {
                box-shadow: 0 0 0 var(--focus);
                }
                &:not(.switch) {
                width: 21px;
                &:after {
                    opacity: var(--o, 0);
                }
                &:checked {
                    --o: 1;
                }
                }
                & + label {
                font-size: 14px;
                line-height: 21px;
                display: inline-block;
                vertical-align: top;
                margin-left: 4px;
                }
            }
            input[type='checkbox'] {
                &:not(.switch) {
                border-radius: 7px;
                &:after {
                    width: 5px;
                    height: 9px;
                    border: 2px solid var(--active-inner);
                    border-top: 0;
                    border-left: 0;
                    left: 7px;
                    top: 4px;
                    transform: rotate(var(--r, 20deg));
                }
                &:checked {
                    --r: 43deg;
                }
                }
                &.switch {
                width: 38px;
                border-radius: 11px;
                &:after {
                    left: 2px;
                    top: 2px;
                    border-radius: 50%;
                    width: 15px;
                    height: 15px;
                    background: var(--ab, var(--border));
                    transform: translateX(var(--x, 0));
                }
                &:checked {
                    --ab: var(--active-inner);
                    --x: 17px;
                }
                &:disabled {
                    &:not(:checked) {
                    &:after {
                        opacity: .6;
                    }
                    }
                }
                }
            }
    </style>
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class='bx bx-file-blank text-white text-xl'></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Solicitudes</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center max-lg:justify-end space-x-4">
                    @if($activeTab !== 'create')
                        <button wire:click="setActiveTab('create')" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class='bx bx-plus mr-2'></i>
                            Nueva Solicitud
                        </button>
                    @endif
                    @if($activeTab !== 'list' && count($solicitudesRender) > 0)
                        <button wire:click="setActiveTab('list')" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-list-ul mr-2'></i>
                            Ver Solicitudes
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($activeTab === 'list')
            <!-- Solicitudes List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="flex max-lg:flex-col items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-show text-blue-600 mr-2'></i>
                            Mis Solicitudes
                        </h2>
                        <div class="flex items-center justify-center lg:justify-end max-lg:flex-col space-x-2 max-lg:mt-4">
                            <div class="flex items-center justify-end space-x-2">
                                <span class="text-sm text-gray-400 max-lg:hidden">Filtro:</span>
                                <div class="relative" x-data="{selectActive: 0}">
                                    <div class="w-full h-full rounded-lg flex items-center justify-center cursor-pointer transition-colors border border-gray-300
                                        {{$estadoSolicitud === 'Pendiente' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                                        ($estadoSolicitud === 'Aprobada' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 
                                        ($estadoSolicitud === 'Rechazada' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'text-black/60 hover:bg-gray-100'))}}"
                                        @click="selectActive = selectActive === 1 ? 0 : 1">
                                        
                                        
                                        <i class='bx bx-filter text-xl max-md:py-2 px-1'></i>
                                        <span class="py-2 px-1 inline-flex text-sm leading-5 rounded-full max-md:hidden">
                                            {{$estadoSolicitud}}
                                        </span>
                                        <i class='bx bx-caret-down text-xl mr-1'
                                        :class="{
                                            'transform rotate-180': selectActive === 1,
                                        }"></i>

                                    </div>
                                    <div class="absolute w-auto mt-1 bg-white border border-gray-300 rounded-lg shadow-lg"
                                        x-show="selectActive === 1" x-transition @click.away="selectActive = 0" x-cloak x-bind
                                        :class="{
                                            'hidden': selectActive !== 1,
                                        }">
                                        <ul>
                                            <li class="text-black/60 p-2 transition-colors cursor-default hover:bg-gray-100 hover:text-gray-800"
                                            wire:click="ordenEstados(0)">Todos</li>
                                            @foreach($estatus as $estatu)
                                                <li class="text-black/60 p-2 transition-colors cursor-default hover:bg-gray-100 hover:text-gray-800"
                                                wire:click="ordenEstados({{ $estatu->estatus_id }})">{{$estatu->getEstatusFormattedAttribute()}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="relative w-full sm:w-auto">
                                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar solicitud..."
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                                    <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                                </div>
                            </div>

                            <span class="text-sm text-gray-500 max-lg:mt-2">{{ count($solicitudesRender) }} solicitudes</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($solicitudesRender as $solicitudRender)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-200 bg-white">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                                    <div class="flex-1 mb-4 lg:mb-0">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class='bx bx-file-blank text-blue-600 text-xl'></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex space-x-2 items-center mb-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $solicitudRender->titulo }}</h3>
                                                    @if($solicitudRender->fecha_actualizacion_usuario)
                                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-600/20">
                                                            Editado
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($solicitudRender->descripcion, 150) }}</p>
                                                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class='bx bx-category mr-1'></i>
                                                        {{ $solicitudRender->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin categoría' }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class='bx bx-map-pin mr-1'></i>
                                                        {{ $solicitudRender->comunidadRelacion->getParroquiaFormattedAttribute() }}, {{ $solicitudRender->comunidadRelacion->getComunidadFormattedAttribute() }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class='bx bx-calendar mr-1'></i>
                                                        {{ $solicitudRender->fecha_creacion->format('d/m/Y H:i') }}
                                                    </span>
                                                    @if($solicitudRender->solicitud_id)
                                                        <span class="flex items-center">
                                                            <i class='bx bx-id-card mr-1'></i>
                                                            Ticket: {{ $solicitudRender->solicitud_id }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{$solicitudRender->estatus === 1 ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                                        ($solicitudRender->estatus === 2 ? 'bg-green-100 text-green-800 hover:bg-green-200' : 
                                        ($solicitudRender->estatus === 3 ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'text-black/60 hover:bg-gray-100'))}}">
                                            {{ $solicitudRender->getEstatusFormattedAttribute() }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="viewSolicitud('{{ $solicitudRender->solicitud_id }}')" 
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                            </button>
                                            @if($solicitudRender->estatus === 'Pendiente')
                                                <button wire:click="editSolicitud('{{ $solicitudRender->solicitud_id }}')" 
                                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Editar">
                                                    <i class='bx bx-edit text-xl'></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($solicitudesRender->isEmpty() && $solicitudesRender->currentPage() == 1)
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class='bx bx-file-blank text-4xl text-gray-400'></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes solicitudes</h3>
                                @if($estadoSolicitud === 'Todos')
                                    <p class="text-gray-600 mb-6">Comienza creando tu primera solicitudaa</p>
                                    <button wire:click="setActiveTab('create')" 
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                        <i class='bx bx-plus mr-2'></i>
                                        Crear Primera Solicitud
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="mx-5">
                                {{ $solicitudesRender->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($activeTab === 'create' || $activeTab === 'edit' && $editingSolicitud)
            <!-- Create/Edit Form -->
            <div class="flex items-center justify-center space-x-2 mb-8 ">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 border-2  {{ $personalData['cedula'] && $personalData['nombre_completo'] && $personalData['telefono'] && $personalData['email'] ? 'bg-blue-50 border-blue-600' : 'border-gray-300' }} rounded-full flex items-center justify-center font-bold">
                        <i class='bx bx-user text-2xl 
                        {{ $personalData['cedula'] && $personalData['nombre_completo'] && $personalData['telefono'] && $personalData['email'] ? 'text-blue-600' : 'text-gray-500' }}'></i>
                    </div>
                    <span class="max-lg:hidden text-sm font-medium {{ $personalData['cedula'] && $personalData['nombre_completo'] && $personalData['telefono'] && $personalData['email'] ? 'text-blue-600' : 'text-gray-500' }}">
                        Datos Personales</span>
                </div>
                <div class="w-3 lg:w-12 h-1 {{ $personalData['cedula'] && $personalData['nombre_completo'] && $personalData['telefono'] && $personalData['email'] ? 'bg-blue-600' : 'bg-gray-300' }} rounded">
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 border-2 {{ $solicitud['solicitudCategoria']['categoria'] && $solicitud['solicitudCategoria']['subcategoria'] ? 'bg-blue-50 border-blue-600' : 'border-gray-300'  }} rounded-full flex items-center justify-center font-bold">
                        <i class='bx bx-category text-2xl 
                        {{ $solicitud['solicitudCategoria']['categoria'] && $solicitud['solicitudCategoria']['subcategoria'] ? 'text-blue-600' : 'text-gray-500' }}'></i>
                    </div>
                    <span class="max-lg:hidden text-sm font-medium {{ $solicitud['solicitudCategoria']['categoria'] ? 'text-blue-600' : 'text-gray-500' }}">
                        Categoría</span>
                </div>
                <div class="w-3 lg:w-12 h-1 {{ $solicitud['solicitudCategoria']['categoria'] && $solicitud['solicitudCategoria']['subcategoria'] ? 'bg-blue-600' : 'bg-gray-300' }} rounded"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 border-2 {{ (strlen($solicitud['direccion_detallada']) >= 10 && strlen($solicitud['direccion_detallada']) <= 200) && ($solicitud['solicitudParroquia']['parroquia'] && $solicitud['solicitudParroquia']['comunidad']) ? 'bg-blue-50 border-blue-600' : 'border-gray-300'  }} rounded-full flex items-center justify-center font-bold">
                        <i class='bx bx-map text-2xl 
                        {{ (strlen($solicitud['direccion_detallada']) >= 10 && strlen($solicitud['direccion_detallada']) <= 200) && ($solicitud['solicitudParroquia']['parroquia'] && $solicitud['solicitudParroquia']['comunidad']) ? 'text-blue-600' : 'text-gray-500' }}'></i>
                    </div>
                    <span class="max-lg:hidden text-sm font-medium {{ (strlen($solicitud['direccion_detallada']) >= 10 && strlen($solicitud['direccion_detallada']) <= 200) && ($solicitud['solicitudParroquia']['parroquia'] && $solicitud['solicitudParroquia']['comunidad']) ? 'text-blue-600' : 'text-gray-500' }}">
                        Ubicación</span>
                </div>
                <div class="w-3 lg:w-12 h-1 {{ (strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? 'bg-blue-600' : 'bg-gray-300' }} rounded"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 border-2 {{ (strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? 'bg-blue-50 border-blue-600' : 'border-gray-300' }} rounded-full flex items-center justify-center ont-bold">
                        <i class='bx bx-edit text-xl
                        {{ (strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? 'text-blue-600' : 'text-gray-500' }}'></i>
                    </div>
                    <span class="max-lg:hidden text-sm font-medium {{ (strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? 'text-blue-600' : 'text-gray-500' }}">
                        Descripción</span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 lg:p-8">
                    <!-- Form Header -->
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex items-center justify-center">
                            <div class="mr-2 w-10 lg:w-12 h-10 lg:h-12 flex justify-center items-center shadow-lg rounded-full
                            {{$editingSolicitud ? 'bg-indigo-100 text-indigo-600 shadow-indigo-50' : 'bg-blue-100 text-blue-600 shadow-blue-50 '}}">
                                <i class='bx {{$editingSolicitud ? 'bx-edit' : 'bx-plus'}} text-lg lg:text-3xl font-bold'></i>
                            </div>
                            <h2 class="texl-xl lg:text-2xl font-bold text-gray-900">
                                {{ $editingSolicitud ? 'Editar Solicitud' : 'Nueva Solicitud' }}
                            </h2>
                        </div>
                        <p class="max-lg:text-sm text-gray-600 mt-3 mb-6 mx-3 text-center">Complete todos los campos requeridos para la {{ $editingSolicitud ? 'actualizar' : 'crear' }}</p>
                    </div>

                    <form wire:submit.prevent="submit" class="space-y-8">
                        
                        <!-- Step 1: Personal Data Display -->
                        <div class="bg-gray-50 rounded-lg p-2 lg:p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-user text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Datos Personales</h3>
                                    <p class="text-sm text-gray-600">Información registrada en el sistema</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cédula de Identidad</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-id-card text-blue-600 mr-2'></i>
                                        <span class="font-medium text-gray-900">{{ $personalData['cedula'] ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-user text-blue-600 mr-2'></i>
                                        <span class="font-medium text-gray-900">{{ $personalData['nombre_completo'] ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-envelope text-blue-600 mr-2'></i>
                                        <span class="font-medium text-gray-900">{{ $personalData['email'] ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-phone text-blue-600 mr-2'></i>
                                        <span class="font-medium text-gray-900">{{ $personalData['telefono'] ?? 'No registrado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PASO 2: CATEGORIAS -->
                        @if ((($personalData['cedula'] && $personalData['nombre_completo'] && $personalData['telefono'] && $personalData['email']) && !$editingSolicitud) || $editingSolicitud)
                        <div class="space-y-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-category text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Categoría de Solicitud</h3>
                                    <p class="text-sm text-gray-600">Seleccione el tipo de solicitud que desea realizar</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($categorias as $categoria)
                                    <div class="border-2 rounded-xl p-6 cursor-pointer transition-all duration-300 hover:shadow-lg transform hover:scale-105
                                        {{ $solicitud['solicitudCategoria']['categoria'] === $categoria->categoria ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200 hover:border-blue-300' }}"
                                        wire:click="$set('solicitud.solicitudCategoria.categoria', '{{ $categoria->categoria }}')">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                                <i class='bx bx-category text-3xl text-blue-600'></i>
                                            </div>
                                            <h4 class="text-sm lg:text-lg font-bold text-gray-900 mb-2">{{ Str::title($categoria->getCategoriaFormattedAttribute()) }}</h4>
                                            <p class="text-sm text-gray-600">{{count($categoria['subcategorias']) }} opciones</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('solicitud.solicitudCategoria.categoria') 
                                <div class="flex justify-end items-center text-red-600 text-sm mt-2">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <!-- Subcategory Selection -->
                            @if ($solicitud['solicitudCategoria']['categoria'] && $subcategorias)
                                <div class="bg-gray-50 rounded-lg p-2 lg:p-6">
                                    <h4 class="text-lg font-bold text-gray-900 mb-4">
                                        Subcategorías de {{ Str::title($solicitud['solicitudCategoria']['categoria']) }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach ($subcategorias as $subcategoria)
                                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-300 hover:shadow-md
                                                {{ $solicitud['solicitudCategoria']['subcategoria'] === $subcategoria->subcategoria ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}"
                                                wire:click="$set('solicitud.solicitudCategoria.subcategoria', '{{ $subcategoria->subcategoria }}')">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                                        <i class='bx bx-check text-white text-sm'></i>
                                                    </div>
                                                    <span class="font-medium text-gray-900">{{ Str::title($subcategoria->getSubcategoriaFormattedAttribute()) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('solicitud.solicitudCategoria.subcategoria') 
                                        <div class="flex justify-end items-center text-red-600 text-sm mt-2">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- PASO 3: LOCALIZACION DE LA SOLICITUD -->
                        @if ((($solicitud['solicitudCategoria']['categoria'] && $solicitud['solicitudCategoria']['subcategoria']) && !$editingSolicitud) || $editingSolicitud)   
                        <div class="space-y-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-map text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Ubicación de la Solicitud</h3>
                                    <p class="text-sm text-gray-600">Proporcione los detalles de ubicación donde se requiere el servicio</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-2 lg:p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                                        <div class="flex items-center">
                                            <i class='bx bx-world text-gray-500 mr-2'></i>
                                            <span class="text-gray-900">{{ $solicitud['pais'] }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                        <div class="flex items-center">
                                            <i class='bx bx-map-pin text-gray-500 mr-2'></i>
                                            <span class="text-gray-900">{{ $solicitud['estado_region'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Municipio</label>
                                        <div class="flex items-center">
                                            <i class='bx bx-buildings text-gray-500 mr-2'></i>
                                            <span class="text-gray-900">{{ $solicitud['municipio'] }}</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg border border-gray-200">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Parroquia *</label>
                                            <select wire:model.live="solicitud.solicitudParroquia.parroquia" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <option value="" disabled selected>Seleccione una parroquia</option>
                                                @foreach ($parroquias as $parroquia)
                                                    <option value="{{$parroquia->parroquia}}">{{$parroquia->getParroquiaFormattedAttribute()}}</option>
                                                @endforeach
                                            </select>
                                            @error('solicitud.solicitudParroquia.parroquia') 
                                                <div class="flex items-center text-red-600 text-sm mt-1">
                                                    <i class='bx bx-error-circle mr-1'></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Comunidad *</label>
                                            <select wire:model.live="solicitud.solicitudParroquia.comunidad" {{!$solicitud['solicitudParroquia']['parroquia'] ? 'disabled' : ''}}
                                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{!$solicitud['solicitudParroquia']['parroquia'] ? 'bg-gray-100 cursor-not-allowed' : ''}}">
                                                <option value="" disabled selected>Seleccione una comunidad</option>
                                                @foreach ($comunidades as $comunidad)
                                                    <option value="{{$comunidad->comunidad}}">{{$comunidad->getComunidadFormattedAttribute()}}</option>
                                                @endforeach
                                            </select>
                                            @error('solicitud.solicitudParroquia.comunidad') 
                                                <div class="flex items-center text-red-600 text-sm mt-1">
                                                    <i class='bx bx-error-circle mr-1'></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección Detallada *</label>
                                    <textarea wire:model.live="solicitud.direccion_detallada" rows="4" 
                                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                              placeholder="Proporcione la dirección completa incluyendo puntos de referencia importantes..." maxlength="200"></textarea>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class='bx bx-info-circle mr-1'></i>
                                            <span>Caracteres: {{ strlen($solicitud['direccion_detallada']) }}/200 (mínimo 10)</span>
                                        </div>
                                        <div class="flex items-center">
                                            @if($solicitud['direccion_detallada'])
                                                @if(strlen($solicitud['direccion_detallada']) >= 10 && strlen($solicitud['direccion_detallada']) <= 200)
                                                    <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                    <span class="text-green-600 text-sm font-medium">Válida</span>
                                                @elseif(strlen($solicitud['direccion_detallada']) < 10)
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy corto</span>
                                                @else
                                                    <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                    <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    @error('solicitud.direccion_detallada') 
                                        <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <!--PASO 4: DESCRIPCION-->
                        @if (((strlen($solicitud['direccion_detallada']) >= 10 && strlen($solicitud['direccion_detallada']) <= 200) && ($solicitud['solicitudParroquia']['parroquia'] && $solicitud['solicitudParroquia']['comunidad']) && !$editingSolicitud) || $editingSolicitud)
                        <div class="space-y-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-edit text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Descripción de la Solicitud</h3>
                                    <p class="text-sm text-gray-600">Describa detalladamente su solicitud (mínimo 50 caracteres)</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-2 lg:p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titulo *</label>
                                <input type="text" wire:model.live="solicitud.titulo" 
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Escriba un breve titulo para su solicitud" maxlength="50">
                                <div class="flex justify-between items-center mt-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class='bx bx-info-circle mr-1'></i>
                                        <span>Caracteres: {{ strlen($solicitud['titulo']) }}/50 (mínimo 5)</span>
                                    </div>
                                    <div class="flex items-center">
                                        @if($solicitud['titulo'])
                                            @if(strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50)
                                                <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                <span class="text-green-600 text-sm font-medium">Válida</span>
                                            @elseif(strlen($solicitud['titulo']) < 5)
                                                <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                <span class="text-red-600 text-sm font-medium">Muy corto</span>
                                            @else
                                                <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                @error('solicitud.titulo') 
                                    <div class="flex justify-end items-center text-red-600 text-sm mt-2">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                    
                                <textarea wire:model.live="solicitud.descripcion" rows="8" 
                                          class="w-full p-4 mt-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                          placeholder="Describa detalladamente su solicitud. Incluya información relevante como el problema específico" maxlength="5000"></textarea>
                                
                                <div class="flex justify-between items-center mt-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class='bx bx-info-circle mr-1'></i>
                                        <span>Caracteres: {{ strlen($solicitud['descripcion']) }}/5000 (mínimo 25)</span>
                                    </div>
                                    <div class="flex items-center">
                                        @if($solicitud['descripcion'])
                                            @if(strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000)
                                                <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                                <span class="text-green-600 text-sm font-medium">Válida</span>
                                            @elseif(strlen($solicitud['descripcion']) < 25)
                                                <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                <span class="text-red-600 text-sm font-medium">Muy corto</span>
                                            @else
                                                <i class='bx bx-error-circle text-red-500 mr-1'></i>
                                                <span class="text-red-600 text-sm font-medium">Muy largo</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                
                                @error('solicitud.descripcion') 
                                    <div class="flex justify-end items-center text-red-600 text-sm mt-2">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Checkbox Derecho de Palabra -->
                        <div class="flex justify-between space-x-6 mt-4 max-md:flex-col p-6">
                            <div class="flex justify-start gap-2 items-center max-md:justify-between">
                                <input type="radio" wire:model.live="solicitud.tipo_solicitud" id="radio1_tipo_solicitud-usuario" value="individual" class="rounded-full" 
                                title="Selecciona esta opción si su solicitud es para su beneficio personal">
                                <label for="radio1_tipo_solicitud" title="Seleccionar esta opción si su solicitud es para su beneficio personal">Solicitud personal</label>
                                <input type="radio" wire:model.live="solicitud.tipo_solicitud" id="radio2_tipo_solicitud-usuario" value="colectivo_institucional" class="rounded-full" 
                                title="Selecciona esta opción si su solicitud es para fines colectevos o institucionales">
                                <label for="radio2_tipo_solicitud" title="Seleccionar esta opción si su solicitud es para fines colectevos institucionales">Solicitud para un Colectivo Institucional</label>
                            </div>
                            <div class="flex items-center justify-end space-x-6 max-md:mt-8">
                                <div class="flex justify-end gap-2 items-center">
                                    <input wire:model.live="solicitud.derecho_palabra" id="s1-create-usuario" type="checkbox" class="switch">
                                    <label for="s1-create-usuario">Solicitar Derecho de Palabra</label>
                                </div>
                            </div>
                        </div>
                        @error('solicitud.tipo_solicitud') 
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        @enderror
                        @endif

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-center pt-8 border-t border-gray-200 space-y-4 sm:space-y-0"
                        :class="{
                            'justify-between': @json($activeTab === 'create' && !$editingSolicitud),
                            'justify-end': @json(!($activeTab === 'create' && !$editingSolicitud))
                        }">
                            @if($activeTab === 'create' && !$editingSolicitud)
                                <button type="button" wire:click="resetForm" 
                                        class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    <i class='bx bx-refresh mr-2'></i>
                                    Reiniciar Formulario
                                </button>
                            @endif

                            <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                <button type="button" wire:click="setActiveTab('list')"
                                       class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    <i class='bx bx-arrow-back mr-2'></i>
                                    Cancelar
                                </button>
                                <button type="submit" {{(strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? '' : 'disabled' }}
                                        class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg
                                        {{(strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? '' : 'opacity-50 cursor-not-allowed' }}">
                                    <i class='bx {{ $editingSolicitud ? 'bx-save' : 'bx-check' }} mr-2'></i>
                                    {{ $editingSolicitud ? 'Actualizar Solicitud' : 'Crear Solicitud' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- View -->
        @if($activeTab === 'show' && $showSolicitud)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-show text-blue-600 text-xl'></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Detalles de la Solicitud</h3>
                                <p class="text-sm text-gray-600">Ticket: {{ $showSolicitud->solicitud_id }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end gap-4">
                            <button type="button" wire:click="setActiveTab('list')"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                <i class='bx bx-arrow-back mr-2'></i>
                                <span class="max-lg:hidden">Regresar Atras</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900 font-medium">{{ $showSolicitud->titulo }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estatus</label>
                            <div class="p-3 bg-gray-50 rounded-lg space-x-2">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{$showSolicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 border-yellow-300' : 
                                    ($showSolicitud->estatus === 2 ? 'bg-green-100 text-green-800 hover:bg-green-200 green-gray-300' : 
                                    ($showSolicitud->estatus === 3 ? 'bg-red-100 text-red-800 hover:bg-red-200 border-red-300' : 'text-gray-700 border-gray-300 hover:bg-gray-100'))}}">
                                    {{ $showSolicitud->getEstatusFormattedAttribute() ?? 'Sin estatus'}}
                                </span>
                                @if($showSolicitud->derecho_palabra)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-300/20 text-blue-800">
                                        Derecho de Palabra
                                    </span>
                                @endif
                                @if($showSolicitud->fecha_actualizacion_usuario)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-600/20">
                                        Editado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin categoría' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subcategoría</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute() ?? 'Sin subcategoría' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-900">{{ $showSolicitud->descripcion }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parroquia</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->comunidadRelacion->getParroquiaFormattedAttribute()  }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comunidad</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->comunidadRelacion->getComunidadFormattedAttribute() }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección Detallada</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-900">{{ $showSolicitud->direccion_detallada }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Creación</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->fecha_creacion->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Última Edición</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">@if($showSolicitud->fecha_actualizacion_usuario) {{$showSolicitud->fecha_actualizacion_usuario->format('d/m/Y H:i') }} @else N/A @endif</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Solicitud</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $showSolicitud->getTipoSolicitudFormattedAttribute()}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>