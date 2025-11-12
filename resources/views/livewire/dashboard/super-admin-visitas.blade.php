<div class="min-h-screen bg-gray-50">

    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="bx bx-book-content text-xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Visitas</h1>
                            <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col-reverse sm:flex-row items-center gap-3">
                    @if($currentStep !== 'list')
                    <button wire:click="changeToList"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer group">
                        <i class='bx bx-arrow-back mr-2 '></i>
                        Volver al Listado
                    </button>
                    @endif

                    @if($currentStep === 'list')
                    <button wire:click="$set('currentStep' , 'create')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                        <i class='bx bx-plus mr-2'></i>
                        Nueva Visita
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @if ($currentStep == 'list')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-list-ul text-blue-600 mr-2'></i>
                    Lista de Visitas
                </h2>

                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por ID de Solicitud..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64 transition shadow-sm">
                    <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto mt-4 rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <i class='bx bx-user-circle mr-2'></i>
                                Ticket Solicitud
                                <i class='bx bx-down-arrow ml-1 text-gray-400'></i>
                            </div>
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class='bx bx-id-card mr-2'></i>
                                Involucrados
                            </div>
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class='bx bx-calendar mr-2'></i>
                                Fecha visita
                            </div>
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class='bx bx-cog mr-2'></i>
                                Estado
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

                    @foreach ($visitas as $visit)
                    <tr class="hover:bg-gray-50 transition-colors">

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $visit->solicitud_id }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-mono">
                            {{ $visit->asistente()->count() }} <span class="text-gray-400">/</span> 5
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            ( {{ \Carbon\Carbon::parse($visit->fecha_inicial)->format('d/m/Y') }}) <span
                                class="text-gray-400">-</span> ({{
                            \Carbon\Carbon::parse($visit->fecha_final)->format('d/m/Y') }})
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                            $estatusId = $visit->estatus->estatus_id ?? 0;

                            // Define las clases CSS basadas en el ID del estatus
                            if ($estatusId == 1) { // Pendiente
                            $clases = 'text-yellow-800 bg-yellow-100 border border-yellow-300';
                            } elseif ($estatusId == 2) { // En Progreso
                            $clases = 'text-blue-800 bg-blue-100 border border-blue-300';
                            } elseif ($estatusId == 6) { // Terminada/Finalizada
                            $clases = 'text-green-800 bg-green-100 border border-green-300';
                            } else { // Otros (e.g., No definido)
                            $clases = 'text-gray-800 bg-gray-100 border border-gray-300';
                            }

                            $texto = $visit->estatus->estatus ?? 'N/A';
                            @endphp

                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $clases }}">
                                {{ $texto }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">

                                <button type="button" wire:click="viewVisit('{{$visit->solicitud_id}}')"
                                    class="p-2 rounded-full text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition-colors"
                                    title="Ver detalles">
                                    <i class='bx bx-show text-xl'></i>
                                </button>

                                <button type="button" wire:click="editVisit('{{$visit->solicitud_id}}')"
                                    class="p-2 rounded-full text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 transition-colors"
                                    title="Editar">
                                    <i class='bx bx-edit text-xl'></i>
                                </button>

                                <button type="button" wire:click="deleteVisit('{{ $visit->solicitud_id }}')"
                                    class="p-2 rounded-full text-red-600 hover:text-red-900 hover:bg-red-50 transition-colors"
                                    title="Eliminar">
                                    <i class='bx bx-trash text-xl'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if ($visitas->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-lg font-medium">
                            <i class='bx bx-info-circle text-4xl text-yellow-500 mb-2'></i>
                            <p>No se encontraron visitas que coincidan con el ID de Solicitud "{{ $search }}"</p>
                        </td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $visitas->links() }}
        </div>
    </div>
    @endif

    @if ($currentStep ==='create')
    <livewire:dashboard.super-admin-visitas.visitas wire:key="visitas-create" @close.self="changeToList" />
    @endif

    @if ($currentStep ==='edit')
    <livewire:dashboard.super-admin-visitas.edit wire:key="visitas-edit-{{ $currentVisitId }}"
        :solicitud-id="$currentVisitId" @close.self="changeToList" />
    @endif

    @if ($currentStep ==='view')
    <livewire:dashboard.super-admin-visitas.view wire:key="visitas-view-{{ $currentVisitId }}"
        :solicitud-id="$currentVisitId" @close.self="changeToList" />
    @endif
</div>