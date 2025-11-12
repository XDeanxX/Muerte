<?php

namespace App\Livewire\Dashboard;

use App\Models\VisitasVisita;
use App\Models\VisitaEquipo;
use App\Models\Solicitud; // Necesitamos este modelo para actualizar la solicitud
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\Url;

class SuperAdminVisitas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Asegúrate de que este listener apunta al método correcto en este componente
    protected $listeners = [
        'close' => 'changeToList', 
        'visitSaved' => 'changeToList', // Opcional: listener desde el componente 'create'
    ];

    #[Url]
    public $search = '';
    public $currentStep = 'list';
    public $currentVisitId = null; // ID de la solicitud para ver/editar/eliminar

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function changeToList()
    {
        $this->currentStep = 'list';
        $this->currentVisitId = null; 
        $this->resetPage(); 
    }

    /**
     * Establece la visita actual y cambia al paso 'view'.
     *
     * @param int $solicitudId
     * @return void
     */
    public function viewVisit($solicitudId)
    {
        $this->currentVisitId = $solicitudId;
        $this->currentStep = 'view';
    }
    
    // --- FUNCIÓN AÑADIDA PARA EDITAR ---
    /**
     * Establece la visita actual y cambia al paso 'edit'.
     *
     * @param int $solicitudId
     * @return void
     */
    public function editVisit($solicitudId)
    {
        $this->currentVisitId = $solicitudId;
        $this->currentStep = 'edit';
    }
    // ------------------------------------

    /**
     *
     * @param int $solicitudId
     * @return void
     */
    public function deleteVisit($solicitudId)
    {
        try {
            DB::transaction(function () use ($solicitudId) {
                VisitaEquipo::where('solicitud_id', $solicitudId)->delete();
                
                $deletedVisits = VisitasVisita::where('solicitud_id', $solicitudId)->delete();

                if ($deletedVisits > 0) {
                    Solicitud::where('solicitud_id', $solicitudId)
                             ->update(['asignada_visita' => null]); 
                } else {
                     throw new \Exception("Visita no encontrada para la solicitud ID: {$solicitudId}");
                }
            });

            $this->dispatch('show-toast', [
                'message' => '¡Visita ' . $solicitudId . ' eliminada con éxito! La solicitud está disponible para reasignación.',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            // Manejo de errores
            $this->dispatch('error-toast', [ 
                'message' => 'Error al eliminar la visita: ' . $e->getMessage(),
                'type' => 'error' 
            ]);
        }
    }


    public function render()
    {
        $visitsQuery = VisitasVisita::with(['asistente', 'estatus']);

        if ($this->search) {
            $visitsQuery->where('solicitud_id', 'like', '%' . $this->search . '%');
        }

        $visitas = $visitsQuery->paginate(5);

        return view('livewire.dashboard.super-admin-visitas' ,[
            'visitas' => $visitas
            ]
        )->layout('components.layouts.rbac');
    }
}