<?php

namespace App\Livewire\Dashboard;

use App\Models\Solicitud;
use App\Models\VisitaEquipo;
use App\Models\Categorias;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdministradorDashboard extends Component
{
    public $activeTab = 'dashboard';
    public $visitas;
    public $usuarios;

    public $categorias;

    public function mount()
    {
        $this->activeTab = request()->get('tab', 'dashboard');
        $this->loadData();
    }

    public function loadData()
    {
        // Load all visitas for admin view
        $this->visitas = VisitaEquipo::with(['visita'])->where('cedula', Auth::user()->persona_cedula)->get();

            $this->categorias = Categorias::all();
            
        $this->usuarios = User::with('persona')->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.dashboard.administrador-dashboard',[
            'solicitudes' => Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion'])->get(),
            'solicitudesDos' => Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion'])->paginate(10)->onEachSide(-0.5),
        ])->layout('components.layouts.rbac');
    }

    // Add method to redirect to CRUD component
    public function redirectToCrud()
    {
        return redirect()->route('dashboard.administrador', [
            'tab' => 'crud',
        ]);
    }
}