<?php

namespace App\Livewire\Dashboard;

use App\Models\Solicitud;
use App\Models\Categorias;
use App\Models\Comunidades;
use App\Models\Estatus;
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

class AdministradorSolicitudes extends Component
{
       use WithPagination;

    protected $paginationTheme = 'disenoPagination'; 

    public $search = '', $sort = 'fecha_creacion', $direction = 'desc';
    public $estatusSolicitud = 0, $estatusName = 'Todos';

    public $activeTab = 'list';
    public $showSolicitud = null;

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
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'Error al cargar las solicitudes: No tienes permisos para ver esta sección',
            ]);
        }
    }

    //open create and open list
    public function setActiveTab($tab = 'create')
    {
        $this->resetForm();
        
        $this->activeTab = $tab;
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
            $this->dispatch('show-toast',[
                'type' => 'error',
                'message' => 'No tienes permisos para ver esta solicitud',
            ]);
            return;
        }
        
        $this->activeTab = 'show';
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

    public function resetForm()
    {
        $this->showSolicitud = null;
    }

    private function canViewSolicitud($solicitud)
    {
        $user = Auth::user();
        
        if ($user->isSuperAdministrador() || $user->isAdministrador()) {
            return true;
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

    private function loadEstatus()
    {
        return Estatus::where('sector_sistema', 'solicitudes')->get();
    }

    public function render()
    {
        $solicitudesRender = $this->loadSolicitudes();
        
        $estatus = $this->loadEstatus();

        return view('livewire.dashboard.administrador-solicitudes' , [
            'solicitudesRender' => $solicitudesRender,
            'estatus' => $estatus
        ])->layout('components.layouts.rbac');
    }
}
