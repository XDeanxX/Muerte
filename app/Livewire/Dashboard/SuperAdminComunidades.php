<?php

namespace App\Livewire\Dashboard;

use App\Models\Comunidades;
use App\Models\Parroquias;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class SuperAdminComunidades extends Component
{
    use WithPagination;

    protected $paginationTheme = 'disenoPagination'; 

    public $cambiarVistaParroquia= false;

    public $validacion = '';

    //VISTA SUBCATEGORIAS
    public $activeTab = 'create';
    public $search = '', $sort = 'created_at', $direction = 'desc'; 

    public $editingCom = '';
    public $showCom = '';
    public $deleteCom = '';

    public $parroquias = '';
    public $comunidad = [
        'comunidad' => '',
        'parroquia' => ''
    ];

    //VISTA CATEGORIAS
    public $activeTabPar = 'create';
    public $searchPar = '', $sortPar = 'created_at', $directionPar = 'desc'; 
    
    public $editingPar = '';
    public $showPar = '';
    public $deletePar = '';

    public $parroquia = [
        'parroquia' => ''
    ];

    //VALIDACIONES
    protected function rules()
    {
        if ($this->validacion === 'parroquia') {
            return [
                'parroquia.parroquia' => 'required|max:50',
            ];
        }

        return [
            'comunidad.comunidad' => 'required|max:50',
            'comunidad.parroquia' => 'required|exists:parroquias,parroquia'
        ];
    }

    protected function messages()
    {
        if ($this->validacion === 'parroquia') {
            return [
                'parroquia.parroquia.required' => 'El nombre de la parroquia es obligatorio.',
                'parroquia.parroquia.max' => 'El nombre de la parroquia no debe exceder los 50 caracteres.'
            ];
        }

        return [
                'comunidad.comunidad.required' => 'El nombre de la comunidad es obligatorio.',
                'comunidad.comunidad.max' => 'El nombre no debe exceder los 50 caracteres.',
                'comunidad.parroquia.required' => 'La parroquia de la comunidad es obligatoria.',
                'comunidad.parroquia.exists' => 'La parroquia no existe en la base de datos.',
        ];
    }

    private function loadComunidades(){
        $comunidad = Comunidades::orderBy($this->sort, $this->direction);

        if($this->search){
            $comunidad->where('comunidad', 'like', '%' . $this->search . '%')
                ->orWhere('parroquia', 'like', '%' . $this->search . '%');
        }
        
        $this->parroquias = Parroquias::get();

        return $comunidad->paginate(10, ['*'], 'ComPage')->onEachSide(-1);
    }

    private function loadParroquia(){
        $parroquia = Parroquias::orderBy($this->sortPar, $this->directionPar);

        if($this->searchPar){
            $parroquia->where('parroquia', 'like', '%' . $this->searchPar . '%');
        }

        return $parroquia->paginate(10, ['*'], 'ParPage')->onEachSide(-1);
    }

    public function submit(){

        if($this->cambiarVistaParroquia){

            $this->validacion = 'parroquia';

            $this->validate();

            try {

                if ($this->editingPar && Auth::user()->isSuperAdministrador()) {

                    $parroquia = Parroquias::find($this->editingPar);

                    $parroquia->update([
                        'parroquia' => $this->parroquia['parroquia'],
                    ]);

                    $this->editingPar = '';
                    $this->resetFormPar();
                    $this->loadParroquia();
                    
                    $this->parroquias = Parroquias::get();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Parroquia actualizada exitosamente',
                    ]);

                } elseif(Auth::user()->isSuperAdministrador()) {

                    Parroquias::create([
                        'parroquia' => $this->parroquia['parroquia'],
                    ]);

                    $this->parroquias = Parroquias::get();
                    
                    $this->resetFormPar();
                    $this->loadParroquia();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Parroquia creada exitosamente',
                    ]);
                } else {
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'Ops, algo salio mal',
                    ]);
                    return;
                }
            }catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Esta Parroquia ya existe!',
                ]);
            }
            
        }else{
            $this->validacion = 'comunidad';

            $this->validate();

            try {

                if ($this->editingCom && Auth::user()->isSuperAdministrador()) {

                    $comunidad = Comunidades::find($this->editingCom);

                    $comunidad->update([
                        'comunidad' => Str::upper($this->comunidad['comunidad']),
                        'parroquia' => $this->comunidad['parroquia'],
                    ]);

                    $this->editingCom = '';
                    $this->resetForm();
                    $this->loadComunidades();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Comunidad actualizada exitosamente',
                    ]);

                } elseif(Auth::user()->isSuperAdministrador()) {

                    Comunidades::create([
                        'comunidad' => Str::upper($this->comunidad['comunidad']),
                        'parroquia' => $this->comunidad['parroquia'],
                    ]);
                    
                    $this->resetForm();
                    $this->loadComunidades();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Comunidad creada exitosamente',
                    ]);
                } else {
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'Ops, algo salio mal',
                    ]);
                    return;
                }
            }catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Esta Comunidad ya existe!',
                ]);
            }
        }
    }

    public function edit($id){

        if($this->cambiarVistaParroquia){

            $parroquia = Parroquias::find($id);

            if(!$parroquia){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Parroquia no encontrado',
                ]);
                return; 
            }
            
            $this->resetFormPar();

            $this->editingPar = $parroquia['id'];

            $this->parroquia = [
                'parroquia' => Str::title($parroquia['parroquia'])
            ];
            
        }else{
            $comunidad = Comunidades::find($id);

            if(!$comunidad){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Comunidad no encontrado',
                ]);
                return;
            }
            
            $this->resetForm();

            $this->editingCom = $comunidad['id'];

            $this->comunidad = [
                'comunidad' => $comunidad['comunidad'],
                'parroquia' => $comunidad['parroquia'],
            ];

            $this->comunidad['comunidad'] = Str::upper($this->comunidad['comunidad']);
        }

    }

    public function view($id){

        if($this->cambiarVistaParroquia){
            $parroquia = Parroquias::find($id);

            if(!$parroquia){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Parroquia no encontrado',
                ]);
                return;
            }
            
            $this->resetFormPar();
            
            $this->showPar = $parroquia;

            $this->activeTabPar = 'show';
        }else{
            $comunidad = Comunidades::find($id);

            if(!$comunidad){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Comunidad no encontrado',
                ]);
                return;
            }
            
            $this->resetForm();
            
            $this->showCom = $comunidad;

            $this->activeTab = 'show';
        }
    }

    public function confirmDelete($id)
    {
        if($this->cambiarVistaParroquia){

            $parroquia = Parroquias::find($id);
        
            if (!$parroquia) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Parroquia no encontrado',
                ]);
                return;
            }
            
            $this->deletePar= $parroquia;

        }else{
            $comunidad = Comunidades::find($id);
            
            if (!$comunidad) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Comunidad no encontrado',
                ]);
                return;
            }
            
            $this->deleteCom= $comunidad;
        }
    }

    public function deleteDefinitive()
    {
        if($this->cambiarVistaParroquia){
            $parroquia = Parroquias::find($this->deletePar['id']);
        
            if (!$parroquia) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Parroquia no encontrado',
                ]);
                return;
            }
            
            try {
                
                if ($parroquia && Auth::user()->isSuperAdministrador()) {

                    $parroquia->delete();
                    
                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Parroquia eliminada exitosamente',
                    ]);
                    
                    $this->deletePar = null;
                    $this->resetFormPar();
                    $this->loadParroquia();
                }
                
            } catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Error al eliminar la parroquia: ' . $e->getMessage(),
                ]);
            }

        }else{

            $comunidad = Comunidades::find($this->deleteCom['id']);
            
            if (!$comunidad) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Comunidad no encontrado',
                ]);
                return;
            }
            
            try {
                
                if ($comunidad && Auth::user()->isSuperAdministrador()) {

                    $comunidad->delete();
                    
                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Comunidad eliminada exitosamente',
                    ]);
                    
                    $this->deleteCom = null;
                    $this->resetForm();
                    $this->loadComunidades();
                }
                
            } catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Error al eliminar la Comunidad: ' . $e->getMessage(),
                ]);
            }
        }
    }

    public function cancelDelete()
    {
        $this->deletePar = null;
        $this->deleteCom = null;
    }

    public function resetForm(){
        $this->comunidad = [
            'comunidad' => '',
            'parroquia' => '',
        ];

        $this->activeTab = 'create';

        $this->editingCom = '';
        $this->showCom = '';
        $this->deleteCom = '';

        $this->validacion = '';
        
        $this->resetValidation();
    }

    public function resetFormPar(){
        $this->parroquia = [
            'parroquia' => '',
        ];

        $this->activeTabPar = 'create';

        $this->editingPar = '';
        $this->showPar = '';
        $this->deletePar = '';

        $this->validacion = '';
        
        $this->resetValidation();
    }

    public function orden($sort)
    {
        if($this->cambiarVistaParroquia){
            if ($this->sortPar == $sort) {
                $this->directionPar = ($this->directionPar == 'asc') ? 'desc' : 'asc';
            } else {
                $this->sortPar = $sort;
                $this->directionPar = 'asc';
            }
        }

        if ($this->sort == $sort) {
            $this->direction = ($this->direction == 'asc') ? 'desc' : 'asc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function cambiarVista($cambiarVista){
        if($cambiarVista === 1 || $cambiarVista === 0){
            $this->cambiarVistaParroquia = $cambiarVista;
        }else{
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Vista no encontrada ',
            ]);
        }
    }

    public function render()
    {
        $comunidadesRender = $this->loadComunidades();

        $parroquiasRender = $this->loadParroquia();

        return view('livewire.dashboard.super-admin-comunidades' , [
            'comunidadesRender' => $comunidadesRender,
            'parroquiasRender' => $parroquiasRender,
        ])->layout('components.layouts.rbac');
    }
}
