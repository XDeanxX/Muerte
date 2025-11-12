<?php

namespace App\Livewire\Dashboard\SuperAdminVisitas;

use Livewire\Component;
use App\Models\VisitasVisita;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Edit extends Component
{
    public $solicitudId;

    public $initDate;
    public $finalDate;
    public $visita;

    const MAX_DAYS_DIFFERENCE = 30;

    // Mensajes de validación específicos para la edición de fechas
    public $messages = [
        'initDate.required' => 'La fecha de inicio es obligatoria para programar la visita.',
        'initDate.date' => 'La fecha de inicio debe ser una fecha válida.',
        'initDate.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',

        'finalDate.required' => 'La fecha de finalización es obligatoria.',
        'finalDate.date' => 'La fecha de finalización debe ser una fecha válida.',
        'finalDate.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',

        'finalDate.before_or_equal' => 'El lapso de la visita no debe exceder los ' . self::MAX_DAYS_DIFFERENCE . ' días.'
    ];

    /**
     * Carga los datos de la visita existente al iniciar el componente.
     */
    public function mount()
    {

        $this->visita = VisitasVisita::where('solicitud_id', $this->solicitudId)->first();

        // 2. Si la visita existe, precargar las fechas
        if ($this->visita) {
            $this->initDate = $this->visita->fecha_inicial;
            $this->finalDate = $this->visita->fecha_final;
        } else {
            // Opcional: Emitir un error si la visita no se encuentra
            $this->dispatch('error-toast', ['message' => 'Error: Visita no encontrada para editar.', 'type' => 'error']);
            $this->dispatch('close'); // Cierra el formulario de edición
        }
    }

    /**
     * Guarda la actualización de las fechas de la visita.
     */
    public function updateVisitDates()
    {
        // 1. Definir la fecha máxima de finalización para la validación de 30 días
        $maxFinalDate = Carbon::parse($this->initDate)
            ->addDays(self::MAX_DAYS_DIFFERENCE)
            ->toDateString();

        $todayString = Carbon::today()->toDateString();

        // 2. Aplicar las reglas de validación
        $this->validate([
            'initDate' => [
                'required',
                'date',
                "after_or_equal:{$todayString}", // La fecha debe ser hoy o posterior
            ],
            'finalDate' => [
                'required',
                'date',
                'after_or_equal:initDate',
                "before_or_equal:{$maxFinalDate}", // Regla de 30 días
            ],
        ]);

        // 3. Persistir los cambios en la base de datos
        try {
            DB::transaction(function () {
                $this->visita->update([
                    'fecha_inicial' => $this->initDate,
                    'fecha_final'   => $this->finalDate,
                ]);
            });

            // Éxito
            $this->dispatch('show-toast', [
                'message' => "Fechas de la visita ({$this->solicitudId}) actualizadas con éxito.",
                'type' => 'success'
            ]);

            // Cerrar el componente de edición y volver a la lista (y refrescar la lista)
            $this->dispatch('close');
        } catch (\Exception $e) {
            // Manejo de errores
            $this->dispatch('error-toast', [
                'message' => 'Error al actualizar las fechas: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin-visitas.edit');
    }
}
