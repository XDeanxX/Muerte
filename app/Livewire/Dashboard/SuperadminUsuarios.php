<?php

namespace App\Livewire\Dashboard;

use Livewire\WithEvents;
use App\Models\Personas;
use App\Models\Role;
use App\Models\genero;
use App\Models\nacionalidad;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use PDF;


class SuperadminUsuarios extends Component
{
    use WithPagination;

    protected $listeners = [
        'close' => 'changeToList',
        'userSaved' => '$refresh',
        'pdf' => 'donwloadPDF'
    
    ];


    public $currentStep = 'list';
    public $currentUser;
    public $wizardStep = 1;
    
    public $editingUser;
    public $isEditMode = true;
    public $editingUserId = null;
    public $originalCedula = null;

    public $viewingUser = null;
        
    public $search = '';
    public $sortField = 'persona_cedula';
    public $sortDirection = 'desc';
    
    public $showDeleteModal = false;
    public $userToDelete = null;
    public $deleteConfirmation = '';
    public $userOnSesion;


  public function changeToList()
    {
        $this->currentStep = 'list';
    }
   

    public function editUser($cedula)
    {
        $user = User::where('persona_cedula', $cedula)->with('persona')->first();
        
        if (!$user || !$user->persona) {
            $this->dispatch('error-toast', [
                'message' => 'Usuario no encontrado',
                'type' => 'error'
            ]);
            return;
        }

        $this->editingUser = $cedula; 
        
        $this->currentStep = 'edit';
    }
    public function viewUser($cedula)
    {
        $user = User::where('persona_cedula', $cedula)->with('persona')->first();
        
        if (!$user || !$user->persona) {
            $this->dispatch('error-toast', [
                'message' => 'Usuario no encontrado',
                'type' => 'error'
            ]);
            return;
        }

        $this->currentUser=
        $this->viewingUser = $user;
        $this->currentStep = 'view';
    }

    public function closeViewUser()
    {
        $this->viewingUser = null;
        $this->currentStep = 'list';
    }

    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getUsuarios()
    {
        $query = User::with('persona')
            ->where(function ($q) {
                $q->where('persona_cedula', 'like', '%' . $this->search . '%')
                  ->orWhereHas('persona', function ($subQuery) {
                      $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });

        if ($this->sortField === 'nombre') {
            $query->join('personas', 'users.persona_cedula', '=', 'personas.cedula')
                  ->orderBy('personas.nombre', $this->sortDirection)
                  ->select('users.*');
        } elseif ($this->sortField === 'role') {
            $query->orderBy('role', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate(5);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    
    public function confirmDelete($cedula){
      
        $this->userOnSesion = Auth()->user()->persona_cedula;
    if ($this->userOnSesion === $cedula) {
        $this->dispatch('error-toast', [
            'message' => 'No puedes eliminar al usuario de la sesión actual.',
            'type' => 'error'
        ]);
        return;
    }

    $this->userToDelete = User::where('persona_cedula', $cedula)->with('persona')->first();
    
    if (!$this->userToDelete) {
        $this->dispatch('error-toast', [
            'message' => 'Error al encontrar al usuario.',
            'type' => 'error'
        ]);
        return;
    }

    try {
        $this->userToDelete->delete(); 

        $this->dispatch('show-toast', [
            'message' => 'Usuario eliminado exitosamente.',
            'type' => 'success'
        ]);

        $this->closeDeleteModal();
        
    } catch (\Exception $e) {
        $this->dispatch('error-toast', [
            'message' => 'Error al eliminar: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
    }

    public function deleteUser()
    {
        if ($this->deleteConfirmation !== $this->userToDelete->persona_cedula) {
            $this->dispatch('error-toast', [
                'message' => 'La cédula ingresada no coincide',
                'type' => 'error'
            ]);
            return;
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
        $this->deleteConfirmation = '';
    }

    public function exportPdf()
    {
        $pdfChunks = new Collection();
        $page = 1;
        
        $query = User::with('persona')->orderBy('persona_cedula', 'asc');
        
        $query->chunk(10, function (Collection $users) use (&$pdfChunks, &$page) {
            $html = view('pdf.tabla-chunk', [
                'usuarios' => $users, 
                'page' => $page,
            ])->render();

            $pdfChunks->push($html);
            $page++;
        });

        $pdf = Pdf::loadView('pdf.reporte-base', [
            'chunks' => $pdfChunks->implode(''), 
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Reporte PDF generado exitosamente',
            'type' => 'success'
        ]);
        
        $filename = 'reporte_usuarios_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
    
    public function exportExcel()
    {
        $users = User::with('persona')->get()->map(function ($user) {
            return [
                'Cédula' => $user->persona_cedula,
                'Primer Nombre' => $user->persona->nombre ?? '',
                'Segundo Nombre' => $user->persona->segundo_nombre ?? '',
                'Primer Apellido' => $user->persona->apellido ?? '',
                'Segundo Apellido' => $user->persona->segundo_apellido ?? '',
                'Teléfono' => $user->persona->telefono ?? 'N/A',
                'Email' => $user->persona->email ?? 'N/A',
                'Dirección' => $user->persona->direccion ?? 'N/A',
                'Fecha Creación' => $user->created_at->format('d-m-Y H:i'),
                'Rol' => $user->getRoleName(),
            ];
        });

        $this->dispatch('show-toast', [
            'message' => 'Exportación a Excel completada',
            'type' => 'success'
        ]);

        return (new FastExcel($users))->download('reporte_usuarios_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function donwloadPDF($cedula)
    {
        $persona = Personas::where('cedula', $cedula)->first();

        if (!$persona) {
            $this->dispatch('error-toast', [
                'message' => 'Persona no encontrada',
                'type' => 'error'
            ]);
            return;
        }

        $pdf = Pdf::loadView('pdf.detalle-usuario', compact('persona'));
        
        $filename = 'detalles_' . $cedula . '.pdf';

        $this->dispatch('show-toast', [
            'message' => 'Datos de ' . $cedula . ' descargados',
            'type' => 'success'
        ]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {


        return view('livewire.dashboard.superadmin-usuarios', [
            'usuarios' => $this->getUsuarios(),
          
        ])->layout('components.layouts.rbac');
    }
}