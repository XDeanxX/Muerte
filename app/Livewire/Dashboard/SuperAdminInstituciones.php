<?php

namespace App\Livewire\Dashboard;

use App\Models\Institucion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdminInstituciones extends Component
{
    use WithPagination;

    // pagination theme
    protected $paginationTheme = 'disenoPagination'; 

    public $search = '', $sort = 'created_at', $direction = 'desc'; 
    public $activeTab = 'create';

    public $editingInstitucion = '';
    public $showInstitucion = '';
    public $deleteInstitucion = '';

    public $institucion = [
        'titulo' => '',
        'descripcion' => '',
    ];

    protected $rules = [
        'institucion.titulo' => 'required|max:100',
        'institucion.descripcion' => 'required|max:100',
    ];

    protected $messages = [
        'institucion.titulo.required' => 'El nombre de la institución es obligatoria',
        'institucion.titulo.max' => 'El nombre de la institución debe ser menor de 100 carácteres',
        'institucion.descripcion.required' => 'La descripción de la institución es obligatorio.',
        'institucion.descripcion.max' => 'La descripción de la institución debe ser menor de 100 carácteres.',
    ];

    private function loadInstituciones(){
        $institucion = Institucion::orderBy($this->sort, $this->direction);

        if($this->search){
            $institucion->Where('titulo', 'like', '%' . $this->search . '%');
        }

        return $institucion->paginate(10)->onEachSide(-1);
    }

    public function submit(){

        $this->validate();

        try {

            if ($this->editingInstitucion && Auth::user()->isSuperAdministrador()) {

                $institucion = Institucion::find($this->editingInstitucion);

                $institucion->update([
                    'titulo' => Str::title($this->institucion['titulo']),
                    'descripcion' => $this->institucion['descripcion'],
                ]);

                $this->editingInstitucion = '';
                $this->resetForm();
                $this->loadInstituciones();

                $this->dispatch('show-toast', [
                    'message' => 'Institución actualizada exitosamente',
                    'type' => 'success'
                ]);

            }else{

                Institucion::create([
                    'titulo' => Str::title($this->institucion['titulo']),
                    'descripcion' => $this->institucion['descripcion'],
                ]);
                
                $this->resetForm();
                $this->loadInstituciones();

                $this->dispatch('show-toast', [
                    'message' => 'Institución creado exitosamente',
                    'type' => 'success'
                ]);
            }
        }catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al procesar el Institución: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function editInstitucion($institucion_id){

        $institucion = Institucion::find($institucion_id);

        if(!$institucion){            
            $this->dispatch('show-toast', [
                'message' => 'Institución no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();

        $this->editingInstitucion = $institucion['id'];

        $this->institucion = [
            'titulo' => $institucion['titulo'],
            'descripcion' => $institucion['descripcion'],
        ];
    }

    public function viewInstitucion($institucion_id){

        $institucion = Institucion::find($institucion_id);

        if(!$institucion){
            $this->dispatch('show-toast', [
                'message' => 'Institución no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->resetForm();
        
        $this->showInstitucion = $institucion;

        $this->activeTab = 'show';
    }

    public function confirmDelete($institucion_id)
    {
        $institucion = Institucion::find($institucion_id);
        
        if (!$institucion) {
            $this->dispatch('show-toast', [
                'message' => 'Institución no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        $this->deleteInstitucion = $institucion;
    }

    public function deleteInstitucionDefinitive()
    {
        $institucion = Institucion::find($this->deleteInstitucion['id']);
        
        if (!$institucion) {
            $this->dispatch('show-toast', [
                'message' => 'Institución no encontrado',
                'type' => 'error'
            ]);
            return;
        }
        
        try {
            
            if ($institucion && Auth::user()->isSuperAdministrador()) {

                $institucion->delete();
                
                $this->dispatch('show-toast', [
                    'message' => 'Institución eliminada exitosamente',
                    'type' => 'success'
                ]);
                
                $this->deleteInstitucion = null;
                $this->resetForm();
                $this->loadInstituciones();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error al eliminar el Institución: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->deleteInstitucion = null;
    }

    public function resetForm(){
        $this->institucion = [
            'titulo' => '',
            'descripcion' => '',
        ];

        $this->activeTab = 'create';

        $this->editingInstitucion = '';
        $this->showInstitucion = '';
        $this->deleteInstitucion = '';

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
        $institucionsRender = $this->loadInstituciones();

        return view('livewire.dashboard.super-admin-instituciones' , [
            'institucionsRender' => $institucionsRender,
        ])->layout('components.layouts.rbac');
    }
}
