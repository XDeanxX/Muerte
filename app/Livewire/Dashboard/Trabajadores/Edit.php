<?php

namespace App\Livewire\Dashboard\Trabajadores;

use Livewire\Component;
use App\Models\Trabajador;
use App\Models\Cargo;

class Edit extends Component
{
    //PARA RECIBIR LA CEDULA DESDE LA VISTA DE INDEX
    public $personaCedula;

    //PARA CARGAR LOS DATOS DEL TRABAJADOR
    public $trabajador = [
        'persona_cedula', 
        'cargo_id', 
        'zona_trabajo'
    ];

    //PARA CARGAR DATOS DE LA PERSONA
    public $persona = [];

    //PARA CARGAR TODOS LOS CARGOS
    public $cargos = [];

    //LAS REGLAS DE VALIDACION
    public $rules = [
        'trabajador.zona_trabajo' => 'required|string|max:100',
        'trabajador.cargo_id' => 'required|exists:cargos,cargo_id',
    ];

    public function messages()
    {
        return [
            'trabajador.zona_trabajo.required' => 'La zona de trabajo es obligatoria.',
            'trabajador.zona_trabajo.string' => 'La zona de trabajo debe ser texto.',
            'trabajador.zona_trabajo.max' => 'La zona de trabajo no debe exceder los 100 caracteres.',
            
            'trabajador.cargo_id.required' => 'Debe seleccionar un cargo.',
            'trabajador.cargo_id.exists' => 'El cargo seleccionado no es válido o no existe en la base de datos.',
        ];
    }

    public function mount()
    {
        $trabajadorCargar = Trabajador::with('persona', 'cargo')->where('persona_cedula', $this->personaCedula)->first();

        $this->trabajador = [
            'persona_cedula' => $trabajadorCargar['persona_cedula'], 
            'cargo_id' => $trabajadorCargar['cargo_id'], 
            'zona_trabajo' => $trabajadorCargar['zona_trabajo']
        ];

        $this->persona = $trabajadorCargar->persona;

        $this->cargos = Cargo::all();
    }

    public function update($cedula)
    {
        $trabajador = Trabajador::where('persona_cedula', $cedula)->with('persona')->firstOrFail();

        $persona = $trabajador->persona;

        if (!$persona) {          
            return $this->dispatch('error-toast', [
                'message' => 'No se encontró la información personal asociada al trabajador',
                'type' => 'error'
            ]);
        }

        $this->validate();

        try {
            
            $trabajador->update($this->trabajador);

            $this->regresarAlListado();

            return $this->dispatch('show-toast', [
                'message' => 'Trabajador Actualizado!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            return $this->dispatch('error-toast', [
                'message' => 'Ops, algo salio mal al actualizar la información!',
                'type' => 'error'
            ]);
        }
    }

    public function regresarAlListado()
    {
        $this->dispatch('regresar-a-listado-trabajadores');
    }

    public function render()
    {
        return view('livewire.dashboard.trabajadores.edit');
    }
}
