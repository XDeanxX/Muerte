<div class="min-h-screen">
    <!-- Dashboard Content -->
    @if($activeTab === 'dashboard')
    <div class="p-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                ¡Bienvenido, {{ auth()->user()->persona->nombre }}!
            </h1>
            <p class="mt-2 text-gray-600">Panel de Usuario - Sistema Municipal CMBEY</p>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-file-blank text-3xl mr-4 text-gray-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->count() }}</h3>
                        <p class="text-blue-100">Total Solicitudes</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-3xl mr-4 text-green-400'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->where('estatus', 2)->count() }}</h3>
                        <p class="text-blue-100">Aprobadas</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-700 to-blue-800 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center">
                    <i class='bx bx-time-five text-3xl mr-4 text-orange-500'></i>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $solicitudes->where('estatus', 1)->count() }}</h3>
                        <p class="text-blue-100">Pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            @if ($showSecurityNotification)
            <div
                class="bg-orange-50 border border-orange-400 text-orange-900 p-6 rounded-2xl shadow-xl relative mb-8 flex items-start space-x-4">

                <div class="flex-shrink-0 mt-1">
                    <i class='bx bxs-error-alt text-4xl text-orange-600'></i>
                </div>

                <div class="flex-1">
                    <strong class="font-extrabold block text-xl mb-2 leading-tight">
                    </strong>
                    <p class="block text-lg sm:inline leading-relaxed">
                        No has establecido tus preguntas de seguridad. Esta configuración es vital para recuperar tu
                        cuenta.
                        <a href="{{ route('dashboard.seguridad') }}"
                            class="font-bold underline text-orange-700 hover:text-orange-900 transition-colors ml-1">
                            Ve a configurarlas ahora
                        </a>.
                    </p>
                </div>

                <span
                    class="absolute top-3 right-3 p-1 cursor-pointer rounded-full hover:bg-orange-100 transition-colors"
                    wire:click="$set('showSecurityNotification', false)" title="Ocultar esta notificación">
                    <i class='bx bx-x text-2xl text-orange-500'></i>
                </span>
            </div>
            @endif

        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <button wire:click.prevent="redirectToCreateSolicitud"
                class="cursor-pointer bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500 block">
                <div class="text-center">
                    <i class='bx bx-plus-circle text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Nueva Solicitud</h3>
                    <p class="text-sm text-gray-600 mt-1">Crear solicitud completa</p>
                </div>
            </button>


            <a href="{{ route('dashboard.usuario.solicitud') }}"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500 block">
                <div class="text-center">
                    <i class='bx bx-plus-circle text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Gestionar Solicitudes</h3>
                    <p class="text-sm text-gray-600 mt-1">Administracion de solicitudes</p>
                </div>
            </a>

            <a href="{{ route('dashboard.visitas') }}"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-calendar-check text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Mis Visitas</h3>
                    <p class="text-sm text-gray-600 mt-1">Programadas y realizadas</p>
                </div>
            </a>

            <a href="{{ route('dashboard.infoGeneral') }}"
                class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-blue-500">
                <div class="text-center">
                    <i class='bx bx-user text-4xl text-blue-600 mb-3'></i>
                    <h3 class="font-bold text-gray-900">Mi Perfil</h3>
                    <p class="text-sm text-gray-600 mt-1">Actualizar información</p>
                </div>
            </a>
        </div>

        <!-- Recent Solicitudes -->
        @if($solicitudes->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class='bx bx-history text-blue-600 mr-2'></i>
                Solicitudes Recientes
            </h2>
            <div class="space-y-4">
                @foreach($solicitudes->take(5) as $solicitud)
                <div
                    class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <i class='bx bx-file-blank text-blue-600'></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $solicitud->titulo }}</h3>
                            <p class="text-sm text-gray-600">{{ $solicitud->subcategoriaRelacion->getCategoriaFormattedAttribute() ?? 'Sin
                                Categoría'
                                }}, {{$solicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute() ?? 'SinSubcategoía'}}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $solicitud->fecha_creacion->format('d/m/Y H:i')
                                }}</p>
                            <p class="text-xs text-gray-500 mt-1">Ticket: {{ $solicitud->solicitud_id }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-medium
                    {{$solicitud->estatus === 1 ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                    ($solicitud->estatus === 2 ? 'bg-green-100 text-green-800 hover:bg-green-200' : 
                    ($solicitud->estatus === 3 ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'text-black/60 hover:bg-gray-100'))}}">
                        {{ $solicitud->getEstatusFormattedAttribute() }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

</div>

<!-- Carousel JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar si el carousel existe (solo para usuarios)
    const carouselContainer = document.getElementById('carousel-container');
    if (!carouselContainer) return;
    
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.carousel-indicator');
    let currentSlide = 0;
    let carouselInterval;

    function showSlide(index) {
        // Remove active class from all slides and indicators
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Add active class to current slide and indicator
        if (slides[index]) {
            slides[index].classList.add('active');
            slides[index].style.opacity = '1';
            
            // Reset opacity for all other slides
            slides.forEach((slide, i) => {
                if (i !== index) {
                    slide.style.opacity = '0';
                }
            });
        }
        
        if (indicators[index]) {
            indicators[index].classList.add('active');
            indicators[index].style.backgroundColor = '#2563eb'; // blue-600
            
            // Reset other indicators
            indicators.forEach((indicator, i) => {
                if (i !== index) {
                    indicator.style.backgroundColor = '#d1d5db'; // gray-300
                }
            });
        }
        
        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function startCarousel() {
        carouselInterval = setInterval(nextSlide, 4000);
    }

    function stopCarousel() {
        if (carouselInterval) {
            clearInterval(carouselInterval);
        }
    }

    // Add click events to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            stopCarousel();
            showSlide(index);
            startCarousel();
        });
    });

    // Pause on hover
    carouselContainer.addEventListener('mouseenter', stopCarousel);
    carouselContainer.addEventListener('mouseleave', startCarousel);

    // Initialize carousel
    if (slides.length > 0) {
        showSlide(0);
        startCarousel();
    }
});
</script>