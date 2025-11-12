<?php

namespace App\Livewire\Dashboard\SuperAdminVisitas;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use App\Models\VisitasVisita;
use App\Models\VisitaEquipo;
use App\Models\User;
use App\Models\Solicitud;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Visitas extends Component
{
    use WithPagination;

    const MIN_PERSONAS = 1;
    const MAX_PERSONAS = 5;

    // Constante para el límite de días (30 días)
    const MAX_DAYS_DIFFERENCE = 30;

    public $messages = [
        'initDate.required' => 'La fecha de inicio es obligatoria para programar la visita.',
        'initDate.date' => 'La fecha de inicio debe ser una fecha válida.',
        'initDate.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
        
        'finalDate.required' => 'La fecha de finalización es obligatoria.',
        'finalDate.date' => 'La fecha de finalización debe ser una fecha válida.',
        'finalDate.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
        
        // Nuevo mensaje para la validación de 30 días
        'finalDate.before_or_equal' => 'El lapso de la visita no debe exceder los ' . self::MAX_DAYS_DIFFERENCE . ' días.'
    ];

    #[Url]
    public $search = '';
    public $paso = 1;
    public $selectedSolicitudId = null; 
    public $selectedSolicitud = null;
    public array $personasSelected = [];
    public $initDate;
    public $finalDate;

    protected $paginationTheme = 'tailwind';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    #[Computed]
    public function solicitudAproved()
    {
        return Solicitud::query()
            // MODIFICACIÓN 1: Solo seleccionar solicitudes que NO tienen visita asignada
            ->where('asignada_visita', null) // Asumo que 0 o null significa no asignada
            ->whereNull('deleted_at')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('titulo', 'like', '%' . $this->search . '%')
                             ->orWhere('solicitud_id', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(5);
    }

    #[Computed]
    public function selectedCount()
    {
        return count($this->personasSelected);
    }

    #[Computed]
    public function isMaxReached()
    {
        return $this->selectedCount >= self::MAX_PERSONAS;
    }

    #[Computed]
    public function isMinNotMet()
    {
        return $this->selectedCount < self::MIN_PERSONAS;
    }
    
    public function selectSolicitud($solicitudId)
    {
        // MODIFICACIÓN 2: Cargar la relación 'persona' para el paso 2
        $solicitud = Solicitud::with('persona')
                             ->where('solicitud_id', $solicitudId)
                             // Aseguramos que no esté asignada aquí también
                             ->where('asignada_visita', null) 
                             ->whereNull('deleted_at')
                             ->first();

        if ($solicitud) {
            $this->selectedSolicitudId = $solicitudId;
            $this->selectedSolicitud = $solicitud;
            $this->paso = 2; 

            $this->dispatch('show-toast', [
                'message' => 'Solicitud seleccionada exitosamente.' ,
                'type' => 'success']);
        } else {
             $this->dispatch('error-toast', [
                'message' => 'Ha ocurrido un error al seleccionar la solicitud o ya fue asignada a una visita.',
                'type' => 'error']);
        }
    }

    #[Computed]
    public function personalDisponible()
    {
       $visitorsQuery = User::with(["persona", "roleModel"])
        ->where("role", "<", 3);

    if ($this->search) {
        $search = '%' . $this->search . '%';

        $visitorsQuery->where(function ($query) use ($search) {
            $query->where('persona_cedula', 'like', $search) 
                  ->orWhereHas('persona', function ($q) use ($search) {
                      $q->where('nombre', 'like', $search)
                        ->orWhere('apellido', 'like', $search);
                  });
        });
    }

    return $visitorsQuery->paginate(5 , pageName:'userPage');
    }
    
    public function updatedPersonasSelected()
    {
        if (count($this->personasSelected) > self::MAX_PERSONAS) {
            $this->personasSelected = array_slice(
                $this->personasSelected, 
                0, 
                self::MAX_PERSONAS
            );
            
            $this->dispatch('error-toast', [
                'message' => 'Límite máximo alcanzado. Solo puedes seleccionar ' . self::MAX_PERSONAS . ' técnicos.',
                'type' => 'warning'
            ]);
        }
    }

    public function previousStep()
    {
        $this->paso--;
    }
    
    public function nextStep()
    {
        if ($this->paso === 2) {
            $this->paso = 3;
        }elseif ($this->paso === 3) {
            // No necesitas saveAssignment aquí si solo se valida
            // Si el paso 3 es solo para seleccionar personal, vamos al 4.
            if ($this->isMinNotMet) {
                 $this->dispatch('error-toast', [
                    'message' => 'Debes seleccionar al menos ' . self::MIN_PERSONAS . ' técnico para continuar.',
                    'type' => 'error'
                 ]);
                 return;
            }
            $this->paso = 4;
        }
    }

    // Se mantiene saveAssignment solo para validación si se requiere
    public function saveAssignment()
    {
         if ($this->isMinNotMet) {
            $this->dispatch('error-toast', [
                'message' => 'Debes seleccionar al menos ' . self::MIN_PERSONAS . ' técnico para continuar.',
                'type' => 'error'
            ]);
            return;
         }
        
         $this->dispatch('show-toast', [
             'message' => '¡Asignación guardada con éxito!',
             'type' => 'success'
         ]);
    }

    public function saveVisit ()
    {
       // MODIFICACIÓN 3: Validar que la fecha final no exceda los 30 días de la fecha inicial
       // Usamos Carbon para calcular la fecha máxima
       $maxFinalDate = Carbon::parse($this->initDate)
                             ->addDays(self::MAX_DAYS_DIFFERENCE)
                             ->toDateString();
                             
       $todayString = Carbon::today()->toDateString();

       $this->validate([
           'initDate' => [
               'required',
               'date',
               "after_or_equal:{$todayString}",
           ],

           'finalDate' => [
               'required',
               'date',
               'after_or_equal:initDate', 
               "before_or_equal:{$maxFinalDate}", // Nueva regla de 30 días
           ],
       ]);


       try {
            DB::transaction(function () {
                // 1. Crear o actualizar la Visita
                $asignacion = VisitasVisita::updateOrCreate(
                    ['solicitud_id' => $this->selectedSolicitudId], 
                    [
                        'fecha_inicial' => $this->initDate,
                        'fecha_final'  => $this->finalDate,
                        'estatus_id' => 1
                    ]
                );

                // 2. Asignar el Equipo
                // Primero eliminamos registros existentes para esta solicitud_id en VisitaEquipo
                // Esto asegura que la asignación de equipo sea limpia si se está actualizando.
                VisitaEquipo::where('solicitud_id', $this->selectedSolicitudId)->delete();
                
                foreach ($this->personasSelected as $cedula) {
                    VisitaEquipo::create([
                        'solicitud_id' => $this->selectedSolicitudId, 
                        'cedula' => $cedula, 
                    ]);
                }

                // 3. Marcar la Solicitud como asignada
                Solicitud::where('solicitud_id', $this->selectedSolicitudId)
                         ->update(['asignada_visita' => 1]); // Asumo que 1 significa "asignada"
            });

            $this->dispatch('show-toast', [
                'message' => '¡Visita y equipo asignados con éxito! La solicitud ha sido marcada como gestionada.',
                'type' => 'success'
            ]);
            
            // Redirigir o reiniciar el componente
            $this->reset(['paso', 'selectedSolicitudId', 'selectedSolicitud', 'personasSelected', 'initDate', 'finalDate', 'search']);
            $this->resetPage();
        
       } catch (\Exception $e) {
           $errorMessage = 'Ocurrió un error al guardar la asignación.';
           
           // Manejo de error de duplicado (VisitaEquipo) o error general
           if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'Duplicate entry')) {
               $errorMessage = "Error: Intentaste asignar el mismo técnico dos veces. Por favor, revisa la lista."; 
           } else {
               $errorMessage .= ' Verifique la conexión o contacte soporte. (Cód: ' . $e->getCode() . ')';
           }

           $this->dispatch('error-toast', [ 
               'message' => $errorMessage,
               'type' => 'error' 
           ]);
       }
    }
    
    public function render()
    {
        return view('livewire.dashboard.super-admin-visitas.visitas', 
        [
            'solicitudAproved' => $this->solicitudAproved, 
            'visitors' => $this->personalDisponible, 
            'isMinNotMet' => $this->isMinNotMet,
            'isMaxReached' => $this->isMaxReached,
            'selectedCount' => $this->selectedCount
        ]);
    }
}