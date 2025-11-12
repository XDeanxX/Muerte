<?php

namespace App\Livewire;

use App\Models\Notificacion;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = ['notificationRead' => 'refreshNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        
        // Cargar todas las notificaciones de tipo "reunion" ordenadas por fecha
        $this->notifications = Notificacion::where('persona_cedula', $user->persona_cedula)
            ->where('tipo', 'reunion')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Contar las no leídas
        $this->unreadCount = Notificacion::where('persona_cedula', $user->persona_cedula)
            ->where('tipo', 'reunion')
            ->where('leida', false)
            ->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            // Al abrir el dropdown, marcar automáticamente todas las notificaciones como leídas
            $this->markAllAsReadOnOpen();
            $this->loadNotifications();
        }
    }

    public function markAllAsReadOnOpen()
    {
        // Marcar todas las notificaciones no leídas como leídas al abrir el dropdown
        Notificacion::where('persona_cedula', Auth::user()->persona_cedula)
            ->where('tipo', 'reunion')
            ->where('leida', false)
            ->update(['leida' => true]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notificacion::find($notificationId);
        
        if ($notification && $notification->persona_cedula == Auth::user()->persona_cedula) {
            $notification->marcarComoLeida();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notificacion::where('persona_cedula', Auth::user()->persona_cedula)
            ->where('tipo', 'reunion')
            ->where('leida', false)
            ->update(['leida' => true]);
        
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications-dropdown');
    }
}
