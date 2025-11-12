<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">

        <!-- Formulario ÚNICO de Edición y Seguridad -->
        <form wire:submit.prevent="updateUser" class="space-y-8" x-data="{
                show1: false,
                show2: false,
                valid: 0,
                // El passwordValue se enlaza con wire:model.live para actualizar la validación
                passwordValue: @entangle('password').live,
                changePassword: @entangle('changePassword'),
                
                // Función de validación para la barra de fuerza
                validatePassword() {
                    this.valid = 0;
                    if (this.passwordValue && this.passwordValue.length >= 8) this.valid++;
                    if (this.passwordValue && /[A-Z]/.test(this.passwordValue)) this.valid++;
                    if (this.passwordValue && /[!@#$%^&*()]/.test(this.passwordValue)) this.valid++;
                }
            }" x-init="validatePassword()">

          
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class='bx bx-user text-blue-600 text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Datos Personales
                        </h2>
                        <p class="text-sm text-gray-600">
                            Modifica la información de la persona
                        </p>
                    </div>
                </div>
            </div>

            <!-- Campos de Nombres y Apellidos -->
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

            <!-- Campos de Nacionalidad, Cédula y Género -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nacionalidad" class="block text-sm font-medium text-gray-700 mb-2">
                        Nacionalidad *
                    </label>
                    <select id="nacionalidad" wire:model="nacionalidad" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                @error('nacionalidad') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        {{-- Asegúrate de que $nacionality esté disponible en el componente PHP --}}
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
                    {{-- Usa la propiedad $editingUser->persona->cedula si estás usando Route Model Binding --}}
                    <input type="text" id="cedula-readonly" value="{{ $cedula ?? 'No Disponible' }}" readonly
                        class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed font-mono">
                    <p class="mt-1 text-xs text-gray-500">La cédula no puede modificarse</p>
                </div>

                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700 mb-2">
                        Género *
                    </label>
                    <select id="genero" wire:model="genero" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                @error('genero') border-red-500 @else border-gray-300 @enderror">
                        <option value="">Seleccionar...</option>
                        {{-- Asegúrate de que $genre esté disponible en el componente PHP --}}
                        @foreach ($genre as $gen)
                        <option value="{{ $gen->id }}">{{ $gen->genero }}</option>
                        @endforeach
                    </select>
                    @error('genero') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Campos de Contacto -->
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

            <!-- Campo de Dirección -->
            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección
                </label>
                <textarea id="direccion" wire:model="direccion" rows="3" placeholder="Dirección completa..."
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- ============================================== -->
            <!-- SECCIÓN 2: DATOS DE CUENTA Y SEGURIDAD -->
            <!-- ============================================== -->
            <div class="pt-8 border-t border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class='bx bx-lock-alt text-blue-600 text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Datos de Cuenta
                        </h2>
                        <p class="text-sm text-gray-600">
                            Administración del rol y contraseña del usuario.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-6">
                <!-- Selector de Rol -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Rol *
                    </label>
                    <select id="role" wire:model="role" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                @error('role') border-red-500 @else border-gray-300 @enderror">
                        <option value="">-- Selecciona un rol --</option>
                        {{-- Asegúrate de que $roleList esté disponible en el componente PHP --}}
                        @foreach ($roleList as $roleItem)
                        <option value="{{ $roleItem->role }}">{{ $roleItem->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Checkbox para cambio de Contraseña -->
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

                <!-- Campos de Contraseña (Visibles solo si changePassword es true) -->
                <div x-show="changePassword" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña Nueva
                            </label>
                            <div class="relative">
                                <input :type="show1 ? 'text' : 'password'" id="password" wire:model="password"
                                    x-model="passwordValue" @input="validatePassword()" :required="changePassword"
                                    placeholder="Mínimo 8 caracteres" class="w-full p-3 pr-10 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                        @error('password') border-red-500 @else border-gray-300 @enderror">

                                <button type="button" @click="show1 = !show1"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-blue-600">
                                    <i :class="show1 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                                </button>
                            </div>
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            {{-- Password Strength Indicator --}}
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
                                    wire:model="password_confirmation" :required="changePassword"
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

                    <!-- Requisitos de la Contraseña -->
                    <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 mt-6">
                        <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <i class='bx bx-shield mr-2'></i>
                            Requisitos de la contraseña:
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1 ml-6 list-disc">
                            <li :class="{ 'line-through opacity-50': valid >= 1 }">Mínimo 8 caracteres</li>
                            <li :class="{ 'line-through opacity-50': passwordValue && /[A-Z]/.test(passwordValue) }">
                                Al menos una letra mayúscula
                            </li>
                            <li
                                :class="{ 'line-through opacity-50': passwordValue && /[!@#$%^&*()]/.test(passwordValue) }">
                                Al menos un carácter especial (!@#$%^&*())
                            </li>
                        </ul>
                    </div>
                </div> 

            </div> 

            <!-- Botón de Envío -->
            <div class="flex justify-end pt-6 border-t mt-8">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium shadow-lg cursor-pointer flex items-center">
                    <i class='bx bx-check-circle mr-2 text-xl'></i>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>