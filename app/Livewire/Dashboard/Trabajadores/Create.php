<?php

namespace App\Livewire\Dashboard\Trabajadores;

use Livewire\Component;
use App\Models\Trabajador;
use App\Models\Personas;
use App\Models\Cargo;

class Create extends Component
{
    //ESTA VARIABLE ES PARA CAMBIAR EL PASO (PASO 1, PASO 2, PASO 3)
    public $avanzarPaso = 1;

    //VARIABLE PARA BUSCAR LA CEDULA EN EL PASO 1
    public $cedula_busqueda = '';
    public $personaEncontrada = '';
    public $iniciarRegistro = false;

    //AQUI CARGARE TODOS LOS CARGOS
    public $cargos = [];

    //AQUI GUARDARE LOS DATOS DE LA PERSONA
    public $persona = [
        'nombre' => '',
        'apellido' => '',
        'segundo_nombre' => '',
        'segundo_apellido' => '',
        'nacionalidad' => '',
        'genero' => '',
        'cedula' => '',
        'nacimiento' => '',
        'direccion' => '',
        'telefono' => '',
        'prefijo' => '',
        'email' => ''
    ];

    //AQUI GUARDARE LOS DATOS DE TRABAJADOR
    public $trabajador = [
        'persona_cedula' => '', 
        'cargo_id' => '', 
        'zona_trabajo' => ''
    ];

    //LAS REGLAS DE VALIDACION
    public function rules()
    {
        if ($this->avanzarPaso === 3) {
            return [
                'trabajador.persona_cedula' => 'required|digits_between:7,15',
                'trabajador.cargo_id' => 'required|exists:cargos,cargo_id', 
                'trabajador.zona_trabajo' => 'nullable|string|max:191',
            ];
        }

        if ($this->avanzarPaso === 2) {
            return [            
                'persona.cedula' => 'required|digits_between:7,15|unique:personas,cedula', 
                'persona.nombre' => 'required|string|max:50',
                'persona.apellido' => 'required|string|max:50', 
                'persona.segundo_nombre' => 'nullable|string|max:50', 
                'persona.segundo_apellido' => 'nullable|string|max:50',
                'persona.nacionalidad' => 'required|string|max:2', 
                'persona.genero' => 'required|string|max:1',     
                'persona.nacimiento' => 'required|date|before_or_equal:' . \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'),
                'persona.direccion' => 'nullable|string|max:255',
                'persona.telefono' => 'required|string|max:20', 
                'persona.prefijo' => 'required',
                'persona.email' => 'nullable|email|max:100|unique:personas,email',

                'trabajador.cargo_id' => 'required|exists:cargos,cargo_id', 
                'trabajador.zona_trabajo' => 'nullable|string|max:191',
            ];
        }
    }

    public function messages()
    {
        return [
            'trabajador.cargo_id.required' => 'Debe seleccionar un cargo para el trabajador.',
            'trabajador.cargo_id.exists' => 'El cargo seleccionado no es válido o no existe.',
            'trabajador.zona_trabajo.max' => 'La zona de trabajo no debe exceder los 191 caracteres.',

            
            'persona.cedula.required' => 'La cédula de identidad es obligatoria.',
            'persona.cedula.digits_between' => 'La cédula debe tener entre 7 y 15 dígitos.',
            'persona.cedula.unique' => 'Esta cédula ya está registrada en el sistema.',
            
            'persona.nombre.required' => 'El primer nombre es obligatorio.',
            'persona.nombre.max' => 'El primer nombre no debe exceder los 50 caracteres.',
            'persona.apellido.required' => 'El apellido es obligatorio.',
            'persona.apellido.max' => 'El apellido no debe exceder los 50 caracteres.',
            'persona.segundo_nombre.max' => 'El segundo nombre no debe exceder los 50 caracteres.',
            'persona.segundo_apellido.max' => 'El segundo apellido no debe exceder los 50 caracteres.',
            
            'persona.nacionalidad.required' => 'Debe seleccionar la nacionalidad.',
            'persona.genero.required' => 'Debe seleccionar el género.',
            
            'persona.nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'persona.nacimiento.date' => 'La fecha de nacimiento no es una fecha válida.',
            'persona.nacimiento.before_or_equal' => 'El trabajador debe ser mayor de 18 años para ser registrado.',
            
            'persona.telefono.required' => 'El número de teléfono es obligatorio.',
            'persona.telefono.max' => 'El teléfono no debe exceder los 20 caracteres.',
            'persona.direccion.max' => 'La dirección no debe exceder los 255 caracteres.',
            'persona.prefijo.required' => 'El prefijo telefónico es obligatorio.',
            
            'persona.email.email' => 'El correo electrónico no tiene un formato válido.',
            'persona.email.max' => 'El correo electrónico no debe exceder los 100 caracteres.',
            'persona.email.unique' => 'Este correo electrónico ya está asociado a otra persona.',

            'trabajador.persona_cedula.required' => 'La cédula del trabajador es obligatoria para la asignación.',
            'trabajador.persona_cedula.digits_between' => 'La cédula del trabajador debe tener entre 7 y 15 dígitos.',
        ];
    }

    public function buscarPersona()
    { 
        $persona = Personas::where('cedula', $this->cedula_busqueda)->first();

        $this->cargos = Cargo::orderBy('descripcion')->get();

        if ($persona) {

            $trabajador_existente = Trabajador::firstWhere('persona_cedula', $persona->cedula);
            
            if ($trabajador_existente) {
                
                //ES UN TRABAJADOR
                $nombre_completo = $persona->nombre . ' ' . $persona->apellido;

                $this->personaEncontrada = $persona;

                return $this->avanzarPaso = 4;
            }

            //PERSONA EXISTE PERO NO ES TRABAJADOR
            $this->personaEncontrada = $persona;
            $this->iniciarRegistro = true;
            $this->trabajador['persona_cedula'] = $this->cedula_busqueda;

            return $this->avanzarPaso = 3;

        } else { 

            //PERSONA NO EXISTE
            $this->personaEncontrada = $persona;
            $this->iniciarRegistro = true;
            $this->persona['cedula'] = $this->cedula_busqueda;

            return $this->avanzarPaso = 2;
        }
    }

    public function store()
    {
        $this->validate();

        try{
            //PERSONA EXISTE PERO NO ES TRABAJADOR
            if ($this->avanzarPaso === 3){

                Trabajador::create($this->trabajador);

                $this->trabajador = [
                    'persona_cedula' => '', 
                    'cargo_id' => '', 
                    'zona_trabajo' => ''
                ];

                $this->dispatch('regresar-a-listado-trabajadores');

                $this->RegresarAlPaso1();

                return $this->dispatch('show-toast', [
                    'message' => 'Trabajador Registrado!',
                    'type' => 'success'
                ]);


            }

            //PERSONA NO EXISTE
            if ($this->avanzarPaso === 2) {
                
                Personas::create([
                    'nombre' => $this->persona['nombre'],
                    'apellido' => $this->persona['apellido'],
                    'segundo_nombre' => $this->persona['segundo_nombre'],
                    'segundo_apellido' => $this->persona['segundo_apellido'],
                    'nacionalidad' => $this->persona['nacionalidad'],
                    'genero' => $this->persona['genero'],
                    'cedula' => $this->persona['cedula'],
                    'nacimiento' => $this->persona['nacimiento'],
                    'direccion' => $this->persona['direccion'],
                    'telefono' => $this->persona['prefijo'] . '-' . $this->persona['telefono'],
                    'email' => $this->persona['email']
                ]);

                Trabajador::create([
                    'persona_cedula' => $this->persona['cedula'], 
                    'cargo_id' => $this->trabajador['cargo_id'], 
                    'zona_trabajo' => $this->trabajador['zona_trabajo']
                ]);

                $this->persona = [
                    'nombre' => '',
                    'apellido' => '',
                    'segundo_nombre' => '',
                    'segundo_apellido' => '',
                    'nacionalidad' => '',
                    'genero' => '',
                    'cedula' => '',
                    'nacimiento' => '',
                    'direccion' => '',
                    'telefono' => '',
                    'prefijo' => '',
                    'email' => ''
                ];

                $this->trabajador = [
                    'persona_cedula' => '', 
                    'cargo_id' => '', 
                    'zona_trabajo' => ''
                ];

                $this->dispatch('regresar-a-listado-trabajadores');

                $this->RegresarAlPaso1();

                return $this->dispatch('show-toast', [
                    'message' => 'Trabajador Registrado!',
                    'type' => 'success'
                ]);
            }

        } catch (\Exception $e) {
            return $this->dispatch('error-toast', [
                'message' => 'Ops, algo salio mal!',
                'type' => 'error'
            ]);
        }
    }

    //ESTA FUNCION ES PARA REGRESAR AL PASO 1
    public function RegresarAlPaso1()
    {
        $this->avanzarPaso = 1;

        $this->personaEncontrada = '';
    }

    public function render()
    {
        return view('livewire.dashboard.trabajadores.create');
    }
}
