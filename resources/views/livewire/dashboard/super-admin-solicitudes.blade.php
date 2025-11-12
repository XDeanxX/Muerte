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
    <!-- Tab Navigation -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
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
                <div class="flex items-center justify-end space-x-4">
                    @if($activeTab !== 'create' && Auth::user()->isSuperAdministrador())
                        <button wire:click="setActiveTab('create')" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class='bx bx-plus mr-2'></i>
                            Nueva Solicitud
                        </button>
                    @endif
                    @if($activeTab !== 'list' && count($solicitudesRender) > 0 && !$showSolicitud)
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

    @if($deleteSolicitud)
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
                        ¿Estás seguro de que deseas eliminar esta solicitud? Se perderán todos los datos asociados.
                    </p>
                    <p class="text-sm text-gray-400 mb-6">Ticket: {{ $deleteSolicitud->solicitud_id }} - {{$deleteSolicitud->persona->nombre}} {{$deleteSolicitud->persona->apellido}}</p>
                    
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cancelDelete" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="deleteSolicitudDefinitive('{{$deleteSolicitud->solicitud_id}}')" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($activeTab === 'list')
            <!-- Solicitudes List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 mb-4 flex max-lg:flex-col lg:items-center lg:justify-between">
                <div class="relative w-full sm:w-auto">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar solicitud..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full lg:w-80">
                    <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                </div>
                <div class="flex items-center justify-center lg:justify-start max-lg:mt-2 space-x-2">
                    <span class="text-sm text-gray-400 max-lg:hidden">Filtro:</span>
                    <div class="relative" x-data="{selectActive: 0}">
                        <div class="w-full h-full rounded-lg flex items-center justify-center cursor-pointer transition-colors border border-gray-300
                            {{$estatusName === 'Pendiente' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                            ($estatusName === 'Aprobada' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 
                            ($estatusName === 'Rechazada' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'text-black/60 hover:bg-gray-100'))}}"
                            @click="selectActive = selectActive === 1 ? 0 : 1">
                            
                            
                            <i class='bx bx-filter text-xl max-md:py-2 px-1'></i>
                            <span class="py-2 px-1 inline-flex text-sm leading-5 rounded-full max-md:hidden">
                                {{$estatusName}}
                            </span>
                            <i class='bx bx-caret-down text-xl mr-1'
                            :class="{
                                'transform rotate-180': selectActive === 1,
                            }"></i>

                        </div>
                        <div class="absolute w-auto mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50"
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

                    <div class="flex space-x-2">
                        <button wire:click="donwloadPDFSolicitudes" type="button"
                            class="w-12 h-12 border border-gray-300 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                            title="Exportar a PDF">
                            <i class='bx bxs-file-pdf text-2xl'></i>
                        </button>
                        <button wire:click="exportExcel" type="button"
                            class="w-12 h-12 border border-gray-300 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                            title="Exportar a Excel">
                            <i class='bx bxs-file text-2xl'></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg lg:text-xl max-md:text-md font-semibold text-gray-900">
                        <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                        Todas las Solicitudes
                    </h2>
                    <span class="text-sm text-gray-500 max-lg:mt-2">{{ count($solicitudesRender) }} solicitudes</span>
                </div>
                <div class="overflow-x-auto max-lg:pb-4 rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-45 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                    wire:click="orden('solicitud_id')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center justify-start">
                                            <i class='bx bx-purchase-tag-alt mr-2'></i>
                                            Solicitud
                                        </div>
                                        @if ($sort == 'solicitud_id')
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
                                    wire:click="orden('subcategoria')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center justify-start">
                                            <i class='bx bx-folder mr-2'></i>
                                            Categoría
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
                                    wire:click="orden('fecha_creacion')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center justify-start">
                                            <i class='bx bx-calendar-alt mr-2'></i>
                                            Fecha
                                        </div>
                                        @if ($sort == 'fecha_creacion')
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
                                    wire:click="orden('persona.nombre')">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center justify-start">
                                            <i class='bx bx-user mr-2'></i>
                                            Solicitante
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
                                </th>{{-- 
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <i class='bx bx-user-plus mr-2'></i>
                                        Visita
                                    </div>
                                </th> --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex justify-end items-end">
                                        <i class='bx bx-cog mr-2'></i>
                                        Acciones
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <i class='bx bx-check-shield mr-2'></i>
                                        Cambiar Estatus
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($solicitudesRender as $solicitudeRender)
                                <tr class="hover:bg-gray-50">
                                    <td class="w-40 flex items-center px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 w-60 truncate" title="{{ $solicitudeRender->titulo }}">
                                                {{ Str::limit($solicitudeRender->titulo, 20) }}
                                                @if ($solicitudeRender->fecha_actualizacion_usuario)
                                                    <i class="bx bx-edit bg-gray-300/70 text-gray-800 p-1 ml-2 rounded-full" title="Solicitud editada por el solicitante"></i>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Ticket: {{ $solicitudeRender->solicitud_id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $solicitudeRender->subcategoriaRelacion->getCategoriaFormattedAttribute() }}</div>
                                        <div class="text-sm text-gray-500">{{ $solicitudeRender->subcategoriaRelacion->getSubcategoriaFormattedAttribute() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $solicitudeRender->fecha_creacion->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $solicitudeRender->persona->nombre ?? 'N/A' }} {{ $solicitudeRender->persona->apellido ?? '' }}
                                    </td>{{-- 
                                    <td class="flex items-center justify-end px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($solicitudeRender->asignada_visita)
                                            <div class="py-1 px-2 bg-blue-100 cursor-default rounded-full shadow-sm shadow-blue-300 transition-colors hover:bg-blue-150" title="Solicitud asignada a visitas">
                                                <span class="text-zinc-900/60 font-semibold">Asignada</span>
                                            </div>
                                        @else
                                            <div class="py-1 px-2 bg-gray-100 cursor-default rounded-full shadow-sm shadow-gray-400 transition-colors hove:bg-gray-150" title="Solicitud no asignada a visitas">
                                                <span class=" text-black/60 font-semibold">No Asignada</span>
                                            </div>
                                        @endif
                                    </td> --}}
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="viewSolicitud('{{ $solicitudeRender->solicitud_id }}')" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                            </button>
                                            <button wire:click="editSolicitud('{{ $solicitudeRender->solicitud_id }}')" 
                                            class="p-2 rounded-full text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 transition-colors"
                                            title="Editar">
                                                <i class='bx bx-edit text-xl'></i>
                                            </button>
                                            <button wire:click="confirmDelete('{{ $solicitudeRender->solicitud_id }}')"
                                            class="p-2 text-red-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Eliminar">
                                                <i class='bx bx-trash text-xl'></i>
                                            </button>
                                            <button wire:click="donwloadPDFSolicitud('{{ $solicitudeRender->solicitud_id }}')"
                                            class="p-2 rounded-full text-orange-600 hover:text-orange-900 hover:bg-orange-50 transition-colors"
                                            title="Descargar PDF">
                                                <i class='bx bx-download text-xl'></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="flex items-center justify-center" x-data="{selectActiveUpdateStatus: 0}">
                                        <div class="relative items-center space-x-1">
                                            <div class="w-27 h-7 rounded-full flex items-center justify-center cursor-pointer transition-colors border 
                                            {{$solicitudeRender->estatus === 1 ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 border-yellow-300' : 
                                            ($solicitudeRender->estatus === 2 ? 'bg-green-100 text-green-800 hover:bg-green-200 green-gray-300' : 
                                            ($solicitudeRender->estatus === 3 ? 'bg-red-100 text-red-800 hover:bg-red-200 border-red-300' : 'text-gray-700 border-gray-300 hover:bg-gray-100'))}}
                                            "
                                                @click="selectActiveUpdateStatus = selectActiveUpdateStatus === 1 ? 0 : 1">
                                                    
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                    {{$solicitudeRender->getEstatusFormattedAttribute() }}
                                                </span>

                                                <i class='bx bx-caret-down text-xl transition-transform duration-200 mr-2'
                                                :class="{
                                                    'transform rotate-180': selectActiveUpdateStatus === 1,
                                                }">
                                                </i>

                                            </div>
                                            <div class="absolute w-auto mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                                                x-show="selectActiveUpdateStatus === 1" x-transition @click.away="selectActiveUpdateStatus = 0" x-cloak x-bind
                                                :class="{
                                                    'hidden': selectActiveUpdateStatus !== 1,
                                                }">
                                                <ul>
                                                    @foreach($estatus as $estatu)
                                                        <li class="p-2 cursor-default bg-white transition-colors hover:bg-gray-200 hover:text-zinc-800"
                                                        wire:click="updateStatus('{{ $solicitudeRender->solicitud_id }}', {{$estatu->estatus_id}})">{{$estatu->getEstatusFormattedAttribute()}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($solicitudesRender->isEmpty() && $solicitudesRender->currentPage() == 1)
                    <div class="text-center py-8">
                        <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes</h3>
                        <p class="text-gray-500">
                            No se encontraron solicitudes en el sistema
                        </p>
                    </div>
                @else
                    <div class="mx-5 mt-4">
                        {{ $solicitudesRender->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

        @if($activeTab === 'create' || $activeTab === 'edit' && $editingSolicitud)
            <!-- Create/Edit Form -->
            
            <div class="flex items-center justify-center space-x-2 mb-8 ">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 border-2  {{ $personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email'] ? 'bg-blue-50 border-blue-600' : 'border-gray-300' }} rounded-full flex items-center justify-center font-bold">
                        <i class='bx bx-user text-2xl 
                        {{ $personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email'] ? 'text-blue-600' : 'text-gray-500' }}'></i>
                    </div>
                    <span class="max-lg:hidden text-sm font-medium {{ $personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email'] ? 'text-blue-600' : 'text-gray-500' }}">
                        Datos Personales</span>
                </div>
                <div class="w-3 lg:w-12 h-1 {{ $personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email'] ? 'bg-blue-600' : 'bg-gray-300' }} rounded">
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
                        
                        <!-- PASO 1: DATOS PERSONALES -->
                        <div class="bg-gray-50 rounded-lg p-2 lg:p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-user text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Datos Personales</h3>
                                    @if($personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email'] && $editingSolicitud)
                                        <p class="text-sm text-gray-600">Información registrada en el sistema</p>
                                    @else
                                        <p class="text-sm text-gray-600">Ingresar información personal</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Cédula de Identidad</label>
                                        @if($activeTab === 'create')
                                            @if ($mensajeSolcitante === 1)
                                                <button wire:click="rellenarDatosSolicitante()" class="border-1 border-green-500 transition-colors bg-green-100 text-green-800 hover:bg-green-200 px-2 rounded-xl text-xs lg:text-sm cursor-pointer">Rellenar Datos</button>
                                            @elseif($mensajeSolcitante === 2)
                                                <p class="text-xs lg:text-sm text-red-600">no registrado</p>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <i class='bx bx-id-card text-blue-600 mr-2'></i>
                                        @if($personalData['cedula'] && $editingSolicitud)
                                            <span class="font-medium text-gray-900">{{$personalData['nacionalidad'] === 1 ? 'V' : 'E'}}-{{ $personalData['cedula'] ?? 'No registrado' }}</span>
                                        @else
                                            <div class="flex items-center">
                                                <select wire:model.live="personalData.nacionalidad" class="w-15 mr-2 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                    <option value="" disabled selected>...</option>
                                                    @foreach ($nacionalidad as $nacionalidadd)
                                                        <option value="{{$nacionalidadd->id}}">{{$nacionalidadd->id === 1 ? 'V' : 'E'}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" maxlength="15" wire:model.live.debounce.500ms="personalData.cedula"
                                                 class="w-full font-medium text-gray-900 focus:outline-none" placeholder="Escribir Cédula..."
                                                 oninput="this.value = this.value.replace(/\D/g, '')">
                                            </div>
                                        @endif
                                    </div>
                                    @error('personalData.nacionalidad') 
                                        <div class="flex items-center text-red-600 text-sm mt-1">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    @error('personalData.cedula') 
                                        <div class="flex items-center text-red-600 text-sm mt-1">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="bg-white flex gap-x-2 p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    
                                    <div class="w-full px-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                        <div class="flex items-center">
                                            <i class='bx bx-user text-blue-600 mr-2'></i>
                                            @if($personalData['nombre'] && $editingSolicitud)
                                                <span class="w-full font-medium text-gray-900">{{ $personalData['nombre'] ?? 'No registrado' }}</span>
                                            @else
                                                <input type="text" wire:model.live="personalData.nombre" maxlength="25" class="w-full font-medium text-gray-900 focus:outline-none" placeholder="Escribir Nombre...">
                                            @endif
                                        </div>
                                        @error('personalData.nombre') 
                                            <div class="flex items-center text-red-600 text-sm mt-1">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="w-full px-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                                        <div class="flex items-center">
                                            <i class='bx bx-user text-blue-600 mr-2'></i>
                                            @if($personalData['apellido'] && $editingSolicitud)
                                                <span class="w-full font-medium text-gray-900">{{ $personalData['apellido'] ?? 'No registrado' }}</span>
                                            @else
                                                <input type="text" wire:model.live="personalData.apellido" maxlength="25" class="w-full font-medium text-gray-900 focus:outline-none" placeholder="Escribir Apellido...">
                                            @endif
                                        </div>
                                        @error('personalData.apellido') 
                                            <div class="flex items-center text-red-600 text-sm mt-1">
                                                <i class='bx bx-error-circle mr-1'></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-envelope text-blue-600 mr-2'></i>
                                        @if($personalData['email'] && $editingSolicitud)
                                            <span class="font-medium text-gray-900">{{ $personalData['email'] ?? 'No registrado' }}</span>
                                        @else
                                            <input type="email" wire:model.live="personalData.email" maxlength="50" class="w-full font-medium text-gray-900 focus:outline-none" placeholder="Escribir Correo...">
                                        @endif
                                    </div>
                                    @error('personalData.email') 
                                        <div class="flex items-center text-red-600 text-sm mt-1">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <div class="flex items-center">
                                        <i class='bx bx-phone text-blue-600 mr-2'></i>
                                        @if($personalData['telefono'] && $editingSolicitud)
                                            <span class="font-medium text-gray-900">{{ $personalData['telefono'] ? preg_replace('/(\d{4})(\d{3})(\d{4})/', '$1-$2-$3', $personalData['telefono']) : 'No registrado' }}</span>
                                        @else
                                            <div class="flex items-center">
                                                <select wire:model.live="personalData.prefijo" class="z-50 w-20 mr-2 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                    <option value="" disabled selected>----</option>
                                                    <option value="0412">0412</option>
                                                    <option value="0422">0422</option>
                                                    <option value="0414">0414</option>
                                                    <option value="0424">0424</option>
                                                    <option value="0416">0416</option>
                                                    <option value="0426">0426</option>
                                                </select>
                                                <input type="text" id="telefono_solicitud" wire:model.live="personalData.telefono" class="w-full font-medium text-gray-900 focus:outline-none"
                                                    pattern="\d{3}-\d{4}"
                                                    oninput="this.value = this.value.replace(/\D/g, '').replace(/(\d{3})(\d{4})/, '$1-$2')"
                                                    placeholder="000-0000" maxlength="8">
                                            </div>
                                        @endif
                                    </div>
                                    @error('personalData.telefono') 
                                        <div class="flex items-center text-red-600 text-sm mt-1">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- PASO 2: CATEGORIAS -->
                        @if ((($personalData['cedula'] && $personalData['nacionalidad'] && $personalData['nombre'] && $personalData['apellido'] && $personalData['telefono'] && $personalData['email']) && !$editingSolicitud) || $editingSolicitud)
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

                            <!-- Super Admin Only Fields -->
                            @if(Auth::user()->isSuperAdministrador())
                                <div class="">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class='bx bx-map text-blue-600 text-xl'></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">Administración</h3>
                                            <p class="text-sm text-gray-600">Observaciones de la solicitud</p>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <textarea wire:model="solicitud.observaciones_admin" rows="3" 
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Escribir una observación o inquietud {{$solicitud['observaciones_admin']}}"></textarea>
                                    </div>
                                    @if ($editingSolicitud && $activeTab === 'edit')
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class='bx bx-minus-circle text-blue-600 text-xl'></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">Estatus de la Solictiud</h3>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-6">
                                            @foreach ($estatus as $estatu)
                                                <div class="border-2 rounded-xl p-2 cursor-pointer transition-all duration-300 hover:shadow-lg transform hover:scale-105
                                                     {{($solicitud['solicitudEstatus'] === $estatu->estatus_id) && $solicitud['solicitudEstatus'] === 1 ? 'border-yellow-500 bg-yellow-50 text-yellow-800 shadow-lg hover:bg-yellow-150' : 
                                                    (($solicitud['solicitudEstatus'] === $estatu->estatus_id) && $solicitud['solicitudEstatus'] === 2 ? 'border-green-500 bg-green-100 text-green-800 shadow-lg hover:bg-green-200' : 
                                                    (($solicitud['solicitudEstatus'] === $estatu->estatus_id) && $solicitud['solicitudEstatus'] === 3 ? 'border-red-500 bg-red-100 text-red-800 shadow-lg hover:bg-red-200' : 'border-gray-200 shadow-lg hover:border-blue-300'))}}"
                                                    wire:click="$set('solicitud.solicitudEstatus', {{ $estatu->estatus_id }})">
                                                    <div class="text-center">
                                                        <h4 class="text-lg font-bold text-gray-900">{{ Str::title($estatu->getEstatusFormattedAttribute()) }}</h4>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Checkbox Derecho de Palabra -->
                            <div class="flex lg:justify-between lg:space-x-6 mt-4 max-lg:flex-col px-6">
                                <div class="flex lg:justify-start gap-4 items-center justify-between">
                                    <div>
                                        <input type="radio" wire:model.live="solicitud.tipo_solicitud" id="radio1_tipo_solicitud" value="individual" class="rounded-full" 
                                        title="Selecciona esta opción si su solicitud es para su beneficio personal">
                                        <label for="radio1_tipo_solicitud" title="Seleccionar esta opción si su solicitud es para su beneficio personal">Solicitud personal</label>
                                    </div>
                                    <div>
                                        <input type="radio" wire:model.live="solicitud.tipo_solicitud" id="radio2_tipo_solicitud" value="colectivo_institucional" class="rounded-full" 
                                        title="Selecciona esta opción si su solicitud es para fines colectevos o institucionales">
                                        <label for="radio2_tipo_solicitud" title="Seleccionar esta opción si su solicitud es para fines colectevos institucionales">Solicitud para un Colectivo Institucional</label>
                                    </div>
                                </div>
                                <div class="flex items-center justify-center lg:justify-end space-x-6 max-md:mt-8">
                                    <div class="flex justify-end gap-2 items-center">
                                        <input wire:model.live="solicitud.derecho_palabra" id="s1" type="checkbox" class="switch">
                                        <label for="s1">Solicitar Derecho de Palabra</label>
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
                        <div class="flex max-lg:flex-col lg:justify-between items-center pt-8 border-t border-gray-200 space-y-4 sm:space-y-0 lg:flex-row-reverse"
                        :class="{
                            'justify-between': @json($activeTab === 'create' && !$editingSolicitud),
                            'justify-end': @json(!($activeTab === 'create' && !$editingSolicitud))
                        }">
                            <button type="submit" {{(strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? '' : 'disabled' }}
                                    class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg
                                    {{(strlen($solicitud['descripcion']) >= 25 && strlen($solicitud['descripcion']) <= 5000) && (strlen($solicitud['titulo']) >= 5 && strlen($solicitud['titulo']) <= 50) ? '' : 'opacity-50 cursor-not-allowed' }}">
                                <i class='bx {{ $editingSolicitud ? 'bx-save' : 'bx-check' }} mr-2'></i>
                                {{ $editingSolicitud ? 'Actualizar Solicitud' : 'Crear Solicitud' }}
                            </button>
                            <div class="flex max-lg:flex-col items-center gap-4">
                                <button type="button" wire:click="setActiveTab('list')"
                                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    <i class='bx bx-arrow-back mr-2'></i>
                                    Cancelar
                                </button>
                                @if($activeTab === 'create' && !$editingSolicitud)
                                    <button type="button" wire:click="resetForm" 
                                            class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                        <i class='bx bx-refresh mr-2'></i>
                                        Reiniciar Formulario
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- View -->
        @if($activeTab === 'show' && $showSolicitud)
        
            <div class="bg-white rounded-xl border border-gray-200 mb-4 inset-shadow-md inset-shadow-white">
                <div class="p-3 border-b border-gray-200">
                    <div class="flex max-lg:flex-col lg:justify-between lg:items-start">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                                <i class='bx bx-show text-blue-600 text-xl'></i>
                            </div>
                            <div>
                                <h3 class="text-lg lg:text-xl font-bold text-gray-800">Detalles de la Solicitud</h3>
                                <p class="text-sm text-gray-600">Ticket: {{ $showSolicitud->solicitud_id }}</p>
                            </div>
                        </div>
                        
                        <div class="bg-white px-3 py-2 rounded-lg gap-4 shadow-lg border border-gray-200 hover:bg-gray-200 transition-colors lg:flex max-lg:mt-2 max-lg:grid max-lg:grid-cols-4">
                            <button type="button" wire:click="setActiveTab('list')"
                                    class="w-full text-center justify-center items-center bg-white sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                <i class='bx bx-arrow-back lg:mr-2'></i>
                                <span class="max-lg:hidden">Regresar Atrás</span>
                            </button>
                            <button wire:click="donwloadPDFSolicitud('{{$showSolicitud->solicitud_id}}')" 
                                    class="flex text-center justify-center items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm"
                                    title="Imprimir en PDF">
                                <i class='bx bx-cloud-download text-xl'></i>
                            </button>
                            <button wire:click="editSolicitud('{{$showSolicitud->solicitud_id}}')" 
                                    class="flex text-center justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
                                    title="Editar Solictiud">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                            <button wire:click="confirmDelete('{{$showSolicitud->solicitud_id}}')" 
                                    class="flex text-center justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm"
                                    title="Eliminar Solicitud">
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                            <label class="block text-xl font-bold text-gray-700 mb-2">Título</label>
                            <div class="">
                                <p class="text-gray-900 font-medium">{{ $showSolicitud->titulo }}</p>
                            </div>
                        </div>                        
                        <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                            <label class="block text-xl font-bold text-gray-700 mb-2">Categoría</label>
                            <div class="">
                                <p class="text-gray-900 font-medium">
                                    {{ $showSolicitud->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin categoría' }} - {{ $showSolicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute() ?? 'Sin subcategoría' }}</p>
                            </div>
                        </div>  
                    </div>

                    <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg rounded-lg p-3">
                        <label class="block text-xl font-bold text-gray-700 mb-2">Descripción</label>
                        <div class="">
                            <p class="text-gray-900 font-medium">{{ $showSolicitud->descripcion }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg rounded-lg p-3">
                            <label class="block text-xl font-bold text-gray-700 mb-2">Ubicación</label>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <p class="text-gray-900 font-medium"><span class="text-gray-700 font-bold">País: </span>{{ $showSolicitud->pais }}</p>
                                <p class="text-gray-900 font-medium"><span class="text-gray-700 font-bold">Estado: </span>{{ $showSolicitud->estado_region }}</p>
                                <p class="text-gray-900 font-medium"><span class="text-gray-700 font-bold">Municipio: </span>{{ $showSolicitud->municipio }}</p>
                                <p class="text-gray-900 font-medium"><span class="text-gray-700 font-bold">Parroquia: </span>{{ $showSolicitud->comunidadRelacion->getParroquiaFormattedAttribute() }}</p>
                                <p class="text-gray-900 font-medium"><span class="text-gray-700 font-bold">Comunidad: </span>{{ $showSolicitud->comunidadRelacion->getComunidadFormattedAttribute() }}</p>
                            </div>
                            <p class="text-gray-900 font-medium mt-1"><span class="font-bold">Dirección: </span>{{ $showSolicitud->direccion_detallada }}</p>
                        </div>
                        <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                            <label class="block text-xl font-bold text-gray-700 mb-2">Etiquetas</label>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-2">
                                <span class="flex items-center text-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors 
                                    {{$showSolicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 border-yellow-300' : 
                                    ($showSolicitud->estatus === 2 ? 'bg-green-100 text-green-800 hover:bg-green-200 green-gray-300' : 
                                    ($showSolicitud->estatus === 3 ? 'bg-red-100 text-red-800 hover:bg-red-200 border-red-300' : 'text-gray-700 border-gray-300 hover:bg-gray-100'))}}">
                                    {{ $showSolicitud->getEstatusFormattedAttribute() ?? 'Sin estatus'}}
                                </span>
                                @if($showSolicitud->derecho_palabra)
                                    <span class="text-center items-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors bg-blue-300/20 hover:bg-blue-200 text-blue-800">
                                        Derecho de Palabra
                                    </span>
                                @endif
                                @if($showSolicitud->asignada_visita)
                                    <span class="text-center items-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors bg-blue-300/20 hover:bg-blue-200 text-blue-800">
                                        Asignada a Visitas
                                    </span>
                                @endif
                                <span class="text-center items-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors bg-gray-300/20 hover:bg-blue-200 text-blue-800">
                                    Solicitud {{ $showSolicitud->getTipoSolicitudFormattedAttribute()}}
                                </span>
                                @if($showSolicitud->fecha_actualizacion_usuario)
                                    <span class="text-center items-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors bg-gray-300/20 hover:bg-gray-200 text-gray-800">
                                        Editado por el solicitante
                                    </span>
                                @endif
                                @if($showSolicitud->fecha_actualizacion_super_admin)
                                    <span class="text-center items-center justify-center px-3 py-2 rounded-xl text-md font-medium border transition-colors bg-gray-300/20 hover:bg-gray-200 text-gray-800">
                                        Editado por la Administración
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3 grid grid-cols-1 lg:grid-cols-3">
                        <div>
                            <label class="block text-md font-bold text-gray-700 mb-2">Fecha de Creación</label>
                            <div>
                            <p class="text-gray-900">{{ $showSolicitud->fecha_creacion->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-md font-bold text-gray-700 mb-2">Última Actualización del Usuario</label>
                            <p class="text-gray-900">@if($showSolicitud->fecha_actualizacion_usuario) {{$showSolicitud->fecha_actualizacion_usuario->format('d/m/Y H:i') }} @else N/A @endif</p>
                            
                        </div>
                        <div>
                            <label class="block text-md font-bold text-gray-700 mb-2">Última Actualización de la Administración</label>
                            <p class="text-gray-900">@if($showSolicitud->fecha_actualizacion_super_admin) {{$showSolicitud->fecha_actualizacion_super_admin->format('d/m/Y H:i') }} @else N/A @endif</p>
                           
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-4">
                    <div class="p-6 space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-user text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Datos del Solicitante</h3>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                                <label class="block text-xl font-bold text-gray-700 mb-2">{{$showSolicitud->persona->nombre}} {{$showSolicitud->persona->apellido}}</label>
                                <div class="">
                                    <p class="text-gray-900 font-medium">{{ $showSolicitud->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{$showSolicitud->persona->cedula}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-4">
                    <div class="p-6 space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-group text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Reuniones Asociadas</h3>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            
                            @if(count($showSolicitud->reunionRelacion ) > 0)
                                @foreach ($showSolicitud->reunionRelacion as $reunionRelacions)
                                    <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                                        <label class="block text-xl font-bold text-gray-700 mb-2">{{ Str::title($reunionRelacions->titulo) }}</label>
                                        <div class="">
                                            <p class="text-gray-900 font-medium">{{$reunionRelacions->tituloEstatus()}} - {{$reunionRelacions->created_at->format('d/m/Y H:i')}}</p>
                                        </div>
                                        <a href="{{ route('dashboard.reuniones.show', $reunionRelacions->id) }}"
                                            class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                                            title="Editar Solictiud">
                                            <span class="max-lg:hidden">Ver Más Detalles</span>
                                            <i class='bx bx-link-external lg:mr-2'></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <i class='bx bx-group text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay reuniones asociadas</h3>
                                    <p class="text-gray-500">Esta solicitud no tiene reuniones vinculadas.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>{{-- 
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-4">
                    <div class="p-6 space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class='bx bx-user-pin text-blue-600 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Visitas Asociadas</h3>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            @if(count($showSolicitud->visitasRelacion ) > 0)
                                <div class="bg-white transition-shadow shadow-sm hover:shadow-xl border border-gray-200 rounded-lg p-3">
                                    <label class="block text-xl font-bold text-gray-700 mb-2">{{$showSolicitud->persona->nombre}} {{$showSolicitud->persona->apellido}}</label>
                                    <div class="">
                                        <p class="text-gray-900 font-medium">{{ $showSolicitud->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{$showSolicitud->persona->cedula}}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <i class='bx bx-user-pin text-4xl text-gray-400 mb-4'></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay visitas asociadas</h3>
                                    <p class="text-gray-500">Esta solictud no tiene visitas vinculadas.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
            </div>
        @endif
    </div>
</div>