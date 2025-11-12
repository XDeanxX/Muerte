<div class="min-h-screen bg-gray-50">
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
            </div>
        </div>
    </div>

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
            <div class="bg-gradient-to-r from-rose-50 to-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-rose-500 rounded-full flex items-center justify-center mr-3">
                        <i class='bx bx-check text-white text-lg'></i>
                    </div>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
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
                <div class="overflow-x-auto max-lg:pb-4">
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
                                    <div class="flex items-center justify-end">
                                        Estatus
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex justify-end items-end">
                                        <i class='bx bx-cog mr-2'></i>
                                        Acciones
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center justify-end">
                                            {{ $solicitudeRender->getEstatusFormattedAttribute()}}
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="viewSolicitud('{{ $solicitudeRender->solicitud_id }}')" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Ver detalles">
                                                <i class='bx bx-search-alt-2 text-xl'></i>
                                            </button>
                                            <button wire:click="donwloadPDFSolicitud('{{ $solicitudeRender->solicitud_id }}')"
                                            class="p-2 rounded-full text-orange-600 hover:text-orange-900 hover:bg-orange-50 transition-colors"
                                            title="Descargar PDF">
                                                <i class='bx bx-download text-xl'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($solicitudesRender->isEmpty() && $solicitudesRender->currentPage() == 1)
                        <div class="text-center py-8">
                            <i class='bx bx-file text-4xl text-gray-400 mb-4'></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay solicitudes</h3>
                            <p class="text-gray-500">
                                No se encontraron solicitudes en el sistema
                            </p>
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
                                <span class="max-lg:hidden">Regresar Atras</span>
                            </button>
                            <button wire:click="donwloadPDFSolicitud('{{$showSolicitud->solicitud_id}}')" 
                                    class="flex text-center justify-center items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm"
                                    title="Imprimir en PDF">
                                <i class='bx bx-cloud-download text-xl'></i>
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