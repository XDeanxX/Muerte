<div class="w-full lg:w-1/2 flex justify-center items-center min-h-screen p-4 sm:p-6 lg:p-8 mx-auto">
    <div
        class="w-full max-w-sm sm:max-w-md bg-white rounded-2xl p-6 sm:p-8 shadow-2xl flex flex-col justify-center min-h-[400px] sm:min-h-[500px] border border-gray-100">

        <div class="flex justify-center w-full mb-8">
            <img src="{{ asset('img/logotipov2.png') }}" alt="Logotipo CMBEY"
                class="w-auto h-20 sm:h-24 object-contain">
        </div>

        <form wire:submit.prevent='login' class="w-full max-w-xs mx-auto space-y-8">

            <div class="relative mb-6">
                <label for="cedula" class="block text-sm font-semibold text-gray-700 mb-1">Documento</label>
                <input class="w-full px-4 py-3 sm:py-4 border border-gray-300 rounded-lg text-base placeholder:text-gray-400 shadow-sm
                       focus:ring-2 focus:ring-sky-600 focus:border-sky-600 transition duration-300 ease-in-out"
                    name="cedula" id="cedula" type="text" inputmode="numeric" wire:model="cedula" required
                    placeholder="Ej: 12345678">
                @error('cedula')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="relative mb-6" x-data="{
            show: false 
        }">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <input class="w-full px-4 py-3 sm:py-4 pr-12 border border-gray-300 rounded-lg text-base shadow-sm
                           focus:ring-2 focus:ring-sky-600 focus:border-sky-600 transition duration-300 ease-in-out"
                        name="contraseña" id="password" :type="show ? 'text' : 'password'" wire:model="password"
                        required placeholder="••••••••">

                    <button type="button" id="togglePassword" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-150">
                        <i x-show="!show" class='bx bx-show'></i>
                        <i x-show="show" class='bx bx-hide'></i>
                    </button>
                </div>
                @error('password')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-center backdrop-blur-sm"
                role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <button type="submit"
                class="w-full py-3 sm:py-4 px-6 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-sky-600 hover:to-blue-700 hover:shadow-xl transform hover:scale-[1.01] transition-all duration-300 flex items-center justify-center gap-2 group text-base sm:text-lg">
                <span>Iniciar Sesión</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </button>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 text-sm">

                <a class="text-slate-600 hover:text-slate-800 hover:underline flex items-center gap-2 transition-all duration-200 group text-xs sm:text-sm"
                    href="{{ route('password.recovery') }}">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>¿Olvidó su contraseña?</span>
                </a>

                <a class="text-slate-600 hover:text-slate-800 hover:underline flex items-center gap-2 transition-all duration-200 group text-xs sm:text-sm"
                    href="{{ route('registro') }}">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span>Regístrate</span>
                </a>
            </div>

        </form>
    </div>


</div>