<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Models\Solicitud;
use App\Models\VisitasVisita;
use App\Models\VisitaEquipo;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Component;

class Visitas extends Component
{
    use WithPagination; 

    public $openSolicitudId = null;
    public $selectedSolicitud;
    public $userCedula;
    
    public $showObservationForm = false;
    public $finalObservation = '';
    public $solicitudToFinalize = null; 

    public function mount()
    {
        $this->userCedula = Auth::user()->persona_cedula;
    }

    public function toggleDetails($solicitudId)
    {
        $this->reset(['showObservationForm', 'finalObservation', 'solicitudToFinalize']); 
        
        if ($this->openSolicitudId === $solicitudId) {
            $this->openSolicitudId = null;
            $this->selectedSolicitud = null;
        } else {
            
            $this->selectedSolicitud = Solicitud::with(['visita', 'subcategoriaRelacion', 'comunidadRelacion', 'visita.asistente'])
                                                 ->where('solicitud_id', $solicitudId)
                                                 // Aseguramos que la solicitud tenga una visita asignada
                                                 ->whereHas('visita') 
                                                 // Aseguramos que el usuario logueado sea el solicitante O un asistente asignado
                                                 ->where(function ($query) {
                                                     $query->where('persona_cedula', $this->userCedula) 
                                                           ->orWhereHas('visita.asistente', function ($q) {
                                                               $q->where('cedula', $this->userCedula); 
                                                           });
                                                 })
                                                 ->first();

            $this->openSolicitudId = $solicitudId;
        }
    }
    
    public function updatingOpenSolicitudId()
    {
        $this->resetPage();
    }
    
    public function openFinishForm($solicitudId)
    {
        $this->solicitudToFinalize = $solicitudId;
        $this->finalObservation = $this->selectedSolicitud->observaciones_admin ?? ''; 
        $this->showObservationForm = true;
    }

    public function finishVisit()
    {
        $solicitudId = $this->solicitudToFinalize;

        if (!$solicitudId) {
            $this->dispatch('show-toast', ['message' => 'Error: ID de solicitud faltante.', 'type' => 'error']);
            return;
        }
        
        $this->validate([
            'finalObservation' => 'required|string|min:10',
        ], [
            'finalObservation.required' => 'La observación es obligatoria para finalizar la visita.',
            'finalObservation.min' => 'La observación debe tener al menos 10 caracteres.',
        ]);

        $visita = VisitasVisita::where('solicitud_id', $solicitudId)->first();
        $solicitud = Solicitud::where('solicitud_id', $solicitudId)->first();

        if ($visita && $solicitud) {
            $isAsistente = $visita->asistente()->where('cedula', $this->userCedula)->exists();

            if ($isAsistente) {
                
                $visita->estatus_id = 6;
                $visita->save();
                
                $solicitud->observaciones_admin = $this->finalObservation;
                $solicitud->save();
                
                $this->reset(['openSolicitudId', 'selectedSolicitud', 'showObservationForm', 'finalObservation', 'solicitudToFinalize']);
                $this->dispatch('show-toast', ['message' => 'Visita finalizada y observación guardada.', 'type' => 'success']);
                
            } else {
                $this->dispatch('show-toast', ['message' => 'Error: No tiene permisos para finalizar esta visita.', 'type' => 'error']);
            }
        } else {
            $this->dispatch('show-toast', ['message' => 'Error: Visita o Solicitud no encontrada.', 'type' => 'error']);
        }
    }
    
    public function updateStatus($solicitudId, $newStatusId)
    {
        $visita = VisitasVisita::where('solicitud_id', $solicitudId)->first();

        if ($visita) {
            $isAsistente = $visita->asistente()->where('cedula', $this->userCedula)->exists();

            if ($isAsistente) {
                $visita->estatus_id = $newStatusId;
                $visita->save();
                
                $this->openSolicitudId = null; 
                $this->selectedSolicitud = null;

                $this->dispatch('show-toast', ['message' => 'Estado de la visita actualizado correctamente.', 'type' => 'success']);
            } else {
                $this->dispatch('show-toast', ['message' => 'Error: No tiene permisos para cambiar el estado de esta visita.', 'type' => 'error']);
            }
        } else {
            $this->dispatch('show-toast', ['message' => 'Error: Visita no encontrada.', 'type' => 'error']);
        }
    }


    public function render()
    {
        $visitasPaginadas = Solicitud::with(['visita.asistente', 'subcategoriaRelacion', 'comunidadRelacion'])
            // 1. Solo mostrar solicitudes que tienen una visita asociada (visita creada/programada)
            ->whereHas('visita')
            // 2. Solo mostrar si el usuario es el solicitante original O si es un asistente asignado
            ->where(function ($query) {
                $query->where('persona_cedula', $this->userCedula) 
                      ->orWhereHas('visita.asistente', function ($q) { 
                          $q->where('cedula', $this->userCedula);
                      });
            })
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(5); 

        return view('livewire.dashboard.visitas', [
            "visita" => $visitasPaginadas, 
            "userCedula" => $this->userCedula
        ])->layout('components.layouts.rbac');
    }
}