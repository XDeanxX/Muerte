<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                Configuración de Seguridad
            </h1>
            <p class="text-lg text-gray-600">
                Gestiona tu contraseña y preguntas de seguridad
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden" x-data="{ activeTab: 'password' }">
            <div class="flex border-b">
                <button @click="activeTab = 'password'"
                    :class="activeTab === 'password' ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 font-medium flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="hidden sm:inline">Cambiar Contraseña</span>
                    <span class="sm:hidden">Contraseña</span>
                </button>

                <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-700' : 'text-gray-600 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 font-medium flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="hidden sm:inline">Preguntas de Seguridad</span>
                    <span class="sm:hidden">Seguridad</span>
                </button>
            </div>

            <!-- Tab Content: Cambiar Contraseña -->
            <div x-show="activeTab === 'password'" class="p-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-t-lg border-b">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Cambiar Contraseña
                    </h3>
                    <p class="text-gray-600 mt-1">Actualiza tu contraseña para mantener tu cuenta segura</p>
                </div>

                <form wire:submit.prevent="changePassword" class="space-y-6 mt-6" x-data="{
                      show1: false,
                      show2: false,
                      show3: false,
                      valid: 0,
                      // Livewire entangle para sincronizar con la propiedad 'new_password'
                      passwordValue: @entangle('new_password').live,
                      
                      validatePassword() {
                          this.valid = 0;
                          if (this.passwordValue && this.passwordValue.length >= 8) this.valid++;
                          if (this.passwordValue && /[A-Z]/.test(this.passwordValue)) this.valid++;
                          if (this.passwordValue && /[!@#$%^&*()]/.test(this.passwordValue)) this.valid++;
                      }
                      
                      
                    }" x-init="validatePassword()">

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Contraseña Actual
                        </label>
                        <div class="relative">
                            <input wire:model="current_password" :type="show1 ? 'text' : 'password'"
                                placeholder="Ingresa tu contraseña actual"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <button type="button" @click="show1 = !show1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i :class="show1 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                            </button>
                        </div>
                        @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input wire:model="new_password" :type="show2 ? 'text' : 'password'"
                                x-on:input.debounce.250ms="validatePassword()" placeholder="Ingresa tu nueva contraseña"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <button type="button" @click="show2 = !show2"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i :class="show2 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                            </button>
                        </div>
                        @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input wire:model="new_password_confirmation" :type="show3 ? 'text' : 'password'"
                                placeholder="Confirma tu nueva contraseña"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <button type="button" @click="show3 = !show3"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i :class="show3 ? 'bx-hide' : 'bx-show'" class="bx text-xl"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <i class='bx bx-shield mr-2'></i>
                            Requisitos de la contraseña:
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li :class="{ 'line-through opacity-50': passwordValue.length >= 8 }">
                                • Mínimo 8 caracteres
                            </li>
                            <li :class="{ 'line-through opacity-50': passwordValue && /[A-Z]/.test(passwordValue) }">
                                • Al menos una letra mayúscula
                            </li>
                            <li
                                :class="{ 'line-through opacity-50': passwordValue && /[!@#$%^&*()]/.test(passwordValue) }">
                                • Al menos un carácter especial (!@#$%^&*())
                            </li>
                        </ul>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Actualizar Contraseña</span>
                        <span wire:loading>Actualizando...</span>
                    </button>


                </form>
            </div>

            <div x-show="activeTab === 'security'" class="p-6">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-t-lg border-b">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Preguntas de Seguridad
                    </h3>
                    <p class="text-gray-600 mt-1">Configura 3 preguntas de seguridad para recuperar tu cuenta</p>
                </div>

                @if(count($currentUserQuestions) > 0)
                <div class="mt-6 mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-900 mb-2">Preguntas actuales configuradas:</p>
                    <ul class="space-y-1">
                        @foreach($currentUserQuestions as $index => $question_text)
                        <li class="text-sm text-blue-700">{{ $index + 1 }}. {{ $question_text }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form wire:submit.prevent="updateSecurityQuestions" class="space-y-6 mt-6">
                    @for($i = 0; $i < 3; $i++) <div class="p-4 bg-gray-50 rounded-lg space-y-4">
                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">
                                Pregunta {{ $i + 1 }}
                            </label>
                            <select wire:model="selectedQuestions.{{ $i }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Selecciona una pregunta</option>
                                @foreach($securityQuestions as $question)
                                <option value="{{ $question->id }}">{{ $question->question_text }}</option>
                                @endforeach
                            </select>
                            @error('selectedQuestions.' . $i) <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-2">
                                Respuesta {{ $i + 1 }}
                            </label>
                            <input type="text" wire:model="securityAnswers.{{ $i }}" placeholder="Ingresa tu respuesta"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            @error('securityAnswers.' . $i) <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
            </div>
            @endfor

            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm text-yellow-800">
                    <strong>Importante:</strong> Recuerda tus respuestas. Las necesitarás para recuperar tu cuenta si
                    olvidas tu contraseña.
                </p>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Actualizar Preguntas de Seguridad</span>
                <span wire:loading>Actualizando...</span>
            </button>
            </form>
        </div>
    </div>
</div>
</div>