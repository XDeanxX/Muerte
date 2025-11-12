@csrf

<style>
    /* Estilos personalizados para formularios reactivos */
    .form-input {
        @apply block w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-gray-900 bg-white;
    }
    
    .form-input.error {
        @apply border-red-300 focus:border-red-500 focus:ring-red-200 bg-red-50;
    }
    
    .form-input.success {
        @apply border-green-300 focus:border-green-500 focus:ring-green-200 bg-green-50;
    }
    
    .form-input.loading {
        @apply border-yellow-300 bg-yellow-50;
    }
    
    .form-group {
        @apply space-y-3;
    }
    
    .form-label {
        @apply block text-sm font-semibold text-gray-700 flex items-center;
    }
    
    .form-label.required::after {
        content: " *";
        @apply text-red-500 ml-1;
    }
    
    .error-message {
        @apply text-red-600 text-sm flex items-center space-x-1 mt-2;
    }
    
    .success-message {
        @apply text-green-600 text-sm flex items-center space-x-1 mt-2;
    }
    
    .warning-message {
        @apply text-yellow-600 text-sm flex items-center space-x-1 mt-2;
    }
    
    .checkbox-custom, .radio-custom {
        @apply w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200;
    }
    
    .radio-custom {
        @apply rounded-full;
    }
    
    .card-section {
        @apply bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 transition-all duration-200 hover:shadow-md;
    }
    
    .section-header {
        @apply flex items-center mb-6 pb-4 border-b border-gray-100;
    }
    
    .section-icon {
        @apply w-12 h-12 rounded-full flex items-center justify-center mr-4 shadow-lg;
    }
    
    .field-counter {
        @apply text-xs text-gray-400 mt-1;
    }
    
    .input-icon {
        @apply absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none;
    }
    
    .select-wrapper {
        @apply relative;
    }
    
    .select-wrapper::after {
        content: '';
        @apply absolute right-3 top-1/2 transform -translate-y-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-400 pointer-events-none;
    }
    
    .participant-card {
        @apply flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 cursor-pointer;
    }
    
    .participant-card.selected {
        @apply bg-blue-50 border-blue-300;
    }
    
    .participant-card.concejal {
        @apply bg-green-50 border-green-300;
    }
        @supports(-webkit-appearance: none) or (-moz-appearance: none) {
            .checkboxCards,
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
            .checkboxCards {
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

<div class="space-y-6">
    <!-- Información Básica -->
    <div class="card-section">
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-info-circle text-blue-600 text-xl'></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Información Básica</h3>
                    <p class="text-sm text-gray-600">Datos principales de la reunión</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="flex flex-col">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-edit-alt mr-2 text-blue-600'></i>
                            Título de la Reunión
                        </label>
                        <div class="flex justify-between">
                            <input type="text" 
                                name="titulo" 
                                id="titulo" 
                                value="{{ old('titulo', $reunion->titulo ?? '') }}" 
                                class="{{ $errors->has('titulo') ? 'error' : '' }} font-medium text-gray-900 focus:outline-none"
                                placeholder="Ej: Reunión de seguimiento sobre solicitud de agua potable"
                                required
                                maxlength="255"
                                data-validation="required|min:5|max:255">
                            <div id="titulo-counter" class="field-counter text-right">0/255</div>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Ingrese un título descriptivo y claro para la reunión</p>
                    </div>
                    @error('titulo')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <!-- Tipo de Reunión -->
                <div class="flex flex-col">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                        <label for="tipo_reunion_id" class="block text-sm font-medium text-gray-700">
                            <i class='bx bx-buildings mr-2 text-green-600'></i>
                            Tipo de Reunión
                        </label>
                        <div class="">
                            <select name="tipo_reunion" 
                                    id="tipo_reunion_id" 
                                    class="{{ $errors->has('tipo_reunion') ? 'error' : '' }} w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required
                                    data-validation="required">
                                <option value="" disabled selected>Seleccione un tipo...</option>
                                @foreach($tipoReunion as $id => $tipoReunions)
                                    <option value="{{ $id }}" 
                                            {{ (old('tipo_reunion', $reunion->tipo_reunion ?? '') == $id) ? 'selected' : '' }}
                                            data-title="{{ $tipoReunions->titulo }}">
                                        {{ Str::title($tipoReunions->titulo) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Tipo de Reunión</p>
                    </div>
                    
                    @error('tipo_reunion')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <!-- Fecha de Reunión -->
                <div class="flex flex-col">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                        <label for="fecha_reunion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-calendar mr-2 text-blue-600'></i>
                            Fecha de la Reunión
                        </label>
                        <div class="relative">
                            <input type="date" 
                                name="fecha_reunion" 
                                id="fecha_reunion" 
                                value="{{ old('fecha_reunion', isset($reunion) ? $reunion->fecha_reunion->format('Y-m-d') : '') }}" 
                                class="{{ $errors->has('fecha_reunion') ? 'error' : '' }} font-medium text-gray-900 focus:outline-none"
                                required
                                min="{{ date('Y-m-d') }}"
                                data-validation="required|future">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Debe ser una fecha futura</p>
                    </div>
                    @error('fecha_reunion')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <!-- Horarios -->
                <div class="flex flex-col">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition duration-150 ease-in-out">
                        <label for="hora_reunion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-time mr-2 text-blue-600'></i>
                            Hora de la Reunión
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="time"   
                                    name="hora_reunion" 
                                    id="hora_reunion" 
                                    value="{{ old('hora_inicio', isset($reunion) && $reunion->hora_reunion ? $reunion->hora_reunion : '') }}" 
                                    class="{{ $errors->has('hora_reunion') ? 'error' : '' }} font-medium text-gray-900 focus:outline-none"
                                    required
                                    data-validation="required">
                                <p class="text-gray-500 text-xs mt-1">Hora de inicio</p>
                            </div>
                        </div>
                    </div>
                    @error('hora_reunion')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Relaciones -->
    <div class="card-section">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
            <i class='bx bx-link-alt text-blue-600 text-xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Relaciones y Contexto</h3>
                <p class="text-sm text-gray-600">Vincule la reunión con solicitudes e instituciones</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Solicitudes Asociadas (Múltiples) -->
                <div class="form-group">
                    <div class="flex flex-col mb-2">
                        <label for="solicitudes" class="block text-lg font-medium mb-2">
                            Seleccionar Solicitudes Pendientes
                        </label>
                        
                        <!-- Búsqueda de solicitudes -->
                        <div class="flex items-center justify-between">
                            <div id="solicitudes-counter" class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                <span id="solicitudes-count">0</span> solicitudes seleccionadas
                            </div>
                            <div class="bg-white px-4 py-2 rounded-lg border border-gray-200">
                                <i class='bx bx-search text-gray-900 input-icon mr-2'></i>
                                <input type="text" 
                                    id="solicitudes-search" 
                                    class="font-medium text-gray-900 focus:outline-none"
                                    placeholder="Buscar solicitud por título o ID...">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lista de solicitudes con checkboxes -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="max-h-64 overflow-y-auto grid sm:grid-cols-1 grid-cols-2 lg:grid-cols-3 gap-2" id="solicitudes-list">
                            @foreach($solicitudes as $solicitud)
                                <div class="js-solicitud-container solicitud-item border-2 flex items-start p-3 rounded-lg border hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer"
                                    data-title="{{ strtolower($solicitud->titulo) }}"
                                    data-id="{{ strtolower($solicitud->solicitud_id) }}">
                                    <input type="checkbox" 
                                        name="solicitudes[]" 
                                        value="{{ $solicitud->solicitud_id }}" 
                                        id="solicitud_{{ $solicitud->solicitud_id }}"
                                        class="checkbox-custom mr-3 solicitud-checkbox js-solicitud-checkbox hidden"
                                        {{ (isset($reunion) && $reunion->solicitudes->contains('solicitud_id', $solicitud->solicitud_id)) ? 'checked' : '' }}>
                                    <label for="solicitud_{{ $solicitud->solicitud_id }}" class="cursor-pointer flex-1">
                                        <div class="font-medium text-gray-900 text-md flex items-center justify-between">
                                            {{ Str::limit($solicitud->titulo, 70) }}
                                            <span class="text-white text-sm bg-blue-600 p-1 rounded-lg">
                                                {{$solicitud->getEstatusFormattedAttribute()}}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <i class='bx bx-id-card mr-1'></i>
                                            Ticket: {{ $solicitud->solicitud_id }}
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-solicitudes-found" class="hidden text-center py-8">
                            <i class='bx bx-search text-gray-400 text-3xl mb-2'></i>
                            <p class="text-gray-500">No se encontraron solicitudes</p>
                        </div>
                    </div>
                    
                    <!-- Contador de solicitudes seleccionadas -->
                    <div class="flex justify-end gap-2 items-center mt-2">
                        <input name="notificar_solicitantes" id="s1-notificar_solicitante" type="checkbox" class="switch checkboxCards">
                        <label for="s1-notificar_solicitante">Notificar a los Solicitantes</label>
                        <i class="bx bx-help-circle text-lg p-1 rounded-lg hover:bg-gray-200" title="Esta opción envia una notificación al solicitante para convocarlo a la reunión"></i>
                    </div>
                    
                    @error('solicitudes')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>

                <!-- Institución Responsable -->
                <div class="form-group">
                    <div class="flex flex-col mb-2">
                        <label for="instituciones" class="block text-lg font-medium mb-2">
                            Convocar Instituciones
                        </label>
                        
                        <!-- Búsqueda de instituciones -->
                        <div class="flex items-center justify-between">
                            <div id="instituciones-counter" class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                <span id="instituciones-count">0</span> instituciones seleccionadas
                            </div>
                            <div class="bg-white px-4 py-2 rounded-lg border border-gray-200">
                                <i class='bx bx-search text-gray-900 input-icon mr-2'></i>
                                <input type="text" 
                                    id="instituciones-search" 
                                    class="font-medium text-gray-900 focus:outline-none"
                                    placeholder="Buscar institucion por nombre...">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lista de instituciones con checkboxes -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="max-h-64 overflow-y-auto grid sm:grid-cols-1 grid-cols-2 lg:grid-cols-3 gap-2" id="instituciones-list">
                            @foreach($instituciones as $id => $institucion)
                                <div class="js-instituciones-container flex items-start p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer"
                                    data-title="{{ strtolower($institucion) }}"
                                    data-id="{{ strtolower($id) }}">
                                    <input type="checkbox" 
                                        name="instituciones[]" 
                                        value="{{ $id }}" 
                                        id="institucion_{{ $id }}"
                                        class="checkbox-custom mr-3 instituciones-checkbox js-instituciones-checkbox hidden"
                                        {{ (isset($reunion) && $reunion->instituciones->contains('id', $id)) ? 'checked' : '' }}>
                                    <label for="institucion_{{ $id }}" class="cursor-pointer flex-1">
                                        <div class="font-medium text-gray-900 text-md">
                                            {{ Str::limit($institucion, 70) }}
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-institucion-found" class="hidden text-center py-8">
                            <i class='bx bx-search text-gray-400 text-3xl mb-2'></i>
                            <p class="text-gray-500">No se encontraron instituciones</p>
                        </div>
                    </div>
                    
                    @error('instituciones')
                        <div class="px-4 py-2">
                            <div class="flex justify-end items-center text-red-600 text-sm mt-1">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Descripcion y Participantes -->
    <div class="card-section">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-detail text-blue-600 text-xl'></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Descripción y Objetivos</h3>
                <p class="text-sm text-gray-600">Describir el objetivo de la reunión</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-6">
            <!-- Descripción -->
            <div class="form-group lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Detalles opcionales sobre objetivos y agenda de la reunión</label>
                <div class="relative">
                    <textarea name="descripcion" 
                            id="descripcion" 
                            rows="4" 
                            class="{{ $errors->has('descripcion') ? 'error' : '' }} w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Describa los objetivos, agenda y temas a tratar en la reunión..."
                            maxlength="1000"
                            data-validation="max:1000">{{ old('descripcion', $reunion->descripcion ?? '') }}</textarea>
                    <div id="descripcion-counter" class="field-counter text-right">0/1000</div>
                </div>
                <div id="descripcion-messages" class="validation-messages">
                    @error('descripcion')
                        <div class="error-message">
                            <i class='bx bx-error-circle'></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <div class="section-header mb-2">
                <div class="flex items-center justify-start">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class='bx bx-group text-blue-600 text-xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Convocar Concejales</h3>
                        <p class="text-sm text-gray-600">Designe un concejal responsable</p>
                    </div>
                </div>
                <div class="ml-auto flex items-center justify-between space-x-3">
                    <div id="participants-counter" class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                        0 seleccionados
                    </div>
                    <div class="bg-white px-4 py-2 rounded-lg border border-gray-200">
                        <i class='bx bx-search input-icon'></i>
                        <input type="text" 
                            id="participants-search" 
                            class="font-medium text-gray-900 focus:outline-none"
                            placeholder="Buscar concejal por nombre o cédula...">
                    </div>
                </div>
            </div>
    
            <!-- Filtros y búsqueda -->
            
            <!-- Lista de participantes -->
            <div class="form-group">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="max-h-64 overflow-y-auto grid sm:grid-cols-1 grid-cols-2 lg:grid-cols-3 gap-2" id="participants-list">
                        @foreach($concejales as $concejal)
                            <div class="participant-card js-concejales-container flex items-start p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer" 
                                data-name="{{ strtolower($concejal->persona->nombre . ' ' . $concejal->persona->apellido) }}"
                                data-cedula="{{ $concejal->persona->cedula }}"
                                id="participant-card-{{ $concejal->persona->cedula }}">
                                <div class="flex items-center flex-1">
                                    <input type="checkbox" 
                                        name="concejales[]" 
                                        value="{{ $concejal->persona->cedula }}" 
                                        id="asistente_{{ $concejal->persona->cedula }}"
                                        class="checkbox-custom mr-4 participant-checkbox js-concejales-checkbox hidden"
                                        data-cedula="{{ $concejal->persona->cedula }}"
                                        {{ (isset($reunion) && $reunion->concejales->contains('persona_cedula', $concejal->persona->cedula)) ? 'checked' : '' }}>
                                    <label for="asistente_{{ $concejal->persona->cedula }}" class="cursor-pointer flex-1">
                                        <div class="font-medium text-md text-gray-900">
                                            {{ $concejal->persona->nombre }} {{ $concejal->persona->apellido }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class='bx bx-id-card mr-1'></i>
                                            C.I: {{ number_format($concejal->persona->cedula, 0, '.', '.') }}
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div id="no-participants-found" class="hidden text-center py-8">
                        <i class='bx bx-search text-gray-400 text-3xl mb-2'></i>
                        <p class="text-gray-500">No se encontraron participantes</p>
                    </div>
                </div>
        
                <div id="participants-messages" class="validation-messages">
                    @error('concejales')
                        <div class="error-message">
                            <i class='bx bx-error-circle'></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                    @error('concejal')
                        <div class="error-message">
                            <i class='bx bx-error-circle'></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    @if($submitButtonText === 'Actualizar Reunión')
        <div class="card-section">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-refresh text-blue-600 text-xl'></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Actualización de Estado</h3>
                    <p class="text-sm text-gray-600">Opcional: Actualice el estado de la solicitud asociada</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="form-group">
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="max-h-64 overflow-y-auto grid sm:grid-cols-1 grid-cols-2 lg:grid-cols-3 gap-2" id="participants-list">
                            @foreach($estatus as $estatu)
                                <div class="participant-card js-estatus-container flex items-start p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer">
                                    <div class="flex items-center flex-1">
                                        <input type="checkbox" 
                                            name="estatus[]" 
                                            value="{{ $estatu->estatus_id }}" 
                                            id="estatus_{{ $estatu->estatus_id }}"
                                            class="checkbox-custom mr-4 participant-checkbox js-estatus-checkbox hidden"
                                            {{ isset($reunion) && ($reunion['estatus'] === $estatu->estatus_id) ? 'checked' : '' }}>
                                        <label for="estatus_{{ $estatu->estatus_id}}" class="cursor-pointer flex-1">
                                            <div class="font-medium text-md text-gray-900">
                                                {{ Str::title($estatu->estatus) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="no-participants-found" class="hidden text-center py-8">
                            <i class='bx bx-search text-gray-400 text-3xl mb-2'></i>
                            <p class="text-gray-500">No se encontraron participantes</p>
                        </div>
                    </div>
                    
                    <!-- Estados sugeridos -->
                    <div class="mt-3">
                        <p class="text-xs text-gray-600 mb-2">Estados comunes:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($estatus as $estatu)
                                <button type="button" 
                                        class="status-suggestion px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors"
                                        data-status="En proceso: Reunión programada">
                                    {{Str::title($estatu->estatus)}}: {{$estatu->descripcion}}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Botones de Acción -->
<div class="flex justify-between items-center pt-8 border-t border-gray-200">
    <a href="{{ route('dashboard.reuniones.index') }}" 
       class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
        <i class='bx bx-arrow-back mr-2'></i>
        Cancelar
    </a>
    
    <button type="submit" 
            id="submitButton"
            class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg">
        <i class='bx bx-save mr-2'></i>
        <span id="submitText">{{ $submitButtonText ?? 'Guardar Reunión' }}</span>
        <div id="submitSpinner" class="hidden ml-2">
            <i class='bx bx-loader bx-spin'></i>
        </div>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN INICIAL =====
    const form = document.querySelector('form');
    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Elementos del formulario
    const titulo = document.getElementById('titulo');
    const fechaReunion = document.getElementById('fecha_reunion');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const ubicacion = document.getElementById('ubicacion');
    const descripcion = document.getElementById('descripcion');
    const solicitudId = document.getElementById('solicitud_id');
    const institucionId = document.getElementById('institucion_id');
    const nuevoEstadoSolicitud = document.getElementById('nuevo_estado_solicitud');
    const participantsSearch = document.getElementById('participants-search');
    
    // Contadores y elementos reactivos
    const formSections = {
        'basic-info': { status: document.getElementById('basic-info-status'), isValid: false },
        'relations': { status: document.getElementById('relations-status'), isValid: false },
        'participants': { status: document.getElementById('participants-status'), isValid: false }
    };
    
    // ===== SISTEMA DE VALIDACIÓN REACTIVA =====
    
    function initializeValidationSystem() {
        // Configurar validaciones para cada campo
        setupFieldValidation(titulo, 'titulo', {
            required: true,
            minLength: 5,
            maxLength: 255,
            section: 'basic-info'
        });
        
        setupFieldValidation(fechaReunion, 'fecha', {
            required: true,
            futureDate: true,
            section: 'basic-info'
        });
        
        setupFieldValidation(ubicacion, 'text', {
            maxLength: 255,
            section: 'basic-info'
        });
        
        setupFieldValidation(descripcion, 'textarea', {
            maxLength: 1000,
            section: 'basic-info'
        });
        
        setupFieldValidation(solicitudId, 'select', {
            required: true,
            section: 'relations'
        });
        
        setupFieldValidation(institucionId, 'select', {
            required: true,
            section: 'relations'
        });
        
        setupFieldValidation(nuevoEstadoSolicitud, 'text', {
            maxLength: 255
        });
        
        // Configurar contadores de caracteres
        setupCharacterCounter(titulo, 255);
        setupCharacterCounter(ubicacion, 255);
        setupCharacterCounter(descripcion, 1000);
        setupCharacterCounter(nuevoEstadoSolicitud, 255);
    }
    
    function setupFieldValidation(field, type, rules) {
        if (!field) return;
        
        const events = ['input', 'change', 'blur'];
        events.forEach(event => {
            field.addEventListener(event, function() {
                validateField(this, type, rules);
            });
        });
        
        // Validación inicial si tiene valor
        if (field.value.trim()) {
            validateField(field, type, rules);
        }
    }
    
    function setupCharacterCounter(field, maxLength) {
        if (!field) return;
        
        const counter = document.getElementById(field.id + '-counter');
        if (!counter) return;
        
        function updateCounter() {
            const currentLength = field.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
            
            if (currentLength > maxLength * 0.8) {
                counter.classList.add('text-yellow-600');
                counter.classList.remove('text-gray-400');
            } else {
                counter.classList.remove('text-yellow-600');
                counter.classList.add('text-gray-400');
            }
            
            if (currentLength > maxLength) {
                counter.classList.add('text-red-600');
                counter.classList.remove('text-yellow-600');
            }
        }
        
        field.addEventListener('input', updateCounter);
        updateCounter(); // Inicial
    }
    
    function validateField(field, type, rules) {
        const value = field.value.trim();
        const messagesContainer = document.getElementById(field.id + '-messages');
        let isValid = true;
        let message = '';
        
        // Limpiar mensajes previos
        if (messagesContainer) {
            const existingMessages = messagesContainer.querySelectorAll('.error-message, .success-message, .warning-message');
            existingMessages.forEach(msg => msg.remove());
        }
        
        // Validar según las reglas
        if (rules.required && !value) {
            isValid = false;
            message = 'Este campo es obligatorio';
        } else if (rules.minLength && value && value.length < rules.minLength) {
            isValid = false;
            message = `Mínimo ${rules.minLength} caracteres`;
        } else if (rules.maxLength && value.length > rules.maxLength) {
            isValid = false;
            message = `Máximo ${rules.maxLength} caracteres`;
        } else if (rules.futureDate && value) {
            const selectedDate = new Date(value);
            const now = new Date();
            if (selectedDate <= now) {
                isValid = false;
                message = 'La fecha debe ser futura';
            }
        }
        
        // Actualizar estado visual del campo
        updateFieldState(field, isValid, message);
        
        // Actualizar estado de la sección
        if (rules.section && formSections[rules.section]) {
            updateSectionStatus(rules.section);
        }
        
        return isValid;
    }
    
    function updateFieldState(field, isValid, message) {
        field.classList.remove('error', 'success', 'loading');
        
        if (!field.value.trim() && !message) {
            // Campo vacío sin error
            return;
        }
        
        if (isValid && field.value.trim()) {
            field.classList.add('success');
            // Mostrar mensaje de éxito brevemente
            setTimeout(() => field.classList.remove('success'), 2000);
        } else if (!isValid) {
            field.classList.add('error');
            
            // Mostrar mensaje de error
            const messagesContainer = document.getElementById(field.id + '-messages');
            if (messagesContainer && message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class='bx bx-error-circle'></i><span>${message}</span>`;
                messagesContainer.appendChild(errorDiv);
            }
        }
    }
    
    function updateSectionStatus(sectionName) {
        const section = formSections[sectionName];
        if (!section || !section.status) return;
        
        let isValid = false;
        
        switch(sectionName) {
            case 'basic-info':
                isValid = validateBasicInfoSection();
                break;
            case 'relations':
                isValid = validateRelationsSection();
                break;
            case 'participants':
                isValid = validateParticipantsSection();
                break;
        }
        
        section.isValid = isValid;
        section.status.className = `w-3 h-3 rounded-full ${isValid ? 'bg-green-500' : 'bg-gray-300'}`;
    }
    
    function validateBasicInfoSection() {
        return titulo.value.trim().length >= 5 && 
               fechaReunion.value && 
               new Date(fechaReunion.value) > new Date();
    }
    
    function validateRelationsSection() {
        const selectedSolicitudes = document.querySelectorAll('.solicitud-checkbox:checked');
        return (selectedSolicitudes.length > 0 || solicitudId.value) && institucionId.value;
    }
    
    function validateParticipantsSection() {
        const selectedParticipants = document.querySelectorAll('input[name="asistentes[]"]:checked');
        return selectedParticipants.length > 0; // Al menos un participante
    }
    
    // ===== SISTEMA DE PARTICIPANTES =====
    
    function initializeParticipantsSystem() {
        setupParticipantsSearch();
        setupParticipantsSelection();
        setupStatusSuggestions();
        updateParticipantsDisplay();
    }
    
    function setupParticipantsSearch() {
        if (!participantsSearch) return;
        
        participantsSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const participantCards = document.querySelectorAll('.participant-card');
            let visibleCount = 0;
            
            participantCards.forEach(card => {
                const name = card.dataset.name || '';
                const cedula = card.dataset.cedula || '';
                const isVisible = name.includes(searchTerm) || cedula.includes(searchTerm);
                
                card.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) visibleCount++;
            });
            
            const noResultsDiv = document.getElementById('no-participants-found');
            if (noResultsDiv) {
                noResultsDiv.classList.toggle('hidden', visibleCount > 0);
            }
        });
    }
    
    function setupParticipantsSelection() {
        const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
        const concejalRadios = document.querySelectorAll('.concejal-radio');
        
        // Manejar selección de participantes
        participantCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const cedula = this.dataset.cedula;
                const card = document.getElementById('participant-card-' + cedula);
                const concejalRadio = document.querySelector(`.concejal-radio[data-cedula="${cedula}"]`);
                
                // Actualizar visual de la card
                if (card) {
                    card.classList.toggle('selected', this.checked);
                }
                
                // Si se deselecciona un participante que era concejal
                if (!this.checked && concejalRadio && concejalRadio.checked) {
                    concejalRadio.checked = false;
                    showNotification('El concejal fue removido de la selección', 'warning');
                    updateConcejalVisual();
                }
                
                updateParticipantsDisplay();
                updateSectionStatus('participants');
            });
        });
        
        // Manejar selección de concejal
        concejalRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const cedula = this.dataset.cedula;
                    const checkbox = document.querySelector(`.participant-checkbox[data-cedula="${cedula}"]`);
                    
                    // Auto-seleccionar como participante
                    if (checkbox && !checkbox.checked) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                        showNotification('Concejal agregado automáticamente como participante', 'success');
                    }
                    
                    updateConcejalVisual();
                    updateParticipantsDisplay();
                }
            });
        });
    }
    
    function updateConcejalVisual() {
        const cards = document.querySelectorAll('.participant-card');
        cards.forEach(card => {
            card.classList.remove('concejal');
        });
        
        const selectedConcejal = document.querySelector('.concejal-radio:checked');
        if (selectedConcejal) {
            const cedula = selectedConcejal.dataset.cedula;
            const card = document.getElementById('participant-card-' + cedula);
            if (card) {
                card.classList.add('concejal');
            }
        }
    }
    
    function updateParticipantsDisplay() {
        const selectedParticipants = document.querySelectorAll('.participant-checkbox:checked');
        const selectedConcejal = document.querySelector('.concejal-radio:checked');
        const counter = document.getElementById('participants-counter');
        const summary = document.getElementById('selection-summary');
        const summaryDetails = document.getElementById('selection-details');
        
        if (counter) {
            counter.textContent = `${selectedParticipants.length} seleccionados`;
        }
        
        if (summary && summaryDetails) {
            if (selectedParticipants.length > 0) {
                summary.classList.remove('hidden');
                
                let details = `<strong>${selectedParticipants.length}</strong> participante(s) seleccionado(s)`;
                if (selectedConcejal) {
                    const concejalName = selectedConcejal.closest('.participant-card').querySelector('label .font-medium').textContent;
                    details += `<br><strong>Concejal responsable:</strong> ${concejalName}`;
                }
                
                summaryDetails.innerHTML = details;
            } else {
                summary.classList.add('hidden');
            }
        }
    }
    
    function setupStatusSuggestions() {
        const suggestions = document.querySelectorAll('.status-suggestion');
        suggestions.forEach(button => {
            button.addEventListener('click', function() {
                const status = this.dataset.status;
                if (nuevoEstadoSolicitud) {
                    nuevoEstadoSolicitud.value = status;
                    nuevoEstadoSolicitud.dispatchEvent(new Event('input'));
                    showNotification('Estado sugerido aplicado', 'success');
                }
            });
        });
    }
    
    // ===== SISTEMA DE NOTIFICACIONES =====
    
    function showNotification(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full max-w-sm`;
        
        const typeClasses = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-black',
            info: 'bg-blue-500 text-white'
        };
        
        const icons = {
            success: 'bx-check-circle',
            error: 'bx-x-circle',
            warning: 'bx-error',
            info: 'bx-info-circle'
        };
        
        toast.className += ` ${typeClasses[type]}`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class='bx ${icons[type]} mr-2'></i>
                <span class="text-sm font-medium">${message}</span>
                <button class="ml-3 hover:opacity-75" onclick="this.parentElement.parentElement.remove()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Mostrar
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        
        // Auto-ocultar
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, duration);
    }
    
    // ===== VALIDACIÓN Y ENVÍO DEL FORMULARIO =====
    
    function setupFormSubmission() {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar todas las secciones
            const isBasicValid = validateBasicInfoSection();
            const isRelationsValid = validateRelationsSection();
            const isParticipantsValid = validateParticipantsSection();
            
            // Actualizar estados visuales
            updateSectionStatus('basic-info');
            updateSectionStatus('relations');
            updateSectionStatus('participants');
            
            if (!isBasicValid) {
                showNotification('Complete la información básica correctamente', 'error');
                titulo.focus();
                return;
            }
            
            if (!isRelationsValid) {
                showNotification('Seleccione la solicitud e institución asociadas', 'error');
                solicitudId.focus();
                return;
            }
            
            if (!isParticipantsValid) {
                showNotification('Debe seleccionar al menos un participante', 'warning');
                document.querySelector('.participant-checkbox').focus();
                return;
            }
            
            // Envío exitoso
            submitButton.disabled = true;
            submitText.textContent = 'Guardando reunión...';
            submitSpinner.classList.remove('hidden');
            
            showNotification('Guardando reunión...', 'info');
            
            setTimeout(() => {
                this.submit();
            }, 500);
        });
    }
    
    // ===== SISTEMA DE SOLICITUDES MÚLTIPLES =====
    
    function initializeSolicitudesSystem() {
        setupSolicitudesSearch();
        setupSolicitudesSelection();
        updateSolicitudesDisplay();
    }
    
    function setupSolicitudesSearch() {
        const solicitudesSearch = document.getElementById('solicitudes-search');
        if (!solicitudesSearch) return;
        
        solicitudesSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const solicitudItems = document.querySelectorAll('.solicitud-item');
            let visibleCount = 0;
            
            solicitudItems.forEach(item => {
                const title = item.dataset.title || '';
                const id = item.dataset.id || '';
                const isVisible = title.includes(searchTerm) || id.includes(searchTerm);
                
                item.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) visibleCount++;
            });
            
            const noResultsDiv = document.getElementById('no-solicitudes-found');
            if (noResultsDiv) {
                noResultsDiv.classList.toggle('hidden', visibleCount > 0);
            }
        });
    }
    
    function setupSolicitudesSelection() {
        const solicitudCheckboxes = document.querySelectorAll('.solicitud-checkbox');
        
        solicitudCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSolicitudesDisplay();
                updateSectionStatus('relations');
            });
        });
    }
    
    function updateSolicitudesDisplay() {
        const selectedSolicitudes = document.querySelectorAll('.solicitud-checkbox:checked');
        const counter = document.getElementById('solicitudes-count');
        
        if (counter) {
            counter.textContent = selectedSolicitudes.length;
        }
    }

    function updateInstitucionesDisplay() {
        const selectedInstituciones = document.querySelectorAll('.instituciones-checkbox:checked');
        const counter = document.getElementById('instituciones-count');
        
        if (counter) {
            counter.textContent = selectedInstituciones.length;
        }
    }
    
    // ===== INICIALIZACIÓN GENERAL =====
    
    function initialize() {
        initializeValidationSystem();
        initializeParticipantsSystem();
        initializeSolicitudesSystem();
        setupFormSubmission();
        
        // Validar estado inicial
        Object.keys(formSections).forEach(sectionName => {
            updateSectionStatus(sectionName);
        });
        
        showNotification('Formulario cargado correctamente', 'success', 2000);
    }
    
    // Inicializar cuando todo esté listo
    initialize();

    
});

document.addEventListener('DOMContentLoaded', function () {
    const ACTIVE_CLASSES = ['border-blue-500', 'bg-blue-50', 'shadow-lg'];
    const INACTIVE_CLASSES = ['border-gray-200', 'hover:border-blue-300', 'hover:bg-blue-50'];

    const checkboxes = document.querySelectorAll('.js-solicitud-checkbox', );
    const checkboxesInstitucion = document.querySelectorAll('.js-instituciones-checkbox');
    const checkboxesConcejal = document.querySelectorAll('.js-concejales-checkbox');
    const checkboxesEstatus = document.querySelectorAll('.js-estatus-checkbox');

    checkboxes.forEach(checkbox => {
        const container = checkbox.closest('.js-solicitud-container');

        const toggleClasses = (element, isChecked) => {
            if (isChecked) {
                element.classList.remove(...INACTIVE_CLASSES);
                element.classList.add(...ACTIVE_CLASSES);
            } else {
                element.classList.remove(...ACTIVE_CLASSES);
                element.classList.add(...INACTIVE_CLASSES);
            }
        };

        if (container) {
            toggleClasses(container, checkbox.checked);
        }

        checkbox.addEventListener('change', (event) => {
            const isChecked = event.target.checked;
            
            if (container) {
                toggleClasses(container, isChecked);
            }
        });
    });

    checkboxesInstitucion.forEach(checkbox => {
        const container = checkbox.closest('.js-instituciones-container');

        const toggleClasses = (element, isChecked) => {
            if (isChecked) {
                element.classList.remove(...INACTIVE_CLASSES);
                element.classList.add(...ACTIVE_CLASSES);
            } else {
                element.classList.remove(...ACTIVE_CLASSES);
                element.classList.add(...INACTIVE_CLASSES);
            }
        };

        if (container) {
            toggleClasses(container, checkbox.checked);
        }

        checkbox.addEventListener('change', (event) => {
            const isChecked = event.target.checked;
            
            if (container) {
                toggleClasses(container, isChecked);
            }
        });
    });

    checkboxesConcejal.forEach(checkbox => {
        const container = checkbox.closest('.js-concejales-container');

        const toggleClasses = (element, isChecked) => {
            if (isChecked) {
                element.classList.remove(...INACTIVE_CLASSES);
                element.classList.add(...ACTIVE_CLASSES);
            } else {
                element.classList.remove(...ACTIVE_CLASSES);
                element.classList.add(...INACTIVE_CLASSES);
            }
        };

        if (container) {
            toggleClasses(container, checkbox.checked);
        }

        checkbox.addEventListener('change', (event) => {
            const isChecked = event.target.checked;
            
            if (container) {
                toggleClasses(container, isChecked);
            }
        });
    });

    checkboxesEstatus.forEach(checkbox => {
        const container = checkbox.closest('.js-estatus-container');

        const toggleClasses = (element, isChecked) => {
            if (isChecked) {
                element.classList.remove(...INACTIVE_CLASSES);
                element.classList.add(...ACTIVE_CLASSES);
            } else {
                element.classList.remove(...ACTIVE_CLASSES);
                element.classList.add(...INACTIVE_CLASSES);
            }
        };

        if (container) {
            toggleClasses(container, checkbox.checked);
        }

        checkbox.addEventListener('change', (event) => {
            const isChecked = event.target.checked;
            
            if (container) {
                toggleClasses(container, isChecked);
            }
        });
    });
});
</script>