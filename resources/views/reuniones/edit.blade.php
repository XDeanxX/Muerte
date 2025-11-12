<x-layouts.rbac>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header Section -->
         <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-calendar-plus text-white text-2xl'></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Gestión de Reuniones</h1>
                                <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard.reuniones.show', $reunion) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-show mr-2'></i>
                            Ver Detalles
                        </a>
                        <a href="{{ route('dashboard.reuniones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Alerts Section -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class='bx bx-error-circle text-red-600 mr-2'></i>
                        <h3 class="text-red-800 font-medium">Hay errores en el formulario</h3>
                    </div>
                    <ul class="text-red-700 text-sm space-y-1 ml-6">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class='bx bx-check-circle text-green-600 mr-2'></i>
                        <span class="text-green-800">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <!-- Form Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-900">
                                Editando Reunión
                            </h2>
                            <div class="flex items-center space-x-2">
                                <div class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    <i class='bx bx-plus mr-1'></i>
                                    Editando
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600">Complete todos los campos requeridos para editar su reunión</p>
                    </div>

                    <form action="{{ route('dashboard.reuniones.update', $reunion) }}" method="POST">
                        @method('PUT')
                        @include('reuniones._form_wizard', ['submitButtonText' => 'Actualizar Reunión'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.rbac>