<?php

namespace App\Livewire\Dashboard\Trabajadores;

use Livewire\Component;
use App\Models\Trabajador;
use Barryvdh\DomPDF\Facade\Pdf;

class Show extends Component
{
    public $personaCedula;

    public $trabajador;

    //VARIABLES PARA QUE FUNCIONE EL DESTROY
    public $persona_cedula;
    public $cambiarVista;

    public function mount()
    {
        $this->trabajador = Trabajador::with('persona', 'cargo')->where('persona_cedula', $this->personaCedula)->first();
    }

    public function edit($vista, $cedula)
    {
        $this->dispatch('editar-trabajador', vista: $vista, cedula: $cedula);
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

    public function cerrarEliminar() // esta funcion es el boton de cancelar de la carta de confirmar eliminacion de trabajador
    {
        $this->cambiarVista = null; 
    }

    public function regresarAlListado()
    {
        $this->dispatch('regresar-a-listado-trabajadores');
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

    public function render()
    {
        return view('livewire.dashboard.trabajadores.show');
    }
}
