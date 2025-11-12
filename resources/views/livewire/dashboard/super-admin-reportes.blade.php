<div class="min-h-screen bg-gray-50">
  <div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center py-6">
        <div class="flex items-center mb-4 md:mb-0">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
              <i class='bx bx-bar-chart text-xl text-white'></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Gestión de Reportes</h1>
              <p class="text-sm text-gray-600">Sistema Municipal CMBEY</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ showChart: 1 }">

    @if ($showVista === 0)
    {{-- GRAFICA SOLICITUDES 1 --}}
    <div class="relative w-full overflow-hidden">
      <div x-show="showChart === 1" x-init="$wire.dispatch('refreshChart1')"
        x-transition:enter="transition ease-out duration-700 transform"
        x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 w-full"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
        class="">
        <div class="flex flex-col max-lg:flex-col bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">

          {{----}}
          <div class="flex flex-col">
            <div class="flex justify-between items-center max-lg:flex-col">
              <h2 class="text-lg lg:text-xl lg:ml-10 my-auto font-bold text-gray-900 max-lg:ml-0 max-lg:text-center">
                Reporte de Solicitudes</h2>
              <div class="flex justify-center items-center gap-4">
                <button type="button" onclick="generarPDF('chart1')"
                    class="w-12 h-12 border border-gray-300 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                    title="Exportar a PDF">
                    <i class='bx bxs-file-pdf text-2xl'></i>
                </button>
                <div class="relative" x-data="{selectActive: 0}">
                  <div
                    class="w-full h-full py-2 px-1 rounded-lg flex items-center justify-center cursor-pointer transition-colors border border-gray-300
                                {{$estatusName === 'Pendiente' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 
                                ($estatusName === 'Aprobada' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 
                                ($estatusName === 'Rechazada' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'text-black/60 hover:bg-gray-100'))}}"
                    @click="selectActive = selectActive === 1 ? 0 : 1">


                    <i class='bx bx-filter text-xl max-md:py-2 px-1'></i>
                    <span class="py-2 px-1 inline-flex text-md leading-5 rounded-full max-md:hidden">
                      {{$estatusName}}
                    </span>
                    <i class='bx bx-caret-down text-xl mr-1' :class="{
                                    'transform rotate-180': selectActive === 1,
                                }"></i>

                  </div>
                  <div class="absolute w-auto mt-1 bg-white border border-gray-300 rounded-lg shadow-lg"
                    x-show="selectActive === 1" x-transition @click.away="selectActive = 0" x-cloak x-bind :class="{
                                    'hidden': selectActive !== 1,
                                }">
                    <ul>
                      <li
                        class="text-black/60 p-2 transition-colors cursor-default hover:bg-gray-100 hover:text-gray-800"
                        wire:click="ordenEstados(0)">Todos</li>
                      @foreach($estatus as $estatu)
                      <li
                        class="text-black/60 p-2 transition-colors cursor-default hover:bg-gray-100 hover:text-gray-800"
                        wire:click="ordenEstados({{ $estatu->estatus_id }})">{{$estatu->getEstatusFormattedAttribute()}}
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <div class="flex justify-end gap-4">
                  <button @click="showChart = 1"
                    class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                    :class="{ 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-900': showChart != 1, 'bg-gray-400': showChart === 1 }">
                    <i class='bx bx-caret-left text-3xl'></i>
                  </button>
                  <button @click="showChart = 2"
                    class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                    :class="{ 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-900': showChart === 1, 'bg-gray-400': showChart != 1}">
                    <i class='bx bx-caret-right text-3xl'></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          {{----}}
          <div class="w-full flex flex-col mx-auto ">
            <div class="w-auto my-5 grid grid-cols-1 lg:grid-cols-3 gap-2 lg:px-20">
              <div
                class="flex flex-col py-3 items-center border border-zinc-100 bg-white shadow-sm border-2 rounded border-transparent">
                <div class="my-auto">
                  <div class="flex flex-col items-center text-center">
                    <i class='bx bx-time-five text-3xl text-yellow-500'></i>
                    <p class="text-zinc-800">Total Pendientes</p>
                  </div>
                  <h3 class="text-2xl font-bold text-center">{{ $solicitudes->where('estatus', 1)->count()}}</h3>
                </div>
              </div>
              <div
                class="flex flex-col items-center border border-zinc-100 bg-white shadow-sm border-2 rounded border-transparent">
                <div class="my-auto">
                  <div class="flex flex-col items-center text-center">
                    <i class='bx bx-check-circle text-3xl text-green-400'></i>
                    <p class="text-zinc-800">Total Aprobadas</p>
                  </div>
                  <h3 class="text-2xl font-bold text-center">{{ $solicitudes->where('estatus', 2)->count()}}</h3>
                </div>
              </div>
              <div
                class="flex flex-col items-center border border-zinc-100 bg-white shadow-sm border-2 rounded border-transparent">
                <div class="my-auto">
                  <div class="flex flex-col items-center text-center">
                    <i class='bx bx-x-circle text-3xl text-red-400'></i>
                    <p class="text-zinc-800">Total Rechazadas</p>
                  </div>
                  <h3 class="text-2xl font-bold text-center">{{ $solicitudes->where('estatus', 3)->count()}}</h3>
                </div>
              </div>
            </div>
            {{--CHART1--}}
            <div class="lg:px-12" wire:ignore>
              <canvas class="w-full" id="chart1"></canvas>
              <div class="flex">
                <div class="relative w-40 ml-3">
                  <select
                    class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                    wire:model.live="selectedPeriod">
                    <option value="today">Hoy</option>
                    <option value="last_7_days">Últimos 7 días</option>
                    <option value="last_30_days">Últimos 30 días</option>
                    <option value="this_month">Este mes</option>
                    <option value="this_year">Este año</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class='bx bx-chevron-down'></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col max-lg:flex-col bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          <h2 class="text-lg lg:text-xl lg:ml-10 my-auto font-bold text-gray-900 max-lg:ml-0 max-lg:text-center">Reporte
            Descriptivo</h2>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            @php
            $totalSolicitudes = $solicitudes->count();

            $divisor = $totalSolicitudes > 0 ? $totalSolicitudes : 1;

            $pendientes = $solicitudes->where('estatus', 1)->count();
            $rechazadas = $solicitudes->where('estatus', 2)->count();
            $aprobadas = $solicitudes->where('estatus', 3)->count();

            $porcentajePendientes = round(($pendientes / $divisor) * 100, 1) . '%';
            $porcentajeRechazadas = round(($rechazadas / $divisor) * 100, 1) . '%';
            $porcentajeAprobadas = round(($aprobadas / $divisor) * 100, 1) . '%';

            @endphp
            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">General</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                  <span class="text-gray-800 pt-1">Total Penditentes: </span>
                  <span class="text-gray-800 pt-1">Total Rechazadas: </span>
                  <span class="text-gray-800 pt-1">Total Aprobadas: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>{{$solicitudes->count()}}</strong></p>
                  <p class="pt-1"><strong>{{$solicitudes->where('estatus', 1)->count()}}</strong></p>
                  <p class="pt-1"><strong>{{$solicitudes->where('estatus', 2)->count()}}</strong></p>
                  <p class="pt-1"><strong>{{$solicitudes->where('estatus', 3)->count()}}</strong></p>
                </div>
              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Porcentaje</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                  <span class="text-gray-800 pt-1">Total Penditentes: </span>
                  <span class="text-gray-800 pt-1">Total Rechazadas: </span>
                  <span class="text-gray-800 pt-1">Total Aprobadas: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>100%</strong></p>
                  <p class="pt-1"><strong>{{$porcentajePendientes}}</strong></p>
                  <p class="pt-1"><strong>{{$porcentajeRechazadas}}</strong></p>
                  <p class="pt-1"><strong>{{$porcentajeAprobadas}}</strong></p>
                </div>
              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Solicitudes Asignadas a Visitas</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>{{$solicitudes->where('asignada_visita', true)->count()}}</strong></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- GRAFICA SOLICITUDES 2 --}}
      <div x-show="showChart === 2" x-init="$wire.dispatch('refreshChart2')"
        x-transition:enter="transition ease-out duration-700 transform"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 w-full"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
        class="">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          {{-- CHART2 --}}
          <div class="flex items-center justify-center lg:justify-between max-lg:flex-col">
            <h2 class="text-lg lg:text-xl lg:ml-10 my-5 font-bold text-gray-900 max-lg:mx-auto max-lg:text-center">
              Historial de Solicitudes</h2>
            <div class="flex justify-end mt-3 gap-4">
              <button type="button" onclick="generarPDF('chart2')"
                  class="w-12 h-12 border border-gray-300 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                  title="Exportar a PDF">
                  <i class='bx bxs-file-pdf text-2xl'></i>
              </button>
              <button @click="showChart = 1"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-900': showChart === 2, 'bg-gray-400': showChart != 2}">
                <i class='bx bx-caret-left text-3xl'></i>
              </button>
              <button @click="showChart = 3"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700': showChart === 2, 'bg-gray-400': showChart != 2 }">
                <i class='bx bx-caret-right text-3xl'></i>
              </button>
            </div>
          </div>
          <div class="lg:px-12" wire:ignore>
            <canvas class="w-full" id="chart2"></canvas>
          </div>
          <div class="relative w-40 ml-9">
            <select
              class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
              wire:model.live="selectedPeriodChart2">
              <option value="today">Hoy</option>
              <option value="last_7_days">Últimos 7 días</option>
              <option value="last_30_days">Últimos 30 días</option>
              <option value="this_month">Este mes</option>
              <option value="this_year">Este año</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class='bx bx-chevron-down'></i>
            </div>
          </div>
        </div>

        <div class="flex flex-col max-lg:flex-col bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          <h2 class="text-lg lg:text-xl lg:ml-10 my-auto font-bold text-gray-900 max-lg:ml-0 max-lg:text-center">Reporte
            Descriptivo</h2>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Esta Semana</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                  <span class="text-gray-800 pt-1">Total Penditentes: </span>
                  <span class="text-gray-800 pt-1">Total Rechazadas: </span>
                  <span class="text-gray-800 pt-1">Total Aprobadas: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>{{ $solicitudesSemana->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesSemana->where('estatus', 1)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesSemana->where('estatus', 2)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesSemana->where('estatus', 3)->count() }}</strong></p>
                </div>
              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Este Mes</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                  <span class="text-gray-800 pt-1">Total Penditentes: </span>
                  <span class="text-gray-800 pt-1">Total Rechazadas: </span>
                  <span class="text-gray-800 pt-1">Total Aprobadas: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>{{ $solicitudesMes->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesMes->where('estatus', 1)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesMes->where('estatus', 2)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesMes->where('estatus', 3)->count() }}</strong></p>
                </div>
              </div>
            </div>
            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Este Año</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  <span class="text-gray-800 pt-1">Total de Solicitudes: </span>
                  <span class="text-gray-800 pt-1">Total Penditentes: </span>
                  <span class="text-gray-800 pt-1">Total Rechazadas: </span>
                  <span class="text-gray-800 pt-1">Total Aprobadas: </span>
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  <p class="pt-1"><strong>{{ $solicitudesAno->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesAno->where('estatus', 1)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesAno->where('estatus', 2)->count() }}</strong></p>
                  <p class="pt-1"><strong>{{ $solicitudesAno->where('estatus', 3)->count() }}</strong></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div x-show="showChart === 3" x-init="$wire.dispatch('refreshChart3')"
        x-transition:enter="transition ease-out duration-700 transform"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 w-full"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
        class="">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          {{-- CHART2 --}}
          <div class="flex items-center justify-center lg:justify-between max-lg:flex-col">
            <h2 class="text-lg lg:text-xl lg:ml-10 my-5 font-bold text-gray-900 max-lg:mx-auto max-lg:text-center">
              Categorías de las Solicitudes</h2>
            <div class="flex justify-end mt-3 gap-4">
              <button type="button" onclick="generarPDF('chart3')"
                  class="w-12 h-12 border border-gray-300 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                  title="Exportar a PDF">
                  <i class='bx bxs-file-pdf text-2xl'></i>
              </button>
              <button @click="showChart = 2"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-900': showChart === 3, 'bg-gray-400': showChart != 3}">
                <i class='bx bx-caret-left text-3xl'></i>
              </button>
              <button @click="showChart = 4"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700': showChart === 3, 'bg-gray-400': showChart != 3}">
                <i class='bx bx-caret-right text-3xl'></i>
              </button>
            </div>
          </div>
          <div class="lg:px-12" wire:ignore>
            <canvas class="w-full" id="chart3"></canvas>
          </div>
          <div class="relative w-40 ml-9">
            <select
              class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
              wire:model.live="selectedPeriodChart3">
              <option value="today">Hoy</option>
              <option value="last_7_days">Últimos 7 días</option>
              <option value="last_30_days">Últimos 30 días</option>
              <option value="this_month">Este mes</option>
              <option value="this_year">Este año</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class='bx bx-chevron-down'></i>
            </div>
          </div>
        </div>

        <div class="flex flex-col max-lg:flex-col bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          <h2 class="text-lg lg:text-xl lg:ml-10 my-auto font-bold text-gray-900 max-lg:ml-0 max-lg:text-center">Reporte
            Descriptivo</h2>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Total de Categorías</h3>
              <div class="flex items-center justify-between">

                <div class="flex flex-col pt-1">
                  @foreach ($solicitudesPorCategoria as $reporte)
                  <span class="text-gray-800 pt-1">{{ ucfirst($reporte->nombre_categoria) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($solicitudesPorCategoria as $reporte)
                  <p class="pt-1"><strong>{{ $reporte->total }}</strong></p>
                  @endforeach
                </div>

              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Top 5 Subcategorías</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  @foreach ($top5Subcategorias as $reporte)
                  <span class="text-gray-800 pt-1">{{ ucfirst($reporte->nombre_subcategoria) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($top5Subcategorias as $reporte)
                  <p class="pt-1"><strong>{{ ucfirst($reporte->total) }}</strong></p>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Últimos 30 Días</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  @foreach ($ultimas5Subcategorias as $reporte)
                  <span class="text-gray-800 pt-1">{{ ucfirst($reporte->nombre_subcategoria) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($ultimas5Subcategorias as $reporte)
                  <p class="pt-1"><strong>{{ ucfirst($reporte->total) }}</strong></p>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div x-show="showChart === 4" x-init="$wire.dispatch('refreshChart4')"
        x-transition:enter="transition ease-out duration-700 transform"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform absolute top-0 left-0 w-full"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
        class="">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          {{-- CHART2 --}}
          <div class="flex items-center justify-center lg:justify-between max-lg:flex-col">
            <h2 class="text-lg lg:text-xl lg:ml-10 my-5 font-bold text-gray-900 max-lg:mx-auto max-lg:text-center">
              Ubicación de las Solicitudes</h2>
            <div class="flex justify-end mt-3 gap-4">
              <button type="button" onclick="generarPDF('chart4')"
                  class="w-12 h-12 border border-gray-300 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                  title="Exportar a PDF">
                  <i class='bx bxs-file-pdf text-2xl'></i>
              </button>
              <button @click="showChart = 3"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700 hover:shadow-blue-900': showChart === 4, 'bg-gray-400': showChart != 4}">
                <i class='bx bx-caret-left text-3xl'></i>
              </button>
              <button @click="showChart = 4"
                class="w-10 h-10 rounded-3xl shadow-md text-white font-bold transition duration-300 flex items-center justify-center"
                :class="{ 'bg-blue-600 hover:bg-blue-700': showChart != 4, 'bg-gray-400': showChart === 4}">
                <i class='bx bx-caret-right text-3xl'></i>
              </button>
            </div>
          </div>
          <div class="lg:px-12" wire:ignore>
            <canvas class="w-full" id="chart4"></canvas>
          </div>
          <div class="relative w-40 ml-9">
            <select
              class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
              wire:model.live="selectedPeriodChart4">
              <option value="today">Hoy</option>
              <option value="last_7_days">Últimos 7 días</option>
              <option value="last_30_days">Últimos 30 días</option>
              <option value="this_month">Este mes</option>
              <option value="this_year">Este año</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class='bx bx-chevron-down'></i>
            </div>
          </div>
        </div>

        <div class="flex flex-col max-lg:flex-col bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
          <h2 class="text-lg lg:text-xl lg:ml-10 my-auto font-bold text-gray-900 max-lg:ml-0 max-lg:text-center">Reporte
            Descriptivo</h2>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Total de Parroquias</h3>
              <div class="flex items-center justify-between">

                <div class="flex flex-col pt-1">
                  @foreach ($solicitudesParroquias as $reporte)
                  <span class="text-gray-800 pt-1">{{ ucfirst($reporte->nombre_parroquia) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($solicitudesParroquias as $reporte)
                  <p class="pt-1"><strong>{{ $reporte->total }}</strong></p>
                  @endforeach
                </div>

              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Top 5 Comunidades</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  @foreach ($top5Comunidades as $reporte)
                  <span class="text-gray-800 pt-1">{{ Str::title($reporte->nombre_comunidad) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($top5Comunidades as $reporte)
                  <p class="pt-1"><strong>{{ ucfirst($reporte->total) }}</strong></p>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="bg-blue-50/50 rounded-xl p-6 border border-white hover:shadow-sm hover:border-blue-100">
              <h3 class="text-md font-bold flex items-center justify-center">Ultimos 30 Días</h3>
              <div class="flex items-center justify-between">
                <div class="flex flex-col pt-1">
                  @foreach ($ultimas5Comunidades as $reporte)
                  <span class="text-gray-800 pt-1">{{ Str::title($reporte->nombre_comunidad) }}: </span>
                  @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-1">
                  @foreach ($ultimas5Comunidades as $reporte)
                  <p class="pt-1"><strong>{{ ucfirst($reporte->total) }}</strong></p>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @endif

      {{-- REPORTES DE VISITAS --}}
      @if ($showVista === 1)
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-6">
        <div wire:ignore class="w-full px-10 max-lg:px-5">
          <h2 class="text-lg ml-7 my-5 font-bold text-gray-900 max-lg:mx-auto max-lg:text-center">Historial de Visitas
          </h2>
          <canvas class="mx-auto w-full" id="chartVisitas"></canvas>
        </div>
      </div>
      @endif
    </div>

  </div>
</div>