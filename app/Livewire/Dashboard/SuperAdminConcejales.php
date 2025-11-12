<?php

namespace App\Livewire\Dashboard;

use App\Models\Concejales;
use App\Models\genero;
use App\Models\nacionalidad;
use App\Models\Personas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdminConcejales extends Component
{
    use WithPagination;

    // pagination theme
    protected $paginationTheme = 'disenoPagination'; 

    public $search = '', $sort = 'created_at', $direction = 'desc'; 
    public $activeTab = 'create', $openForm = 'list';
    public $buscarCedula = '', $mensajeCedula = '';

    public $validacion = 0;
    public $editingConcejal = '';
    public $showConcejal = '';
    public $deleteConcejal = '';

    public $nacionalidad = '';
    public $genero = '';

    public $concejal = [
        'persona_cedula' => '',
        'cargo_concejal' => '',
    ];

    public $persona = [
        'cedula' => '',
        'nombre' => '',
        'segundo_nombre' => '',
        'apellido' => '',
        'segundo_apellido' => '',
        'email' => '',
        'telefono' => '',
        'genero' => '',
        'nacionalidad' => '',
        'direccion' => '',
    ];

    protected function rules(){
        switch($this->validacion){
            case 1:
                return [
                    'concejal.cargo_concejal' => 'required|max:100',
                ];
                break;
            case 2:
                return [
                    'persona.nombre' => 'required|string|max:50',
                    'persona.segundo_nombre' => 'nullable|string|max:50',
                    'persona.apellido' => 'required|string|max:50',
                    'persona.segundo_apellido' => 'nullable|string|max:50',
                    'persona.nacionalidad' => 'required',
                    'persona.genero' => 'required',
                    'persona.cedula' => 'required|numeric|unique:personas,cedula',
                    'persona.email' => 'required|email|max:100|unique:personas,email',
                    'persona.telefono' => 'nullable|string|max:20',
                ];
                break;
        }
    }

    protected $messages = [
        'concejal.persona_cedula.required' => 'La cédula es requerida',
        'concejal.persona_cedula.numeric' => 'La cédula debe ser numerica',
        'concejal.persona_cedula.exists' => 'La cédula no existe en la base de datos',

        'concejal.cargo_concejal.required' => 'El cargo es obligatorio',
        'concejal.cargo_concejal.max' => 'El cargo no debe superar los 50 caracteres',

        'persona.nombre.required' => 'El nombre es obligatorio',
        'persona.nombre.max' => 'El nombre no debe superar los 50 caracteres',
        'persona.segundo_nombre.required' => 'El nombre es obligatorio',
        'persona.segundo_nombre.max' => 'El nombre no debe superar los 50 caracteres',

        'persona.apellido.required' => 'El apellido es obligatorio',
        'persona.apellido.max' => 'El apellido no debe superar los 50 caracteres',
        'persona.segundo_apellido.required' => 'El apellido es obligatorio',
        'persona.segundo_apellido.max' => 'El apellido no debe superar los 50 caracteres',

        'persona.nacionalidad.required' => 'Debes seleccionar una nacionalidad',

        'persona.genero.required' => 'Debes seleccionar un género',

        'persona.cedula.required' => 'La cédula es obligatoria',
        'persona.cedula.unique' => 'Esta cédula ya está registrada',
        'persona.cedula.digits_between' => 'La cédula debe tener entre 6 y 8 dígitos',

        'persona.email.required' => 'El correo electrónico es obligatorio',
        'persona.email.email' => 'Formato de correo inválido',
        'persona.email.unique' => 'Este correo ya está registrado',
    ];

    private function loadConcejal(){
        $concejal = Concejales::with('persona')->orderBy($this->sort, $this->direction);

        if($this->search){
            $concejal->where(function ($q) {
                    $q->where('cargo_concejal', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('persona', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orwhere('apellido', 'like', '%' . $this->search . '%');
                });
        }

        if (strpos($this->sort, '.') !== false) {
            list($table, $column) = explode('.', $this->sort);
        
            $concejal->leftJoin($table . 's', $table . 's.cedula', '=', 'concejales.' . $table . '_cedula')
                ->select('concejales.*')
                ->orderBy($table . 's.' . $column, $this->direction);
        } else {
            $concejal->orderBy($this->sort, $this->direction);
        }
        
        return $concejal->paginate(10)->onEachSide(-1);
    }

    public function buscarPersona()
    {
        if(strlen($this->buscarCedula) >= 7){

            if(Concejales::where('persona_cedula', $this->buscarCedula)->exists()){
                $persona = Personas::where('cedula', $this->buscarCedula)->first();

                $this->persona = [
                    'cedula' => $persona->cedula,
                    'nombre' => $persona->nombre,
                    'apellido' => $persona->apellido,
                    'nacionalidad' => $persona->nacionalidad,
                ];

                $this->mensajeCedula = 1;
                return;
            }

            if(!Concejales::where('persona_cedula', $this->buscarCedula)->exists() && Personas::where('cedula', $this->buscarCedula)->exists()){

                $persona = Personas::where('cedula', $this->buscarCedula)->first();

                $this->persona = [
                    'cedula' => $persona->cedula,
                    'nombre' => $persona->nombre,
                    'apellido' => $persona->apellido,
                    'nacionalidad' => $persona->nacionalidad,
                ];

                $this->mensajeCedula = 2;
                return;

            }

            if(!Personas::where('cedula', $this->buscarCedula)->exists()){
                $this->mensajeCedula = 3;
                return;
            }

        }else{
            $this->mensajeCedula = '';
        }


    }

    public function openFormPersona()
    {
        $this->resetForm();

        $this->persona = [
            'cedula' => $this->buscarCedula,
        ];

        $this->concejal = [
            'persona_cedula' => $this->buscarCedula,
            'cargo_concejal' => '',
        ];

        $this->nacionalidad = nacionalidad::all();
        $this->genero = genero::all();

        $this->openForm = 'create';
    }

    public function closeFormPersona()
    {
        $this->resetForm();

        $this->openForm = 'list';
    }


    public function submit(){


        try {
            
            $this->validacion = 1;
            $this->validate();

            if ($this->editingConcejal && Auth::user()->isSuperAdministrador()) {

                $concejal = Concejales::find($this->editingConcejal);

                $concejal->update([
                    'cargo_concejal' => Str::title($this->concejal['cargo_concejal']),
                ]);

                $this->editingConcejal = '';
                $this->resetForm();
                $this->loadConcejal();

                $this->dispatch('show-toast', [
                    'message' => 'Concejal actualizada exitosamente',
                    'type' => 'success'
                ]);

            }else{

                if($this->openForm === 'list'){
                    
                    $this->validacion = 1;
                    $this->validate();

                    Concejales::create([
                        'persona_cedula' => $this->buscarCedula,
                        'cargo_concejal' => Str::title($this->concejal['cargo_concejal']),
                    ]);
                    
                    $this->buscarCedula = '';
                    $this->resetForm();
                    $this->loadConcejal();

                    $this->dispatch('show-toast', [
                        'message' => 'Concejal creado exitosamente',
                        'type' => 'success'
                    ]);
                }

                if($this->openForm === 'create'){

                    $this->validacion = 2;
                    $this->validate();

                    $exists = Personas::where('cedula', $this->persona['cedula'])->first();

                    if (!$exists) {
                        Personas::create([
                            'cedula' => $this->persona['cedula'],
                            'nombre' => strtolower(trim($this->persona['nombre'])),
                            'segundo_nombre' => strtolower(trim($this->persona['segundo_nombre'])),
                            'apellido' => strtolower(trim($this->persona['apellido'])),
                            'segundo_apellido' => strtolower(trim($this->persona['segundo_apellido'])),
                            'email' => strtolower(trim($this->persona['email'])),
                            'telefono' => $this->persona['telefono'],
                            'genero' => $this->persona['genero'],
                            'nacionalidad' => $this->persona['nacionalidad'],
                            'direccion' => $this->persona['direccion'],
                        ]);

                    }else{
                        $this->dispatch('show-toast', [
                            'message' => 'El concejal existe',
                            'type' => 'error'
                        ]);
                        return;
                    }

                    Concejales::create([
                        'persona_cedula' => $this->persona['cedula'],
                        'cargo_concejal' => Str::title($this->concejal['cargo_concejal']),
                    ]);

                    $this->dispatch('show-toast', [
                        'message' => 'Concejal creado exitosamente',
                        'type' => 'success'
                    ]);

                    $this->resetForm();
                    $this->openForm = 'list';
                }
            }
        }catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al procesar el Concejal: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function editConcejal($concejal_id){

        $concejal = Concejales::with('persona')->find($concejal_id);

        if(!$concejal){            
            $this->dispatch('show-toast', [
                'message' => 'Concejal no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();

        $this->editingConcejal = $concejal['persona_cedula'];

        $this->persona = [
            'cedula' => $concejal->persona->cedula,
            'nombre' => $concejal->persona->nombre,
            'apellido' => $concejal->persona->apellido,
            'nacionalidad' => $concejal->persona->nacionalidad,
        ];

        $this->concejal = [
            'cargo_concejal' => $concejal['cargo_concejal'],
        ];
    }

    public function viewConcejal($concejal_id){

        $concejal = Concejales::with('persona')->find($concejal_id);

        if(!$concejal){
            $this->dispatch('show-toast', [
                'message' => 'Concejal no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();
        
        $this->showConcejal = $concejal;

        $this->activeTab = 'show';
    }

    public function confirmDelete($concejal_id)
    {
        $concejal = Concejales::with('persona')->find($concejal_id);
        
        if (!$concejal) {
            $this->dispatch('show-toast', [
                'message' => 'Concejal no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->deleteConcejal = $concejal;
    }

    public function deleteConcejalDefinitive()
    {
        $concejal = Concejales::find($this->deleteConcejal['persona_cedula']);
        
        if (!$concejal) {
            $this->dispatch('show-toast', [
                'message' => 'Concejal no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        try {
            
            if ($concejal && Auth::user()->isSuperAdministrador()) {

                $concejal->delete();
                
                $this->dispatch('show-toast', [
                    'message' => 'Concejal eliminada exitosamente',
                    'type' => 'success'
                ]);
                
                $this->deleteConcejal = null;
                $this->resetForm();
                $this->loadConcejal();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al eliminar el Concejal: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->deleteConcejal = null;
    }

    public function resetForm(){
        $this->concejal = [
            'persona_cedula' => '',
            'cargo_concejal' => '',
        ];

        $this->persona = [
            'cedula' => '',
            'nombre' => '',
            'segundo_nombre' => '',
            'apellido' => '',
            'segundo_apellido' => '',
            'email' => '',
            'telefono' => '',
            'genero' => '',
            'nacionalidad' => '',
            'direccion' => '',
        ];

        $this->activeTab = 'create';
        $this->validacion = '';

        $this->editingConcejal = '';
        $this->showConcejal = '';
        $this->deleteConcejal = '';

        $this->resetValidation();
    }

    public function orden($sort)
    {
        if ($this->sort == $sort) {
            $this->direction = ($this->direction == 'asc') ? 'desc' : 'asc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function render()
    {
        if($this->activeTab === 'create'){
            $this->buscarPersona();
        }

        $concejalsRender = $this->loadConcejal();

        return view('livewire.dashboard.super-admin-concejales', [
            'concejalsRender' => $concejalsRender,    
        ])->layout('components.layouts.rbac');
    }
}
