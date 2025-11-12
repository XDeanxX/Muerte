    <div class="min-h-screen bg-gray-50" x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
        
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="bx bx-group text-xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
                                <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($currentStep !== 'list')
                            <button wire:click="$set('currentStep', 'list')"
                                class="inline-flex items-center px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class='bx bx-arrow-back mr-2'></i>
                                Volver al Listado
                            </button>
                        @endif
                        
                        @if($currentStep === 'list')
                            <button wire:click="startWizard"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class='bx bx-plus mr-2'></i>
                                Nuevo Usuario
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- LIST VIEW --}}
        @if ($currentStep === 'list')
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                
                {{-- Table Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                        Lista de Usuarios
                    </h2>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4">

                        {{-- Search --}}
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" 
                                type="text" 
                                placeholder="Buscar usuarios..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('nombre')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <i class='bx bx-user-circle mr-2'></i>
                                        Nombre Completo
                                        @if($sortField === 'nombre')
                                            <i class='bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow ml-1'></i>
                                        @endif
                                    </div>
                                </th>

                                <th wire:click="sortBy('persona_cedula')" 
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center justify-center">
                                        <i class='bx bx-id-card mr-2'></i>
                                        Cédula
                                        @if($sortField === 'persona_cedula')
                                            <i class='bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow ml-1'></i>
                                        @endif
                                    </div>
                                </th>

                                <th wire:click="sortBy('role')" 
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center justify-center">
                                        <i class='bx bx-shield mr-2'></i>
                                        Rol
                                        @if($sortField === 'role')
                                            <i class='bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow ml-1'></i>
                                        @endif
                                    </div>
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <i class='bx bx-cog mr-2'></i>
                                        Acciones
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($usuarios as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ ucwords($user->persona->nombre . ' ' . $user->persona->segundo_nombre . ' ' . $user->persona->apellido . ' ' . $user->persona->segundo_apellido) }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-mono">
                                    {{ $user->persona->cedula }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->getRoleName() }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        {{-- Ver Detalles --}}
                                        <button type="button" 
                                            wire:click="viewUser({{ $user->persona->cedula }})"
                                            class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors"
                                            title="Ver detalles">
                                            <i class='bx bx-show text-xl'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class='bx bx-user-x text-6xl mb-3'></i>
                                        <p class="text-lg font-medium">No se encontraron usuarios</p>
                                        <p class="text-sm">Intenta ajustar tu búsqueda o crea un nuevo usuario</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
        @endif

        @if ($currentStep === 'view' && $viewingUser)
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                
                {{-- Header del Perfil --}}
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
                        
                        <div class="flex space-x-2">
                            
                            <button type="button" 
                                wire:click="closeViewUser"
                                class="px-4 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition-colors font-medium">
                                <i class='bx bx-x mr-2'></i>
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    {{-- Status Badge --}}
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold flex items-center">
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
                    </div>

                    {{-- Información Personal --}}
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

                        {{-- Información de Contacto --}}
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

                    {{-- Información del Usuario --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class='bx bx-lock-alt text-blue-600 mr-2 text-2xl'></i>
                            Credenciales de Acceso
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-sm text-gray-500 mb-2">Nombre de Usuario (Username)</p>
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

                    {{-- Fechas de Registro --}}
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

                    {{-- Botones de Acción --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <button type="button" 
                            wire:click="closeViewUser"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Volver al Listado
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush