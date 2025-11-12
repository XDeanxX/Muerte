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
                                class="inline-flex items-center px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer group">
                                <i class='bx bx-arrow-back mr-2 '></i>
                                Volver al Listado
                            </button>
                        @endif
                        
                        @if($currentStep === 'list')
                            <button wire:click="$set('currentStep' , 'wizard')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                                <i class='bx bx-plus mr-2'></i>
                                Nuevo Usuario
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($currentStep === 'list')
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                        Lista de Usuarios
                    </h2>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex space-x-2">

                            <button wire:click="exportPdf" type="button"
                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                title="Exportar a PDF">
                                <i class='bx bxs-file-pdf text-2xl'></i>
                            </button>
                            <button wire:click="exportExcel" type="button"
                                class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors"
                                title="Exportar a Excel">
                                <i class='bx bxs-file text-2xl'></i>
                            </button>
                        </div>

                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" 
                                type="text" 
                                placeholder="Buscar usuarios..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                    </div>
                </div>

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

                                        <button type="button" 
                                            wire:click="viewUser({{ $user->persona->cedula }})"
                                            class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors"
                                            title="Ver detalles">
                                            <i class='bx bx-show text-xl'></i>
                                        </button>

                                        <button type="button" 
                                            wire:click="editUser({{ $user->persona->cedula }})"
                                            class="p-2 rounded-full text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 transition-colors"
                                            title="Editar">
                                            <i class='bx bx-edit text-xl'></i>
                                        </button>

                                        <button type="button" 
                                            wire:click="confirmDelete({{ $user->persona->cedula }})"
                                            class="p-2 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors"
                                            title="Eliminar">
                                            <i class='bx bx-trash text-xl'></i>
                                        </button>

                                        <button type="button" 
                                            wire:click="donwloadPDF({{ $user->persona->cedula }})"
                                            class="p-2 rounded-full text-orange-600 hover:text-orange-900 hover:bg-orange-50 transition-colors"
                                            title="Descargar PDF">
                                            <i class='bx bx-download text-xl'></i>
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
        <livewire:dashboard.superadmin-usuarios.view :viewing-user="$viewingUser">
        @endif

        @if ($currentStep === 'wizard')
        <livewire:dashboard.superadmin-usuarios.wizard >
        @endif

        @if ($currentStep === 'edit')
        <livewire:dashboard.superadmin-usuarios.edit :cedula-user="$editingUser">
        @endif

        @if ($currentStep === 'charts')
        <livewire:dashboard.superadmin-usuarios.charts>
        @endif

    </div>
