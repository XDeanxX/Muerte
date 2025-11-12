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

        <span class="absolute top-3 right-3 p-1 cursor-pointer rounded-full hover:bg-orange-100 transition-colors"
            wire:click="$set('showSecurityNotification', false)" title="Ocultar esta notificación">
            <i class='bx bx-x text-2xl text-orange-500'></i>
        </span>
    </div>
    @endif

</div>