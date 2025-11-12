<x-layouts.rbac>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                                <i class='bx bx-check-circle text-white text-2xl'></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Finalizar Reunión</h1>
                                <p class="text-sm text-gray-600">{{ $reunion->titulo }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard.reuniones.show', $reunion) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <i class='bx bx-error text-white text-lg'></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-red-800 font-semibold mb-2">Se encontraron los siguientes errores:</h3>
                            <ul class="list-disc list-inside text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form method="POST" action="{{ route('dashboard.reuniones.finalization.store', $reunion) }}">
                @csrf

                <!-- Información de la Reunión -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-info-circle text-blue-600 mr-2'></i>
                        Información de la Reunión
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Programada</label>
                            <p class="text-gray-900 font-semibold">{{ $reunion->fecha_reunion->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora Programada</label>
                            <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reunion->hora_reunion)->format('g:i a') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duración Estimada</label>
                            <p class="text-gray-900 font-semibold">{{ $reunion->duracion_reunion ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Duración Real de la Reunión -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-timer text-blue-600 mr-2'></i>
                        ¿Cuánto Duró la Reunión?
                    </h2>
                    <div class="max-w-md">
                        <label for="duracion_real" class="block text-sm font-medium text-gray-700 mb-2">
                            Duración Real de la Reunión <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="duracion_real" 
                               name="duracion_real" 
                               value="{{ old('duracion_real') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <p class="text-sm text-gray-500 mt-1">Formato: HH:MM (ejemplo: 02:30 para 2 horas y 30 minutos)</p>
                    </div>
                </div>

                <!-- Registro de Asistencia: Solicitantes -->
                @if($reunion->solicitudes->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-user-check text-blue-600 mr-2'></i>
                        Asistencia de Solicitantes
                    </h2>
                    <div class="space-y-3">
                        @foreach($reunion->solicitudes as $solicitud)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                <!-- Indicador de Derecho de Palabra -->
                                <div class="mr-3 flex items-center" title="{{ $solicitud->derecho_palabra ? 'Solicitó Derecho de Palabra' : 'No solicitó Derecho de Palabra' }}">
                                    <div class="w-3 h-3 rounded-full {{ $solicitud->derecho_palabra ? 'bg-blue-500' : 'bg-gray-400' }}"></div>
                                </div>
                                
                                <input type="checkbox" 
                                       id="asistencia_solicitante_{{ $solicitud->solicitud_id }}" 
                                       name="asistencia_solicitantes[]" 
                                       value="{{ $solicitud->solicitud_id }}"
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="asistencia_solicitante_{{ $solicitud->solicitud_id }}" class="ml-3 flex-1">
                                    <span class="font-medium text-gray-900">{{ $solicitud->persona->nombre ?? 'N/A' }} {{ $solicitud->persona->apellido ?? '' }}</span>
                                    <span class="text-sm text-gray-600 ml-2">({{ $solicitud->titulo }})</span>
                                </label>
                                
                                @if(Auth::user()->isSuperAdministrador())
                                    <!-- Botón de Notificación Individual -->
                                    <button type="button" 
                                            onclick="notificarSolicitante('{{ $solicitud->solicitud_id }}')"
                                            class="ml-3 px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-sm flex items-center"
                                            title="Notificar a este solicitante">
                                        <i class='bx bx-bell text-lg'></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Leyenda del Indicador de Derecho de Palabra -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 flex items-center">
                            <span class="font-medium mr-2">Leyenda:</span>
                            <span class="inline-flex items-center mr-4">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-1"></div>
                                Derecho de Palabra
                            </span>
                            <span class="inline-flex items-center">
                                <div class="w-3 h-3 rounded-full bg-gray-400 mr-1"></div>
                                Sin Derecho de Palabra
                            </span>
                        </p>
                    </div>
                </div>
                @endif

                <!-- Registro de Asistencia: Concejales -->
                @if($reunion->concejales->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-user-check text-blue-600 mr-2'></i>
                        Asistencia de Concejales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($reunion->concejales as $concejal)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                <input type="checkbox" 
                                       id="asistencia_concejal_{{ $concejal->id }}" 
                                       name="asistencia_concejales[]" 
                                       value="{{ $concejal->id }}"
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="asistencia_concejal_{{ $concejal->id }}" class="ml-3 font-medium text-gray-900">
                                    {{ $concejal->persona->nombre ?? 'N/A' }} {{ $concejal->persona->apellido ?? '' }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Registro de Asistencia: Instituciones -->
                @if($reunion->instituciones->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-building text-blue-600 mr-2'></i>
                        Asistencia de Instituciones
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($reunion->instituciones as $institucion)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                <input type="checkbox" 
                                       id="asistencia_institucion_{{ $institucion->id }}" 
                                       name="asistencia_instituciones[]" 
                                       value="{{ $institucion->id }}"
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="asistencia_institucion_{{ $institucion->id }}" class="ml-3 font-medium text-gray-900">
                                    {{ $institucion->titulo }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Decisiones sobre Solicitudes -->
                @if($reunion->solicitudes->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-spreadsheet text-blue-600 mr-2'></i>
                        Decisiones sobre Solicitudes
                    </h2>
                    <div class="space-y-6">
                        @foreach($reunion->solicitudes as $solicitud)
                            <div class="p-4 border-2 border-gray-200 rounded-lg bg-gray-50">
                                <div class="mb-3">
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $solicitud->titulo }}</h3>
                                    <p class="text-sm text-gray-600">Solicitante: {{ $solicitud->persona->nombre ?? 'N/A' }} {{ $solicitud->persona->apellido ?? '' }}</p>
                                </div>

                                <!-- Decisión sobre el estatus -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Decisión sobre la Solicitud:</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="decision_solicitudes[{{ $solicitud->solicitud_id }}]" 
                                                   value="aprobada"
                                                   class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                            <span class="ml-2 text-gray-900">
                                                <i class='bx bx-check-circle text-green-600'></i> Aprobada
                                            </span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="decision_solicitudes[{{ $solicitud->solicitud_id }}]" 
                                                   value="rechazada"
                                                   class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                            <span class="ml-2 text-gray-900">
                                                <i class='bx bx-x-circle text-red-600'></i> Rechazada
                                            </span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                   name="decision_solicitudes[{{ $solicitud->solicitud_id }}]" 
                                                   value="pendiente"
                                                   checked
                                                   class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-900">
                                                <i class='bx bx-time-five text-yellow-600'></i> Dejar Pendiente
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Asignar a Módulo de Visitas -->
                                <div class="mt-3 pt-3 border-t border-gray-300">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" 
                                               name="asignar_visitas[]" 
                                               value="{{ $solicitud->solicitud_id }}"
                                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 font-medium text-gray-900">
                                            <i class='bx bx-map text-blue-600'></i> Asignar al Módulo de Visitas
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Conclusiones y Resolución -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class='bx bx-file-blank text-blue-600 mr-2'></i>
                        Conclusiones y Resolución
                    </h2>
                    <div>
                        <label for="resolucion" class="block text-sm font-medium text-gray-700 mb-2">
                            Resolución de la Reunión <span class="text-red-500">*</span>
                        </label>
                        <textarea id="resolucion" 
                                  name="resolucion" 
                                  rows="6" 
                                  required
                                  placeholder="Describa las conclusiones, decisiones y resultados de la reunión..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('resolucion') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Incluya todas las decisiones importantes, acuerdos y acciones a seguir.</p>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row gap-4 justify-end">
                        <a href="{{ route('dashboard.reuniones.show', $reunion) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200">
                            <i class='bx bx-x mr-2'></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class='bx bx-check-circle mr-2 text-xl'></i>
                            Finalizar Reunión
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para Notificaciones Individuales -->
    <script>
        function notificarSolicitante(solicitudId) {
            if (!confirm('¿Desea enviar una notificación a este solicitante?')) {
                return;
            }
            
            // Enviar notificación mediante AJAX
            fetch('{{ route("dashboard.reuniones.notificar.solicitante", $reunion) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    solicitud_id: solicitudId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notificación enviada exitosamente');
                } else {
                    alert('Error al enviar la notificación: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la notificación');
            });
        }
    </script>
</x-layouts.rbac>
