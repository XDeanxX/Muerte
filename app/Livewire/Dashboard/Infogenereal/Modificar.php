<?php

namespace App\Livewire\Dashboard\Infogenereal;

use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\User;
use App\Models\Personas;
use App\Models\nacionalidad;
use Carbon\Carbon; // Asegúrate de que Carbon está importado

class Modificar extends Component
{
    public $nombre;
    public $segundo_nombre;
    public $apellido;
    public $segundo_apellido;
    public $email;
    public $telefono;
    public $nacionalidad;
    public $genero;
    public $nacimiento;
    public $direccion;
    public $prefijo_telefono;
    public $ageError = '';

    public $userCedula;
    
    // Almacena el objeto Persona cargado en mount
    protected $personaModel; 
    
    // Usamos una función para definir las reglas para poder usar $this->userCedula en la regla 'unique'
    protected function rules()
    {
        // Se mantiene la regla de unicidad del email, excluyendo la cédula actual
        $cedulaToExclude = $this->userCedula; 

        return [
            'nombre' => 'required|string|max:12|min:3',
            'apellido' => 'required|string|max:12|min:3',
            'email' => [
                'required', 
                'email', 
                'max:100', 
                Rule::unique('personas', 'email')->ignore($cedulaToExclude, 'cedula'),
            ], 
            'telefono' => 'nullable|regex:/^\d{3}-\d{4}$/',
            'genero' => 'required|in:1,2,3', 
            'nacimiento' => 'required|date',
            'direccion' => 'nullable|string|max:100',
            'segundo_nombre' => 'required|string|max:12|min:3',
            'segundo_apellido' => 'required|string|max:12|min:3',
        ];
    }

    protected $messages = [
        'nacimiento.required' => 'Fecha de nacimiento requerida',
        // ... otros mensajes se mantienen
        'nombre.required' => 'El primer nombre es obligatorio.',
        'nombre.string' => 'El nombre debe ser texto.',
        'nombre.max' => 'El nombre no debe superar los 12 caracteres.',
        'nombre.min' => 'El nombre debe contener mínimo 3 caracteres.',
        'segundo_nombre.required' => 'El segundo nombre es obligatorio.',
        'segundo_nombre.string' => 'El segundo nombre debe ser texto.',
        'segundo_nombre.max' => 'El segundo nombre no debe superar los 12 caracteres.',
        'segundo_nombre.min' => 'El segundo nombre debe contener mínimo 3 caracteres.',
        'apellido.required' => 'El primer apellido es obligatorio.',
        'apellido.string' => 'El apellido debe ser texto.',
        'apellido.max' => 'El apellido no debe superar los 12 caracteres.',
        'apellido.min' => 'El apellido debe tener mínimo 3 caracteres.',
        'segundo_apellido.required' => 'El segundo apellido es obligatorio.',
        'segundo_apellido.string' => 'El segundo apellido debe ser texto.',
        'segundo_apellido.max' => 'El segundo apellido no debe superar los 12 caracteres.',
        'segundo_apellido.min' => 'El segundo apellido debe contener mínimo 3 caracteres.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El campo debe contener un correo electrónico válido.',
        'email.unique' => 'Este correo electrónico ya está registrado por otro usuario.',
        'telefono.regex' => 'Ingrese un numero telefónico válido (formato ###-####).', 
        'genero.required' => 'Debe seleccionar una opción de género.',
        'genero.in' => 'El valor de género seleccionado no es válido.',
        'nacimiento.date' => 'La fecha de nacimiento no es válida.',
        'direccion.string' => 'La dirección debe ser texto.',
        'direccion.max' => 'La dirección es demasiado larga (máx. 100 caracteres).',
    ];

    public function mount($userCedula)
    {
        $this->userCedula = $userCedula;
        $persona = Personas::where('cedula', $this->userCedula)->first();
        
        if(!$persona){
            $this->dispatch('error-toast', [
            'message' => 'Error en cargar los datos',
            'type' => 'error'
            ]);
            return;
        }
        $this->personaModel = $persona;
        $this->loadData($persona);
    }

    /**
     * Mantenemos esta función para precargar los datos, pero ya no la usamos para validación de edad
     */
    public function validateAge() 
    {
        // Esta función se vacía porque la lógica se mueve al método modify() para un mejor control
        return; 
    }


    public function loadData($persona){
        
        $this->nombre = $persona->nombre;
        $this->segundo_nombre = $persona->segundo_nombre;
        $this->apellido = $persona->apellido;
        $this->segundo_apellido = $persona->segundo_apellido;
        $this->nacionalidad = nacionalidad::where('id' , $persona->nacionalidad)->first();
        $this->telefono = substr($persona->telefono , 4);
        $this->email = $persona->email;
        $this->nacimiento = Carbon::parse($persona->nacimiento)->format('Y-m-d'); // Usamos Carbon
        $this->genero = $persona->genero;
        $this->direccion = $persona->direccion;
        $this->prefijo_telefono=substr($persona->telefono , 0 ,4);

    }

    public function modify(){

        $this->validate();
        
        // **INICIO: Lógica de validación de edad integrada**
        if (!empty($this->nacimiento)) {
            try {
                // Calcular si el usuario tiene al menos 18 años
                $age = Carbon::parse($this->nacimiento)->diffInYears(Carbon::now());

                if ($age < 18) {
                    $this->addError('nacimiento', 'El usuario debe ser mayor de 18 años.');
                    return; // Detener la ejecución si es menor de 18
                }
            } catch (\Exception $e) {
                $this->addError('nacimiento', 'Formato de fecha de nacimiento inválido.');
                return; // Detener la ejecución si hay error en la fecha
            }
        }
        // **FIN: Lógica de validación de edad integrada**

        try {
            $persona = $this->personaModel ?? Personas::where('cedula', $this->userCedula)->firstOrFail();
            
            $persona->update([
                'nombre' => strtolower(trim($this->nombre)),
                'segundo_nombre' => strtolower(trim($this->segundo_nombre)),
                'apellido' => strtolower(trim($this->apellido)),
                'segundo_apellido' => strtolower(trim($this->segundo_apellido)),
                'email' => strtolower(trim($this->email)),
                'telefono' => $this->prefijo_telefono . ' ' . $this->telefono,
                'genero' => $this->genero,
                'nacimiento' => $this->nacimiento ?? null,
                'direccion' => $this->direccion ?? null,
                'updated_at' => now(),
            ]);

            $this->dispatch('show-toast', [
                'message' => 'Usuario actualizado exitosamente',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
             $this->dispatch('error-toast', [
                'message' => 'Ocurrió un error al actualizar los datos.' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    { 
        $this->rules();
        return view('livewire.dashboard.infogenereal.modificar');
    }
}