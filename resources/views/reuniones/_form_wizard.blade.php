@csrf

<style>
    /* Estilos para el wizard */
    .wizard-step {
        display: none;
    }
    
    .wizard-step.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .step-indicator {
        transition: all 0.3s ease;
    }
    
    .step-indicator.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
    }
    
    .step-indicator.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-color: #3b82f6;
        transform: scale(1.1);
    }
    
    .step-indicator.pending {
        background: #e5e7eb;
        border-color: #d1d5db;
    }
    
    .checkbox-custom, .radio-custom {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        transition: all 0.2s;
    }
    
    .checkbox-custom:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .hidden-section {
        display: none !important;
    }
</style>

<div id="wizardContainer" class="space-y-6">
    <!-- Progress Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Progreso del Formulario</h3>
            <span id="progress-text" class="text-sm text-gray-600">Paso <span id="current-step-num">1</span> de <span id="total-steps">4</span></span>
        </div>
        
        <!-- Progress Steps -->
        <div class="flex items-center justify-between relative">
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 -z-10">
                <div id="progress-bar" class="h-full bg-blue-600 transition-all duration-500" style="width: 25%"></div>
            </div>
            
            <div class="flex items-center step-item" data-step="1">
                <div class="step-indicator completed w-10 h-10 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow-lg z-10">
                    1
                </div>
                <span class="ml-2 text-sm font-medium text-gray-700 max-md:hidden">Información Básica</span>
            </div>
            
            <div class="flex items-center step-item" data-step="2">
                <div class="step-indicator pending w-10 h-10 rounded-full flex items-center justify-center text-gray-600 font-bold border-4 border-white shadow-lg z-10">
                    2
                </div>
                <span class="ml-2 text-sm font-medium text-gray-500 max-md:hidden">Participantes</span>
            </div>
            
            <div class="flex items-center step-item" data-step="3">
                <div class="step-indicator pending w-10 h-10 rounded-full flex items-center justify-center text-gray-600 font-bold border-4 border-white shadow-lg z-10">
                    3
                </div>
                <span class="ml-2 text-sm font-medium text-gray-500 max-md:hidden">Relaciones</span>
            </div>
            
            <div class="flex items-center step-item" data-step="4">
                <div class="step-indicator pending w-10 h-10 rounded-full flex items-center justify-center text-gray-600 font-bold border-4 border-white shadow-lg z-10">
                    4
                </div>
                <span class="ml-2 text-sm font-medium text-gray-500 max-md:hidden">Descripción</span>
            </div>
        </div>
    </div>

    <!-- Step 1: Información Básica -->
    <div class="wizard-step active" data-step="1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class='bx bx-info-circle text-blue-600 text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Información Básica</h3>
                    <p class="text-sm text-gray-600">Configure los datos principales de la reunión</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="lg:col-span-2">
                    <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-edit-alt mr-2 text-blue-600'></i>
                        Título de la Reunión <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           value="{{ old('titulo', $reunion->titulo ?? '') }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                           placeholder="Ej: Reunión de seguimiento sobre solicitud de agua potable"
                           required
                           maxlength="255">
                    <p class="text-gray-500 text-xs mt-1">Ingrese un título descriptivo y claro</p>
                    @error('titulo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Reunión -->
                <div>
                    <label for="tipo_reunion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-category mr-2 text-green-600'></i>
                        Tipo de Reunión <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_reunion" 
                            id="tipo_reunion" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                            required>
                        <option value="">Seleccione un tipo...</option>
                        @foreach($tipoReunion as $tipo)
                            <option value="{{ $tipo->id }}" 
                                    {{ (old('tipo_reunion', $reunion->tipo_reunion ?? '') == $tipo->id) ? 'selected' : '' }}>
                                {{ $tipo->titulo }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-1">Seleccione el tipo de reunión</p>
                    @error('tipo_reunion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Reunión -->
                <div>
                    <label for="fecha_reunion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-2 text-blue-600'></i>
                        Fecha de la Reunión <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="fecha_reunion" 
                           id="fecha_reunion" 
                           value="{{ old('fecha_reunion', isset($reunion) ? $reunion->fecha_reunion->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                           required
                           min="{{ isset($reunion) ? '' : date('Y-m-d') }}">
                    <p class="text-gray-500 text-xs mt-1">{{ isset($reunion) ? 'Fecha de la reunión' : 'Debe ser una fecha futura' }}</p>
                    @error('fecha_reunion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hora de Reunión -->
                <div>
                    <label for="hora_reunion" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-time mr-2 text-blue-600'></i>
                        Hora de la Reunión <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="hora_reunion" 
                           id="hora_reunion" 
                           value="{{ old('hora_reunion', isset($reunion->hora_reunion) ? \Carbon\Carbon::parse($reunion->hora_reunion)->format('H:i') : '') }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                           required>
                    <p class="text-gray-500 text-xs mt-1">Hora de inicio (bloqueo de 4 horas automático)</p>
                    @error('hora_reunion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Convocar Concejales -->
    <div class="wizard-step" data-step="2" id="step-concejales">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class='bx bx-group text-green-600 text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Convocar Concejales</h3>
                    <p class="text-sm text-gray-600">Seleccione los concejales que participarán</p>
                </div>
            </div>

            <!-- Búsqueda -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                        <span id="concejales-count">0</span> concejales seleccionados
                    </div>
                    <div class="relative flex-1 max-w-md ml-4">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        <input type="text" 
                               id="concejales-search" 
                               class="w-full pl-10 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                               placeholder="Buscar concejal por nombre o cédula...">
                    </div>
                </div>
            </div>

            <!-- Lista de Concejales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2" id="concejales-list">
                @foreach($concejales as $concejal)
                    <div class="concejal-item border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer"
                         data-name="{{ strtolower($concejal->persona->nombre . ' ' . $concejal->persona->apellido) }}"
                         data-cedula="{{ $concejal->persona->cedula }}">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="concejales[]" 
                                   value="{{ $concejal->persona->cedula }}" 
                                   class="checkbox-custom mt-1 mr-3"
                                   {{ (isset($reunion) && $reunion->concejales->contains('persona_cedula', $concejal->persona->cedula)) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">
                                    {{ $concejal->persona->nombre }} {{ $concejal->persona->apellido }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class='bx bx-id-card mr-1'></i>
                                    C.I: {{ number_format($concejal->persona->cedula, 0, '.', '.') }}
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
            
            <div id="no-concejales-found" class="hidden text-center py-8">
                <i class='bx bx-search text-gray-400 text-4xl mb-2'></i>
                <p class="text-gray-500">No se encontraron concejales</p>
            </div>
            
            @error('concejales')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Step 3: Relaciones (Solicitudes e Instituciones) -->
    <div class="wizard-step" data-step="3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                    <i class='bx bx-link-alt text-purple-600 text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Relaciones y Contexto</h3>
                    <p class="text-sm text-gray-600">Vincule solicitudes e instituciones</p>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Solicitudes -->
                <div id="solicitudes-section">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class='bx bx-file-blank text-blue-600 mr-2'></i>
                        Solicitudes Pendientes
                    </h4>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            <span id="solicitudes-count">0</span> solicitudes seleccionadas
                        </div>
                        <div class="relative flex-1 max-w-md ml-4">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            <input type="text" 
                                   id="solicitudes-search" 
                                   class="w-full pl-10 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   placeholder="Buscar solicitud por título o ID...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2" id="solicitudes-list">
                        @foreach($solicitudes as $solicitud)
                            <div class="solicitud-item border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer"
                                 data-title="{{ strtolower($solicitud->titulo) }}"
                                 data-id="{{ strtolower($solicitud->solicitud_id) }}">
                                <label class="flex items-start cursor-pointer">
                                    <!-- Indicador de Derecho de Palabra -->
                                    <div class="mr-2 mt-1">
                                        <div class="w-3 h-3 rounded-full {{ $solicitud->derecho_palabra ? 'bg-blue-500' : 'bg-gray-400' }}" 
                                             title="{{ $solicitud->derecho_palabra ? 'Solicitó Derecho de Palabra' : 'No solicitó Derecho de Palabra' }}"></div>
                                    </div>
                                    
                                    <input type="checkbox" 
                                           name="solicitudes[]" 
                                           value="{{ $solicitud->solicitud_id }}" 
                                           class="checkbox-custom mt-1 mr-3"
                                           {{ (isset($reunion) && $reunion->solicitudes->contains('solicitud_id', $solicitud->solicitud_id)) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-sm mb-1">
                                            {{ Str::limit($solicitud->titulo, 60) }}
                                        </div>
                                        
                                        <!-- Información del Solicitante -->
                                        @if($solicitud->persona)
                                            <div class="text-xs text-gray-700 mb-1">
                                                <i class='bx bx-user mr-1 text-blue-600'></i>
                                                {{ $solicitud->persona->nombre }} {{ $solicitud->persona->apellido }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                <i class='bx bx-id-card mr-1'></i>
                                                {{ $solicitud->solicitud_id }}
                                            </div>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                {{ $solicitud->getEstatusFormattedAttribute() }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center mt-4">
                        <input type="checkbox" name="notificar_solicitantes" id="notificar_solicitantes" class="checkbox-custom mr-2">
                        <label for="notificar_solicitantes" class="text-sm text-gray-700 cursor-pointer">
                            Notificar a los solicitantes sobre esta reunión
                        </label>
                        <i class='bx bx-help-circle text-gray-400 ml-2' title="Se enviará una notificación interna a cada solicitante"></i>
                    </div>

                    @error('solicitudes')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instituciones -->
                <div id="instituciones-section">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class='bx bx-buildings text-purple-600 mr-2'></i>
                        Instituciones a Convocar
                    </h4>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            <span id="instituciones-count">0</span> instituciones seleccionadas
                        </div>
                        <div class="relative flex-1 max-w-md ml-4">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                            <input type="text" 
                                   id="instituciones-search" 
                                   class="w-full pl-10 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   placeholder="Buscar institución...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2" id="instituciones-list">
                        @foreach($instituciones as $id => $institucion)
                            <div class="institucion-item border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer"
                                 data-title="{{ strtolower($institucion) }}">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" 
                                           name="instituciones[]" 
                                           value="{{ $id }}" 
                                           class="checkbox-custom mt-1 mr-3"
                                           {{ (isset($reunion) && $reunion->instituciones->contains('id', $id)) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-sm">
                                            {{ $institucion }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('instituciones')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Step 4: Descripción -->
    <div class="wizard-step" data-step="4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                    <i class='bx bx-detail text-yellow-600 text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Descripción y Objetivos</h3>
                    <p class="text-sm text-gray-600">Detalles adicionales sobre la reunión</p>
                </div>
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class='bx bx-message-square-detail mr-2 text-blue-600'></i>
                    Descripción de la Reunión
                </label>
                <textarea name="descripcion" 
                          id="descripcion" 
                          rows="6" 
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                          placeholder="Describa los objetivos, agenda y temas a tratar en la reunión..."
                          maxlength="1000">{{ old('descripcion', $reunion->descripcion ?? '') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Máximo 1000 caracteres</p>
                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex lg:justify-between max-lg:flex-col items-center bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <button type="button" 
                id="prevBtn" 
                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
            <i class='bx bx-chevron-left mr-2'></i>
            Anterior
        </button>
        
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <i class='bx bx-info-circle'></i>
            <span id="step-hint">Complete la información básica para continuar</span>
        </div>
        
        <button type="button" 
                id="nextBtn" 
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-lg">
            Siguiente
            <i class='bx bx-chevron-right ml-2'></i>
        </button>
        
        <button type="submit" 
                id="submitBtn" 
                class="hidden px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 font-medium shadow-lg">
            <i class='bx bx-check-circle mr-2'></i>
            {{ $submitButtonText ?? 'Crear Reunión' }}
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const tipoReunionSelect = document.getElementById('tipo_reunion');
    
    // Inicializar visibilidad según tipo de reunión
    function updateVisibilityByType() {
        const tipo = tipoReunionSelect.value;
        const solicitudesSection = document.getElementById('solicitudes-section');
        const institucionesSection = document.getElementById('instituciones-section');
        const concejalesStep = document.getElementById('step-concejales');
        
        // Reset all
        solicitudesSection.classList.remove('hidden-section');
        institucionesSection.classList.remove('hidden-section');
        concejalesStep.style.display = '';
        
        // Aplicar reglas según tipo
        if (tipo == '2') { // Mesa de Trabajo
            solicitudesSection.classList.add('hidden-section');
        } else if (tipo == '3') { // Sesión Solemne
            solicitudesSection.classList.add('hidden-section');
            institucionesSection.classList.add('hidden-section');
            concejalesStep.style.display = 'none';
        }
    }
    
    tipoReunionSelect.addEventListener('change', updateVisibilityByType);
    updateVisibilityByType();
    
    // Navegación de pasos
    function showStep(step) {
        document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
        document.querySelector(`.wizard-step[data-step="${step}"]`).classList.add('active');
        
        // Actualizar indicadores
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            indicator.classList.remove('active', 'completed', 'pending');
            if (index + 1 < step) {
                indicator.classList.add('completed');
            } else if (index + 1 === step) {
                indicator.classList.add('active');
            } else {
                indicator.classList.add('pending');
            }
        });
        
        // Actualizar barra de progreso
        const progress = (step / totalSteps) * 100;
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('current-step-num').textContent = step;
        
        // Actualizar botones
        prevBtn.disabled = step === 1;
        
        if (step === totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
        
        // Actualizar hints
        const hints = [
            'Complete la información básica para continuar',
            'Seleccione los concejales que participarán',
            'Asocie solicitudes e instituciones si es necesario',
            'Agregue una descripción detallada y finalice'
        ];
        document.getElementById('step-hint').textContent = hints[step - 1];
    }
    
    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            // Si estamos en paso 2 y el tipo es Sesión Solemne, saltar al paso 4
            const tipo = tipoReunionSelect.value;
            if (currentStep === 1 && tipo == '3') {
                currentStep = 4;
            } else if (currentStep < totalSteps) {
                currentStep++;
            }
            showStep(currentStep);
        }
    });
    
    prevBtn.addEventListener('click', function() {
        const tipo = tipoReunionSelect.value;
        if (currentStep === 4 && tipo == '3') {
            currentStep = 1;
        } else if (currentStep > 1) {
            currentStep--;
        }
        showStep(currentStep);
    });
    
    function validateStep(step) {
        if (step === 1) {
            const titulo = document.getElementById('titulo').value.trim();
            const tipo = tipoReunionSelect.value;
            const fecha = document.getElementById('fecha_reunion').value;
            const hora = document.getElementById('hora_reunion').value;
            
            if (!titulo || !tipo || !fecha || !hora) {
                alert('Por favor complete todos los campos requeridos');
                return false;
            }
        }
        return true;
    }
    
    // Búsqueda de concejales
    document.getElementById('concejales-search').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const items = document.querySelectorAll('.concejal-item');
        let visible = 0;
        
        items.forEach(item => {
            const name = item.dataset.name || '';
            const cedula = item.dataset.cedula || '';
            if (name.includes(search) || cedula.includes(search)) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });
        
        document.getElementById('no-concejales-found').classList.toggle('hidden', visible > 0);
    });
    
    // Búsqueda de solicitudes
    document.getElementById('solicitudes-search').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const items = document.querySelectorAll('.solicitud-item');
        let visible = 0;
        
        items.forEach(item => {
            const title = item.dataset.title || '';
            const id = item.dataset.id || '';
            if (title.includes(search) || id.includes(search)) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Búsqueda de instituciones
    document.getElementById('instituciones-search').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const items = document.querySelectorAll('.institucion-item');
        
        items.forEach(item => {
            const title = item.dataset.title || '';
            item.style.display = title.includes(search) ? '' : 'none';
        });
    });
    
    // Actualizar contadores
    function updateCounters() {
        const concejalesCount = document.querySelectorAll('input[name="concejales[]"]:checked').length;
        const solicitudesCount = document.querySelectorAll('input[name="solicitudes[]"]:checked').length;
        const institucionesCount = document.querySelectorAll('input[name="instituciones[]"]:checked').length;
        
        document.getElementById('concejales-count').textContent = concejalesCount;
        document.getElementById('solicitudes-count').textContent = solicitudesCount;
        document.getElementById('instituciones-count').textContent = institucionesCount;
    }
    
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', updateCounters);
    });
    
    updateCounters();
    showStep(1);
});
</script>
