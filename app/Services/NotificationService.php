<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Reunion;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notificar a los solicitantes sobre una reunión asignada
     */
    public function notificarSolicitantesReunion(Reunion $reunion, $enviarEmail = false)
    {
        $solicitudes = $reunion->solicitudes;

        if ($solicitudes->isEmpty()) {
            return;
        }

        foreach ($solicitudes as $solicitud) {
            // Obtener la persona del solicitante
            $persona = $solicitud->persona;
            
            if (!$persona) {
                continue;
            }

            // Crear notificación interna
            $this->crearNotificacionInterna(
                $persona->cedula,
                $reunion,
                $solicitud
            );

            // Enviar email si está habilitado
            if ($enviarEmail && $persona->email) {
                $this->enviarEmailNotificacion($persona, $reunion, $solicitud);
            }
        }
    }

    /**
     * Crear notificación interna en el sistema
     */
    private function crearNotificacionInterna($personaCedula, Reunion $reunion, Solicitud $solicitud)
    {
        $fechaReunion = $reunion->fecha_reunion->format('d/m/Y');
        $horaReunion = $reunion->hora_reunion;

        Notificacion::create([
            'persona_cedula' => $personaCedula,
            'tipo' => 'reunion',
            'titulo' => 'Convocatoria a Reunión: ' . $reunion->titulo,
            'mensaje' => "Ha sido convocado(a) a la reunión '{$reunion->titulo}' programada para el {$fechaReunion} a las {$horaReunion}. Esta reunión está relacionada con su solicitud: {$solicitud->titulo}.",
            'reunion_id' => $reunion->id,
            'solicitud_id' => $solicitud->solicitud_id,
            'leida' => false
        ]);

        Log::info("Notificación interna creada para persona {$personaCedula} - Reunión ID: {$reunion->id}");
    }

    /**
     * Enviar notificación por email
     */
    private function enviarEmailNotificacion($persona, Reunion $reunion, Solicitud $solicitud)
    {
        try {
            $fechaReunion = $reunion->fecha_reunion->format('d/m/Y');
            $horaReunion = $reunion->hora_reunion;

            $data = [
                'nombre' => $persona->nombre . ' ' . $persona->apellido,
                'titulo_reunion' => $reunion->titulo,
                'fecha' => $fechaReunion,
                'hora' => $horaReunion,
                'descripcion' => $reunion->descripcion,
                'solicitud_titulo' => $solicitud->titulo,
                'solicitud_id' => $solicitud->solicitud_id
            ];

            Mail::send('emails.convocatoria-reunion', $data, function ($message) use ($persona, $reunion) {
                $message->to($persona->email, $persona->nombre . ' ' . $persona->apellido)
                        ->subject('Convocatoria a Reunión: ' . $reunion->titulo);
            });

            Log::info("Email enviado a {$persona->email} para reunión ID: {$reunion->id}");
        } catch (\Exception $e) {
            Log::error("Error al enviar email: " . $e->getMessage());
        }
    }

    /**
     * Obtener notificaciones no leídas de una persona
     */
    public function obtenerNotificacionesNoLeidas($personaCedula)
    {
        return Notificacion::where('persona_cedula', $personaCedula)
                          ->where('leida', false)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida($notificacionId)
    {
        $notificacion = Notificacion::find($notificacionId);
        
        if ($notificacion) {
            $notificacion->marcarComoLeida();
            return true;
        }

        return false;
    }

    /**
     * Notificar a un solicitante individual sobre una reunión
     */
    public function notificarSolicitanteIndividual(Reunion $reunion, Solicitud $solicitud, $enviarEmail = false)
    {
        // Obtener la persona del solicitante
        $persona = $solicitud->persona;
        
        if (!$persona) {
            throw new \Exception('No se encontró información del solicitante');
        }

        // Crear notificación interna
        $this->crearNotificacionInterna(
            $persona->cedula,
            $reunion,
            $solicitud
        );

        // Enviar email si está habilitado
        if ($enviarEmail && $persona->email) {
            $this->enviarEmailNotificacion($persona, $reunion, $solicitud);
        }

        return true;
    }
}
