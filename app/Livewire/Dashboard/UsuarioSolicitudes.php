<?php

namespace App\Livewire\Dashboard;

use App\Models\Categorias;
use App\Models\Comunidades;
use App\Models\Estatus;
use App\Models\Parroquias;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Models\SubCategorias;
use Livewire\Component;
use Livewire\WithPagination;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UsuarioSolicitudes extends Component
{
    use WithPagination;

    protected $scrollTo = true;
    
    protected $paginationTheme = 'disenoPagination'; 

    /* public $activeTab = 'create';
    public $solicitudes = []; */

    public $search = '', $sort = 'fecha_creacion', $direction = 'desc';
    public $estatusSolicitud = 0, $estadoSolicitud = 'Todos';

    public $activeTab = 'list';
    public $showSolicitud = null;
    public $editingSolicitud = null;

    public $categorias = [], $subcategorias = [];
    public $parroquias = [], $comunidades = [];

    public $personalData = [
        'cedula' => '',
        'nombre_completo' => '',
        'telefono' => '',
        'email' => ''
    ];
    
    public $solicitud = [
        'titulo' => '',
        'descripcion' => '',
        'solicitudEstatus' => '',
        'fecha_actualizacion_usuario' => '',
        'fecha_actualizacion_super_admin' => '',
        'fecha_creacion' => '',
        'persona_cedula' => '',
        'derecho_palabra' => 0,
        'solicitudCategoria' => [
            'categoria' => '',
            'subcategoria' => ''
        ],
        'tipo_solicitud' => 'individual',
        'pais' => 'Venezuela',
        'estado_region' => 'Yaracuy',
        'municipio' => 'Bruzual',
        'solicitudParroquia' => [
            'parroquia' => '',
            'comunidad' => ''
        ],
        'direccion_detallada' => '',
    ];

    protected $rules = [
        'solicitud.titulo' => 'required|min:5|max:50',
        'solicitud.solicitudCategoria.categoria' => 'required|exists:categorias,categoria',
        'solicitud.solicitudCategoria.subcategoria' => 'required|exists:sub_categorias,subcategoria',
        'solicitud.solicitudParroquia.parroquia' => 'required|exists:parroquias,parroquia',
        'solicitud.solicitudParroquia.comunidad' => 'required|exists:comunidades,comunidad',
        'solicitud.direccion_detallada' => 'required|min:10|max:200',
        'solicitud.descripcion' => 'required|min:25|max:5000',
        'solicitud.derecho_palabra' => 'boolean',
        'solicitud.tipo_solicitud' => 'required|in:individual,colectivo_institucional',
    ];

    protected $messages = [
        'solicitud.titulo.required' => 'El título es obligatorio',
        'solicitud.titulo.min' => 'El título debe tener al menos 5 caracteres',
        'solicitud.titulo.max' => 'El título no puede exceder los 50 caracteres',

        'solicitud.solicitudCategoria.categoria.required' => 'La categoría es obligatoria',
        'solicitud.solicitudCategoria.categoria.exists' => 'La categoría no existe en nuestra base de datos',

        'solicitud.solicitudCategoria.subcategoria.required' => 'La subcategoría es obligatoria',
        'solicitud.solicitudCategoria.subcategoria.exists' => 'La subcategoría no existe en nuestra base de datos',

        'solicitud.solicitudParroquia.parroquia.required' => 'La parroquia es obligatoria',
        'solicitud.solicitudParroquia.parroquia.exists' => 'La parroquia no existe en nuestra base de datos',
        'solicitud.solicitudParroquia.comunidad.required' => 'La comunidad es obligatoria',
        'solicitud.solicitudParroquia.comunidad.exists' => 'La comunidad no existe en nuestra base de datos',

        'solicitud.direccion_detallada.required' => 'La dirección detallada es obligatoria',
        'solicitud.direccion_detallada.min' => 'La dirección detallada debe tener al menos 10 caracteres',
        'solicitud.direccion_detallada.max' => 'La dirección detallada no puede exceder los 200 caracteres',

        'solicitud.descripcion.required' => 'La descripción es obligatoria',
        'solicitud.descripcion.min' => 'La descripción debe tener al menos 25 caracteres',
        'solicitud.descripcion.max' => 'La descripción no puede exceder los 5000 caracteres',

        'solicitud.derecho_palabra.boolean' => 'El valor de derecho a la palabra debe ser verdadero o falso',

        'solicitud.tipo_solicitud.required' => 'El tipo de solicitud es obligatorio',
        'solicitud.tipo_solicitud.in' => 'El tipo de solicitud seleccionado no es válido',
    ];

    public function mount(){
        if (Session::has('open-tab-event')) {
            $tabToOpen = Session::pull('open-tab-event');
            $this->setActiveTab($tabToOpen);
        }
    }

    private function loadPersonalData()
    {
        $user = Auth::user();
        
        if ($user) {
            $this->personalData = [
                'cedula' => $user->persona->nacionalidad . $user->persona->cedula,
                'nombre_completo' => $user->persona->nombre . ' ' . $user->persona->apellido,
                'email' => $user->persona->email,
                'telefono' => $user->persona->telefono,
            ];
        }
    }

    private function loadSolicitudes()
    {
        $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])->where('persona_cedula', Auth::user()->persona_cedula);

        if($this->estatusSolicitud){
            $solicitud->where('estatus', $this->estatusSolicitud);
        }

        if ($this->search) {
            $solicitud->where(function ($q) {
                $q->where('solicitud_id', 'like', '%' . $this->search . '%')
                    ->orWhere('titulo', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                    ->orWhere('fecha_creacion', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('subcategoriaRelacion', function ($q) {
                $q->where('categoria', 'like', '%' . $this->search . '%');
            });
            
        }else{
            $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])->where('persona_cedula', Auth::user()->persona_cedula);
        }

        if($this->estatusSolicitud){
            $solicitud->where('estatus', $this->estatusSolicitud);
        }

        $solicitud->orderBy($this->sort, $this->direction);

        return $solicitud->paginate(10)->onEachSide(-0.5);
    }

    public function setActiveTab($tab)
    {
        $solicitudesActivas = 
            Solicitud::where('estatus', 1)->where('persona_cedula', Auth::user()->persona_cedula)->count()
            + Solicitud::where('estatus', 4)->where('persona_cedula', Auth::user()->persona_cedula)->count();


        if($tab === 'create' && $solicitudesActivas >= 5){
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Usted alcanzo la cantidad maxima de solicitudes activas al mismo tiempo',
            ]);
            $this->activeTab = 'list';
            return;
        }
        
        $this->resetForm();

        if($tab === 'create'){
            $this->loadPersonalData();
        }

        $this->activeTab = $tab;
    }

    private function loadDatos()
    {
        $this->categorias = Categorias::all();

        $this->subcategorias = SubCategorias::where('categoria', $this->solicitud['solicitudCategoria']['categoria'])->get();

        $this->parroquias = Parroquias::all();
        
        $this->comunidades = Comunidades::where('parroquia', $this->solicitud['solicitudParroquia']['parroquia'])->get();
    }

    public function submit()
    {
        $this->validate(); 
        
        try {
            if ($this->editingSolicitud) {
                    
                if ($this->editingSolicitud->persona_cedula !== Auth::user()->persona_cedula) {
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'No tienes permisos para editar esta solicitud',
                    ]);
                    return;
                }

                if (!Auth::user()->isUsuario()) {
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'No tienes permisos para editar esta solicitud',
                    ]);
                    return;
                }
                
                $this->editingSolicitud->update([
                    'titulo' => Str::title($this->solicitud['titulo']),
                    'descripcion' => $this->solicitud['descripcion'],
                    'subcategoria' => $this->solicitud['solicitudCategoria']['subcategoria'],
                    'tipo_solicitud' => $this->solicitud['tipo_solicitud'],
                    'pais' => $this->solicitud['pais'],
                    'estado_region' => $this->solicitud['estado_region'],
                    'municipio' => $this->solicitud['municipio'],
                    'comunidad' => $this->solicitud['solicitudParroquia']['comunidad'],
                    'direccion_detallada' => $this->solicitud['direccion_detallada'],
                    'fecha_actualizacion_usuario' => now(),
                    'derecho_palabra' => $this->solicitud['derecho_palabra'],
                ]);
                
                $this->dispatch('show-toast',[
                    'type' => 'success',
                    'message' => 'Solicitud actualizada exitosamente',
                ]);

            } else {

                if (!Auth::user()->isUsuario()) {
                    $this->dispatch('show-toast',[
                        'type' => 'error',
                        'message' => 'No tienes permisos para crear una solicitud',
                    ]);
                    return;
                }
                
                $solicitudId = Solicitud::generateSolicitudId(Auth::user()->persona_cedula);
                
                Solicitud::create([
                    'solicitud_id' => $solicitudId,
                    'titulo' => Str::title($this->solicitud['titulo']),
                    'descripcion' => $this->solicitud['descripcion'],
                    'subcategoria' => $this->solicitud['solicitudCategoria']['subcategoria'],
                    'tipo_solicitud' => $this->solicitud['tipo_solicitud'],
                    'persona_cedula' => Auth::user()->persona_cedula,
                    'pais' => $this->solicitud['pais'],
                    'estado_region' => $this->solicitud['estado_region'],
                    'municipio' => $this->solicitud['municipio'],
                    'comunidad' => $this->solicitud['solicitudParroquia']['comunidad'],
                    'direccion_detallada' => $this->solicitud['direccion_detallada'],
                    'estatus' => Estatus::where('sector_sistema', 'solicitudes')->where('estatus', 'Pendiente')->value('estatus_id'),
                    'fecha_creacion' => now(),
                    'derecho_palabra' => $this->solicitud['derecho_palabra'],
                ]);

                $this->dispatch('show-toast',[
                    'type' => 'success',
                    'message' => 'Solicitud creada exitosamente. Ticket: ' . $solicitudId,
                ]);
            }
            
            $this->resetForm();
            $this->setActiveTab('list');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ]);
        }
    }

    public function editSolicitud($solicitudId)
    {
        $solicitud = Solicitud::with('persona', 'subcategoriaRelacion', 'comunidadRelacion')->find($solicitudId);

        
        if ($solicitud->persona->cedula !== Auth::user()->persona_cedula) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para editar esta solicitud',
            ]);
            return;
        }

        if (!$solicitud) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }

        if (!$solicitud->persona) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Error de datos: La solicitud con ticket ' . $solicitudId . ' no tiene una persona asociada válida.',
            ]);
            return;
        }

        if($solicitud->estatus !== 1 && !Auth::user()->isUsuario()){
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Ya no tienes permisos para editar esta solicitud',
            ]);
            return;
        }

        $this->resetForm();

        $this->loadPersonalData();
        
        $this->editingSolicitud = $solicitud;
        
        $this->solicitud = [
            'titulo' => $solicitud->titulo,
            'descripcion' => $solicitud->descripcion,
            'solicitudEstatus' => $solicitud->estatus,
            'observaciones_admin' => $solicitud->observaciones_admin,
            'fecha_actualizacion_usuario' => '',
            'fecha_actualizacion_super_admin' => '',
            'fecha_creacion' => '',
            'persona_cedula' => $solicitud->persona_cedula,
            'derecho_palabra' => $solicitud->derecho_palabra,
            'solicitudCategoria' => [
                'categoria' => $solicitud->subcategoriaRelacion->categoria,
                'subcategoria' => $solicitud->subcategoriaRelacion->subcategoria
            ],
            'tipo_solicitud' => $solicitud->tipo_solicitud,
            'pais' => 'Venezuela',
            'estado_region' => 'Yaracuy',
            'municipio' => 'Bruzual',
            'solicitudParroquia' => [
                'parroquia' => $solicitud->comunidadRelacion->parroquia,
                'comunidad' => $solicitud->comunidadRelacion->comunidad
            ],
            'direccion_detallada' => $solicitud->direccion_detallada,
        ];

        $this->activeTab = 'edit';
    }

    public function viewSolicitud($solicitudId)
    {
        $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])
            ->find($solicitudId);
            
            
        if (!$solicitud) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }

        if (($solicitud->persona->cedula !== Auth::user()->persona_cedula) && !Auth::user()->isUsuario()) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para ver esta solicitud',
            ]);
            return;
        }
        
        
        $this->showSolicitud = $solicitud;
        $this->activeTab = 'show';
    }

    public function resetForm()
    {
        $this->personalData = [
            'cedula' => '',
            'nombre_completo' => '',
            'telefono' => '',
            'email' => ''
        ];

        $this->solicitud = [
            'titulo' => '',
            'descripcion' => '',
            'solicitudEstatus' => '',
            'observaciones_admin' => '',
            'fecha_actualizacion_usuario' => '',
            'fecha_actualizacion_super_admin' => '',
            'fecha_creacion' => '',
            'persona_cedula' => '',
            'derecho_palabra' => 0,
            'solicitudCategoria' => [
                'categoria' => '',
                'subcategoria' => ''
            ],
            'tipo_solicitud' => 'individual',
            'pais' => 'Venezuela',
            'estado_region' => 'Yaracuy',
            'municipio' => 'Bruzual',
            'solicitudParroquia' => [
                'parroquia' => '',
                'comunidad' => ''
            ],
            'direccion_detallada' => '',
        ];

        $this->categorias = [];
        $this->subcategorias = [];

        $this->parroquias = [];
        $this->comunidades = [];

        $this->showSolicitud = null;
        $this->editingSolicitud = null;
        $this->resetValidation();
    }
    
    public function ordenEstados($estado){
        
        $this->estatusSolicitud = $estado;

        if($this->estatusSolicitud === 0){
            $this->estadoSolicitud = 'Todos';
        }else{
            $name = Estatus::find($estado);
            $this->estadoSolicitud = Str::title($name->estatus);
        }
    }

    private function loadEstatus()
    {
        return Estatus::where('sector_sistema', 'solicitudes')->get();
    }

    public function render()
    {
        $solicitudesRender = $this->loadSolicitudes();

        if($this->activeTab === 'create' || $this->activeTab === 'edit'){
            $this->loadDatos();
        }
        
        $estatus = $this->loadEstatus();

        return view('livewire.dashboard.usuario-solicitudes', [
            'solicitudesRender' => $solicitudesRender,
            'estatus' => $estatus
        ])->layout('components.layouts.rbac');
    }
}