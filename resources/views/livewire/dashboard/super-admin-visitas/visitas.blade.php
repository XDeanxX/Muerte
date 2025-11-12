<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-center">

            <div class="flex flex-col items-center {{ $paso >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-file-blank text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Solicitud </span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso >= 2 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>


            <div class="flex flex-col items-center {{ $paso >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-calendar-check text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Detalles Solicitud </span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso >= 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $paso >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-user text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Encargados</span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso > 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $paso > 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso > 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-timer text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Fecha</span>
            </div>
        </div>
    </div>

    @if ($paso == 1)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">

            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-check-shield text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 1: Selecciona una Solicitud Aprobada</h2>
                <p class="text-gray-600">Haz clic en el ticket que deseas gestionar para ver sus detalles en el
                    siguiente paso.</p>
            </div>

            <div class="mb-6 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título o id......"
                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class='bx bx-search text-gray-400'></i>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse ($solicitudAproved as $solicitud)
                <button wire:click="selectSolicitud('{{ $solicitud->solicitud_id }}')" type="button"
                    title="Seleccionar Solicitud: {{ $solicitud->titulo }}"
                    class="p-4 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 text-left 
                            border-2 
                            {{ ($selectedSolicitudId ?? null) == $solicitud->solicitud_id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-500 bg-white hover:bg-gray-50' }} ">

                    <p class="text-xs font-mono text-gray-500 uppercase">Ticket No.</p>
                    <p class="text-lg font-bold text-blue-600 mb-2">{{ $solicitud->solicitud_id }}</p>
                    <p class="text-sm font-semibold text-gray-600 line-clamp-2">{{ $solicitud->titulo }}</p>
                </button>
                @empty
                <div class="col-span-full text-center py-10 bg-gray-50 border border-gray-200 rounded-lg">
                    <i class='bx bx-info-circle text-4xl text-yellow-600 mb-2'></i>
                    <p class="text-gray-600 font-medium">No se encontraron solicitudes.</p>
                </div>
                @endforelse
            </div>

            @if($solicitudAproved->hasPages())
            <div class="mt-8 pt-4 border-t border-gray-200">
                {{ $solicitudAproved->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    @if ($paso == 2 && $selectedSolicitud)
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">

            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-file-find text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Paso 2: Detalles de la Solicitud Seleccionada
                </h2>
                <p class="text-gray-600">Revisa cuidadosamente la información antes de continuar con la asignación de la
                    visita técnica.</p>
            </div>

            <div class="space-y-8">

                <div class="p-6 border border-blue-100 rounded-xl bg-blue-50 shadow-inner">
                    <h3 class="text-xl font-bold text-blue-800 mb-4 pb-2 border-b border-blue-200 flex items-center">
                        <i class='bx bx-receipt text-2xl text-blue-600 mr-2'></i>
                        Datos Generales del Ticket
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Número de Ticket</p>
                            <p class="text-2xl font-extrabold text-blue-700">{{ $selectedSolicitud->solicitud_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Tipo de Solicitud</p>
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-200 text-indigo-900">
                                {{ ucfirst($selectedSolicitud->tipo_solicitud) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Fecha de Creación</p>
                            <p class="text-base text-gray-700 font-mono">{{ $selectedSolicitud->fecha_creacion }}</p>
                        </div>
                    </div>

                    <hr class="my-6 border-blue-200">

                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase">Título de la Solicitud</p>
                        <p class="text-xl font-extrabold text-gray-800">{{ $selectedSolicitud->titulo }}</p>

                        <p class="text-sm font-semibold text-gray-600 uppercase pt-2">Descripción Detallada</p>
                        <div
                            class="text-base text-gray-700 whitespace-pre-line bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            {{ $selectedSolicitud->descripcion }}
                        </div>
                    </div>
                </div>

                @php
                $personaModel = App\Models\Personas::find($selectedSolicitud->persona_cedula);
                @endphp

                @if ($personaModel)
                <div class="p-6 border border-gray-200 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class='bx bx-user-circle text-2xl text-blue-600 mr-2'></i>
                        Datos del Solicitante
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Nombre Completo</p>
                            <p class="text-lg text-gray-800 font-bold">
                                {{ ucwords(strtolower($personaModel->nombre)) }}
                                {{ ucwords(strtolower($personaModel->segundo_nombre)) }}
                                {{ ucwords(strtolower($personaModel->apellido)) }}
                                {{ ucwords(strtolower($personaModel->segundo_apellido))}}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Cédula de Identidad</p>
                            <p class="text-lg text-gray-800 font-mono font-bold"> {{$personaModel->nacionalidad === 1 ?
                                'V' : 'E'}}-{{ $selectedSolicitud->persona_cedula
                                }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Teléfono de Contacto</p>
                            <p class="text-base text-gray-700">{{ $personaModel->telefono }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="p-6 border border-gray-200 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class='bx bx-map-pin text-2xl text-blue-600 mr-2'></i>
                        Ubicación de la Solicitud
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Comunidad</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->comunidad }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Municipio</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->municipio }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Estado / Región</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->estado_region }}</p>
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-gray-600 uppercase">Dirección Detallada</p>
                    <div class="text-base text-gray-700 bg-white p-4 rounded-lg border border-gray-200 shadow-sm mt-2">
                        {{ $selectedSolicitud->direccion_detallada}}
                    </div>
                </div>

            </div>

            <div class="mt-10 flex justify-between border-t pt-6">
                <button wire:click="previousStep" type="button"
                    class="py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors cursor-pointer group ">
                    <i class='bx bx-arrow-back mr-2 transition-transform group-hover:-translate-x-0.5'></i> Volver a
                    Selección
                </button>

                <button type="button" wire:click="nextStep"
                    class="py-3 px-6 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all group cursor-pointer">
                    Continuar y Asignar Visita
                    <i class='bx bx-check ml-2 transition-transform group-hover:translate-x-0.5'></i>
                </button>
            </div>
        </div>
    </div>
    @endif


    @if ($paso == 3 )
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-8">
        <div class="max-w-4xl mx-auto space-y-6">

            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-group text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 3: Asignar asistente</h2>
                <p class="text-gray-600">
                    Selecciona entre {{ self::MIN_PERSONAS }} y {{ self::MAX_PERSONAS }} personas para conformar el
                    equipo de visita.
                </p>
            </div>

            @php
            $selectedCount = count($personasSelected ?? []);
            $isMaxReached = $selectedCount >= self::MAX_PERSONAS;
            $isMinNotMet = $selectedCount < self::MIN_PERSONAS; @endphp 
            
            <div class="relative max-w-lg mb-6 mx-auto">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por cédula, nombre o apellido..."
                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class='bx bx-search text-gray-400'></i>
                </div>
        </div>
        <div class="text-center text-sm font-semibold">
            <span class="p-1 px-3 bg-blue-50 text-blue-700 rounded-full border border-blue-200">
                Seleccionados: {{ $selectedCount }} / 5
            </span>
        </div>

        @if($isMaxReached)
        <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 border border-yellow-200 font-medium"
            role="alert">
            <i class='bx bx-error-alt mr-1'></i> Límite Máximo Alcanzado: Has seleccionado el máximo de
            {{self::MAX_PERSONAS }} técnicos.
        </div>
        @elseif ($isMinNotMet)
        <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 font-medium" role="alert">
            <i class='bx bx-alert-circle mr-1'></i> Requerido: Debes seleccionar al menos {{ self::MIN_PERSONAS
            }} asistente para continuar.
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse($visitors as $admin)

            @php($isSelected = in_array($admin->persona_cedula, $personasSelected ?? []))

            @php(
            $isDisabled = ($selectedCount >= self::MAX_PERSONAS) && !$isSelected
            )

            <label
                class="relative flex items-center p-4 rounded-lg transition-all duration-200 cursor-pointer h-full border-2 
                    {{ $isSelected
                        ? 'border-blue-600 bg-blue-50 transform shadow-xl ring-2 ring-blue-500/50' 
                        : ($isDisabled ? 'border-gray-100 bg-gray-50 opacity-60 cursor-not-allowed' : 'border-gray-200 bg-white hover:border-blue-400 hover:bg-gray-50 shadow-md') }}"
                title="Seleccionar visitante: {{ $admin->persona_cedula }}">

                <input type="checkbox" wire:model.live="personasSelected" value="{{ $admin->persona_cedula }}" {{
                    $isDisabled ? 'disabled' : '' }}
                    class="absolute top-4 right-4 h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 z-10 cursor-pointer disabled:opacity-50" />

                <div class="flex-grow pr-8 text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Encargado.</p>

                    <div class="mb-2">
                        <p class="text-lg font-bold text-blue-600 leading-tight">V- <span class="text-blue-500">{{
                                $admin->persona_cedula }}</span></p>

                        <div class="flex items-center text-sm font-semibold text-gray-700 mt-1">
                            <i class="bx bx-user-circle mr-1 text-gray-500"></i>
                            <p class="line-clamp-2">{{ $admin->persona->nombre }} {{ $admin->persona->apellido}}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center text-sm font-medium mt-2 
                        {{ $isSelected ? 'text-blue-700 font-bold' : 'text-gray-600' }}">

                        <i class="bx {{ $admin->role === 1 ? 'bx-hard-hat' : 'bxs-group' }} mr-1 text-base"></i>

                        {{ $admin->role === 1 ? 'Administrador' : 'Asistente' }}
                    </div>
                </div>
            </label>
            @empty
            <div class="col-span-full text-center py-6 bg-gray-50 border border-gray-200 rounded-lg">
                <i class='bx bx-info-circle text-4xl text-yellow-500 mb-2'></i>
                <p class="text-gray-600 font-medium">No hay personal disponible para asignar la visita.</p>
                <p class="text-xs text-gray-500 mt-1">Intenta con otro término de búsqueda.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200">
            {!! $visitors->links(data:['pageName'=>'userPage']) !!}
        </div>
        <div class="mt-10 flex justify-between border-t pt-6">
            <button wire:click="previousStep" type="button"
                class="py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors group">
                <i class='bx bx-arrow-back mr-2 transition-transform group-hover:-translate-x-0.5'></i> Volver atras
            </button>

            <button type="button" wire:click="nextStep" {{ $isMinNotMet ? 'disabled' : '' }} class="py-3 px-6 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white transition-all group 
                            {{ $isMinNotMet ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} cursor-pointer">
                Siguiente <i class='bx bx-right-arrow-alt ml-2 transition-transform group-hover:translate-x-0.5'></i>
            </button>
        </div>
    </div>
</div>
@endif

@if ($paso == 4)
<div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-8">
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class='bx bx-calendar-check text-blue-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 4: Programar la Visita</h2>
            <p class="text-gray-600">
                Define el rango de fechas para la ejecución de la visita técnica.
            </p>
        </div>



        <form wire:submit.prevent="saveVisit" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="fecha_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Inicio
                    </label>
                    <input type="date" id="fecha_inicial" wire:model.blur="initDate" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">

                    @error('initDate')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha_final" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Finalización
                    </label>
                    <input type="date" id="fecha_final" wire:model.blur="finalDate" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 transition duration-150">

                    @error('finalDate')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div
                class="bg-blue-50 border border-blue-200 p-6 rounded-xl shadow-lg transition-all duration-300 space-y-4">

                <div class="flex items-start text-sm text-blue-800 font-semibold border-b border-blue-200 pb-3">
                    <i class='bx bxs-lock-alt text-lg mr-2 mt-0.5'></i>
                    <p>
                        ¡Atención! Una vez creado el registro, la asignación de esta visita no podrá modificarse.
                        Revise los datos cuidadosamente.
                    </p>
                </div>

                <div class="space-y-1">
                    <p class="text-xs font-medium text-gray-500 uppercase">Solicitud a Asignar:</p>
                    <p class="text-lg font-bold text-gray-900 leading-tight">
                        {{ $selectedSolicitud->titulo ?? "N/A" }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-blue-100 pt-4">

                    <div>
                        <p class="text-xs font-medium text-gray-500">N° de Ticket</p>
                        <p class="text-base font-bold text-blue-600">
                            #{{ $selectedSolicitud->solicitud_id ?? "N/A" }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500">Direccion</p>
                        <p class="text-base font-bold text-blue-600">
                            #{{ $selectedSolicitud->direccion_detallada ?? "N/A" }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium text-gray-500">Personal Asignado</p>
                        <p class="text-base font-bold {{ $selectedCount > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $selectedCount }} Visitador{{ $selectedCount !== 1 ? 'es' : '' }}
                        </p>
                    </div>

                </div>

                @if(isset($initDate) && isset($finalDate))
                <div class="border-t border-blue-100 pt-3">
                    <p class="text-xs font-medium text-gray-500 mb-1">Período Programado:</p>
                    <p class="text-sm font-semibold text-gray-700">
                        Desde el {{ \Carbon\Carbon::parse($initDate)->isoFormat('D MMM YYYY') }}
                        hasta el {{ \Carbon\Carbon::parse($finalDate)->isoFormat('D MMM YYYY') }}
                    </p>
                </div>
                @endif

            </div>

            <div class="mt-10 flex justify-between border-t pt-6">
                <button wire:click="previousStep" type="button"
                    class="py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors group">
                    <i class='bx bx-arrow-back mr-2 transition-transform group-hover:-translate-x-0.5'></i> Volver a
                    Atras
                </button>

                <button type="submit"
                    class="py-3 px-6 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white transition-all group bg-blue-600 hover:bg-blue-600 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 cursor-pointer">
                    Confirmar y Guardar Visita
                    <i class='bx bx-check-double ml-2 transition-transform group-hover:translate-x-0.5'></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endif

</div>