<div class="bg-white rounded-xl shadow-xl border border-gray-200">

    {{-- Encabezado del Componente --}}
    <div class="bg-white shadow-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-5">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class='bx bx-calendar-check text-white text-xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Visitas programadas</h1>
                        <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenido de la Lista de Solicitudes/Visitas --}}
    <div class="p-4 sm:p-6 lg:p-8">

        @foreach ($visita as $v)

        <div
            class="mb-6 last:mb-0 border border-gray-200 rounded-lg p-4 sm:p-5 bg-white shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4">


                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                        Ticket: <span class="text-blue-700 ml-1 font-bold">{{$v->solicitud_id}}</span>
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-snug truncate" title="{{$v->titulo}}">
                        {{$v->titulo}}</p>
                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                        <i class='bx bx-current-location text-base mr-1 text-blue-500 flex-shrink-0'></i>
                        <span class="truncate">{{$v->direccion_detallada}}</span>
                    </p>

                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center">
                            <i class='bx bx-category mr-1'></i>
                            {{ $v->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin categoría' }}
                        </span>
                        <span class="flex items-center">
                            <i class='bx bx-map-pin mr-1'></i>
                            {{ $v->comunidadRelacion->getParroquiaFormattedAttribute() }}
                        </span>
                    </div>
                </div>


                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 text-sm pt-2 lg:pt-0">

                    <div class="flex gap-4">

                        <div class="flex flex-col items-start">
                            <span class="text-xs font-medium text-gray-500 uppercase mb-1">Estado</span>
                            @php
                            $estatusId = $v->visita->estatus_id ?? 0;
                            $clases = $estatusId == 1 ? 'bg-yellow-100 text-yellow-800 border border-yellow-300'
                            : ($estatusId == 6 ? 'bg-green-100 text-green-800 border border-green-300' // Terminada
                            : ($estatusId == 2 ? 'bg-blue-100 text-blue-800 border border-blue-300' // En Progreso
                            : 'bg-gray-100 text-gray-800 border border-gray-300'));
                            
                            $texto = $estatusId == 1 ? 'Pendiente'
                            : ($estatusId == 6 ? 'Terminada'
                            : ($estatusId == 2 ? 'En Progreso'
                            : 'Estatus ID: ' . $estatusId));
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $clases }}">
                                {{ $texto }}
                            </span>
                        </div>

                        <div class="flex flex-col items-start sm:items-end">
                            <span class="text-xs font-medium text-gray-500 uppercase mb-1">Aproximación visita</span>
                            <span class="text-base font-semibold text-gray-800 flex items-center whitespace-nowrap">
                                <i class='bx bx-calendar text-lg mr-1 text-blue-600'></i>
                                Del {{ $v->visita->fecha_inicial ?? 'N/A' }} Al {{ $v->visita->fecha_final ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <button type="button" wire:click="toggleDetails('{{$v->solicitud_id}}')"
                        class="w-full sm:w-auto p-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-md flex items-center justify-center flex-shrink-0 cursor-pointer"
                        title="{{ $openSolicitudId === $v->solicitud_id ? 'Ocultar Detalles' : 'Ver detalles de la Visita' }}">

                        <i @class([ 'bx text-xl transition-transform duration-300' , 'bx-chevron-up'=>
                            $openSolicitudId === $v->solicitud_id,
                            'bx-search-alt-2' => $openSolicitudId !== $v->solicitud_id
                            ])></i>
                        <span class="ml-2 hidden sm:inline">
                            {{ $openSolicitudId === $v->solicitud_id ? 'Ocultar' : 'Ver Detalles' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Panel de Detalles (Abierto) --}}
        @if ($openSolicitudId === $v->solicitud_id && $selectedSolicitud)
        <div
            class="bg-blue-50/50 p-4 sm:p-5 border-x border-b border-blue-100 rounded-b-lg mb-6 last:mb-0 -mt-6 pt-8 shadow-inner">

            <h4 class="text-lg font-bold text-blue-800 mb-3">Detalles de la Solicitud</h4>
            
            <p class="text-gray-700 mb-4">{{ $selectedSolicitud->descripcion ?? 'Sin descripción detallada.' }}</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 text-sm">  

                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase block">Tipo Solicitud</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ $selectedSolicitud->tipo_solicitud }}</span>
                </div>

                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase block">Fecha Solicitud</span>
                    <span class="font-semibold text-gray-800">{{ $selectedSolicitud->fecha_creacion->format('d/m/Y') }}</span>
                </div>
            </div>


            {{-- 💡 SECCIÓN: Opciones de Actualización para Asistente --}}
            @php
            // ✅ CORREGIDO: Usamos 'cedula' para verificar si el usuario logueado es asistente.
            $isUserAsistente = $selectedSolicitud->visita
            ? $selectedSolicitud->visita->asistente->contains('cedula', $userCedula)
            : false;
            @endphp

            @if ($isUserAsistente)
            <div class="mt-8 pt-4 border-t border-blue-200">
                <h4 class="text-lg font-bold text-green-700 mb-3 flex items-center">
                    <i class='bx bx-user-check mr-2'></i>
                    Opciones de Asistente de Campo
                </h4>

                @if (!$showObservationForm)
                    {{-- Botones de acción estándar (solo si el formulario de finalización no está abierto) --}}
                    <p class="text-sm text-gray-600 mb-4">
                        Actualice el estado de la visita según su progreso:
                    </p>

                    <div class="flex flex-wrap gap-4">

                        @if (($selectedSolicitud->visita->estatus_id ?? 0) == 1)
                        {{-- Botón para cambiar a "En Progreso" (Estatus ID: 2) --}}
                        <button wire:click="updateStatus('{{ $selectedSolicitud->solicitud_id }}', 2)"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors shadow-md">
                            <i class='bx bx-loader-circle mr-2'></i>
                            Marcar como "En Progreso"
                        </button>
                        @endif

                        @if (($selectedSolicitud->visita->estatus_id ?? 0) != 6)
                        {{-- Botón que abre el formulario para finalizar --}}
                        <button wire:click="openFinishForm('{{ $selectedSolicitud->solicitud_id }}')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md">
                            <i class='bx bx-check-circle mr-2'></i>
                            Finalizar Visita (Requiere Observación)
                        </button>
                        @endif

                    </div>
                @endif
                
                {{-- 💡 Formulario para ingresar la observación antes de finalizar --}}
                @if ($showObservationForm && $solicitudToFinalize === $selectedSolicitud->solicitud_id)
                    <div class="mt-6 p-4 bg-white border border-green-200 rounded-lg shadow-inner">
                        <h5 class="font-bold text-green-700 mb-3">Observación de Finalización</h5>

                        <div class="mb-4">
                            <label for="finalObservation" class="block text-sm font-medium text-gray-700">
                                Observaciones de la Visita (Mín. 10 caracteres):
                            </label>
                            <textarea id="finalObservation" wire:model.live="finalObservation" rows="3"
                                class="mt-1 block w-full rounded-md border-blue-400 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                placeholder="Describa el resultado y los detalles de la visita..."></textarea>
                            @error('finalObservation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button wire:click="$set('showObservationForm', false)"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                Cancelar
                            </button>
                            <button wire:click="finishVisit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class='bx bx-save mr-2'></i>
                                Guardar y Finalizar Visita
                            </button>
                        </div>
                    </div>
                @endif


            </div>
            @else
            <div class="mt-8 pt-4 border-t border-blue-200">
                <h4 class="text-lg font-bold text-gray-600 mb-3 flex items-center">
                    <i class='bx bx-info-circle mr-2'></i>
                    Equipo Asignado
                </h4>
                <p class="text-sm text-gray-500 mb-2">
                    Esta solicitud tiene {{ $selectedSolicitud->visita->asistente->count() ?? 0 }} asistente(s)
                    asignado(s).
                </p>
                <ul class="list-disc list-inside text-sm text-gray-700 ml-4">
                    @forelse ($selectedSolicitud->visita->asistente ?? [] as $asistente)
                    <li>{{ $asistente->user->nombre}} {{ $asistente->user->apellido}} {{ $asistente->user->segundo_nombre}} {{ $asistente->user->segundo_apellido}}(Cédula: {{
                        $asistente->cedula }})</li>
                    @empty
                    <li>No hay asistentes registrados para esta visita.</li>
                    @endforelse
                </ul>
            </div>
            @endif

            <h4 class="text-sm font-semibold text-gray-600 mt-6 mb-2">Observaciones del Administrador:</h4>
            <p class="text-xs text-gray-500 italic border-l-2 border-yellow-500 pl-2">
                {{ $selectedSolicitud->observaciones_admin ?? 'Ninguna observación registrada.' }}
            </p>

        </div>
        @endif

        @endforeach

        {{-- Manejo de lista vacía --}}
        @if ($visita->isEmpty())
        <div class="py-12 text-center text-gray-500">
            <i class='bx bx-calendar-x text-6xl mb-4'></i>
            <p class="text-xl font-semibold">No tiene visitas asignadas ni solicitudes pendientes.</p>
            <p>Verifique si las visitas ya han sido finalizadas o consulte al administrador.</p>
        </div>
        @endif
        
        <div class="mt-6">
            {{ $visita->links() }}
        </div>

    </div>

</div>