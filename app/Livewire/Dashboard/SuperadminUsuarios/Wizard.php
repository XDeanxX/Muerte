<?php

namespace App\Livewire\Dashboard\SuperadminUsuarios;

use App\Models\Personas;
use App\Models\Role;
use App\Models\genero;
use App\Models\nacionalidad;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Wizard extends Component
{
    use WithPagination;

    public $wizardStep = 1;
    
    public $isEditMode = false;
    public $editingUserId = null;
    public $originalCedula = null;

    
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
    
    public $role = '';
    public $password = '';
    public $password_confirmation = '';
    public $changePassword = false; 
    
    

    protected function getRulesForStep()
    {
        switch ($this->wizardStep) {
            case 1:
                return [
                    'cedula' => 'required|numeric|digits_between:6,8',
                ];
            case 2:
                $cedulaRule = $this->isEditMode && $this->cedula === $this->originalCedula
                    ? 'required|numeric|digits_between:6,8'
                    : 'required|numeric|digits_between:6,8|unique:personas,cedula';
                
                $emailRule = 'required|email|max:100|unique:personas,email' . ($this->isEditMode ? ',' . $this->originalCedula . ',cedula' : '');

                return [
                    'nombre' => 'required|string|max:50',
                    'segundo_nombre' => 'nullable|string|max:50',
                    'apellido' => 'required|string|max:50',
                    'segundo_apellido' => 'nullable|string|max:50',
                    'nacionalidad' => 'required',
                    'genero' => 'required',
                    'cedula' => $cedulaRule,
                    'email' => $emailRule,
                    'telefono' => 'nullable|string|max:20',
                ];
            case 3:
                if ($this->isEditMode && !$this->changePassword) {
                    return [
                        'role' => 'required|exists:roles,role',
                    ];
                }
                
                return [
                    'role' => 'required|exists:roles,role',
                    'password' => 'required|min:8|regex:/[A-Z]/|regex:/[!@#$%^&*()]/|same:password_confirmation',
                    'password_confirmation' => 'required',
                ];
            default:
                return [];
        }
    }

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio',
        'nombre.max' => 'El nombre no debe superar los 50 caracteres',
        'apellido.required' => 'El apellido es obligatorio',
        'apellido.max' => 'El apellido no debe superar los 50 caracteres',
        'nacionalidad.required' => 'Debes seleccionar una nacionalidad',
        'genero.required' => 'Debes seleccionar un género',
        'cedula.required' => 'La cédula es obligatoria',
        'cedula.unique' => 'Esta cédula ya está registrada',
        'cedula.digits_between' => 'La cédula debe tener entre 6 y 8 dígitos',
        'email.required' => 'El correo electrónico es obligatorio',
        'email.email' => 'Formato de correo inválido',
        'email.unique' => 'Este correo ya está registrado',
        'role.required' => 'Debes seleccionar un rol',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.regex' => 'La contraseña debe contener al menos una mayúscula y un carácter especial',
        'password.same' => 'Las contraseñas no coinciden',
    ];

    public function previousStep()
    {
        if ($this->wizardStep > 1) {
            if ($this->isEditMode && $this->wizardStep === 2) {
                return;
            }
            
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
        $this->dispatch('close'); 
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

    public function savePersonalData()
    {
        $this->validate($this->getRulesForStep());
        
        $this->wizardStep = 3;
        
        $this->dispatch('show-toast', [
            'message' => 'Datos personales validados. Ahora asigna rol y contraseña.',
            'type' => 'success'
        ]);
    }


    public function saveUser()
    {
        if ($this->isEditMode && !$this->changePassword) {
            $this->validate($this->getRulesForStep(), $this->messages);
        } else {
            $this->validate($this->getRulesForStep(), $this->messages);
        }
        
        try {
            \DB::beginTransaction();

            if ($this->isEditMode) {
                $persona = Personas::where('cedula', $this->originalCedula)->first();
                $persona->update([
                    'nombre' => strtolower(trim($this->nombre)),
                    'segundo_nombre' => strtolower(trim($this->segundo_nombre)),
                    'apellido' => strtolower(trim($this->apellido)),
                    'segundo_apellido' => strtolower(trim($this->segundo_apellido)),
                    'email' => strtolower(trim($this->email)),
                    'telefono' => $this->telefono,
                    'genero' => $this->genero,
                    'nacionalidad' => $this->nacionalidad,
                    'direccion' => $this->direccion,
                ]);

                $user = User::where('persona_cedula', $this->originalCedula)->first();
                $userData = ['role' => $this->role];
                
                if ($this->changePassword && !empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }
                
                $user->update($userData);

                \DB::commit();

                $this->dispatch('show-toast', [
                    'message' => 'Usuario actualizado exitosamente',
                    'type' => 'success'
                ]);

            } else {
                if (!$this->personaExists) {
                    $persona = Personas::create([
                        'cedula' => $this->cedula,
                        'nombre' => strtolower(trim($this->nombre)),
                        'segundo_nombre' => strtolower(trim($this->segundo_nombre)),
                        'apellido' => strtolower(trim($this->apellido)),
                        'segundo_apellido' => strtolower(trim($this->segundo_apellido)),
                        'email' => strtolower(trim($this->email)),
                        'telefono' => $this->telefono,
                        'genero' => $this->genero,
                        'nacionalidad' => $this->nacionalidad,
                        'direccion' => $this->direccion,
                    ]);
                }

                $user = User::create([
                    'persona_cedula' => $this->cedula,
                    'role' => $this->role,
                    'password' => Hash::make($this->password),
                ]);

                \DB::commit();

                $this->dispatch('show-toast', [
                    'message' => 'Usuario creado exitosamente. Username: ' . $this->cedula,
                    'type' => 'success'
                ]);
            }

            $this->resetWizard();
            $this->currentStep = 'list';

            $this->dispatch('close'); 
            $this->dispatch('userSaved'); 

        } catch (\Exception $e) {
            \DB::rollBack();
            
            $this->dispatch('error-toast', [
                'message' => 'Error al guardar el usuario: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function resetWizard()
    {
        $this->reset([
            'wizardStep',  'originalCedula','personaExists', 'personaId', 'nombre', 'segundo_nombre', 'apellido', 'segundo_apellido',
            'nacionalidad', 'cedula', 'prefijo_telefono', 'telefono', 'email', 'nacimiento','genero', 'direccion', 'role', 'password', 'password_confirmation', 'changePassword'
        ]);
        $this->currentStep = 'list';
    }


    public function render()
    {
        return view('livewire.dashboard.superadmin-usuarios.wizard', [
            'genre' => genero::all(),
            'nacionality' => nacionalidad::all(),
            'roleList' => Role::all()
        ]);
    }
}
