<div class="p-6 flex justify-center w-full items-center" x-data="{ showImport: false }">
    <div class="max-w-2xl w-full space-y-6">

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center justify-center border-b pb-4">
                <i class='bx bx-export text-blue-600 mr-3 text-3xl'></i>
                Exportación de la Base de Datos
            </h2>

            <p class="text-gray-600 text-center mb-6">
                Selecciona el formato para exportar el respaldo de la información.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button type="button" wire:click="exportSql('sql')"
                    class="flex items-center justify-center px-4 py-3 bg-gray-500 text-white font-medium rounded-lg 
                           hover:bg-gray-600 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <i class='bx bxs-file-doc mr-2 text-xl'></i> Exportar a .SQL
                </button>

                <button type="button" wire:click="exportSql('json')"
                    class="flex items-center justify-center px-4 py-3 bg-green-600 text-white font-medium rounded-lg 
                           hover:bg-green-700 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <i class='bx bxs-file-json mr-2 text-xl'></i> Exportar a .JSON
                </button>
            </div>

            <div wire:loading wire:target="exportSql" class="mt-4 text-center text-sm text-gray-500">
                <i class='bx bx-loader-alt bx-spin mr-1'></i> Generando respaldo, por favor espera...
            </div>
        </div>


        @if($message)
            <div class="p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center">
                    <i class='bx {{ $messageType === 'success' ? 'bx-check-circle' : 'bx-error-circle' }} text-2xl mr-2'></i>
                    <span>{{ $message }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center justify-center border-b pb-4">
                <i class='bx bx-import text-purple-600 mr-3 text-3xl'></i>
                Importación de la Base de Datos
            </h2>

            <p class="text-gray-600 text-center mb-6">
                Selecciona un archivo de respaldo (.sql o .json) para restaurar la base de datos.
            </p>

            <div x-show="!showImport" class="text-center">
                <button type="button" @click="showImport = true"
                    class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg 
                           hover:bg-purple-700 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class='bx bx-upload mr-2 text-xl'></i> Seleccionar Archivo
                </button>
            </div>

            <div x-show="showImport" x-cloak class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <i class='bx bx-cloud-upload text-5xl text-gray-400 mb-3'></i>
                    
                    <label for="importFile" class="cursor-pointer">
                        <span class="text-blue-600 hover:text-blue-700 font-medium">
                            Haz clic para seleccionar
                        </span>
                        <span class="text-gray-600"> o arrastra el archivo aquí</span>
                    </label>
                    
                    <input type="file" id="importFile" wire:model="importFile" 
                           accept=".sql,.json"
                           class="hidden">
                    
                    <p class="text-xs text-gray-500 mt-2">SQL o JSON (Máx. 100MB)</p>
                    
                    @error('importFile')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                @if($importFile)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class='bx bxs-file text-blue-600 text-2xl mr-3'></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $importFile->getClientOriginalName() }}</p>
                                    <p class="text-sm text-gray-600">{{ number_format($importFile->getSize() / 1024, 2) }} KB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="cancelarImportacion"
                                class="text-red-600 hover:text-red-800">
                                <i class='bx bx-x text-2xl'></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Formato del archivo:
                        </label>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50
                                   {{ $importType === 'sql' ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model.live="importType" value="sql" class="sr-only">
                                <i class='bx bxs-file-doc text-2xl mr-2 {{ $importType === 'sql' ? 'text-blue-600' : 'text-gray-400' }}'></i>
                                <span class="font-medium {{ $importType === 'sql' ? 'text-blue-600' : 'text-gray-700' }}">SQL</span>
                            </label>

                            <label class="relative flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50
                                   {{ $importType === 'json' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model.live="importType" value="json" class="sr-only">
                                <i class='bx bxs-file-json text-2xl mr-2 {{ $importType === 'json' ? 'text-green-600' : 'text-gray-400' }}'></i>
                                <span class="font-medium {{ $importType === 'json' ? 'text-green-600' : 'text-gray-700' }}">JSON</span>
                            </label>
                        </div>

                        @error('importType')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" wire:click="importDatabase"
                            class="flex-1 flex items-center justify-center px-4 py-3 bg-purple-600 text-white font-medium rounded-lg 
                                   hover:bg-purple-700 transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <i class='bx bx-check-circle mr-2 text-xl'></i> Importar Base de Datos
                        </button>

                        <button type="button" @click="showImport = false" wire:click="$set('importFile', null)"
                            class="px-4 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg 
                                   hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                    </div>
                @endif

                <div wire:loading wire:target="importDatabase" class="text-center text-sm text-gray-500">
                    <i class='bx bx-loader-alt bx-spin mr-1'></i> Importando base de datos, esto puede tomar varios minutos...
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex">
                <i class='bx bx-error text-yellow-400 text-2xl mr-3'></i>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Advertencia de Seguridad</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        La importación de base de datos sobrescribirá todos los datos actuales. 
                        Asegúrate de tener un respaldo antes de proceder.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush