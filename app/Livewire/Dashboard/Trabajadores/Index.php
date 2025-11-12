<?php

namespace App\Livewire\Dashboard\Trabajadores;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Trabajador;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class Index extends Component
{
    //VARIABLE PARA CAMBIAR DE VISTAS
    public $cambiarVista = 'list';

    //VARIABLE PARA EL BUSCADOR
    public $buscador = '';

    //VARIABLE PARA GUARDAR LA CEDULA DEL TRABAJADOR
    public $persona_cedula = '';

    public $cumpleaneros =  '';

    public function index()
    {   
        $trabajadores = Trabajador::with('persona', 'cargo')->orderBy('created_at', 'desc');

        if ($this->buscador) { //buscador
            $trabajadores->where(function ($q) {
                $q->where('persona_cedula', 'like', '%' . $this->buscador . '%')
                    ->orWhere('zona_trabajo', 'like', '%' . $this->buscador . '%');
            })
            ->orWhereHas('persona', function ($q) {
                $q->where('nombre', 'like', '%' . $this->buscador . '%')
                    ->orwhere('apellido', 'like', '%' . $this->buscador . '%')
                    ->orwhere('segundo_nombre', 'like', '%' . $this->buscador . '%')
                    ->orwhere('segundo_apellido', 'like', '%' . $this->buscador . '%')
                    ->orwhere('telefono', 'like', '%' . $this->buscador . '%')
                    ->orwhere('nacimiento', 'like', '%' . $this->buscador . '%');
            })
            ->orWhereHas('cargo', function ($q) {
                $q->where('descripcion', 'like', '%' . $this->buscador . '%');
            });
        }

        return $trabajadores->paginate(10)->onEachSide(-0.5);
    }

    #[On('regresar-a-listado-trabajadores')]
    public function regresarAlListado()
    {
        $this->cambiarVista = 'list';
        $this->persona_cedula = '';    
    }

    //PARA ABRIR EL FORMULARIO DE CREAR
    public function create($vista) // esta variable $vista es la que recibe el dato enviado por la etiqueta de wire:click = create 
    {
        if ($vista === 'create') {
            $this->cambiarVista = $vista; 
        }else{                
            return $this->dispatch('error-toast', [
                'message' => 'Error al cargar el formulario',
                'type' => 'error'
            ]);
        }
    }

    #[On('editar-trabajador')]
    public function edit($vista, $cedula)
    {
        if ($vista === 'edit') {

            $this->cambiarVista = $vista; 
            $this->persona_cedula = $cedula;

        }else{                
            return $this->dispatch('error-toast', [
                'message' => 'Error al cargar el formulario para editar',
                'type' => 'error'
            ]);
        }
    }

    public function show($vista, $cedula)
    {
        if ($vista === 'show') {
            $this->cambiarVista = $vista; 
            $this->persona_cedula = $cedula;
        }else{                
            return $this->dispatch('error-toast', [
                'message' => 'Error al cargar los datos del trabajador',
                'type' => 'error'
            ]);
        }
    }

    public function destroy($vista, $cedula)
    {
        if ($vista === 'destroy') {
            $this->cambiarVista = $vista; 
            $this->persona_cedula = Trabajador::with('persona')->find($cedula);
        }else{                
            return $this->dispatch('error-toast', [
                'message' => 'Error al eliminar al trabajdador',
                'type' => 'error'
            ]);
        }
    }

    public function destroyDefinitivo($cedula)
    {
        try {
            $trabajador = Trabajador::where('persona_cedula', $cedula)->firstOrFail();

            $trabajador->delete();

            $this->regresarAlListado();

            return $this->dispatch('show-toast',[
                'type' => 'success',
                'message' => 'Trabajador eliminado correctamente',
            ]);
        } catch (\Exception $e) {
            return $this->dispatch('error-toast',[
                'type' => 'error',
                'message' => 'Ops, ocurrio un error al eliminar el trabajador.',
            ]);
        }
    }

    public function exportTodosExcel()
    {
        \Log::info('Método exportExcel ejecutado');

        $trabajadores = Trabajador::with(['persona', 'cargo'])->get()->map(function ($trabajador) {
            return [
                'Cédula' => $trabajador->persona->cedula ?? '',
                'Primer Nombre' => $trabajador->persona->nombre ?? '',
                'Segundo Nombre' => $trabajador->persona->segundo_nombre ?? '',
                'Primer Apellido' => $trabajador->persona->apellido ?? '',
                'Segundo Apellido' => $trabajador->persona->segundo_apellido ?? '',
                'Fecha de Nacimiento' => optional($trabajador->persona->nacimiento)->format('d-m-Y'),
                'Teléfono' => $trabajador->persona->telefono ?? 'N/A',
                'Email' => $trabajador->persona->email ?? 'N/A',
                'Dirección' => $trabajador->persona->direccion ?? 'N/A',
                'Cargo' => $trabajador->cargo->descripcion ?? 'Sin cargo',
                'Zona de trabajo' => $trabajador->zona_trabajo ?? '—',
                'Fecha de registro' => optional($trabajador->created_at)->format('d-m-Y H:i'),
            ];
        });

        return (new FastExcel($trabajadores))->download('reporte_trabajadores_' . now()->format('Ymd_His') . '.xlsx');
    }
        

    public function exportPdf($persona_cedula)
    {
        $trabajador = Trabajador::with('persona')->where('persona_cedula', $persona_cedula)->firstOrFail();

        $pdf = Pdf::loadView('pdf.trabajadores.detalle-trabajador', ['trabajador' => $trabajador]);

        $filename = 'reporte_trabajador_' . $trabajador->persona->cedula . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function exportarTodosPdf()
    {
        $trabajadores = Trabajador::with(['persona', 'cargo'])->get();

        $pdf = Pdf::loadView('pdf.trabajadores.trabajadores_completo', ['trabajadores' => $trabajadores]);

        $filename = 'reporte_trabajadores_' . now()->format('Ymd_His'). '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render() // siempre actualiza la vista cuando hay un cambio livewire
    {
        return view('livewire.dashboard.trabajadores.index',[
            'trabajadores' => $this->index(),
        ])->layout('components.layouts.rbac');
    }
}
