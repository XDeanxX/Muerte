<div class="w-full max-w-7xl bg-white p-6 sm:p-10 rounded-xl shadow-2xl border border-gray-100 mx-auto">

    <header class="mb-8 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-800 flex items-center">
            <i class='bx bxs-user-detail text-blue-600 mr-3 text-4xl'></i> Modificación de Datos Personales
        </h1>
        <p class="mt-2 text-gray-500">
            Edite los campos necesarios para actualizar la información del usuario.
        </p>
    </header>

    <form class="space-y-6" wire:submit.prevent="modify">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6" id="form-full-name-group">
                
                <div class="sm:col-span-1">
                    <label for="nombre" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                        <i class='bx bx-user text-lg mr-1'></i> Primer Nombre *
                    </label>
                    <input type="text" id="nombre" wire:model="nombre"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                        required>
                    @error('nombre') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
                </div>
                
                <div class="sm:col-span-1">
                    <label for="segundo_nombre" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                        <i class='bx bx-user-plus text-lg mr-1'></i> Segundo Nombre
                    </label>
                    <input type="text" id="segundo_nombre" wire:model="segundo_nombre"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm">
                    @error('segundo_nombre') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
                </div>

                <div class="sm:col-span-1">
                    <label for="apellido" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                        <i class='bx bx-user text-lg mr-1'></i> Primer Apellido *
                    </label>
                    <input type="text" id="apellido" wire:model="apellido"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                        required>
                    @error('apellido') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-1">
                    <label for="segundo_apellido" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                        <i class='bx bx-user-plus text-lg mr-1'></i> Segundo Apellido
                    </label>
                    <input type="text" id="segundo_apellido" wire:model="segundo_apellido"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm">
                    @error('segundo_apellido') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
                </div>

            </div>
            
            
            <div id="form-cedula-grupo">
                <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-id-card text-lg mr-1'></i> Documento
                </label>
                <div class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-800 font-bold shadow-sm">
                   {{$nacionalidad->Nacionalidad}}-{{ $userCedula ?? 'N/A' }}
                </div>
            </div>

            
            <div id="form-email" class="ml-6">
                <label for="email" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-at text-lg mr-1'></i> Email *
                </label>
                <input type="email" id="email" wire:model="email"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                    required>
                @error('email') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
            </div>
            
            
            <div id="form-telefono">
                <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-phone text-lg mr-1'></i> Teléfono *</label>
                <div class="flex space-x-3">
                    
                    <div class="flex-shrink-0 w-28">
                        <select id="prefijo_telefono" wire:model="prefijo_telefono"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                            required>
                            <option value="">Prefijo</option>
                            <option value="0412">0412</option>
                            <option value="0422">0422</option>
                            <option value="0414">0414</option>
                            <option value="0424">0424</option>
                            <option value="0416">0416</option>
                            <option value="0426">0426</option>
                        </select>
                        @error('prefijo_telefono') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
                    </div>
                    
                    <div class="flex-grow">
                        <input type="text" id="telefono" wire:model="telefono" maxlength="8"
                            x-data="{}" x-mask="999-9999" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                            required>
                        @error('telefono') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            
            <div id="form-sexo">
                <label for="genero" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-male-female text-lg mr-1'></i> Género *
                </label>
                <select id="genero" wire:model="genero"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"
                    required>
                    <option value="">Seleccione...</option>
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                    <option value="3">Otro</option>
                </select>
                @error('genero') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
            </div>

            
            <div id="form-nacimiento">
                <label for="nacimiento" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-cake text-lg mr-1'></i> Nacimiento
                </label>
                <input type="date" id="nacimiento" wire:model="nacimiento"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm">
                @error('nacimiento') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
            </div>

            
            <div class="md:col-span-2" id="form-direccion">
                <label for="direccion" class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                    <i class='bx bx-map text-lg mr-1'></i> Dirección Completa
                </label>
                <textarea id="direccion" wire:model="direccion" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm"></textarea>
                @error('direccion') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror 
            </div>

        </div>

        
        <div class="pt-6 border-t mt-8 flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out shadow-lg shadow-blue-500/50 flex items-center">
                <i class='bx bxs-save text-xl mr-2'></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
