<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-8">   

    <div class="mb-8">
        <div class="flex items-center justify-center">
            
            <div class="flex flex-col items-center {{ $avanzarPaso >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                    {{ $avanzarPaso >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-search text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Búsqueda</span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $avanzarPaso >= 2 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $avanzarPaso >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                    {{ $avanzarPaso >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-user text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Datos Personales</span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $avanzarPaso >= 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $avanzarPaso >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                    {{ $avanzarPaso >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-briefcase text-2xl'></i> {{-- Usamos un ícono de trabajo/credenciales --}}
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Datos Laborales</span>
            </div>
        </div>
    </div>

    @if ($avanzarPaso === 1 || $avanzarPaso === 4)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="max-w-2xl mx-auto">
                @if ($avanzarPaso === 4)
                    <div class="mb-8 p-6 border border-red-300 bg-red-50 text-red-800 rounded-lg text-center">
                        <i class='bx bx-error-alt text-3xl mb-2'></i>
                        <p class="font-bold text-xl"> ¡Ya es Trabajador Registrado! </p>
                        <p class="text-lg mt-1 font-semibold">{{ $personaEncontrada->nombre }} {{ $personaEncontrada->apellido }}</p>
                        <p class="text-sm text-gray-800">Cédula: {{ $personaEncontrada->cedula }}</p>
                        <p class="mt-2 font-medium">
                        	La persona {{ $personaEncontrada->nombre }} {{ $personaEncontrada->apellido }}
                        	 (Cédula: {{ $personaEncontrada->cedula }}) ya está registrada como trabajador.</p>
                    </div>
                     <button wire:click="RegresarAlPaso1" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-2">
                        <i class='bx bx-rotate-left'></i>
                        Nueva Búsqueda
                    </button>
                @else
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-id-card text-blue-600 text-3xl'></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 1: Búsqueda del trabajador</h2>
                        <p class="text-gray-600">Ingresa la cédula para verificar si el trabajador ya está registrado</p>
                    </div>

                    <div x-data="{ 
                        cedulaValue: '{{ old('cedula_busqueda') }}', 
                        isInvalid: false,
                        validateCedula() {
                            const len = this.cedulaValue.length;
                            this.isInvalid = (len > 0 && len < 6) || len > 8;
                            document.getElementById('buscar-button').disabled = this.isInvalid || len < 6;
                            document.getElementById('buscar-button').classList.toggle('opacity-50', this.isInvalid || len < 6);
                            document.getElementById('buscar-button').classList.toggle('cursor-not-allowed', this.isInvalid || len < 6);
                        }
                    }" x-init="validateCedula()">

                         <label for="cedula-search" class="block text-lg font-semibold text-gray-800 mb-3">
                            Cédula de Identidad *
                        </label>

                        <div class="relative">
                            <input x-model="cedulaValue" @input="$el.value = $el.value.replace(/[^0-9]/g, ''); cedulaValue = $el.value; validateCedula();"
                                type="text" inputmode="numeric" wire:model.live.debounce.300ms="cedula_busqueda" id="cedula-search"
                                placeholder="Ingrese número de cédula (6-8 dígitos)" maxlength="8"
                                class="block w-full rounded-lg border-2 pr-12 text-lg
                                    @error('cedula_busqueda') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-600 focus:ring-blue-600 p-4"
                                required value="{{ old('cedula_busqueda') }}">

                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class='bx bx-id-card text-2xl text-gray-400'></i>
                            </div>
                        </div>
                        @error('cedula_busqueda') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div x-show="isInvalid" x-cloak class="mt-2 text-sm text-red-600">
                            <i class='bx bx-error-circle mr-1'></i>
                            La cédula debe tener entre 6 y 8 dígitos
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <i class='bx bx-info-circle mr-2'></i>
                                Próximos Pasos
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1 ml-6">
                                <li>• Si la persona **no existe**: Registrará **Datos Personales** y Laborales (Paso 2).</li>
                                <li>• Si la persona **existe sin ser trabajador**: Registrará solo **Datos Laborales** (Paso 3).</li>
                                <li>• Si la persona **ya es trabajador**: Se notificará (Paso 4).</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex justify-center pt-6">
                        <button wire:click="buscarPersona" id="buscar-button" 
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            Buscar y Continuar
                            <i class='bx bx-right-arrow-alt ml-2'></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

        @if ($avanzarPaso === 2 || $avanzarPaso === 3)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                
                <div class="mb-6 border-b border-gray-300 pb-4">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            @if ($avanzarPaso === 2)
                                <i class='bx bx-user text-blue-600 text-2xl'></i>
                            @else
                                <i class='bx bx-briefcase text-blue-600 text-2xl'></i>
                            @endif
                        </div>	
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if ($avanzarPaso === 2)
                                    Paso 2: Datos Personales y Laborales
                                @else
                                    Paso 3: Datos Laborales
                                @endif
                            </h2>
                            <p class="text-sm text-gray-600">
                                @if ($avanzarPaso === 2)
                                    Persona no encontrada. Complete la información personal y laboral.
                                @else
                                    Persona existente. Complete la información laboral para registrarla como trabajador.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="cedula" value="{{ $personaEncontrada->cedula ?? $cedula_busqueda }}">

                @if (isset($personaEncontrada))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class='bx bx-check-circle text-green-600 text-2xl mr-3'></i>
                            <div>
                                <h4 class="font-semibold text-green-900 mb-1">Persona Encontrada</h4>
                                <p class="text-sm text-green-800">
                                    <strong>{{ ucwords($personaEncontrada->nombre . ' ' . $personaEncontrada->apellido) }}</strong> - Cédula: {{ $personaEncontrada->cedula }}
                                </p>
                                <p class="text-xs text-green-700 mt-1">Solo necesitas asignar el cargo y zona de trabajo.</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!isset($personaEncontrada))
                    <h3 class="text-xl font-bold mt-6 mb-4 text-gray-700 pb-2 flex items-center">
                        <i class='bx bx-id-card text-xl mr-2'></i>
                        Información Básica (Paso 2)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-1">Cédula *</label>
                            <input type="text" wire:model.live="persona.cedula" disabled
                                class="w-full border-2 rounded-lg px-4 py-3 border-gray-300 cursor-not-allowed bg-gray-100 transition duration-150 ease-in-out">
                        </div>
                        
                        <div>
                            <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-1">Nacionalidad *</label>
                            <select wire:model.live="persona.nacionalidad" id="nacionalidad" required 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.nacionalidad') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="1" {{ old('nacionalidad') == 'V' ? 'selected' : '' }}>Venezolana</option>
                                <option value="2" {{ old('nacionalidad') == 'E' ? 'selected' : '' }}>Extranjera</option>
                            </select>
                            @error('persona.nacionalidad') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
                            <input type="text" wire:model.live="persona.nombre" id="nombre" value="{{ old('persona.nombre') }}" required 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.nombre') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="segundo_nombre" class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                            <input type="text" wire:model.live="persona.segundo_nombre" id="segundo_nombre" value="{{ old('persona.segundo_nombre') }}" 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.segundo_nombre') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.segundo_nombre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido *</label>
                            <input type="text" wire:model.live="persona.apellido" id="apellido" value="{{ old('persona.apellido') }}" required 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.apellido') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="segundo_apellido" class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                            <input type="text" wire:model.live="persona.segundo_apellido" id="segundo_apellido" value="{{ old('persona.segundo_apellido') }}" 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.segundo_apellido') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.segundo_apellido') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="genero" class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                            <select wire:model.live="persona.genero" id="genero" required 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.genero') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="1" {{ old('persona.genero') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="2" {{ old('persona.genero') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('persona.genero') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="nacimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento *</label>
                            <input type="date" wire:model.live="persona.nacimiento" id="nacimiento" value="{{ old('persona.nacimiento') }}" required 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.nacimiento') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.nacimiento') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                <div class="flex items-center">
                                    <select wire:model.live="persona.prefijo" class="w-25 mr-2 px-2 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="" disabled selected>XXXX</option>
                                        <option value="0412">0412</option>
                                        <option value="0422">0422</option>
                                        <option value="0414">0414</option>
                                        <option value="0424">0424</option>
                                        <option value="0416">0416</option>
                                        <option value="0426">0426</option>
                                    </select>
                                    <input type="text" wire:model.live="persona.telefono" id="telefono" value="{{ old('persona.telefono') }}" required 
                                        placeholder="Ej: 123-4567 (7 dígitos)" maxlength="7" 
                                        class="w-full border-2 rounded-lg px-4 py-3 
                                            @error('persona.telefono') border-red-500 @else border-gray-300 @enderror
                                            focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    @error('persona.telefono') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <div id="telefono-feedback" class="text-sm mt-1"></div>
                                </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model.live="persona.email" id="email" value="{{ old('persona.email') }}"
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.email') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('persona.email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            <div id="email-feedback" class="text-sm mt-1"></div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <textarea wire:model.live="persona.direccion" id="direccion" rows="3" 
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('persona.direccion') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">{{ old('persona.direccion') }}</textarea>
                            @error('persona.direccion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif
                
                
                @if (!isset($trabajadorExistente))
                    <h3 class="text-xl font-bold mt-6 mb-4 text-gray-700 pb-2 flex items-center">
                         <i class='bx bx-briefcase text-xl mr-2'></i>
                         Datos Laborales (Paso {{ !isset($personaEncontrada) ? '3' : '3' }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- CAMPO CARGO --}}
                        <div>
                            <label for="cargo_id" class="block text-sm font-medium text-gray-700 mb-1">Cargo *</label>
                            <select wire:model.live="trabajador.cargo_id" id="cargo_id" required onchange="validateCargo()"
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('cargo_id') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <option value="" disabled selected>Seleccione un cargo</option>
                                @foreach ($cargos as $cargo)
                                    <option value="{{ $cargo->cargo_id }}" {{ old('cargo_id') == $cargo->cargo_id ? 'selected' : '' }}>
                                        {{ $cargo->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cargo_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="zona_trabajo" class="block text-sm font-medium text-gray-700 mb-1">Zona de Trabajo *</label>
                            <input type="text" wire:model.live="trabajador.zona_trabajo" id="zona_trabajo" 
                                placeholder="Ej: Sede Central" 
                                value="{{ old('zona_trabajo') }}"
                                required
                                class="w-full border-2 rounded-lg px-4 py-3 
                                    @error('zona_trabajo') border-red-500 @else border-gray-300 @enderror
                                    focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                            @error('zona_trabajo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif
                

                <div class="flex lg:justify-end max-lg:flex-col gap-3 pt-6 border-t border-gray-300 mt-8">
                    <button wire:click="RegresarAlPaso1" class="max-lg:text-md px-5 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-2">
                        <i class='bx bx-rotate-left'></i>
                        Nueva Búsqueda / Cancelar
                    </button>
                    
                    @if (!isset($trabajadorExistente))
                        <button wire:click="store" id="submit-button" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class='bx bx-save text-xl'></i>
                            Registrar Trabajador
                        </button>
                    @endif
                </div>
            </div>
        @endif

<script>
    // Variables para rastrear el estado de la validación general del formulario de registro
    let isCedulaValid = false;
    let isTelefonoValid = false;
    let isEmailValid = true; 

    // Variable para rastrear el estado de la validación del campo de búsqueda
    let isBusquedaValid = false;

    // Variables nuevas para campos laborales
    let isCargoValid = false;
    let isZonaValid = false;
    
    // Estilos de feedback (para que sea más limpio)
    function setFeedback(input, feedbackDiv, isValid, message) {
        // Estilos del input
        input.classList.remove('border-green-500', 'border-red-500');
        if (input.value.trim() !== '') {
            input.classList.add(isValid ? 'border-green-500' : 'border-red-500');
        } else {
            // Limpiar si está vacío
            input.classList.remove('border-green-500', 'border-red-500');
        }

        // Estilos y contenido del mensaje
        feedbackDiv.classList.remove('text-green-600', 'text-red-600');
        feedbackDiv.classList.add(isValid ? 'text-green-600' : 'text-red-600');
        feedbackDiv.innerHTML = message;
    }
    
    // Función principal para verificar si el botón de Registro es válido
    function updateSubmitButton() {
        const submitButton = document.getElementById('submit-button');
        if (!submitButton) return;

        // 1. Detectar el modo de registro
        // Usamos la presencia de un campo visible de Nombre para distinguir:
        const inputNombre = document.getElementById('nombre'); 
        // Verificamos si existe y si su estilo de visualización no es 'none'
        const isNewPersonMode = inputNombre && window.getComputedStyle(inputNombre).display !== 'none';
        
        // También verificamos si los inputs están presentes en el DOM (en caso de que la lógica de PHP cambie)
        const cedulaInput = document.getElementById('cedula');
        
        let isValid;

        if (cedulaInput && window.getComputedStyle(cedulaInput).display !== 'none' && isNewPersonMode) {
            // MODO 1: PERSONA NUEVA (Requiere campos personales Y laborales)
            // Se asume que los campos personales obligatorios (nombre, apellido, nacionalidad, genero, nacimiento)
            // están cubiertos por la validación de Laravel en el backend,
            // y solo se valida la lógica de JS (Cedula, Teléfono, Email, Cargo, Zona)
            
            // Revisa campos personales obligatorios del form (no cubiertos por la validación JS)
            const requiredFields = ['nombre', 'apellido', 'nacionalidad', 'genero', 'nacimiento'];
            const areRequiredFieldsFilled = requiredFields.every(id => {
                const field = document.getElementById(id);
                return field && field.value.trim() !== '';
            });

            isValid = isCedulaValid && isTelefonoValid && isEmailValid && isCargoValid && isZonaValid && areRequiredFieldsFilled;

        } else {
            // MODO 2: PERSONA EXISTENTE (SOLO requiere campos laborales)
            isValid = isCargoValid && isZonaValid;
        }
        
        // Habilitación / Deshabilitación del botón
        submitButton.disabled = !isValid;
        submitButton.classList.toggle('opacity-50', !isValid);
        submitButton.classList.toggle('cursor-not-allowed', !isValid);
    }
    
    // Función principal para verificar si el botón de Búsqueda es válido
    function updateBusquedaButton() {
        const buscarButton = document.getElementById('buscar-button');
        // Este botón usa Alpine.js en el HTML, pero si la validación JS falla, se usa el script para controlarlo.
        if (!buscarButton) return;
        
        // La lógica de búsqueda en el HTML ya usa Alpine.js, pero mantenemos esta función si se requiere.
        // En este caso, la lógica de Alpine en el HTML (`x-data` en el Paso 1) ya controla esto, por lo que esta función no es estrictamente necesaria.
    }

    // ------------------------------------------------------------------------
    // ⭐ NUEVAS VALIDACIONES PARA CAMPOS LABORALES (NECESARIOS AHORA) ⭐
    // ------------------------------------------------------------------------

    // Ejecutar al cargar la página si el formulario de registro está visible, para el estado inicial de los select
    document.addEventListener('DOMContentLoaded', () => {
        const registroForm = document.getElementById('registroTrabajadorForm');
        if (registroForm) {
            validateCargo();
            validateZona();
            
            // Agregar listeners para forzar la re-validación de los campos laborales al cambiar
            const cargoSelect = document.getElementById('cargo_id');
            const zonaInput = document.getElementById('zona_trabajo');
            if(cargoSelect) cargoSelect.addEventListener('change', validateCargo);
            if(zonaInput) zonaInput.addEventListener('input', validateZona);

            // Si es modo Persona Nueva (Paso 2), validar campos personales
            if (document.getElementById('cedula')) {
                validateCedula();
                validateTelefono();
                validateEmail();
                // Agregar listeners a los inputs restantes obligatorios para habilitar el botón
                document.getElementById('nombre')?.addEventListener('input', updateSubmitButton);
                document.getElementById('apellido')?.addEventListener('input', updateSubmitButton);
                document.getElementById('nacionalidad')?.addEventListener('change', updateSubmitButton);
                document.getElementById('genero')?.addEventListener('change', updateSubmitButton);
                document.getElementById('nacimiento')?.addEventListener('input', updateSubmitButton);
            }
        }
    });

    function validateCargo() {
        const select = document.getElementById('cargo_id');
        if (!select) return; 

        isCargoValid = select.value !== ""; // El valor debe ser diferente al placeholder ("")
        
        if (select.value === "") {
            select.classList.add('border-red-500');
            select.classList.remove('border-green-500');
        } else {
            select.classList.remove('border-red-500');
            select.classList.add('border-green-500');
        }
        
        updateSubmitButton();
    }
    
    function validateZona() {
        const input = document.getElementById('zona_trabajo');
        if (!input) return;

        isZonaValid = input.value.trim() !== "";
        
        // Aplicar estilos de validación al input
        input.classList.remove('border-green-500', 'border-red-500');
        if (input.value.trim() !== '') {
            input.classList.add(isZonaValid ? 'border-green-500' : 'border-red-500');
        }

        updateSubmitButton();
    }
    
    // ------------------------------------------------------------------------
    // 1. Validación: Cédula de Búsqueda (8 dígitos, solo números)
    // ------------------------------------------------------------------------
    // NOTE: Esta función ya está siendo manejada por Alpine.js en el HTML (x-data)
    
    // ------------------------------------------------------------------------
    // 2. Validación de Cédula (Formulario de Registro): 8 dígitos y solo números
    // ------------------------------------------------------------------------
    function validateCedula() {
        const input = document.getElementById('cedula');
        const feedback = document.getElementById('cedula-feedback');
        if (!input || !feedback) return;

        let value = input.value.trim();

        value = value.replace(/\D/g, '').substring(0, 8); // Limitar a 8 dígitos
        input.value = value; 

        isCedulaValid = false;
        
        input.classList.remove('border-green-500', 'border-red-500', 'border-gray-300'); // Limpiar clases
        
        if (value === '') {
            setFeedback(input, feedback, false, 'La cédula es obligatoria.');
            input.classList.add('border-gray-300');
        } else if (value.length < 8) {
            setFeedback(input, feedback, false, ` Faltan ${8 - value.length} dígitos (debe tener 8).`);
            input.classList.add('border-red-500');
        } else {
            setFeedback(input, feedback, true, ' Cédula válida.');
            isCedulaValid = true;
            input.classList.add('border-green-500');
        }
        
        updateSubmitButton();
    }

    // ------------------------------------------------------------------------
    // 3. Validación de Teléfono: 11 dígitos y solo números (Estándar Venezolano)
    // ------------------------------------------------------------------------
    function validateTelefono() {
        const input = document.getElementById('telefono');
        const feedback = document.getElementById('telefono-feedback');
        if (!input || !feedback) return; 

        let value = input.value.trim();

        value = value.replace(/\D/g, '').substring(0, 11); // Limitar a 11 dígitos
        input.value = value; 

        isTelefonoValid = false;

        input.classList.remove('border-green-500', 'border-red-500', 'border-gray-300'); // Limpiar clases
        
        if (value === '') {
            setFeedback(input, feedback, false, 'El teléfono es obligatorio.');
            input.classList.add('border-gray-300');
        } else if (value.length < 11) {
            setFeedback(input, feedback, false, ` Faltan ${11 - value.length} dígitos (debe tener 11).`);
            input.classList.add('border-red-500');
        } else {
            setFeedback(input, feedback, true, ' Teléfono válido.');
            isTelefonoValid = true;
            input.classList.add('border-green-500');
        }

        updateSubmitButton();
    }

    // 4. Validación de Email: formato correcto (ej: hola@gmail.com)
    function validateEmail() {
        const input = document.getElementById('email');
        const feedback = document.getElementById('email-feedback');
        if (!input || !feedback) return; 

        const value = input.value.trim();
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

        isEmailValid = true; 

        input.classList.remove('border-green-500', 'border-red-500', 'border-gray-300'); // Limpiar clases
        
        if (value === '') {
            setFeedback(input, feedback, true, 'Campo opcional. (Vacío)');
            input.classList.add('border-gray-300');
        } else if (!emailRegex.test(value)) {
            setFeedback(input, feedback, false, ' Formato de email inválido.');
            isEmailValid = false;
            input.classList.add('border-red-500');
        } else {
            setFeedback(input, feedback, true, ' Formato de email correcto.');
            input.classList.add('border-green-500');
        }
        
        updateSubmitButton();
    }
</script>

</div>

