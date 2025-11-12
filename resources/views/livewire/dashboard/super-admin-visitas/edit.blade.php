<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-100">

        <h3 class="text-xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200 flex items-center">
            <i class='bx bx-calendar-edit text-blue-600 mr-2 text-2xl'></i>
            Editar Fechas de Visita - Ticket: <span class="text-blue-600 ml-2">{{ $solicitudId }}</span>
        </h3>
        
        @if ($visita)
        <form wire:submit.prevent="updateVisitDates">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Fecha Inicial --}}
                <div class="relative">
                    <label for="initDate" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class='bx bx-calendar-plus mr-1 text-indigo-500'></i> Fecha de Inicio
                    </label>
                    <input type="date" id="initDate" wire:model.live="initDate"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-lg p-3 
                               focus:ring-green-500 focus:border-green-500 transition duration-150 
                               bg-gray-50 border-2">
                    @error('initDate') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Fecha Final --}}
                <div class="relative">
                    <label for="finalDate" class="block text-sm font-semibold text-gray-700 mb-1">
                        <i class='bx bx-calendar-check mr-1 text-indigo-500'></i> Fecha de Finalización
                    </label>
                    <input type="date" id="finalDate" wire:model.live="finalDate"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-lg p-3 
                               focus:ring-green-500 focus:border-green-500 transition duration-150 
                               bg-gray-50 border-2">
                    @error('finalDate') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                
            </div>
            
            <p class="text-sm text-gray-600 mt-6 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-sm">
                <i class='bx bx-info-circle mr-1'></i> Nota: El lapso de la visita no debe exceder los **{{ self::MAX_DAYS_DIFFERENCE }} días** entre la fecha de inicio y finalización.
            </p>

            <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end space-x-4">
                
                {{-- Botón Cancelar/Volver --}}
                <button type="button" wire:click="$dispatch('close')"
                    class="px-5 py-2 border-2 border-gray-300 text-gray-700 rounded-lg 
                           hover:bg-gray-100 transition-colors shadow-sm">
                    Cancelar
                </button>
                
                {{-- Botón Guardar (Cambiado a verde) --}}
                <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-lg 
                           hover:bg-green-700 transition-colors shadow-md 
                           focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class='bx bx-save mr-2'></i>
                    Actualizar Fechas
                </button>
            </div>
        </form>
        @else
        <div class="text-center py-10 text-gray-500 border border-dashed border-gray-300 rounded-lg bg-gray-50">
            <i class='bx bx-error-alt text-5xl mb-3 text-red-500'></i>
            <p class="text-lg font-medium">No se encontraron datos de visita para este ticket.</p>
        </div>
        @endif

    </div>
</div>