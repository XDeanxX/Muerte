<?php

namespace App\Livewire\Dashboard;

use App\Models\Solicitud;
use App\Models\Categorias;
use App\Models\Comunidades;
use App\Models\Estatus;
use App\Models\nacionalidad;
use App\Models\Parroquias;
use App\Models\Personas;
use App\Models\SubCategorias;
use Livewire\Component;
use Livewire\WithPagination;    
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\AbstractOptions;
use Illuminate\Support\Collection;
use PDF;

class SuperAdminSolicitudes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'disenoPagination'; 

    public $search = '', $sort = 'fecha_creacion', $direction = 'desc';
    public $estatusSolicitud = 0, $estatusName = 'Todos';
    public $mensajeSolcitante;

    public $categorias = [], $subcategorias = [];
    public $parroquias = [], $comunidades = [];
    public $nacionalidad = [], $prefijos = [];

    public $activeTab = 'list';
    public $showSolicitud = null;
    public $editingSolicitud = null;
    public $deleteSolicitud = null;

    public $personalData = [
        'cedula' => '',
        'nombre' => '',
        'apellido' => '',
        'telefono' => '',
        'prefijo' => '',
        'email' => '',
        'nacionalidad' => ''
    ];

    public $solicitud = [
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

    public function rules()
    {
        if($this->editingSolicitud){
            return [
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
        }

        return [
            'personalData.nombre' => 'required|max:25',
            'personalData.apellido' => 'required|max:25',
            'personalData.cedula' => 'required|min:7|max:15',
            'personalData.telefono' => 'required|max:8',
            'personalData.prefijo' => 'required|in:0412,0422,0414,0424,0416,0426',
            'personalData.email' => 'required|email|max:100',
            'personalData.nacionalidad' => 'required|exists:nacionalidads,id',

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
    }

    protected $messages = [
        'personalData.nombre.required' => 'El nombre es obligatorio',
        'personalData.nombre.max' => 'El nombre no puede exceder los 25 caracteres',
        'personalData.apellido.required' => 'El apellido es obligatorio',
        'personalData.apellido.max' => 'El apellido no puede exceder los 25 caracteres',
        'personalData.cedula.required' => 'La cédula es obligatoria',
        'personalData.cedula.min' => 'La cédula debe tener al menos 7 caracteres',
        'personalData.cedula.max' => 'La cédula no puede exceder los 8 caracteres',
        'personalData.email.email' => 'El correo electrónico debe ser una dirección válida',
        'personalData.email.required' => 'El correo electrónico es obligatorio',
        'personalData.email.max' => 'El correo electrónico no puede exceder los 100 caracteres',
        'personalData.telefono.required' => 'El teléfono es obligatorio',
        'personalData.telefono.max' => 'El teléfono no puede exceder los 13 caracteres',
        'personalData.nacionalidad.required' => 'La nacionalidad es obligatorio',
        'personalData.nacionalidad.exists' => 'La nacionalidad no existe en nuestra base de datos',

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

    private function loadSolicitudes()
    {
        if (Auth::user()->isSuperAdministrador() || Auth::user()->isAdministrador()) {

            $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion']);

            if($this->estatusSolicitud){
                $solicitud->where('estatus', $this->estatusSolicitud);
            }

            if ($this->search) {
                $solicitud->where(function ($q) {
                    $q->where('solicitud_id', 'like', '%' . $this->search . '%')
                        ->orWhere('titulo', 'like', '%' . $this->search . '%')
                        ->orWhere('fecha_creacion', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('persona', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orwhere('apellido', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('subcategoriaRelacion', function ($q) {
                    $q->where('categoria', 'like', '%' . $this->search . '%')
                        ->orwhere('subcategoria', 'like', '%' . $this->search . '%');
                });
            }else{
                $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion']);
            }
            
            if($this->estatusSolicitud){
                $solicitud->where('estatus', $this->estatusSolicitud);
            }
            
            if (strpos($this->sort, '.') !== false) {
                list($table, $column) = explode('.', $this->sort);
            
                $solicitud->leftJoin($table . 's', $table . 's.cedula', '=', 'solicitudes.' . $table . '_cedula')
                    ->select('solicitudes.*')
                    ->orderBy($table . 's.' . $column, $this->direction);
            } else {
                $solicitud->orderBy($this->sort, $this->direction);
            }

            return $solicitud->paginate(10)->onEachSide(-0.5);

        } else {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Error al cargar las solicitudes: No tienes permisos para ver esta sección',
            ]);
        }
    }

    private function loadDatos()
    {
        $this->nacionalidad = nacionalidad::all();

        $this->categorias = Categorias::all();

        $this->subcategorias = SubCategorias::where('categoria', $this->solicitud['solicitudCategoria']['categoria'])->get();

        $this->parroquias = Parroquias::all();
        
        $this->comunidades = Comunidades::where('parroquia', $this->solicitud['solicitudParroquia']['parroquia'])->get();
    }

    //open create and open list
    public function setActiveTab($tab = 'create')
    {
        if($tab === 'create' && !$this->canCreateSolicitud()){
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para crear una solicitud',
            ]);
            return;
        }

        $this->resetForm();
        
        $this->activeTab = $tab;
    }

    public function submit()
    {

        if(!$this->solicitud['titulo']){
            return;
        }

        $this->validate();
        
        try {
            if ($this->editingSolicitud && Auth::user()->isSuperAdministrador()) {
                    
                if (!$this->canEditSolicitud($this->editingSolicitud)) {
                    $this->dispatch('error-toast',[
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
                    'estatus' => $this->solicitud['solicitudEstatus'],
                    'fecha_actualizacion_super_admin' => now(),
                    'derecho_palabra' => $this->solicitud['derecho_palabra'],
                    'observaciones_admin' => $this->solicitud['observaciones_admin'],
                ]);
                
                $this->dispatch('show-toast',[
                    'type' => 'success',
                    'message' => 'Solicitud actualizada exitosamente',
                ]);

            } elseif(Auth::user()->isSuperAdministrador()) {

                if (!$this->canCreateSolicitud()) {
                    $this->dispatch('error-toast',[
                        'type' => 'error',
                        'message' => 'No tienes permisos para crear una solicitud',
                    ]);
                    return;
                }

                $persona = Personas::find($this->personalData['cedula']);

                if (!$persona) {
                    Personas::create([
                        'cedula' => $this->personalData['cedula'],
                        'nombre' => $this->personalData['nombre'],
                        'apellido' => $this->personalData['apellido'],
                        'telefono' => $this->personalData['prefijo'] . '-' . $this->personalData['telefono'],
                        'email' => $this->personalData['email'],
                        'nacionalidad' => $this->personalData['nacionalidad'],
                    ]);
                }
                
                $solicitudId = Solicitud::generateSolicitudId($this->personalData['cedula']);
                
                Solicitud::create([
                    'solicitud_id' => $solicitudId,
                    'titulo' => Str::title($this->solicitud['titulo']),
                    'descripcion' => $this->solicitud['descripcion'],
                    'subcategoria' => $this->solicitud['solicitudCategoria']['subcategoria'],
                    'tipo_solicitud' => $this->solicitud['tipo_solicitud'],
                    'persona_cedula' => $this->personalData['cedula'],
                    'pais' => $this->solicitud['pais'],
                    'estado_region' => $this->solicitud['estado_region'],
                    'municipio' => $this->solicitud['municipio'],
                    'comunidad' => $this->solicitud['solicitudParroquia']['comunidad'],
                    'direccion_detallada' => $this->solicitud['direccion_detallada'],
                    'estatus' => Estatus::where('sector_sistema', 'solicitudes')->where('estatus', 'Pendiente')->value('estatus_id'),
                    'fecha_creacion' => now(),
                    'derecho_palabra' => $this->solicitud['derecho_palabra'],
                    'observaciones_admin' => $this->solicitud['observaciones_admin'],
                ]);

                $this->dispatch('show-toast',[
                    'type' => 'success',
                    'message' => 'Solicitud creada exitosamente con ID: ' . $solicitudId,
                ]);
            }else{
                $this->dispatch('error-toast',[
                    'type' => 'error',
                    'message' => 'Ops, algo salio mal',
                ]);
                return;
            }
            
            $this->resetForm();
            $this->setActiveTab('list');
            
        } catch (\Exception $e) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ]);
        }
    }

    public function editSolicitud($solicitudId)
    {
        $solicitud = Solicitud::with('persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion')->find($solicitudId);
        
        if (!$solicitud) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }
        
        if (!$this->canEditSolicitud($solicitud)) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para editar esta solicitud',
            ]);
            return;
        }

        if (!$solicitud->persona) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Error de datos: La solicitud con ID ' . $solicitudId . ' no tiene una persona asociada válida.',
            ]);
            return;
        }

        $this->resetForm();

        $this->personalData = [
            'cedula' => $solicitud->persona->cedula,
            'nombre' => ($solicitud->persona->nombre ?? ''), 
            'apellido' => ($solicitud->persona->apellido ?? ''),
            'telefono' => $solicitud->persona->telefono,
            'email' => $solicitud->persona->email,
            'nacionalidad' => $solicitud->persona->nacionalidad,
        ];
        
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
        $this->showSolicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion', 'reunionRelacion'])
            ->find($solicitudId);
        
        if (!$this->showSolicitud) {
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }
        
        if (!$this->canViewSolicitud($this->showSolicitud)) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para ver esta solicitud',
            ]);
            return;
        }
        
        $this->activeTab = 'show';
    }

    public function confirmDelete($solicitudId)
    {
        $solicitud = Solicitud::with('persona')->find($solicitudId);
        
        if (!$solicitud) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }

        if (!$this->canDeleteSolicitud($solicitud)) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para eliminar esta solicitud',
            ]);
            return;
        }
        
        $this->deleteSolicitud = $solicitud;
    }

    public function deleteSolicitudDefinitive($solicitudId)
    {
        $solicitud = Solicitud::find($solicitudId);
        
        if (!$solicitud) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }

        if (!$this->canDeleteSolicitud($solicitud)) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para eliminar esta solicitud',
            ]);
            return;
        }
        
        try {
            
            if ($solicitud && Auth::user()->isSuperAdministrador()) {

                $solicitud->delete();
                
                $this->dispatch('show-toast',[
                    'type' => 'success',
                    'message' => 'Solicitud eliminada exitosamente',
                ]);
                
                $this->resetForm();
                $this->activeTab = 'list';
                $this->loadSolicitudes();
            }
            
        } catch (\Exception $e) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Error al eliminar la solicitud: ' . $e->getMessage(),
            ]);
            $this->deleteSolicitud = null;
        }
    }

    public function cancelDelete()
    {
        $this->deleteSolicitud = null;
    }

    public function updateStatus($solicitudId, $newStatus)
    {
        $solicitud = Solicitud::find($solicitudId);

        $estatus = Estatus::find($newStatus);
        
        if (!$newStatus) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Estatus no encontrado',
            ]);
            return;
        }
        
        if (!$solicitud) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Solicitud no encontrada',
            ]);
            return;
        }
        
        if (!Auth::user()->isSuperAdministrador()) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para cambiar el estado',
            ]);
            return;
        }
        
        try {
            $solicitud->update([
                'estatus' => $estatus->estatus_id,
                'fecha_actualizacion_super_admin' => now()
            ]);
            
            $this->loadSolicitudes();

            $this->dispatch('show-toast',[
                'type' => 'success',
                'message' => 'Estatus actualizado exitosamente',
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Error al actualizar el estado: ' . $e->getMessage(),
            ]);
        }
    }

    public function resetForm()
    {
        $this->personalData = [
            'cedula' => '',
            'nombre' => '',
            'apellido' => '',
            'telefono' => '',
            'prefijo' => '',
            'email' => '',
            'nacionalidad' => '',
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

        $this->parroquias = [];

        $this->mensajeSolcitante = null;
        $this->showSolicitud = null;
        $this->editingSolicitud = null;
        $this->deleteSolicitud = null;
        $this->resetValidation();
    }

    // Permission check methods
    private function canCreateSolicitud()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdministrador()){
            return true;
        }

        if ($user->isAdministrador()) {
            return false; // Admins can only view
        }
        
        return false; // Regular users can create
    }

    private function canViewSolicitud($solicitud)
    {
        $user = Auth::user();
        
        if ($user->isSuperAdministrador() || $user->isAdministrador()) {
            return true;
        }
        
        return $solicitud->persona_cedula === $user->persona_cedula;
    }

    private function canEditSolicitud($solicitud)
    {
        $user = Auth::user();
        
        if ($user->isSuperAdministrador()) {
            return true;
        }
        
        if ($user->isAdministrador()) {
            return false; // Admins can only view
        }
        
        return $solicitud->persona_cedula === $user->persona_cedula;
    }

    private function canDeleteSolicitud($solicitud)
    {
        $user = Auth::user();
        
        if ($user->isSuperAdministrador()) {
            return true;
        }
        
        if ($user->isAdministrador()) {
            return false; // Admins can only view
        }
        
        return $solicitud->persona_cedula === $user->persona_cedula;
    }
    
   /*  ordernar labla */
    public function orden($sort)
    {
        if ($this->sort == $sort) {
            $this->direction = ($this->direction == 'asc') ? 'desc' : 'asc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function ordenEstados($estado){
        
        $this->estatusSolicitud = $estado;

        if($this->estatusSolicitud === 0){
            $this->estatusName = 'Todos';
        }else{
            $name = Estatus::find($estado);
            $this->estatusName = Str::title($name->estatus);
        }
    }

    public function donwloadPDFSolicitud($solicitud_id){
        $solicitud = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])->find($solicitud_id);

        $pdf = PDF::loadView('pdf.solicitudes.detalle-solicitud', compact('solicitud'));
        
        $filename = 'solicitud_' . $solicitud_id . '.pdf';
        
        $this->dispatch('show-toast', [
            'message' => 'Exportación a PDF completada',
            'type' => 'success'
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function donwloadPDFSolicitudes()
    {
        $pdfChunks = new Collection();
        $page = 1;

        $solicitudess = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])->orderBy('fecha_creacion', 'desc');

        $solicitudess->chunk(10, function (Collection $solicitudes) use (&$pdfChunks, &$page) {
            $html = view('pdf.solicitudes.lista-completa-solicitudes', [
                'solicitudes' => $solicitudes, 
                'page' => $page,
            ])->render();

            $pdfChunks->push($html);
            $page++;
        });

        $pdf = PDF::loadView('pdf.solicitudes.reporte-base',[
            'chunks' => $pdfChunks->implode(''), 
        ]);
        
        $filename = 'registro_solicitudes_' . now()->format('Ymd_His') . '.pdf';
        
        $this->dispatch('show-toast', [
            'message' => 'Exportación a PDF completada',
            'type' => 'success'
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function exportExcel()
    {
        $usuarioGenerador = collect([
            [
                'N° Ticket' => 'Registro Generado el:',
                'Título' => now()->format('d-m-Y H:i'),
                'Descripcion' => 'Reporte generado por el sistema de gestión de solicitudes del CMBEY', 'Estatus' => '', 'Categoría' => '', 'Subcategoría' => '',
                'Tipo de Solicitud' => '', 'Derecho de Palabra' => '', 'Solicitante' => '', 'Cédula' => '', 
                'País' => '', 'Estado/Región' => '', 'Municipio' => '', 'Parroquia' => '', 'Comunidad' => '', 'Dirección' => '', 
                'Observaciones Administrativas' => '', 'Asignación a Visitas' => '', 'Fecha de Creación' => '',
            ],[
                'N° Ticket' => 'Generado por:',
                'Título' => Auth::user()->persona->nombre . ' ' . Auth::user()->persona->apellido . ' ' . '(' . Auth::user()->getRoleNameColoquela() . ')', 
                'Descripcion' => '', 'Estatus' => '', 'Categoría' => '', 'Subcategoría' => '',
                'Tipo de Solicitud' => '', 'Derecho de Palabra' => '', 'Solicitante' => '', 'Cédula' => '', 
                'País' => '', 'Estado/Región' => '', 'Municipio' => '', 'Parroquia' => '', 'Comunidad' => '', 'Dirección' => '', 
                'Observaciones Administrativas' => '', 'Asignación a Visitas' => '', 'Fecha de Creación' => '',
            ],

            // Fila vacía para separación
            array_fill_keys(array_keys((new Solicitud)->toArray()), '') 
        ]);

        $solicitudes = Solicitud::with(['persona', 'subcategoriaRelacion', 'comunidadRelacion', 'estatusRelacion'])
        ->orderBy('fecha_creacion', 'desc')->get()->map(function ($solicitud) {
            return [         
                'N° Ticket' => $solicitud->solicitud_id,
                'Título' => Str::title($solicitud->titulo),
                'Descripcion' => $solicitud->descripcion,
                'Estatus' => $solicitud->getEstatusFormattedAttribute(),
                'Categoría' => $solicitud->subcategoriaRelacion->getCategoriaFormattedAttribute(),
                'Subcategoría' => $solicitud->subcategoriaRelacion->getSubcategoriaFormattedAttribute(),
                'Tipo de Solicitud' => $solicitud->getTipoSolicitudFormattedAttribute(),
                'Derecho de Palabra' => ($solicitud->derecho_palabra ? 'Solicitada' : 'No Solicitada'),
                'Solicitante' => $solicitud->persona->nombre . ' ' . $solicitud->persona->segundo_nombre . ' ' . $solicitud->persona->apellido . ' ' . $solicitud->persona->segundo_apellido,
                'Cédula' => $solicitud->persona->nacionalidadTransform() . '-' . $solicitud->persona->cedula,
                'País' => Str::title($solicitud->pais),
                'Estado/Región' => Str::title($solicitud->estado_region),
                'Municipio' => Str::title($solicitud->municipio),
                'Parroquia' => Str::title($solicitud->comunidadRelacion->parroquia),
                'Comunidad' => Str::title($solicitud->comunidad),
                'Dirección' => $solicitud->direccion_detallada ?? 'N/A',
                'Observaciones Administrativas' => $solicitud->observaciones_admin ?? 'N/A',
                'Asignación a Visitas' => ($solicitud->asignada_visita ? 'Asignada' : 'No Asignada'),
                'Fecha de Creación' => $solicitud->fecha_creacion->format('d-m-Y H:i') ?? 'N/A',
            ];
        });

        $export = $usuarioGenerador->concat($solicitudes);

        $header_style = (new Style())
            ->setFontBold()
            ->setBackgroundColor("EDEDED");

        $this->dispatch('show-toast', [
            'message' => 'Exportación a Excel completada',
            'type' => 'success'
        ]);
        
        return (new FastExcel($export))->configureOptionsUsing(function (AbstractOptions $options) {
                $options->setColumnWidth(20, 1);
                $options->setColumnWidth(45, 2);
                $options->setColumnWidth(40, 3);
                $options->setColumnWidth(15, 6);
                $options->setColumnWidth(20, 7);
                $options->setColumnWidth(20, 8);
                $options->setColumnWidth(30, 9);
                $options->setColumnWidth(15, 12);
                $options->setColumnWidth(20, 14);
                $options->setColumnWidth(20, 15);
                $options->setColumnWidth(30, 16);
                $options->setColumnWidth(45, 17);
                $options->setColumnWidth(20, 18);
                $options->setColumnWidth(20, 19);
            })
            ->headerStyle($header_style)
            ->download('reporte_solicitudes_' . now()->format('Ymd_His') . '.xlsx');
    }

    private function loadEstatus()
    {
        return Estatus::where('sector_sistema', 'solicitudes')->get();
    }

    public function buscarSolicitante()
    {
        $solicitante = Personas::where('cedula', $this->personalData['cedula'])->exists();

        if($this->personalData['cedula']){
            if($solicitante){
                $this->mensajeSolcitante = 1; 
            }else{
                $this->mensajeSolcitante = 2; 
            }
        }
    }

    public function rellenarDatosSolicitante()
    {
        $solicitante = Personas::where('cedula', $this->personalData['cedula'])->first();

        $telefono = explode('-', $solicitante->telefono);

        $this->personalData = [
            'cedula' => $solicitante->cedula,
            'nombre' => $solicitante->nombre,
            'apellido' => $solicitante->apellido,
            'telefono' => ($telefono[1].'-'.$telefono[2]) ?? $solicitante->telefono,
            'prefijo' => $telefono[0],
            'email' => $solicitante->email,
            'nacionalidad' => $solicitante->nacionalidad,
        ];

        $this->resetValidation(); 
    }

    public function render()
    {
        $solicitudesRender = $this->loadSolicitudes();

        if($this->activeTab === 'create' || $this->activeTab === 'edit'){
            $this->loadDatos();
            $this->buscarSolicitante();
        }
        
        $estatus = $this->loadEstatus();

        return view('livewire.dashboard.super-admin-solicitudes' , [
            'solicitudesRender' => $solicitudesRender,
            'estatus' => $estatus
        ])->layout('components.layouts.rbac');
    }
}