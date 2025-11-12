@php $currentStep = $currentStep ?? 1; @endphp

<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 ">
    <div class="w-full max-w-lg lg:max-w-xl bg-white rounded-2xl p-6 sm:p-8 shadow-2xl">

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('login') }}"
                class="p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex justify-center">
                <img src="{{ asset('img/logotipov2.png') }}" alt="Logotipo CMBEY"
                    class="w-auto h-12 sm:h-16 object-contain">
            </div>
            <div class="w-10 h-10"></div>
        </div>

        <div>
            <div class="flex justify-between items-center space-x-1 sm:space-x-2 mb-8">
                @for ($i = 1; $i <= 5; $i++) @php $isActive=$currentStep==$i; $isCompleted=$currentStep> $i;
                    $bgClass = $isCompleted ? 'bg-blue-600' : ($isActive ? 'bg-sky-500' : 'bg-gray-300');
                    @endphp
                    <div class="flex-1 h-2 rounded-full transition-colors duration-300 {{ $bgClass }}">
                    </div>
                    @endfor
            </div>

            <form wire:submit.prevent="register" class="space-y-6">

                @if ($currentStep == 1)
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">¿Cómo te llamas?</h2>
                    <p class="text-gray-600 text-center mb-6">Ingresa tu información personal</p>

                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="flex-1 space-y-1">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Primer Nombre</label>
                            <input type="text" id="nombre" wire:model="nombre" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('nombre')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-1 space-y-1">
                            <label for="segundo_nombre" class="block text-sm font-medium text-gray-700">Segundo
                                Nombre</label>
                            <input type="text" id="segundo_nombre" wire:model="segundo_nombre"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('segundo_nombre')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 space-y-1">
                            <label for="apellido" class="block text-sm font-medium text-gray-700">Primer Apellido
                                *</label>
                            <input type="text" id="apellido" wire:model="apellido" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('apellido')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-1 space-y-1">
                            <label for="segundo_apellido" class="block text-sm font-medium text-gray-700">Segundo
                                Apellido</label>
                            <input type="text" id="segundo_apellido" wire:model="segundo_apellido"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('segundo_apellido')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                @if ($currentStep == 2)
                <div class="mb-6 space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Información Personal</h2>
                    <p class="text-gray-600 text-center mb-6">Completa tus datos personales</p>

                    <div class="flex flex-col ss:flex-row gap-4">
                        <div class="space-y-1" style="flex: 0 0 100px;">
                            <label for="nacionalidad" class="block text-sm font-medium text-gray-700">Nacionalidad
                                *</label>
                            <select id="nacionalidad" wire:model="nacionalidad" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="">Selec.</option>
                                @foreach ($nacionality as $nacionalidad)
                                <option value="{{ $nacionalidad->id }}">{{ $nacionalidad->Nacionalidad }}</option>
                                @endforeach
                            </select>
                            @error('nacionalidad')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-1 space-y-1">
                            <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula de Identidad
                                *</label>

                            <input type="number" id="cedula" wire:model="cedula" required maxlength="8"
                                @input="if (event.target.value.length > 8) event.target.value = event.target.value.slice(0, 8)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('cedula')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col ss:flex-row gap-4">
                        <div class="space-y-1" style="flex: 0 0 100px;">
                            <label for="prefijo_telefono" class="block text-sm font-medium text-gray-700">Prefijo
                                *</label>
                            <select id="prefijo_telefono" wire:model="prefijo_telefono" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="" selected disabled>Prefijo</option>
                                <option value="0412">0412</option>
                                <option value="0422">0422</option>
                                <option value="0414">0414</option>
                                <option value="0424">0424</option>
                                <option value="0416">0416</option>
                                <option value="0426">0426</option>
                            </select>
                            @error('prefijo_telefono')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-1 space-y-1">
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Número de Teléfono
                                *</label>
                            <input type="text" id="telefono" wire:model="telefono" placeholder="XXX-XXXX" maxlength="8"
                                pattern="\d{3}-\d{4}"
                                oninput="this.value = this.value.replace(/\D/g, '').replace(/(\d{3})(\d{4})/, '$1-$2')"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('telefono')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico
                            *</label>
                        <input type="email" id="email" wire:model="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                        @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 space-y-1">
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de
                                Nacimiento *</label>
                            <input type="date" id="fecha_nacimiento" wire:model="nacimiento" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('nacimiento')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            @if ($ageError ?? false)
                            <div class="text-red-600 text-sm mt-1">{{ $ageError }}</div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-1">
                            <label for="genero" class="block text-sm font-medium text-gray-700">Género *</label>
                            <select id="genero" wire:model="genero" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="" selected disabled>Seleccionar</option>
                                @foreach ($genre as $genero)
                                <option value="{{ $genero->id }}">{{ $genero->genero }}</option>
                                @endforeach
                            </select>
                            @error('genero')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                @if ($currentStep == 3)
                <div class="mb-6 space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Ubicación y Seguridad</h2>
                    <p class="text-gray-600 text-center mb-6">Completa tu dirección y contraseña</p>

                    <div class="relative mb-4">
                        <label for="direccion" class="block text-sm font-semibold text-slate-800 mb-1.5">Dirección
                            Completa *</label>
                        <input type="text" id="direccion" wire:model="direccion"
                            placeholder="Ingresa tu dirección completa" required
                            class="w-full px-4 py-3 bg-white/95 border-2 border-slate-200 rounded-lg text-slate-800 text-sm shadow-sm
               focus:outline-none focus:border-sky-700 focus:ring-4 focus:ring-sky-700/20 transition duration-300 hover:border-slate-400">
                        @error('direccion')
                        <div class="text-red-600 text-xs mt-0.5">{{ $message }}</div>
                        @enderror
                    </div>

                    <div x-data="{
                           show1: false,
                           show2: false,
                           valid: 0,
                           passwordValue: @entangle('password').defer,
                           validatePassword: function() {
                               this.valid = 0;
                               if (this.passwordValue && this.passwordValue.length >= 8) this.valid++;
                                                      if (this.passwordValue && /[A-Z]/.test(this.passwordValue)) this.valid++; 
                               if (this.passwordValue && /[!@#$%^&*()]/.test(this.passwordValue)) this.valid++;
                           }
                       }" x-init="validatePassword()">

                        <div class="flex flex-col md:flex-row gap-4 mb-4">

                            <div class="relative flex-1">
                                <label for="password"
                                    class="block text-sm font-semibold text-slate-800 mb-1.5">Contraseña *</label>
                                <div class="relative">
                                    <input :type="show1 ? 'text' : 'password'" id="password" wire:model.blur="password"
                                        x-model="passwordValue" @input="validatePassword()" @blur="validatePassword()"
                                        placeholder="Mínimo 8 caracteres" required
                                        class="w-full px-4 py-3 bg-white/95 border-2 border-slate-200 rounded-lg text-slate-800 text-sm shadow-sm
                           focus:outline-none focus:border-sky-700 focus:ring-4 focus:ring-sky-700/20 transition duration-300 hover:border-slate-400">

                                    <button type="button" @click="show1 = !show1"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-transparent border-none text-slate-500 cursor-pointer transition duration-200 hover:text-sky-700 p-1">
                                        <i x-show="!show1" class='bx bx-show'></i>
                                        <i x-show="show1" class='bx bx-hide'></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="text-red-600 text-xs mt-0.5">{{ $message }}</div>
                                @enderror

                                {{-- Barra de seguridad --}}
                                <div class="flex space-x-1 w-full h-2 mt-2 rounded-full overflow-hidden">
                                    <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                    'bg-red-500': valid === 1,
                    'bg-yellow-500': valid === 2,
                    'bg-green-500': valid === 3,
                    'bg-gray-200': valid === 0
                }">
                                    </div>
                                    <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                    'bg-yellow-500': valid === 2,
                    'bg-green-500': valid === 3,
                    'bg-gray-200': valid < 2
                }">
                                    </div>
                                    <div class="flex-1 rounded-full transition-colors duration-300" :class="{
                    'bg-green-500': valid === 3,
                    'bg-gray-200': valid < 3
                }">
                                    </div>
                                </div>
                                <div
                                    class="mt-1 text-sm font-semibold flex justify-center transition-opacity duration-300">
                                    <span x-show="valid === 1" class="text-red-500">Inválido</span>
                                    <span x-show="valid === 2" class="text-yellow-500">Falta un poco</span>
                                    <span x-show="valid === 3" class="text-green-500">Válido</span>
                                    <span x-show="passwordValue.length > 0 && valid === 0" class="text-red-500">Inválido
                                        (Mínimo 8 caracteres)</span>
                                    <span x-show="!passwordValue" class="text-gray-500">Ingrese una contraseña</span>
                                </div>
                            </div>

                            <div class="relative flex-1">
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-slate-800 mb-1.5">Confirmar Contraseña
                                    *</label>
                                <div class="relative">
                                    <input :type="show2 ? 'text' : 'password'" id="password_confirmation"
                                        wire:model="password_confirmation" required
                                        class="w-full px-4 py-3 bg-white/95 border-2 border-slate-200 rounded-lg text-slate-800 text-sm shadow-sm
                           focus:outline-none focus:border-sky-700 focus:ring-4 focus:ring-sky-700/20 transition duration-300 hover:border-slate-400">

                                    <button type="button" @click="show2 = !show2"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-transparent border-none text-slate-500 cursor-pointer transition duration-200 hover:text-sky-700 p-1">
                                        <i x-show="!show2" class='bx bx-show'></i>
                                        <i x-show="show2" class='bx bx-hide'></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                <div class="text-red-600 text-xs mt-0.5">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                                <i class='bx bx-shield mr-2'></i>
                                Requisitos de la contraseña:
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1 ml-6 list-disc">
                                <li :class="{ 'line-through opacity-50': passwordValue && passwordValue.length >= 8 }">
                                    Mínimo 8 caracteres</li>
                                <li
                                    :class="{ 'line-through opacity-50': passwordValue && /[A-Z]/.test(passwordValue) }">
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
                @endif

                @if ($currentStep == 4)
                <div class="mb-6 space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Preguntas de Seguridad</h2>
                    <p class="text-gray-600 text-center mb-6">Selecciona 3 preguntas de seguridad para proteger tu
                        cuenta</p>
                    @for ($i = 0; $i < 3; $i++) <div
                        class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 space-y-3">
                        <h4 class="font-semibold text-gray-800 mb-3">Pregunta {{ $i + 1 }}</h4>

                        <div class="space-y-1">
                            <label for="question_{{ $i }}" class="block text-sm font-medium text-gray-700">Selecciona
                                una pregunta</label>
                            <select id="question_{{ $i }}" wire:model="selectedQuestions.{{ $i }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="">Selecciona una pregunta...</option>
                                @foreach ($securityQuestions as $question)
                                <option value="{{ $question->id }}">{{ $question->question_text }}</option>
                                @endforeach
                            </select>
                            @error("selectedQuestions.{$i}")
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="answer_{{ $i }}" class="block text-sm font-medium text-gray-700">Tu
                                respuesta</label>
                            <input type="text" id="answer_{{ $i }}" wire:model="securityAnswers.{{ $i }}"
                                placeholder="Escribe tu respuesta..." required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error("securityAnswers.{$i}")
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
                @endfor

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
                    <h4 class="font-semibold text-yellow-800 mb-2">⚠️ Importante:</h4>
                    <p class="text-sm text-yellow-700">Recuerda bien tus respuestas, las necesitarás para recuperar
                        tu contraseña en el futuro.</p>
                </div>
        </div>
        @endif

        @if ($currentStep == 5)
        <div class="mb-6 space-y-4">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Verificación</h2>
            <p class="text-gray-600 text-center mb-6">Completa la verificación para finalizar</p>

            <div
                class="flex flex-col items-center justify-center space-y-4 bg-gray-100 p-6 rounded-lg border border-gray-200">
                <div class="text-lg font-bold text-gray-800">{{ $captchaQuestion }}</div>
                <input type="number" wire:model="captchaAnswer" placeholder="Escribe el resultado" required
                    class="text-center text-xl w-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
            </div>
            @error('captchaAnswer')
            <div class="text-red-600 text-sm mt-1 text-center">{{ $message }}</div>
            @enderror

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                <h3 class="font-semibold text-blue-800 mb-2">Resumen de tu información:</h3>
                <p class="text-sm text-blue-700"><strong>Nombre:</strong> {{ $nombre }}
                    {{ $segundo_nombre }} {{ $apellido }} {{ $segundo_apellido }}</p>
                <p class="text-sm text-blue-700"><strong>Cédula:</strong> {{ $cedula }}</p>
                <p class="text-sm text-blue-700"><strong>Teléfono:</strong>
                    {{ $prefijo_telefono }}-{{ $telefono }}</p>
                <p class="text-sm text-blue-700"><strong>Email:</strong> {{ $email }}</p>
            </div>
        </div>
        @endif


        <div class="flex justify-between items-center pt-4">
            @if ($currentStep > 1)
            <button type="button" wire:click="previousStep"
                class="px-5 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-all duration-200 flex items-center gap-2 group text-sm font-semibold">
                <svg class="w-4 h-4 transform transition-transform group-hover:translate-x-[-2px]" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Anterior
            </button>
            @else
            <div></div>
            @endif

            @if ($currentStep < 5) <button type="button" wire:click="nextStep"
                class="px-5 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all duration-300 flex items-center gap-2 group text-sm font-semibold">
                Siguiente
                <svg class="w-4 h-4 transform transition-transform group-hover:translate-x-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                </button>
                @else
                <button type="submit" wire:loading.attr="disabled"
                    class="px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 text-sm font-semibold">
                    <span wire:loading.remove>Registrarse</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Procesando...
                    </span>
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </button>
                @endif
        </div>
        </form>
    </div>
</div>
</div>