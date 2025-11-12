<?php

namespace App\Livewire\Dashboard\SuperAdminVisitas;

use App\Models\VisitasVisita;
use Livewire\Component;
use Livewire\Attributes\On;

class View extends Component
{
    // Propiedad que recibe el ID de la solicitud desde el componente padre
    public $solicitudId;

    // Propiedad para almacenar todos los datos de la visita (incluyendo relaciones)
    public $visitData;

    // Propiedad para almacenar el mensaje de error si la visita no se encuentra
    public $errorMessage;

    // Reglas de Livewire 3: La propiedad debe estar tipada si se recibe del padre
    public function mount($solicitudId)
    {
        $this->solicitudId = $solicitudId;
        $this->loadVisitData();
    }

    /**
     * Carga todos los datos relacionados con la visita.
     */
    public function loadVisitData()
    {
        // Limpiar datos y errores previos
        $this->visitData = null;
        $this->errorMessage = null;

        if (!$this->solicitudId) {
            $this->errorMessage = "No se ha especificado un ID de solicitud para ver.";
            return;
        }

        try {

            $this->visitData = VisitasVisita::with([
                'solicitud.persona',
                'asistente.User.usuario.roleModel',
                'estatus'
            ])
                // ...
                ->where('solicitud_id', $this->solicitudId)
                ->first();

            if (!$this->visitData) {
                $this->errorMessage = "No se encontró ninguna visita asignada con el ID de Solicitud: {$this->solicitudId}.";
            }
        } catch (\Exception $e) {
            $this->errorMessage = "Error al cargar los datos: " . $e->getMessage();
            // Opcional: emitir un toast de error
            $this->dispatch('error-toast', ['message' => $this->errorMessage, 'type' => 'error']);
        }
    }

    /**
     * Emite un evento para que el componente padre regrese a la lista.
     */
    public function goBack()
    {
        $this->dispatch('close');
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin-visitas.view');
    }
}
