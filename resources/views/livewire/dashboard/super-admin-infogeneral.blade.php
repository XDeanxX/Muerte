<div class="p-4 sm:p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-2xl shadow-2xl shadow-gray-300/50 overflow-hidden transform transition-all duration-500"
            id="main-card">

            <div class="bg-gradient-to-r from-blue-600 to-blue-400 text-white p-6 md:p-8 
                            flex items-center justify-between gap-3 sm:gap-6">

                <div class="flex items-center gap-4 sm:gap-6 shrink">

                    <h2 class="text-base sm:text-2xl lg:text-3xl font-extralight flex items-center tracking-wider"
                        id="titulo-perfil">
                        {{$currentStep==='view' ? 'Detalles usuario' : 'Modificar usuario' }}
                    </h2>
                </div>

                @if ($currentStep === 'view')
                <button wire:click="$set('currentStep', 'form')" class="inline-flex items-center 
            px-4 py-2 sm:px-5 sm:py-2 
            border-2 border-gray-300 
            text-gray-700 
            bg-white hover:bg-gray-200 
            rounded-lg 
            transition-colors 
            font-bold text-xs sm:text-sm shadow-sm min-w-max" id="btn-modificar">
                    <i class='bx bx-edit mr-1 sm:mr-2'></i>
                    modificar
                </button>
                @endif

                @if ($currentStep !== 'view')
                <button wire:click="$set('currentStep', 'view')" class="inline-flex items-center 
            px-4 py-2 sm:px-5 sm:py-2 
            border-2 border-gray-300 
            text-gray-700 
            bg-white hover:bg-gray-200 
            rounded-lg 
            transition-colors 
            font-bold text-xs sm:text-sm shadow-sm min-w-max">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Volver al Listado
                </button>
                @endif

            </div>

            @if ($currentStep==='view')

            <div class="p-4 sm:p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">

                    <div class="md:col-span-2" id="info-nombre">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-user-circle text-lg mr-1'></i> Nombre Completo
                        </label>
                        <div
                            class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 font-extrabold text-lg sm:text-xl tracking-wide">
                            {{ $currentUser->persona->nombre }} {{ $currentUser->persona->apellido }} {{
                            $currentUser->persona->segundo_nombre }} {{ $currentUser->persona->segundo_apellido }}
                        </div>
                    </div>

                    <div id="info-cedula">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-id-card text-lg mr-1'></i> Documento
                        </label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                            @php
                            $nacionalidadModel = App\Models\nacionalidad::find($currentUser->persona->nacionalidad);
                            @endphp
                            {{ $nacionalidadModel ? ucfirst($nacionalidadModel->Nacionalidad) : 'No registrado' }} -
                            {{ $currentUser->persona_cedula }}
                        </div>
                    </div>

                    <div id="info-email">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-at text-lg mr-1'></i> Email
                        </label>
                        <div
                            class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 break-words font-medium">
                            {{ $currentUser->persona->email ?? 'No registrado' }}
                        </div>
                    </div>

                    <div id="info-telefono">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-phone text-lg mr-1'></i> Teléfono
                        </label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                            {{ $currentUser->persona->telefono ?? 'No registrado' }}
                        </div>
                    </div>

                    <div id="info-sexo">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-male-female text-lg mr-1'></i> Género
                        </label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                            @php
                            $generoModel = App\Models\genero::find($currentUser->persona->genero);
                            @endphp
                            {{ $generoModel ? ucfirst($generoModel->genero) : 'No registrado' }}
                        </div>
                    </div>

                    <div id="info-rol">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-key text-lg mr-1'></i> Rol de Usuario
                        </label>
                        <div
                            class="p-4 bg-green-100 border border-green-300 rounded-lg text-green-800 font-bold flex items-center text-base">
                            <i class='bx bx-shield-alt-2 mr-2 text-xl'></i>
                            {{ $currentUser->getRoleName() }}
                        </div>
                    </div>

                    <div id="info-miembro-desde">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-time text-lg mr-1'></i> Miembro Desde
                        </label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                            {{ $currentUser->created_at->format('d/m/Y') }}
                        </div>
                    </div>

                    <div id="info-nacimiento">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-cake text-lg mr-1'></i> Nacimiento
                        </label>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium">
                            {{ $currentUser->persona->nacimiento ?
                            $currentUser->persona->nacimiento->format('d/m/Y') : 'No registrado' }}
                        </div>
                    </div>

                    <div class="md:col-span-2" id="info-direccion">
                        <label class="block text-sm font-semibold text-gray-500 uppercase mb-1 flex items-center">
                            <i class='bx bx-map text-lg mr-1'></i> Dirección Completa
                        </label>
                        <div
                            class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 min-h-[6rem] max-h-32 overflow-y-auto font-medium">
                            {{ $currentUser->persona->direccion ?? 'No registrado' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($currentStep==='form')
    <livewire:dashboard.infogenereal.modificar :user-cedula="$currentUser->persona_cedula" />

    @endif

    <div class="z-50">
        <button @click="iniciarTour()"
            class="fixed bottom-6 right-6 w-14 h-14 bg-green-600 hover:bg-green-700 text-white text-3xl font-bold rounded-full shadow-2xl flex items-center justify-center cursor-pointer z-50 transition-all duration-300 transform hover:scale-110 ring-4 ring-green-300/50"
            title="Iniciar Tour de Ayuda">
            ?
        </button>
    </div>

    <script>
        function iniciarTour() {
            if (typeof window.driver === 'undefined' && typeof window.driver.js.driver === 'undefined') {
                console.error("El pepe");
                return;
            }

            const driver = window.driver.js.driver;

            const driverObj = driver({
                animate: true,
                showButtons: ['next', 'previous', 'close'],
                steps: [
                    { element: '#main-card', popover: { title: '¡Bienvenido a tu Perfil!', description: 'Panel central con toda tu información registrada.', side: 'left' } },
                    { element: '#profile-image-container', popover: { title: 'Tu Avatar', description: 'Aquí puedes ver tu foto de perfil. ¡Recuerda mantenerla actualizada!', side: 'right' } },
                    { element: '#btn-modificar', popover: { title: 'Editar Información', description: 'Usa este botón para actualizar tus datos personales.', side: 'left' } },
                    { element: '#info-nombre', popover: { title: 'Datos Personales', description: 'Tu nombre completo.', side: 'top' } },
                    { element: '#info-rol', popover: { title: 'Tu Rol', description: 'Nivel de acceso que tienes en la plataforma.', side: 'bottom' } },
                    { element: '#info-direccion', popover: { title: 'Domicilio Registrado', description: 'La dirección principal de tu cuenta.', side: 'top' } },
                    { element: '#main-card', popover: { title: '¡Tour Finalizado!', description: 'Explora y mantén tus datos actualizados.', side: 'right' } }
                ]
            });

            driverObj.drive();
        }
    </script>
</div>