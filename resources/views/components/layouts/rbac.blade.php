<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/isotipo.png') }}">

    <title>@yield('title', 'CMBEY - Sistema Municipal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles




</head>

<body class="bg-white font-roboto">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-40 md:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"></div>

        <!-- Sidebar -->
        <div class="w-68 sidebar-gradient shadow-2xl flex flex-col sidebar-responsive" :class="{'open': sidebarOpen}"
            x-data="{
                profileOptions: false, 
                configurateOptions: false,
                configurateParameters: false,
                currentUser: @js(auth()->user()),
                userRole: @js(auth()->user()->role)                
            }">
            <!-- Logo Section -->
            <div class="p-6 border-b border-white border-opacity-20">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('img/logotipo.png') }}" alt="CMBEY Logo" class="h-16 w-auto object-contain">
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 overflow-y-auto">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
                            class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                            <i class='bx bx-home text-xl mr-3'></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- SOLICITANTE -->
                    <template x-if="userRole === 3">
                        <li>
                            <a href="{{ route('dashboard.usuario.solicitud') }}"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-file-blank text-xl mr-3'></i>
                                <span>Solicitudes</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole > 1">
                        <li>
                            <a href="{{ route('dashboard.visitas') }}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-calendar-check text-xl mr-3'></i>
                                <span>Visitas</span>
                            </a>
                        </li>
                    </template>

                    <!--ASISTENTE-->
                    <template x-if="userRole === 2">
                        <li>
                            <a href="{{ route('dashboard.admin.solicitudes') }}"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-file-blank text-xl mr-3'></i>
                                <span>Solicitudes</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole === 2">
                        <li>
                            <a href="{{route('dashboard.admin.usuarios')}}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-user text-xl mr-4'></i>
                                <span>Usuarios</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole === 2">
                        <li>
                            <a href="{{  route('dashboard.reuniones.index') }}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-show text-lg mr-4'></i>
                                <span>Reuniones</span>
                            </a>
                        </li>
                    </template>

                    <!-- ADMINISTRADOR -->
                    <template x-if="userRole === 1">
                        <div>
                            <button @click="configurateOptions = !configurateOptions"
                                class="sidebar-item w-full flex items-center justify-between p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <div class="flex items-center">
                                    <i class='bx bx-notepad text-xl mr-3'></i>
                                    <span>Derecho de Palabra</span>
                                </div>
                                <i class='bx bx-chevron-down transition-transform'
                                    :class="{'rotate-180': configurateOptions}"></i>
                            </button>
                            <ul class="sidebar-submenu ml-6 mt-2 space-y-1" :class="{'open': configurateOptions}">
                                <li>
                                    <a href="{{route('dashboard.superadmin.solicitudes')}}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-file-blank text-xl mr-2 '></i>
                                        <span class="text-sm">Solicitudes</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{  route('dashboard.reuniones.index') }}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-show text-lg mr-2'></i>
                                        <span class="text-sm">Reuniones</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('dashboard.superadmin.visitas')}}" @click=" sidebarOpen=false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-calendar-check text-xl mr-4'></i>
                                        <span class="text-sm">Visitas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </template>

                    <template x-if="userRole <= 3">
                        <div>
                            <button @click="profileOptions = !profileOptions"
                                class="sidebar-item w-full flex items-center justify-between p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <div class="flex items-center">
                                    <i class='bx bx-wrench text-xl mr-3'></i>
                                    <span>Perfil</span>
                                </div>
                                <i class='bx bx-chevron-down transition-transform'
                                    :class="{'rotate-180': profileOptions}"></i>
                            </button>
                            <ul class="sidebar-submenu ml-6 mt-2 space-y-1" :class="{'open': profileOptions}">
                                <li>
                                    <a href="{{route('dashboard.infoGeneral')}}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-male text-xl mr-2 '></i>
                                        <span class="text-sm">Informacion general</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('dashboard.seguridad') }}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-lock-open text-lg mr-2'></i>
                                        <span class="text-sm">Seguridad</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </template>
                    
                    <template x-if="userRole === 1">
                        <div>
                            <button @click="configurateParameters = !configurateParameters"
                                class="sidebar-item w-full flex items-center justify-between p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <div class="flex items-center">
                                    <i class='bx bx-hard-hat text-xl mr-3'></i>
                                    <span>Parámetros</span>
                                </div>
                                <i class='bx bx-chevron-down transition-transform'
                                    :class="{'rotate-180': configurateParameters}"></i>
                            </button>
                            <ul class="sidebar-submenu ml-6 mt-2 space-y-1" :class="{'open': configurateParameters}">
                                <li>
                                    <a href="{{route('dashboard.superadmin.categorias')}}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-file text-xl mr-2 '></i>
                                        <span class="text-sm">Categorías</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('dashboard.superadmin.comunidad') }}" @click="sidebarOpen = false"
                                        class="sidebar-item flex items-center p-2 text-blue-100 rounded hover:bg-white hover:bg-opacity-10">
                                        <i class='bx bx-group text-lg mr-2'></i>
                                        <span class="text-sm">Comunidades</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </template>

                    <!-- Gestión usuarios (Only For SuperAdmins) -->
                    <template x-if="userRole === 1">
                        <li>
                            <a href="{{route('dashboard.superadmin.usuarios')}}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-user text-xl mr-4'></i>
                                <span>Gestión Usuarios</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole === 1">
                        <li>
                            <a href="{{route('dashboard.superadmin.trabajadores')}}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class="bx bx-book-content text-xl mr-3"></i>
                                <span>Gestión Trabajadores</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole === 1">
                        <li>
                            <a href="{{route('dashboard.superadmin.reportes')}}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-bar-chart-alt-2 text-xl mr-3'></i>
                                <span>Reportes</span>
                            </a>
                        </li>
                    </template>

                    <template x-if="userRole === 1">
                        <li>
                            <a href="{{route('dashboard.superadmin.basedatos')}}" @click="sidebarOpen = false"
                                class="sidebar-item flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-10">
                                <i class='bx bx-data mr-3 text-xl'></i>
                                <span>Base de datos</span>
                            </a>
                        </li>
                    </template>
                </ul>
            </nav>



            <div class="p-4 border-t border-white border-opacity-20">
                <form method="POST" action="{{route('logout')}}">
                    @csrf
                    <button type="submit"
                        class="sidebar-item w-full flex items-center p-3 text-white rounded-lg hover:bg-red-600 hover:bg-opacity-80 transition-colors">
                        <i class='bx bx-log-out text-xl mr-3'></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden main-content-mobile">
            <header class="navbar-gradient shadow-lg">
                <div class="flex items-center justify-between px-4 md:px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="text-white p-2 rounded-lg hover:bg-white hover:bg-opacity-10 mr-3 md:hidden">
                            <i class='bx bx-menu text-xl'></i>
                        </button>

                    </div>
                    <div class="flex items-center space-x-2 md:space-x-4">
                        @if(auth()->user()->role === 3)
                        @livewire('notifications-dropdown')
                        @endif
                        <div class="text-right hidden md:block">
                            <p class="text-white text-sm font-medium">{{ auth()->user()->persona->nombre }} {{
                                auth()->user()->persona->apellido }}</p>
                            <p class="text-blue-100 text-xs">{{ auth()->user()->getRoleName() }}</p>
                        </div>
                        <div class="h-8 w-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="bx
                                {{auth()->user()->getUserIcon()}}
                              text-gray-500">
                            </i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>