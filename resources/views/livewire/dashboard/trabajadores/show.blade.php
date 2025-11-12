<div class="">
    @if($cambiarVista === 'destroy')
        <div class="fixed inset-0 bg-black/10 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Confirmar Eliminación</h3>
                            <p class="text-sm text-gray-600">Esta acción no se puede deshacer</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-6">
                        ¿Estás seguro de que deseas eliminar esta trabajador? Se perderán todos los datos asociados.
                    </p>
                    <p class="text-sm text-gray-400 mb-6">Trabajador: {{ $persona_cedula->persona->nombre }} {{ $persona_cedula->persona->apellido }} {{ $persona_cedula->persona->nacionalidad === 1 ? 'V' : 'E' }}-{{ $persona_cedula->persona->cedula }}, {{ $persona_cedula->cargo->descripcion }}</p>
                    
                    <div class="flex justify-end space-x-4">
                        <button wire:click="cerrarEliminar" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="destroyDefinitivo({{$persona_cedula->persona_cedula}})" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-xl p-2 mb-4">

        <div class="flex items-center lg:justify-between max-lg:flex-col">
            <button type="button" wire:click="regresarAlListado"
                    class="w-full text-center justify-center items-center bg-white sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                <i class='bx bx-arrow-back lg:mr-2'></i>
                <span class="max-lg:hidden">Regresar Atras</span>
            </button>
              <div class="grid grid-cols-3 gap-4 max-lg:mt-4">
                    <button wire:click="exportPdf({{$trabajador->persona_cedula}})" 
                            class="flex text-center justify-center items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm"
                            title="Imprimir en PDF">
                        <i class='bx bx-cloud-download text-xl'></i>
                    </button>
                    <button wire:click="edit('edit', {{$trabajador->persona_cedula}})" 
                            class="flex text-center justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
                            title="Editar Solictiud">
                        <i class='bx bx-edit text-xl'></i>
                    </button>
                    <button wire:click="destroy('destroy', {{$trabajador->persona_cedula}})" 
                            class="flex text-center justify-center items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm"
                            title="Eliminar Solicitud">
                        <i class='bx bx-trash text-xl'></i>
                    </button>
              </div>  
        </div>

    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-300 bg-gradient-to-r from-blue-600 to-blue-500 rounded-t-xl flex items-center justify-start">
            {{-- Agregué el título aquí para mayor claridad en el encabezado --}}
            <div class="bg-white rounded-full w-12 h-12 mr-2 flex items-center justify-center">
                <i class="bx bx-user text-2xl text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-white text-xl font-semibold">Información del trabajador</h2>
                <p class="text-blue-100 text-sm mt-0.5">Información registrada en el sistema CMBEY</p>
            </div>
        </div>

        <div class="p-6"> 
            {{-- El grid se mantiene, pero con un gap ligeramente menor --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 text-sm text-gray-700">
                
                {{-- Cédula --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cédula</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->cedula ?? '—' }}
                    </div>
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Teléfono</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->telefono ?? '—' }}
                    </div>
                </div>

                {{-- Primer Nombre --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Primer Nombre</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->nombre ?? '—' }}
                    </div>
                </div>

                {{-- Primer Apellido --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Primer Apellido</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->apellido ?? '—' }}
                    </div>
                </div>
                
                {{-- Segundo Nombre --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Segundo Nombre</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->segundo_nombre ?? '—' }}
                    </div>
                </div>

                {{-- Segundo Apellido --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Segundo Apellido</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->segundo_apellido ?? '—' }}
                    </div>
                </div>
                
                {{-- Correo Electrónico --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Correo Electrónico</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->email ?? '—' }}
                    </div>
                </div>
                
                {{-- Género --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Género</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->genero ?? '—' }}
                    </div>
                </div>

                 <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nacionalidad</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->nacionalidad ?? '—' }}
                    </div>
                </div>

                {{-- Dirección (Dos Columnas) --}}
                <div class="md:col-span-2 mt-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Dirección</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->persona->direccion ?? '—' }}
                    </div>
                </div>
                
                {{-- Título de la Sección Laboral --}}
                <div class="md:col-span-2 mt-4 mb-2">
                    <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">Información Laboral</h3>
                </div>
                
                {{-- Zona de trabajo --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Zona de trabajo</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->zona_trabajo ?? '—' }}
                    </div>
                </div>

                {{-- Cargo --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cargo</label>
                    <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-gray-800 font-medium">
                        {{ $trabajador->cargo->descripcion ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>