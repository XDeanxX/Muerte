<div class="w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl mx-auto p-2 sm:p-4 md:p-6 lg:p-8">
    <div class="w-full bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('login') }}"
                class="p-2 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition-all duration-200"
                aria-label="Volver al inicio de sesión">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                    </path>
                </svg>
            </a>
            <div class="flex justify-center flex-1">
                <img src="{{ asset('img/logotipov2.png') }}" alt="Logotipo CMBEY"
                    class="w-auto h-12 sm:h-16 object-contain">
            </div>
            <div class="w-10"></div>
        </div>

        <div>
            <div class="flex justify-center items-center mb-10 relative">
                <div class="absolute w-2/3 h-0.5 bg-gray-200 top-1/2 -translate-y-1/2 z-0"></div>

                <div class="flex items-center z-10 space-x-6 sm:space-x-8">
                    <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full transition-all duration-300
                {{ $step >= 1 ? 'bg-blue-600' : 'bg-gray-200' }}
                {{ $step > 1 ? 'bg-blue-300 scale-100' : '' }}
                {{ $step == 1 ? 'scale-125 shadow-md' : '' }}" aria-label="Paso 1: Ingresar Cédula">
                    </div>
                    <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full transition-all duration-300
                {{ $step >= 2 ? 'bg-blue-600' : 'bg-gray-200' }}
                {{ $step > 2 ? 'bg-blue-300 scale-100' : '' }}
                {{ $step == 2 ? 'scale-125 shadow-md' : '' }}" aria-label="Paso 2: Preguntas de Seguridad">
                    </div>
                    <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full transition-all duration-300
                {{ $step >= 3 ? 'bg-blue-600' : 'bg-gray-200' }}
                {{ $step > 3 ? 'bg-green-500 scale-100' : '' }}
                {{ $step == 3 ? 'scale-125 shadow-md' : '' }}" aria-label="Paso 3: Nueva Contraseña">
                    </div>
                </div>
            </div>


            @if ($step == 1)
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Recuperar Contraseña</h2>
                <p class="text-gray-600 text-sm sm:text-base">Ingresa tu cédula para comenzar el proceso de recuperación
                </p>
            </div>

            <form wire:submit.prevent="findUser">
                <div class="relative mb-8">
                    <label for="cedula" class="block mb-2 text-slate-800 font-semibold text-sm">Cédula de
                        Identidad</label>
                    <input type="text" id="cedula" wire:model="cedula" placeholder="Ingresa tu cédula"
                        inputmode="numeric" required
                        class="w-full p-3 sm:p-4 bg-white border-2 border-gray-200 rounded-xl text-slate-800 text-base transition duration-300 shadow-sm
                        focus:outline-none focus:border-blue-600 focus:shadow-xl focus:shadow-blue-300/50 hover:border-slate-400">
                    @error('cedula')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3 sm:py-4 px-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-xl transform hover:scale-[1.01] transition-all duration-300">
                    Continuar
                </button>
            </form>
            @endif

            @if ($step == 2)
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Preguntas de Seguridad</h2>
                <p class="text-gray-600 text-sm sm:text-base">Responde las siguientes preguntas para verificar tu
                    identidad</p>
            </div>

            <form wire:submit.prevent="verifyAnswers">
                @foreach ($securityQuestions as $index => $securityAnswer)
                <div
                    class="bg-slate-50 border-2 border-gray-200 rounded-xl p-4 sm:p-6 mb-4 transition duration-300 hover:border-slate-400 hover:shadow-md">
                    <div
                        class="bg-blue-600 text-white w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm mb-3">
                        {{ $index + 1 }}
                    </div>
                    <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                        {{ $securityAnswer->securityQuestion->question_text }}
                    </label>
                    <input type="text" wire:model="userAnswers.{{ $index }}" placeholder="Tu respuesta..." class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none transition-colors duration-300
                    focus:shadow-md focus:shadow-blue-200/50" required>
                    @error("userAnswers.{$index}")
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach

                @error('general')
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm sm:text-base">
                    {{ $message }}
                </div>
                @enderror

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-8">
                    <button type="button" wire:click="goBack"
                        class="flex-1 py-3 px-4 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-200 text-sm sm:text-base font-semibold">
                        Volver
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 sm:py-4 px-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-xl transform hover:scale-[1.01] transition-all duration-300 text-sm sm:text-base">
                        Verificar Respuestas
                    </button>
                </div>
            </form>
            @endif

            @if ($step == 3)
            <div class="text-center mb-8">
                <div
                    class="w-14 h-14 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Nueva Contraseña</h2>
                <p class="text-gray-600 text-sm sm:text-base">Crea una nueva contraseña segura para tu cuenta</p>
            </div>

            <form wire:submit.prevent="resetPassword" x-data="{
show1: false,
show2: false,
valid: 0,
passwordValue: @entangle('newPassword').live,
changePassword: @entangle('changePassword'),

validatePassword() {
    this.valid = 0;
    if (this.passwordValue && this.passwordValue.length >= 8) this.valid++;
    if (this.passwordValue && /[A-Z]/.test(this.passwordValue)) this.valid++;
    if (this.passwordValue && /[!@#$%^&*()]/.test(this.passwordValue)) this.valid++;
}


}" x-init="validatePassword()">

                <div class="relative mb-6">
                    <label for="newPassword" class="block mb-2 text-slate-800 font-semibold text-sm">
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" :type="show1 ? 'text' : 'password'" id="newPassword"
                            wire:model="newPassword" x-on:input.debounce.250ms="validatePassword()" class="w-full p-3 sm:p-4 bg-white border-2 border-gray-200 rounded-xl text-slate-800 text-base transition duration-300 shadow-sm pr-12
            focus:outline-none focus:border-blue-600 focus:shadow-xl focus:shadow-blue-300/50 hover:border-slate-400">

                        <button type="button" id="password"
                            class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-slate-500 hover:text-blue-600 transition-colors duration-200"
                            aria-label="Mostrar/Ocultar Nueva Contraseña" @click="show1 = !show1">
                            <i :class="show1 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                        </button>
                    </div>
                    @error('newPassword')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-1 mb-6">
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
                    <p class="text-xs text-gray-600 mt-1 font-medium" x-text="
        valid === 0 ? '' :
        valid === 1 ? 'Débil' :
        valid === 2 ? 'Media' :
        'Fuerte'
    "></p>
                </div>

                <div class="relative mb-6">
                    <label for="confirmPassword" class="block mb-2 text-slate-800 font-semibold text-sm">
                        Confirmar Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" :type="show2 ? 'text' : 'password'" id="confirmPassword"
                            wire:model="confirmPassword" placeholder="Confirma tu nueva contraseña" required class="w-full p-3 sm:p-4 bg-white border-2 border-gray-200 rounded-xl text-slate-800 text-base transition duration-300 shadow-sm pr-12
            focus:outline-none focus:border-blue-600 focus:shadow-xl focus:shadow-blue-300/50 hover:border-slate-400">

                        <button type="button" @click='show2=!show2' id="toggleConfirmPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-slate-500 hover:text-blue-600 transition-colors duration-200"
                            aria-label="Mostrar/Ocultar Confirmar Contraseña">
                            <i :class="show2 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                        </button>
                    </div>
                    @error('confirmPassword')
                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i class='bx bx-shield-alt-2 text-xl mr-2 text-blue-600'></i>
                        Requisitos de la contraseña:
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1 list-none">
                        <li class="flex items-center"
                            :class="{ 'line-through opacity-60 text-gray-500': passwordValue.length >= 8 }">
                            <i class='bx mr-2 text-lg'
                                :class="passwordValue.length >= 8 ? 'bx-check text-green-600' : 'bx-minus text-gray-500'"></i>
                            Mínimo 8 caracteres
                        </li>
                        <li class="flex items-center"
                            :class="{ 'line-through opacity-60 text-gray-500': passwordValue && /[A-Z]/.test(passwordValue) }">
                            <i class='bx mr-2 text-lg'
                                :class="passwordValue && /[A-Z]/.test(passwordValue) ? 'bx-check text-green-600' : 'bx-minus text-gray-500'"></i>
                            Al menos una letra mayúscula
                        </li>
                        <li class="flex items-center"
                            :class="{ 'line-through opacity-60 text-gray-500': passwordValue && /[!@#$%^&*()]/.test(passwordValue) }">
                            <i class='bx mr-2 text-lg'
                                :class="passwordValue && /[!@#$%^&*()]/.test(passwordValue) ? 'bx-check text-green-600' : 'bx-minus text-gray-500'"></i>
                            Al menos un carácter especial (!@#$%^&*())
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-8">
                    <button type="button" wire:click="goBack"
                        class="flex-1 py-3 px-4 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-200 text-sm sm:text-base font-semibold">
                        Volver
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 sm:py-4 px-6 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-sky-600 hover:to-blue-700 hover:shadow-xl transform hover:scale-[1.01] transition-all duration-300 flex items-center justify-center gap-2 group text-base sm:text-lg">
                        <i class='bx bx-save'></i> Actualizar Contraseña
                    </button>
                </div>

            </form>
            @endif

        </div>
    </div>

</div>