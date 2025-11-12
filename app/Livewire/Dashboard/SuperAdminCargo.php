<?php

namespace App\Livewire\Dashboard;

use App\Models\Cargo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdminCargo extends Component
{    
    use WithPagination;

    // pagination theme
    protected $paginationTheme = 'disenoPagination'; 

    public $search = '', $sort = 'created_at', $direction = 'desc'; 
    public $activeTab = 'create';

    public $editingCargo = '';
    public $showCargo = '';
    public $deleteCargo = '';

    public $cargo = [
        'descripcion' => '',
    ];

    protected $rules = [
        'cargo.descripcion' => 'required|max:100',
    ];

    protected $messages = [
        'cargo.descripcion.required' => 'El nombre del cargo es obligatorio.',
        'cargo.descripcion.max' => 'El nombre del cargo debe ser menos de 100 carácteres.',
    ];

    private function loadCargos(){
        $cargo = Cargo::orderBy($this->sort, $this->direction);

        if($this->search){
            $cargo->Where('descripcion', 'like', '%' . $this->search . '%');
        }

        return $cargo->paginate(10)->onEachSide(-1);
    }

    public function submit(){

        $this->validate();

        try {

            if ($this->editingCargo && Auth::user()->isSuperAdministrador()) {

                $cargo = Cargo::find($this->editingCargo);

                $cargo->update([
                    'descripcion' => Str::title($this->cargo['descripcion']),
                ]);

                $this->editingCargo = '';
                $this->resetForm();
                $this->loadCargos();

                $this->dispatch('show-toast', [
                    'message' => 'Cargo actualizada exitosamente',
                    'type' => 'success'
                ]);

            }else{

                Cargo::create([
                    'descripcion' => Str::title($this->cargo['descripcion']),
                ]);
                
                $this->resetForm();
                $this->loadCargos();

                $this->dispatch('show-toast', [
                    'message' => 'Cargo creado exitosamente',
                    'type' => 'success'
                ]);
            }
        }catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al procesar el Cargo: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function editCargos($cargo_id){

        $cargo = Cargo::find($cargo_id);

        if(!$cargo){            
            $this->dispatch('show-toast', [
                'message' => 'Cargo no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();

        $this->editingCargo = $cargo['cargo_id'];

        $this->cargo = [
            'descripcion' => $cargo['descripcion'],
        ];
    }

    public function viewCargo($cargo_id){

        $cargo = Cargo::find($cargo_id);

        if(!$cargo){
            $this->dispatch('show-toast', [
                'message' => 'Cargo no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();
        
        $this->showCargo = $cargo;

        $this->activeTab = 'show';
    }

    public function confirmDelete($cargo_id)
    {
        $cargo = Cargo::find($cargo_id);
        
        if (!$cargo) {
            $this->dispatch('show-toast', [
                'message' => 'Cargo no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->deleteCargo = $cargo;
    }

    public function deleteCargoDefinitive()
    {
        $cargo = Cargo::find($this->deleteCargo['cargo_id']);
        
        if (!$cargo) {
            $this->dispatch('show-toast', [
                'message' => 'Cargo no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        try {
            
            if ($cargo && Auth::user()->isSuperAdministrador()) {

                $cargo->delete();
                
                $this->dispatch('show-toast', [
                    'message' => 'Cargo eliminada exitosamente',
                    'type' => 'success'
                ]);
                
                $this->deleteCargo = null;
                $this->resetForm();
                $this->loadCargos();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al eliminar el Cargo: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->deleteCargo = null;
    }

    public function resetForm(){
        $this->cargo = [
            'descripcion' => '',
        ];

        $this->activeTab = 'create';

        $this->editingCargo = '';
        $this->showCargo = '';
        $this->deleteCargo = '';

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
        $cargosRender = $this->loadCargos();

        return view('livewire.dashboard.super-admin-cargo' , [
            'cargosRender' => $cargosRender,
        ])->layout('components.layouts.rbac');
    }
}
