<?php

namespace App\Livewire\Dashboard\SuperadminUsuarios;

use Livewire\Component;
use App\Models\Personas;
use App\Models\Role;
use App\Models\genero;
use App\Models\nacionalidad;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    // Identificador del usuario a editar
    public $cedulaUser;
    
    public $cedula;
    public $nombre = '';
    public $segundo_nombre = '';
    public $apellido = '';
    public $segundo_apellido = '';
    public $nacionalidad = '';
    public $prefijo_telefono = '0412';
    public $telefono = '';
    public $email = ''; 
    public $nacimiento = '';
    public $genero = '';
    public $direccion = '';
    
    public $role = '';
    public $changePassword = false;
    public $password = '';
    public $password_confirmation = '';

    public $currentUser;
    public $currentPersona;

    protected function rules()
    {
        
        $rules = [
            'nombre' => 'required|string|max:12|min:3',
            'segundo_nombre' => 'nullable|string|max:12|min:3',
            'apellido' => 'required|string|max:12|min:3',
            'segundo_apellido' => 'nullable|string|max:12|min:3',
            'nacionalidad' => 'required|exists:nacionalidads,id',
            
           
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('personas', 'email')->ignore($this->cedulaUser, 'cedula') 
            ],
            
            'telefono' => 'nullable|digits:7', 
            'prefijo_telefono' => 'required|in:0412,0414,0416,0424,0426', 
            'genero' => 'required|in:1,2,3',
            'nacimiento' => 'required|date|before:today|before_or_equal:' . now()->subYears(18)->format('Y-m-d'), 
            'direccion' => 'nullable|string|max:200',
            'role' => 'required|exists:roles,role',
        ];

        if ($this->changePassword) {
            $rules['password'] = [
                'required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[!@#$%^&*()]/', 'confirmed'
            ];
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }

    protected $messages = [
        'nombre.required' => 'El primer nombre es obligatorio.',
        'nombre.min' => 'El nombre debe tener mínimo 3 caracteres.',
        'nombre.max' => 'El nombre no debe superar los 12 caracteres.',
        'segundo_nombre.min' => 'El segundo nombre debe tener mínimo 3 caracteres.',
        'segundo_nombre.max' => 'El segundo nombre no debe superar los 12 caracteres.',
        'apellido.required' => 'El primer apellido es obligatorio.',
        'apellido.min' => 'El apellido debe tener mínimo 3 caracteres.',
        'apellido.max' => 'El apellido no debe superar los 12 caracteres.',
        'segundo_apellido.min' => 'El segundo apellido debe tener mínimo 3 caracteres.',
        'segundo_apellido.max' => 'El segundo apellido no debe superar los 12 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingrese un correo electrónico válido.',
        'email.unique' => 'Este correo ya está registrado por otra persona.',
        'telefono.digits' => 'El teléfono debe tener 7 dígitos.',
        'prefijo_telefono.required' => 'El prefijo telefónico es obligatorio.',
        'prefijo_telefono.in' => 'El prefijo telefónico seleccionado no es válido.',
        'nacionalidad.required' => 'Debe seleccionar una nacionalidad.',
        'nacionalidad.exists' => 'La nacionalidad seleccionada no es válida.',
        'genero.required' => 'Debe seleccionar un género.',
        'genero.in' => 'El género seleccionado no es válido.',
        'nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'nacimiento.date' => 'Ingrese una fecha válida.',
        'nacimiento.before' => 'La fecha debe ser anterior a hoy.',
        'nacimiento.before_or_equal' => 'El usuario debe ser mayor de 18 años.',
        'direccion.max' => 'La dirección es demasiado larga (máx. 200 caracteres).',
        'role.required' => 'Debe seleccionar un rol.',
        'role.exists' => 'El rol seleccionado no es válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
        'password.regex' => 'La contraseña debe contener mayúsculas, números y caracteres especiales.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password_confirmation.required' => 'Debe confirmar la contraseña.',
    ];


    protected function ensureModelsAreLoaded()
    {
        if (is_null($this->currentPersona) && !empty($this->cedulaUser)) {
            $this->currentPersona = Personas::where('cedula', $this->cedulaUser)->first();
        }
        
        if (is_null($this->currentUser) && $this->currentPersona) {
            $this->currentUser = User::where('persona_cedula', $this->currentPersona->cedula)->first();
        }
    }


    public function mount()
    {
        if (empty($this->cedulaUser)) {
            $this->dispatch('error-toast', ['message' => 'Error: Identificador de usuario no proporcionado.', 'type' => 'error']);
            return redirect()->route('superadmin.usuarios.index');
        }

        try {
            $this->currentPersona = Personas::where('cedula', $this->cedulaUser)->firstOrFail();
            $this->currentUser = User::where('persona_cedula', $this->currentPersona->cedula)->firstOrFail();
            
            $this->loadData();
            
        } catch (Exception $e) {
            Log::error('Error en mount (Edit Component): ' . $e->getMessage());
            
            $this->dispatch('error-toast', [
                'message' => 'Error: No se encontró el usuario o persona asociada.',
                'type' => 'error'
            ]);
            
            return redirect()->route('superadmin.usuarios.index'); 
        }
    }

    public function loadData()
    {
        $persona = $this->currentPersona;
        $user = $this->currentUser;
        
        $this->cedula = $persona->cedula ?? '';
        $this->nombre = $persona->nombre ?? '';
        $this->segundo_nombre = $persona->segundo_nombre ?? '';
        $this->apellido = $persona->apellido ?? '';
        $this->segundo_apellido = $persona->segundo_apellido ?? '';
        $this->nacionalidad = $persona->nacionalidad ?? '';
        $this->genero = $persona->genero ?? '';
        $this->direccion = $persona->direccion ?? '';
        
        // El email se carga desde la tabla personas
        $this->email = $persona->email ?? ''; 

        $this->nacimiento = $persona->nacimiento 
            ? (is_string($persona->nacimiento) 
                ? $persona->nacimiento 
                : $persona->nacimiento->format('Y-m-d'))
            : '';

        $fullPhone = preg_replace('/[^0-9]/', '', $persona->telefono ?? '');

        if (strlen($fullPhone) >= 11) {
            $this->prefijo_telefono = substr($fullPhone, 0, 4);
            $this->telefono = substr($fullPhone, 4);
        } else {
             $this->prefijo_telefono = '0412';
             $this->telefono = '';
        }

        $this->role = $user->role ?? '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updateUser()
    {
        $this->ensureModelsAreLoaded();

        if (is_null($this->currentPersona) || is_null($this->currentUser)) {
            Log::error('Intento de actualización fallido: Modelos Personas o Usuario son NULL DESPUÉS del fallback. Cedula: ' . $this->cedulaUser);
            $this->dispatch('error-toast', ['message' => 'Error de registro recargue la página y vuelva a intentarlo.', 'type' => 'error']);
            return; 
        }

        $this->validate();

        try {
            
            $personaData = [
                'nombre' => strtolower(trim($this->nombre)),
                'segundo_nombre' => !empty($this->segundo_nombre) ? strtolower(trim($this->segundo_nombre)) : null,
                'apellido' => strtolower(trim($this->apellido)),
                'segundo_apellido' => !empty($this->segundo_apellido) ? strtolower(trim($this->segundo_apellido)) : null,
                'nacionalidad' => $this->nacionalidad,
                'telefono' => $this->prefijo_telefono . $this->telefono, 
                'genero' => $this->genero,
                'nacimiento' => $this->nacimiento,
                'direccion' => !empty($this->direccion) ? trim($this->direccion) : null,
                'email' => strtolower(trim($this->email)), // Email se actualiza SOLO en Personas
                'updated_at' => now(),
            ];
            
            $this->currentPersona->update($personaData); 

            // ============================================
            // ACTUALIZAR DATOS DE USUARIO
            // Se actualiza el rol y la contraseña, pero NO el email.
            // ============================================
            $userData = [
                // CORRECCIÓN CRÍTICA: Se elimina el email de aquí, ya que no existe en la tabla users
                'role' => $this->role,
                'updated_at' => now(),
            ];

            if ($this->changePassword && !empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $this->currentUser->update($userData);

            $successMessage = '¡Usuario actualizado correctamente!';
            if ($this->changePassword) {
                $successMessage .= ' La contraseña ha sido modificada.';
            }

            $this->dispatch('show-toast', [
                'message' => $successMessage,
                'type' => 'success'
            ]);

            $this->reset(['password', 'password_confirmation', 'changePassword']);

        } catch (Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            
            $this->dispatch('error-toast', [
                'message' => 'Error al actualizar los datos. Intente nuevamente.',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.superadmin-usuarios.edit', [
            'genre' => genero::all(),
            'nacionality' => nacionalidad::all(),
            'roleList' => Role::all()
        ]);
    }
}
