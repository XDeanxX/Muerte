<?php

namespace App\Livewire\Dashboard;

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

class AdministradoUsuarios extends Component
{    use WithPagination;

    // Wizard Steps Control
    public $currentStep = 'list';
    public $wizardStep = 1;
    
    // Edit Mode
    public $isEditMode = false;
    public $editingUserId = null;
    public $originalCedula = null;
    
    // View Mode
    public $viewingUser = null;
    
    // Persona Data
    public $personaExists = false;
    public $personaId = null;
    public $nombre = '';
    public $segundo_nombre = '';
    public $apellido = '';
    public $segundo_apellido = '';
    public $nacionalidad = '';
    public $cedula = '';
    public $prefijo_telefono = '';
    public $telefono = '';
    public $email = '';
    public $nacimiento = '';
    public $genero = '';
    public $direccion = '';
    
    // User Data
    public $role = '';
    public $password = '';
    public $password_confirmation = '';
    public $changePassword = false; // Para edición
    
    // Table Controls
    public $search = '';
    public $sortField = 'persona_cedula';
    public $sortDirection = 'desc';
    
    // Delete Modal
    public $showDeleteModal = false;


    
    public function startWizard()
    {
        $this->resetWizard();
        $this->currentStep = 'wizard';
        $this->wizardStep = 1;
        $this->isEditMode = false;
    }

    public function searchUserByCedula()
    {
        $this->validate($this->getRulesForStep());

        $persona = Personas::where('cedula', $this->cedula)->first();
        $user = User::where('persona_cedula', $this->cedula)->first();

        if ($persona && $user) {
            $this->dispatch('error-toast', [
                'message' => 'Esta persona ya tiene una cuenta de usuario activa',
                'type' => 'error'
            ]);
            return;
        }

        if ($persona && !$user) {
            $this->loadPersonaData($persona);
            $this->personaExists = true;
            $this->personaId = $persona->id;
            $this->wizardStep = 3;
            
            $this->dispatch('show-toast', [
                'message' => 'Persona encontrada. Asigna rol y contraseña para crear el usuario.',
                'type' => 'info'
            ]);
            return;
        }

        $this->personaExists = false;
        $this->wizardStep = 2;
        
        $this->dispatch('show-toast', [
            'message' => 'Persona no encontrada. Completa los datos personales.',
            'type' => 'info'
        ]);
    }

    private function loadPersonaData($persona)
    {
        $this->nombre = $persona->nombre;
        $this->segundo_nombre = $persona->segundo_nombre;
        $this->apellido = $persona->apellido;
        $this->segundo_apellido = $persona->segundo_apellido;
        $this->nacionalidad = $persona->nacionalidad;
        $this->telefono = $persona->telefono;
        $this->email = $persona->email;
        $this->nacimiento = $persona->nacimiento;
        $this->genero = $persona->genero;
        $this->direccion = $persona->direccion;
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

        $this->viewingUser = $user;
        $this->currentStep = 'view';
    }

    public function closeViewUser()
    {
        $this->viewingUser = null;
        $this->currentStep = 'list';
    }

    
    public function previousStep()
    {
        if ($this->wizardStep > 1) {
            if ($this->isEditMode && $this->wizardStep === 2) {
                return;
            }
            
            // Si estamos en step 3 y la persona ya existía, volver a step 1
            if ($this->wizardStep === 3 && $this->personaExists && !$this->isEditMode) {
                $this->wizardStep = 1;
            } else {
                $this->wizardStep--;
            }
        }
    }

    public function cancelWizard()
    {
        $this->resetWizard();
        $this->currentStep = 'list';
    }

    private function resetWizard()
    {
        $this->wizardStep = 1;
        $this->personaExists = false;
        $this->personaId = null;
        $this->isEditMode = false;
        $this->editingUserId = null;
        $this->originalCedula = null;
        $this->changePassword = false;
        $this->reset([
            'nombre', 'segundo_nombre', 'apellido', 'segundo_apellido',
            'nacionalidad', 'cedula', 'telefono', 'email',
            'nacimiento', 'genero', 'direccion', 'role',
            'password', 'password_confirmation'
        ]);
        $this->resetValidation();
    }

    // ======================
    // TABLE FUNCTIONALITY
    // ======================
    
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

        // Apply sorting
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


    public function render()
    {
        $roleList = Role::all();
        $genre = genero::all();
        $nacionality = nacionalidad::all();

        return view('livewire.dashboard.administrado-usuarios', [
            'usuarios' => $this->getUsuarios(),
            'genre' => $genre,
            'nacionality' => $nacionality,
            'roleList' => $roleList
        ])->layout('components.layouts.rbac');
    }
}
