<div class="bg-white rounded-xl shadow-xl p-2 mb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">















        <div class="flex items-center justify-center mb-2">
            <div class="mr-2 w-10 lg:w-12 h-10 lg:h-12 flex justify-center items-center shadow-lg rounded-full bg-indigo-100 text-indigo-600 shadow-indigo-50">
                <i class="bx bx-edit text-lg lg:text-3xl font-bold"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Editar Trabajador</h2>
        </div>

        <p class="text-gray-500 text-center mb-6">Modifique los campos necesarios y presione actualizar.</p>

        <div class="p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200 mb-4 lg:mx-90">
            <h3 class="text-lg text-center font-semibold text-gray-800 mb-4">Datos Personales</h3>
            <div class="text-center flex flex-col">
                <span class="text-md text-gray-800">{{$persona->nombre}} {{$persona->segundo_nombre}} {{$persona->apellido}} {{$persona->segundo_apellido}}</span> 
                <span class="text-md text-gray-600">{{$persona->nacionalidad === 1 ? 'V' : 'E'}}-{{$persona->cedula}}</span> 
                <span class="text-md text-gray-600">{{$persona->nacimiento->format('d/m/Y')}}</span> 
                <span class="text-md text-gray-600">{{preg_replace('/(\d{4})(\d{3})(\d{4})/', '$1-$2-$3', $persona->telefono)}}</span> 
            </div>
        </div>

        <div class="p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Laboral</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label for="zona_trabajo.edit" class="block text-sm font-medium text-gray-700">Cargo *</label>
                    <select wire:model.live="trabajador.cargo_id" id="cargo_id" required
                        class="mt-1 w-full bg-white border rounded-lg px-4 py-3 @error('trabajador.cargo_id') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="" disabled>Seleccione un cargo</option>
                        @foreach ($cargos as $cargo)
                            <option value="{{ $cargo->cargo_id }}" {{$trabajador['cargo_id'] === $cargo->cargo_id ? 'selected' : ''}}> 
                                {{ $cargo->descripcion }}
                            </option>
                        @endforeach
                    </select>                  
                </div>
  
                {{-- ZONA DE TRABAJO (Original) --}}
                <div>
                    <label for="zona_trabajo.edit" class="block text-sm font-medium text-gray-700">Zona de trabajo *</label>
                    <input type="text" id="zona_trabajo.edit" wire:model.live="trabajador.zona_trabajo"
                        class="mt-1 w-full bg-white border rounded-lg px-4 py-2 @error('trabajador.zona_trabajo') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-blue-400 focus:border-blue-400" required>
                    @error('trabajador.zona_trabajo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- BOTONES DE ACCIÓN (Original) --}}
        <div class="flex justify-between mt-8 gap-4 border-t border-gray-300 pt-6">
            <button wire:click="regresarAlListado"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                <i class='bx bx-arrow-back lg:mr-2'></i>
                Volver
            </button>
            <button wire:click="update({{$trabajador['persona_cedula']}})"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-md">
                <i class="bx bx-save mr-1"></i> Actualizar
            </button>
        </div>
    </div>

</div>