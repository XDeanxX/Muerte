<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center">
                        <i class='bx bx-user text-blue-600 text-4xl'></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-3xl font-bold">
                            {{ ucwords($viewingUser->persona->nombre . ' ' . $viewingUser->persona->apellido) }}
                        </h2>
                        <p class="text-blue-100 text-lg flex items-center mt-1">
                            <i class='bx bx-id-card mr-2'></i>
                            Cédula: {{ $viewingUser->persona->cedula }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span
                    class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold flex items-center">
                    <i class='bx bx-check-circle mr-2'></i>
                    Usuario Activo
                </span>

                <span class="px-4 py-2 
                                {{ $viewingUser->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} 
                                rounded-full text-sm font-semibold flex items-center">
                    <i class='bx bx-shield mr-2'></i>
                    {{ $viewingUser->getRoleName() }}
                </span>
            </div>

            <button type="button" wire:click="dispatchCedula({{ $viewingUser->persona->cedula }})"
                class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors font-medium">
                <i class='bx bx-download mr-2'></i>
                Descargar PDF
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                    <i class='bx bx-user text-blue-600 mr-2 text-2xl'></i>
                    Información Personal
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-start py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ ucwords($viewingUser->persona->nombre . ' ' .
                                $viewingUser->persona->segundo_nombre . ' ' .
                                $viewingUser->persona->apellido . ' ' .
                                $viewingUser->persona->segundo_apellido) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Cédula de Identidad</p>
                            <p class="text-base font-semibold text-gray-900 font-mono">
                                {{ $viewingUser->persona->nacionalidad }}-{{ $viewingUser->persona->cedula }}
                            </p>
                        </div>
                        <i class='bx bx-id-card text-2xl text-gray-400'></i>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Género</p>
                            <p class="text-base font-semibold text-gray-900">
                                @php
                                $generoModel = \App\Models\genero::find($viewingUser->persona->genero);
                                @endphp
                                {{ $generoModel ? ucfirst($generoModel->genero) : 'N/A' }}
                            </p>
                        </div>
                        <i class='bx bx-user text-2xl text-gray-400'></i>
                    </div>

                    @if($viewingUser->persona->nacimiento)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($viewingUser->persona->nacimiento)->format('d/m/Y') }}
                            </p>
                        </div>
                        <i class='bx bx-cake text-2xl text-gray-400'></i>
                    </div>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center border-b pb-2">
                    <i class='bx bx-phone text-blue-600 mr-2 text-2xl'></i>
                    Información de Contacto
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                            <p class="text-base font-semibold text-gray-900 break-all">
                                {{ $viewingUser->persona->email ?? 'No registrado' }}
                            </p>
                        </div>
                        <i class='bx bx-envelope text-2xl text-gray-400 ml-2'></i>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $viewingUser->persona->telefono ?? 'No registrado' }}
                            </p>
                        </div>
                        <i class='bx bx-phone-call text-2xl text-gray-400'></i>
                    </div>

                    <div class="py-3">
                        <p class="text-sm text-gray-500 mb-2">Dirección</p>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-base text-gray-900">
                                {{ $viewingUser->persona->direccion ?: 'No registrada' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class='bx bx-lock-alt text-blue-600 mr-2 text-2xl'></i>
                Credenciales de Acceso
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-2">Credencial de acceso</p>
                    <p class="text-lg font-bold text-blue-700 font-mono flex items-center">
                        <i class='bx bx-user-circle mr-2 text-2xl'></i>
                        {{ $viewingUser->persona->cedula }}
                    </p>
                </div>

                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <p class="text-sm text-gray-500 mb-2">Rol Asignado</p>
                    <p class="text-lg font-bold text-blue-700 flex items-center">
                        <i class='bx bx-shield mr-2 text-2xl'></i>
                        {{ $viewingUser->getRoleName() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <i class='bx bx-calendar-plus text-green-600 text-3xl mr-3'></i>
                    <div>
                        <p class="text-sm text-gray-500">Fecha de Registro</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $viewingUser->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $viewingUser->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center">
                    <i class='bx bx-calendar-edit text-blue-600 text-3xl mr-3'></i>
                    <div>
                        <p class="text-sm text-gray-500">Última Actualización</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $viewingUser->updated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $viewingUser->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>