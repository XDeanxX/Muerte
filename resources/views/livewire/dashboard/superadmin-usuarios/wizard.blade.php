<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">

        <div class="flex items-center justify-center">

            <div class="flex flex-col items-center {{ $wizardStep >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $wizardStep >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}
                            {{ $isEditMode ? 'opacity-50' : '' }}">
                    <i class='bx bx-search text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Búsqueda </span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $wizardStep >= 2 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $wizardStep >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $wizardStep >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-user text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Datos Personales</span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $wizardStep >= 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $wizardStep >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $wizardStep >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-lock-alt text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Credenciales</span>
            </div>
        </div>
    </div>

    @if ($wizardStep === 1 && !$isEditMode)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-id-card text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 1: Búsqueda de Usuario</h2>
                <p class="text-gray-600">Ingresa la cédula para verificar si la persona ya está registrada</p>
            </div>

            <form wire:submit.prevent="searchUserByCedula" class="space-y-6">
                <div x-data="{ 
                            cedulaValue: @entangle('cedula').live, 
                            isInvalid: false,
                            validateCedula() {
                                const len = this.cedulaValue ? this.cedulaValue.length : 0;
                                this.isInvalid = (len > 0 && len < 6) || len > 8;
                            }
                        }" x-init="validateCedula()">

                    <label for="cedula-search" class="block text-lg font-semibold text-gray-800 mb-3">
                        Cédula de Identidad *
                    </label>

                    <div class="relative">
                        <input x-model="cedulaValue" @input="
                                        $el.value = $el.value.replace(/[^0-9]/g, '');
                                        cedulaValue = $el.value;
                                        validateCedula();
                                    " type="text" inputmode="numeric" id="cedula-search"
                            placeholder="Ingrese número de cédula (6-8 dígitos)" maxlength="8" class="block w-full rounded-lg border-2 pr-12 text-lg
                                        @error('cedula') border-red-500 @else border-gray-300 @enderror
                                        focus:border-blue-600 focus:ring-blue-600 p-4" required>

                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <i class='bx bx-id-card text-2xl text-gray-400'></i>
                        </div>
                    </div>

                    <div x-show="isInvalid" x-cloak class="mt-2 text-sm text-red-600">
                        <i class='bx bx-error-circle mr-1'></i>
                        La cédula debe tener entre 6 y 8 dígitos
                    </div>

                    @error('cedula')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                        <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <i class='bx bx-info-circle mr-2'></i>
                            ¿Qué sucederá?
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1 ml-6">
                            <li>• Si la persona <strong>existe y tiene usuario</strong>: se notificará</li>
                            <li>• Si la persona <strong>existe sin usuario</strong>: asignar credenciales (Paso 3)</li>
                            <li>• Si la persona <strong>no existe</strong>: registrar datos personales (Paso 2)</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-between pt-6">
                    <button type="button" wire:click="cancelWizard"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium cursor-pointer">
                        <i class='bx bx-x mr-2'></i>
                        Cancelar
                    </button>

                    <button type="submit" x-bind:disabled="isInvalid || !cedulaValue"
                        :class="{ 'opacity-50 cursor-not-allowed': isInvalid || !cedulaValue }"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg transition-all cursor-pointer group">
                        Buscar y Continuar
                        <i class='bx bx-right-arrow-alt ml-2 transition-transform group-hover:translate-x-1'></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if ($wizardStep === 2)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class='bx bx-user text-blue-600 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Paso 2: Datos Personales
                    </h2>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="savePersonalData" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Primer Nombre *
                    </label>
                    <input type="text" id="nombre" wire:model="nombre" required placeholder="Ej: Juan" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                    @error('nombre') border-red-500 @else border-gray-300 @enderror">
                    @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="segundo_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Segundo Nombre
                    </label>
                    <input type="text" id="segundo_nombre" wire:model="segundo_nombre" placeholder="Ej: Carlos" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                    @error('segundo_nombre') border-red-500 @else border-gray-300 @enderror">
                    @error('segundo_nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="apellido" class="block text-sm font-medium text-gray-700 mb-2">
                        Primer Apellido *
                    </label>
                    <input type="text" id="apellido" wire:model="apellido" required placeholder="Ej: Rodríguez" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                    @error('apellido') border-red-500 @else border-gray-300 @enderror">
                    @error('apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="segundo_apellido" class="block text-sm font-medium text-gray-700 mb-2">
                        Segundo Apellido
                    </label>
                    <input type="text" id="segundo_apellido" wire:model="segundo_apellido" placeholder="Ej: Pérez"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                    @error('segundo_apellido') border-red-500 @else border-gray-300 @enderror">
                    @error('segundo_apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Nacionalidad *
                    </label>
                    <select id="nacionalidad" wire:model="nacionalidad" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                    @error('nacionalidad') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        @foreach ($nacionality as $nac)
                        <option value="{{ $nac->id }}">{{ $nac->Nacionalidad }}</option>
                        @endforeach
                    </select>
                    @error('nacionalidad') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cedula-readonly" class="block text-sm font-medium text-gray-700 mb-2">
                        Cédula *
                    </label>
                    <input type="text" id="cedula-readonly" value="{{ $cedula }}" readonly
                        class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed font-mono">
                    @if($isEditMode)
                    <p class="mt-1 text-xs text-gray-500">La cédula no puede modificarse</p>
                    @endif
                </div>

                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                        Género *
                    </label>
                    <select id="genero" wire:model="genero" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                    @error('genero') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        @foreach ($genre as $gen)
                        <option value="{{ $gen->id }}">{{ $gen->genero }}</option>
                        @endforeach
                    </select>
                    @error('genero') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico *
                    </label>
                    <input type="email" id="email" wire:model="email" required placeholder="ejemplo@correo.com" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                    @error('email') border-red-500 @else border-gray-300 @enderror">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" id="telefono" wire:model="telefono" placeholder="0412-1234567" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                    @error('telefono') border-red-500 @else border-gray-300 @enderror">
                    @error('telefono') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección
                </label>
                <textarea id="direccion" wire:model="direccion" rows="3" placeholder="Dirección completa..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-between pt-6 border-t">
                @if($isEditMode)
                <button type="button" wire:click="cancelWizard"
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium cursor-pointer">
                    <i class='bx bx-x mr-2'></i>
                    Cancelar Edición
                </button>
                @else
                <button type="button" wire:click="previousStep"
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium cursor-pointer group">
                    <i class='bx bx-left-arrow-alt mr-2 transition-transform group-hover:translate-x-[-4px]'></i>
                    Anterior
                </button>
                @endif

                <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-lg cursor-pointer group">
                    Continuar
                    <i class='bx bx-right-arrow-alt ml-2 transition-transform group-hover:translate-x-1'></i>
                </button>
            </div>
        </form>
    </div>
    @endif


    @if ($wizardStep === 3)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class='bx bx-lock-alt text-blue-600 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Paso 3: Credenciales de Usuario
                    </h2>
                    <p class="text-sm text-gray-600">Asigna rol y contraseña para el acceso al sistema</p>
                </div>
            </div>
        </div>

        @if($personaExists && !$isEditMode)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class='bx bx-check-circle text-green-600 text-2xl mr-3'></i>
                <div>
                    <h4 class="font-semibold text-green-900 mb-1">Persona Encontrada</h4>
                    <p class="text-sm text-green-800">
                        <strong>{{ ucwords($nombre . ' ' . $apellido) }}</strong> - Cédula: {{ $cedula }}
                    </p>
                    <p class="text-xs text-green-700 mt-1">Solo necesitas asignar el rol y la contraseña</p>
                </div>
            </div>
        </div>
        @endif

        <form wire:submit.prevent="saveUser" class="space-y-6" x-data="{
                    show1: false,
                    show2: false,
                    valid: 0,
                    passwordValue: @entangle('password').live,
                    changePassword: @entangle('changePassword'),
                    isEditMode: {{ $isEditMode ? 'true' : 'false' }},
                    validatePassword() {
                        this.valid = 0;
                        if (this.passwordValue && this.passwordValue.length >= 8) this.valid++;
                        if (this.passwordValue && /[A-Z]/.test(this.passwordValue)) this.valid++;
                        if (this.passwordValue && /[!@#$%^&*()]/.test(this.passwordValue)) this.valid++;
                    }
                }" x-init="validatePassword()">

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class='bx bx-user-circle text-blue-600 text-2xl mr-3'></i>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Credencial de acceso</p>
                        <p class="text-lg font-bold text-blue-700">{{ $cedula }}</p>
                        <p class="text-xs text-blue-600 mt-1">La cédula será utilizada como nombre de usuario único</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Rol *
                </label>
                <select id="role" wire:model="role" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                @error('role') border-red-500 @else border-gray-300 @enderror">
                    <option value="">-- Selecciona un rol --</option>
                    @foreach ($roleList as $roleItem)
                    <option value="{{ $roleItem->role }}">{{ $roleItem->name }}</option>
                    @endforeach
                </select>
                @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            @if($isEditMode)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="changePassword"
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-3">
                    <div>
                        <span class="text-sm font-medium text-gray-900">¿Deseas cambiar la contraseña?</span>
                        <p class="text-xs text-gray-600 mt-1">
                            Si no marcas esta opción, la contraseña actual se mantendrá sin cambios
                        </p>
                    </div>
                </label>
            </div>
            @endif

            <div x-show="!isEditMode || changePassword" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña {{ $isEditMode && $changePassword ? 'Nueva' : '' }} *
                        </label>
                        <div class="relative">
                            <input :type="show1 ? 'text' : 'password'" id="password" wire:model="password"
                                x-model="passwordValue" @input="validatePassword()"
                                :required="!isEditMode || changePassword" placeholder="Mínimo 8 caracteres" class="w-full p-3 pr-10 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                            @error('password') border-red-500 @else border-gray-300 @enderror">

                            <button type="button" @click="show1 = !show1"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600">
                                <i :class="show1 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                            </button>
                        </div>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        <div class="flex space-x-1 mt-2 h-2">
                            <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                                            'bg-red-500': valid === 1,
                                            'bg-yellow-500': valid === 2,
                                            'bg-green-500': valid === 3,
                                            'bg-gray-200': valid === 0
                                        }"></div>
                            <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                                            'bg-yellow-500': valid === 2,
                                            'bg-green-500': valid === 3,
                                            'bg-gray-200': valid < 2
                                        }"></div>
                            <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                                            'bg-green-500': valid === 3,
                                            'bg-gray-200': valid < 3
                                        }"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1" x-text="
                                    valid === 0 ? '' :
                                    valid === 1 ? 'Débil' :
                                    valid === 2 ? 'Media' :
                                    'Fuerte'
                                "></p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Contraseña *
                        </label>
                        <div class="relative">
                            <input :type="show2 ? 'text' : 'password'" id="password_confirmation"
                                wire:model="password_confirmation" :required="!isEditMode || changePassword"
                                placeholder="Repite la contraseña"
                                class="w-full p-3 pr-10 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                            @error('password_confirmation') border-red-500 @else border-gray-300 @enderror">

                            <button type="button" @click="show2 = !show2"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600">
                                <i :class="show2 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                            </button>
                        </div>
                        @error('password_confirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Password Requirements --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i class='bx bx-shield mr-2'></i>
                        Requisitos de la contraseña:
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1 ml-6">
                        <li :class="{ 'line-through opacity-50': valid >= 1 }">• Mínimo 8 caracteres</li>
                        <li :class="{ 'line-through opacity-50': passwordValue && /[A-Z]/.test(passwordValue) }">
                            • Al menos una letra mayúscula
                        </li>
                        <li :class="{ 'line-through opacity-50': passwordValue && /[!@#$%^&*()]/.test(passwordValue) }">
                            • Al menos un carácter especial (!@#$%^&*())
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-between pt-6 border-t">
                <button type="button" wire:click="previousStep"
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium cursor-pointer">
                    <i class='bx bx-left-arrow-alt mr-2'></i>
                    Anterior
                </button>

                <button type="submit"
                    class="px-8 py-3 {{ $isEditMode ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors font-medium shadow-lg cursor-pointer">
                    <i class='bx {{ $isEditMode ? ' bx-save' : 'bx-check-circle' }} mr-2'></i>
                    {{ $isEditMode ? 'Guardar Cambios' : 'Crear Usuario' }}
                </button>
            </div>
        </form>
    </div>
    @endif
</div>