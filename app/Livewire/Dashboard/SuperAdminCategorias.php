<?php

namespace App\Livewire\Dashboard;

use App\Models\Categorias;
use App\Models\SubCategorias;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class SuperAdminCategorias extends Component
{    
    use WithPagination;

    protected $paginationTheme = 'disenoPagination'; 

    public $cambiarVistaCategorias = false;

    public $validacion = '';

    //VISTA SUBCATEGORIAS
    public $activeTab = 'create';
    public $search = '', $sort = 'created_at', $direction = 'desc'; 

    public $editingSub = '';
    public $showSub = '';
    public $deleteSub = '';

    public $categorias = '';
    public $subcategoria = [
        'subcategoria' => '',
        'descripcion' => '',
        'categoria' => '',
    ];

    //VISTA CATEGORIAS
    public $activeTabCat = 'create';
    public $searchCat = '', $sortCat = 'created_at', $directionCat = 'desc'; 
    
    public $editingCat = '';
    public $showCat = '';
    public $deleteCat = '';

    public $categoria = [
        'categoria' => '',
        'descripcion' => ''
    ];

    //VALIDACIONES
    protected function rules()
    {
        if ($this->validacion === 'categoria') {
            return [
                'categoria.categoria' => 'required|max:50',
                'categoria.descripcion' => 'required|max:1000|min:10',
            ];
        }

        return [
            'subcategoria.subcategoria' => 'required|max:50',
            'subcategoria.descripcion' => 'required|max:1000|min:10',
            'subcategoria.categoria' => 'required|exists:categorias,categoria'
        ];
    }

    protected function messages()
    {
        if ($this->validacion === 'categoria') {
            return [
                'categoria.categoria.required' => 'El nombre de la categoria es obligatorio.',
                'categoria.categoria.max' => 'El nombre de la categoria no debe exceder los 50 caracteres.',
                'categoria.descripcion.required' => 'La descripción de la categoria es obligatoria.',
                'categoria.descripcion.max' => 'La descripción no debe exceder los 1000 caracteres.',
                'categoria.descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            ];
        }

        return [
                'subcategoria.subcategoria.required' => 'El nombre de la subcategoría es obligatorio.',
                'subcategoria.subcategoria.max' => 'El nombre no debe exceder los 50 caracteres.',
                'subcategoria.descripcion.required' => 'La descripción de la subcategoría es obligatoria.',
                'subcategoria.descripcion.max' => 'La descripción no debe exceder los 1000 caracteres.',
                'subcategoria.descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
                'subcategoria.categoria.required' => 'La categoría de la subcategoría es obligatoria.',
                'subcategoria.categoria.exists' => 'La categoría no existe en la base de datos.',
        ];
    }

    private function loadSubcategoria(){
        $subcategoria = SubCategorias::orderBy($this->sort, $this->direction);

        if($this->search){
            $subcategoria->where('subcategoria', 'like', '%' . $this->search . '%')
                        ->orWhere('categoria', 'like', '%' . $this->search . '%');
        }

        $this->categorias = Categorias::get();

        return $subcategoria->paginate(10, ['*'], 'subPage')->onEachSide(-1);
    }

    private function loadCategoria(){
        $categoria = Categorias::orderBy($this->sortCat, $this->directionCat);

        if($this->searchCat){
            $categoria->where('categoria', 'like', '%' . $this->searchCat . '%');
        }

        return $categoria->paginate(10, ['*'], 'catPage')->onEachSide(-1);
    }

    public function submit(){

        if($this->cambiarVistaCategorias){

            $this->validacion = 'categoria';

            $this->validate();

            try {
                
                $this->categoria['categoria'] = Str::lower($this->categoria['categoria']);

                if ($this->editingCat && Auth::user()->isSuperAdministrador()) {

                    $categoria = Categorias::find($this->editingCat);

                    $categoria->update([
                        'categoria' => $this->categoria['categoria'],
                        'descripcion' => $this->categoria['descripcion'],
                    ]);

                    $this->editingCat = '';
                    $this->resetFormCat();
                    $this->loadCategoria();
                    
                    $this->categorias = Categorias::get();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Categoría actualizada exitosamente',
                    ]);

                }elseif(Auth::user()->isSuperAdministrador()){

                    Categorias::create([
                        'categoria' => $this->categoria['categoria'],
                        'descripcion' => $this->categoria['descripcion']
                    ]);

                    $this->categorias = Categorias::get();
                    
                    $this->resetFormCat();
                    $this->loadCategoria();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Categoría creado exitosamente',
                    ]);
                }
            }catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Esta Categoría ya Existe!',
                ]);
            }
            
        }else{
            $this->validacion = 'subcategoria';

            $this->validate();

            try {
                
                $this->subcategoria['subcategoria'] = Str::lower($this->subcategoria['subcategoria']);

                if ($this->editingSub && Auth::user()->isSuperAdministrador()) {

                    $subcategoria = SubCategorias::find($this->editingSub);

                    $subcategoria->update([
                        'subcategoria' => Str::ucfirst($this->subcategoria['subcategoria']),
                        'descripcion' => $this->subcategoria['descripcion'],
                        'categoria' => $this->subcategoria['categoria'],
                    ]);

                    $this->editingSub = '';
                    $this->resetForm();
                    $this->loadSubcategoria();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Subcategoría actualizada exitosamente',
                    ]);

                }elseif(Auth::user()->isSuperAdministrador()){

                    SubCategorias::create([
                        'subcategoria' => Str::ucfirst($this->subcategoria['subcategoria']),
                        'descripcion' => $this->subcategoria['descripcion'],
                        'categoria' => $this->subcategoria['categoria'],
                    ]);
                    
                    $this->resetForm();
                    $this->loadSubcategoria();

                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Subcategoría creado exitosamente',
                    ]);
                }else{
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'Ops, algo salio mal',
                    ]);
                    return;
                }
            }catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Esta Subcategoría ya existe!',
                ]);
            }
        }
    }

    public function edit($id){

        if($this->cambiarVistaCategorias){
            $categoria = Categorias::find($id);

            if(!$categoria){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Categoría no encontrado',
                ]);
                return;
            }
            
            $this->resetFormCat();

            $this->editingCat = $categoria['id'];

            $this->categoria = [
                'categoria' => $categoria['categoria'],
                'descripcion' => $categoria['descripcion']
            ];

            $this->categoria['categoria'] = Str::ucfirst($this->categoria['categoria']);
            
        }else{
            $subcategoria = SubCategorias::find($id);

            if(!$subcategoria){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Subcategoría no encontrado',
                ]);
                return;
            }
            
            $this->resetForm();

            $this->editingSub = $subcategoria['id'];

            $this->subcategoria = [
                'subcategoria' => $subcategoria['subcategoria'],
                'descripcion' => $subcategoria['descripcion'],
                'categoria' => $subcategoria['categoria'],
            ];

            $this->subcategoria['subcategoria'] = Str::ucfirst($this->subcategoria['subcategoria']);

        }

    }

    public function view($id){

        if($this->cambiarVistaCategorias){
            $categoria = Categorias::find($id);

            if(!$categoria){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Categoria no encontrado',
                ]);
                return;
            }
            
            $this->resetFormCat();
            
            $this->showCat = $categoria;

            $this->activeTabCat = 'show';
        }else{
            $subcategoria = SubCategorias::find($id);

            if(!$subcategoria){
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Subcategoria no encontrado',
                ]);
                return;
            }
            
            $this->resetForm();
            
            $this->showSub = $subcategoria;

            $this->activeTab = 'show';
        }
    }

    public function confirmDelete($id)
    {
        if($this->cambiarVistaCategorias){

            $categoria = Categorias::find($id);
        
            if (!$categoria) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Categoría no encontrado',
                ]);
                return;
            }
            
            $this->deleteCat= $categoria;

        }else{
            $subcategoria = SubCategorias::find($id);
            
            if (!$subcategoria) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Subcategoría no encontrado',
                ]);
                return;
            }
            
            $this->deleteSub= $subcategoria;
        }
    }

    public function deleteDefinitive()
    {
        if($this->cambiarVistaCategorias){
            $categoria = Categorias::find($this->deleteCat['id']);
        
            if (!$categoria) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Categoría no encontrado',
                ]);
                return;
            }
            
            try {
                
                if ($categoria && Auth::user()->isSuperAdministrador()) {

                    $categoria->delete();
                    
                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Categoría eliminada exitosamente',
                    ]);
                    
                    $this->deleteCat = null;
                    $this->resetFormCat();
                    $this->loadCategoria();
                }
                
            } catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Error al eliminar la categoria: ' . $e->getMessage(),
                ]);
            }
        }else{

            $subcategoria = SubCategorias::find($this->deleteSub['id']);
            
            if (!$subcategoria) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Subcategoría no encontrada',
                ]);
                return;
            }
            
            try {
                
                if ($subcategoria && Auth::user()->isSuperAdministrador()) {

                    $subcategoria->delete();
                    
                    $this->dispatch('show-toast',[
                        'type' => 'success',
                        'message' => 'Subcategoría eliminada exitosamente',
                    ]);
                    
                    $this->deleteSub = null;
                    $this->resetForm();
                    $this->loadSubcategoria();
                }
                
            } catch (\Exception $e) {
                $this->dispatch('show-toast',[
                    'type' => 'error',
                    'message' => 'Error al eliminar la subcategoria: ' . $e->getMessage(),
                ]);
            }
        }
    }

    public function cancelDelete()
    {
        $this->deleteCat = null;
        $this->deleteSub = null;
    }

    public function resetForm(){
        $this->subcategoria = [
            'subcategoria' => '',
            'descripcion' => '',
            'categoria' => '',
        ];

        $this->activeTab = 'create';

        $this->editingSub = '';
        $this->showSub = '';
        $this->deleteSub = '';

        $this->validacion = '';
        
        $this->resetValidation();
    }

    public function resetFormCat(){
        $this->categoria = [
            'categoria' => '',
            'descripcion' => '',
        ];

        $this->activeTabCat = 'create';

        $this->editingCat = '';
        $this->showCat = '';
        $this->deleteCat = '';

        $this->validacion = '';
        
        $this->resetValidation();
    }

    public function orden($sort)
    {
        if($this->cambiarVistaCategorias){
            if ($this->sortCat == $sort) {
                $this->directionCat = ($this->directionCat == 'asc') ? 'desc' : 'asc';
            } else {
                $this->sortCat = $sort;
                $this->directionCat = 'asc';
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
            $this->cambiarVistaCategorias = $cambiarVista;
        }else{
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Vista no encontrada ',
            ]);
        }
    }

    public function render()
    {
        $subcategoriasRender = $this->loadSubcategoria();

        $categoriasRender = $this->loadCategoria();

        return view('livewire.dashboard.super-admin-categorias' , [
            'subcategoriasRender' => $subcategoriasRender,
            'categoriasRender' => $categoriasRender,
        ])->layout('components.layouts.rbac');
    }
}
