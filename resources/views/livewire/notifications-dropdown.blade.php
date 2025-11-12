<div>
    <style>
        [x-cloak] { 
            display: none !important; 
        }
    </style>

    <div class="relative" x-data="{ open: @entangle('showDropdown').live }">
        <!-- Notification Bell Icon -->
        <button @click="$wire.toggleDropdown()" 
                type="button"
                class="relative p-2 text-white hover:bg-opacity-10 rounded-lg transition-colors focus:outline-none">
            <i class='bx bx-bell text-2xl'></i>
            
            <!-- Badge for unread count - Se actualiza reactivamente -->
            <span 
                x-show="$wire.unreadCount > 0" 
                x-transition
                class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                <span x-text="$wire.unreadCount > 9 ? '9+' : $wire.unreadCount"></span>
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             x-cloak
             @click.outside="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute -right-9 lg:right-0 mt-2 w-80 md:w-96 bg-white rounded-lg shadow-xl z-50 max-h-96 overflow-hidden border border-gray-200">
            
            <!-- Header -->
            <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class='bx bx-bell text-xl'></i>
                    <h3 class="font-semibold text-base">Notificaciones</h3>
                </div>
                <button @click="open = false" 
                        class="text-white hover:bg-white hover:bg-opacity-20 p-1 rounded transition-colors">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>

            <!-- Notifications List -->
            <div class="overflow-y-auto max-h-80 ">
                @if(count($notifications) > 0)
                    @foreach($notifications as $notification)
                        <div class="px-4 py-3 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class='bx bx-calendar-event text-blue-600 text-xl'></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm text-gray-800 mb-1">{{ $notification->titulo }}</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $notification->mensaje }}</p>
                                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                        <i class='bx bx-time-five text-xs'></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="px-4 py-12 text-center text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-bell-off text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700">No tienes notificaciones</p>
                        <p class="text-xs text-gray-500 mt-1">Te notificaremos cuando seas convocado a una reunión</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if(count($notifications) > 0)
                <div class="px-4 py-2 bg-gray-50 text-center border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <i class='bx bx-info-circle text-sm'></i>
                        Las notificaciones se marcan como leídas automáticamente
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
