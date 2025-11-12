<div class="container mx-auto px-4 py-8">
    <div class="mb-8 pb-6 border-b-2 border-gray-100">
        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-2 tracking-tight"
            style="font-family: 'Space Grotesk', sans-serif;">
            Panel de Usuarios
        </h1>
        <p class="text-lg text-gray-600">
            Monitorea el crecimiento de usuarios mes a mes
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Tarjeta: Total de Usuarios --}}
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Total de Usuarios
            </div>
            <div class="text-4xl font-bold text-gray-800" style="font-family: 'Space Grotesk', sans-serif;">
                {{ number_format($totalUsers) }}
            </div>
        </div>

        {{-- Tarjeta: Crecimiento (LÓGICA CORREGIDA) --}}
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Crecimiento (Intermensual)
            </div>
            <div class="text-4xl font-bold {{ $growthPercentage >= 0 ? 'text-green-600' : 'text-red-600' }}"
                style="font-family: 'Space Grotesk', sans-serif;">
                {{ $growthPercentage > 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}%
            </div>
        </div>

        {{-- Tarjeta: Período Activo --}}
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Período Activo
            </div>
            <div class="text-4xl font-bold text-blue-600" style="font-family: 'Space Grotesk', sans-serif;">
                {{ count($chartData) }}
                <span class="text-lg text-gray-500">meses</span>
            </div>
        </div>
    </div>

    {{-- GRÁFICO (Solución Alpine/Chart.js Opción 2) --}}
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-8">
        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-2xl font-semibold text-gray-800 mb-1" style="font-family: 'Space Grotesk', sans-serif;">
                Usuarios Registrados por Mes
            </h2>
            <p class="text-sm text-gray-600">
                Gráfico de escala temporal mostrando la tendencia de registros
            </p>
        </div>

        @if(count($chartData) > 0)
        <div x-data="{ chartInstance: null }" x-init="chartInstance = initChart($refs.chartCanvas, @js($chartData))"
            x-on:chart-data-updated.window="updateChart(chartInstance, $event.detail.chartData)" class="relative"
            style="height: 400px;">
            <canvas x-ref="chartCanvas"></canvas>
        </div>
        @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4 opacity-30">📊</div>
            <p class="text-lg text-gray-500 font-medium">
                No hay datos disponibles. Los usuarios registrados aparecerán aquí.
            </p>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        /**
         * FUNCIÓN DE INICIALIZACIÓN GLOBAL (Resuelve ReferenceError)
         * @param {HTMLElement} canvasElement - El elemento <canvas>
         * @param {Array} chartData - Los datos de Livewire
         * @returns {Chart|null}
         */
        function initChart(canvasElement, chartData) {
            // Asegura que Chart.js esté disponible (VITAL para NPM)
            if (!window.Chart || !chartData || chartData.length === 0) {
                return null;
            }

            const ctx = canvasElement.getContext('2d');
            const labels = chartData.map(item => item.label);
            const counts = chartData.map(item => item.count);

            return new window.Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Usuarios Registrados',
                        data: counts,
                        fill: true,
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: 'rgba(118, 75, 162, 1)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: { family: "'Space Grotesk', sans-serif", size: 13, weight: 600 },
                                color: '#4a5568', padding: 15, usePointStyle: true, pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(26, 32, 44, 0.95)',
                            titleFont: { family: "'Space Grotesk', sans-serif", size: 14, weight: 600 },
                            bodyFont: { family: "'Inter', sans-serif", size: 13 },
                            padding: 12, cornerRadius: 8, displayColors: true,
                            callbacks: {
                                label: (context) => ` Usuarios: ${context.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: "'Inter', sans-serif", size: 12 }, color: '#718096' },
                            grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false }
                        },
                        x: {
                            ticks: { font: { family: "'Inter', sans-serif", size: 12 }, color: '#718096' },
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        /**
         * FUNCIÓN DE ACTUALIZACIÓN GLOBAL (Para eventos Livewire)
         * @param {Chart|null} chartInstance - La instancia del gráfico a actualizar
         * @param {Array} newData - Los nuevos datos de Livewire
         */
        function updateChart(chartInstance, newData) {
            if (chartInstance) {
                const labels = newData.map(item => item.label);
                const counts = newData.map(item => item.count);
                
                chartInstance.data.labels = labels;
                chartInstance.data.datasets[0].data = counts;
                chartInstance.update();
            }
        }
    </script>
    @endpush
</div>