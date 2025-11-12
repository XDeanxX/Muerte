<?php

namespace App\Services;

use App\Models\Reunion;
use App\Models\Personas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReunionValidationService
{
    /**
     * Validate if a person (by cedula) is available for a meeting at the given time.
     *
     * @param string $cedula
     * @param string $fechaReunion (Y-m-d format)
     * @param string $horaInicio (H:i:s format)
     * @param string $horaFin (H:i:s format)
     * @param int|null $excludeReunionId
     * @return array ['available' => bool, 'conflictos' => array]
     */
    public function validarDisponibilidadPersona(
        string $cedula, 
        string $fechaReunion, 
        string $horaInicio, 
        string $horaFin, 
        ?int $excludeReunionId = null
    ): array {
        // Get all meetings this person (concejal) is assigned to on the same date
        $query = Reunion::whereDate('fecha_reunion', $fechaReunion)
            ->whereHas('concejales', function ($q) use ($cedula) {
                $q->where('persona_cedula', $cedula);
            });
        
        if ($excludeReunionId) {
            $query->where('id', '!=', $excludeReunionId);
        }
        
        $reunionesExistentes = $query->get();
        
        $conflictos = [];
        
        foreach ($reunionesExistentes as $reunion) {
            // Calcular hora_fin temporal: hora_reunion + 4 horas (bloqueo de slot)
            $reunionHoraInicio = $reunion->hora_reunion;
            $reunionHoraFin = Carbon::parse($reunion->hora_reunion)->addHours(4)->format('H:i:s');
            
            if ($this->horariosSeSolapan($horaInicio, $horaFin, $reunionHoraInicio, $reunionHoraFin)) {
                $persona = Personas::where('cedula', $cedula)->first();
                $conflictos[] = [
                    'reunion_id' => $reunion->id,
                    'reunion_titulo' => $reunion->titulo,
                    'persona_nombre' => $persona ? $persona->nombre . ' ' . $persona->apellido : 'Persona ' . $cedula,
                    'hora_inicio' => $reunion->hora_reunion,
                    'hora_fin' => $reunionHoraFin,
                ];
            }
        }
        
        return [
            'available' => count($conflictos) === 0,
            'conflictos' => $conflictos,
        ];
    }

    /**
     * Validate if a location is available at the given date and time.
     *
     * @param string $ubicacion
     * @param string $fechaReunion
     * @param string $horaInicio
     * @param string $horaFin
     * @param int|null $excludeReunionId
     * @return array ['available' => bool, 'conflictos' => array]
     */
    public function validarDisponibilidadEspacio(
        string $ubicacion, 
        string $fechaReunion, 
        string $horaInicio, 
        string $horaFin, 
        ?int $excludeReunionId = null
    ): array {
        $query = Reunion::whereDate('fecha_reunion', $fechaReunion)
            ->where('ubicacion', $ubicacion);
        
        if ($excludeReunionId) {
            $query->where('id', '!=', $excludeReunionId);
        }
        
        $reunionesExistentes = $query->get();
        
        $conflictos = [];
        
        foreach ($reunionesExistentes as $reunion) {
            // Calcular hora_fin temporal: hora_reunion + 4 horas (bloqueo de slot)
            $reunionHoraInicio = $reunion->hora_reunion;
            $reunionHoraFin = Carbon::parse($reunion->hora_reunion)->addHours(4)->format('H:i:s');
            
            if ($this->horariosSeSolapan($horaInicio, $horaFin, $reunionHoraInicio, $reunionHoraFin)) {
                $conflictos[] = [
                    'reunion_id' => $reunion->id,
                    'reunion_titulo' => $reunion->titulo,
                    'ubicacion' => $reunion->ubicacion,
                    'hora_inicio' => $reunion->hora_reunion,
                    'hora_fin' => $reunionHoraFin,
                ];
            }
        }
        
        return [
            'available' => count($conflictos) === 0,
            'conflictos' => $conflictos,
        ];
    }

    /**
     * Validate availability for multiple people at once.
     *
     * @param array $cedulas Array of cedulas
     * @param string $fechaReunion
     * @param string $horaInicio
     * @param string $horaFin
     * @param int|null $excludeReunionId
     * @return array ['all_available' => bool, 'resultados' => array]
     */
    public function validarDisponibilidadMultiple(
        array $cedulas, 
        string $fechaReunion, 
        string $horaInicio, 
        string $horaFin, 
        ?int $excludeReunionId = null
    ): array {
        $resultados = [];
        $todosDisponibles = true;
        
        foreach ($cedulas as $cedula) {
            $resultado = $this->validarDisponibilidadPersona(
                $cedula, 
                $fechaReunion, 
                $horaInicio, 
                $horaFin, 
                $excludeReunionId
            );
            
            if (!$resultado['available']) {
                $todosDisponibles = false;
            }
            
            $resultados[$cedula] = $resultado;
        }
        
        return [
            'all_available' => $todosDisponibles,
            'resultados' => $resultados,
        ];
    }

    /**
     * Validate availability for multiple solicitudes (and their personas).
     *
     * @param array $solicitudIds
     * @param string $fechaReunion
     * @param string $horaInicio
     * @param string $horaFin
     * @param int|null $excludeReunionId
     * @return array ['all_available' => bool, 'solicitudes_validas' => array, 'solicitudes_conflictos' => array]
     */
    public function validarDisponibilidadSolicitudes(
        array $solicitudIds, 
        string $fechaReunion, 
        string $horaInicio, 
        string $horaFin, 
        ?int $excludeReunionId = null
    ): array {
        $solicitudesValidas = [];
        $solicitudesConflictos = [];
        
        foreach ($solicitudIds as $solicitudId) {
            $solicitud = \App\Models\Solicitud::where('solicitud_id', $solicitudId)
                ->with(['persona'])
                ->first();
            
            if (!$solicitud) {
                continue;
            }
            
            $personasCedulas = collect();
            
            // Add main persona
            if ($solicitud->persona_cedula) {
                $personasCedulas->push($solicitud->persona_cedula);
            }
            
            $personasCedulas = $personasCedulas->unique()->toArray();
            
            if (empty($personasCedulas)) {
                $solicitudesValidas[] = [
                    'solicitud_id' => $solicitudId,
                    'solicitud_titulo' => $solicitud->titulo,
                    'mensaje' => 'Sin personas asociadas',
                ];
                continue;
            }
            
            // Validate all personas
            $resultado = $this->validarDisponibilidadMultiple(
                $personasCedulas,
                $fechaReunion,
                $horaInicio,
                $horaFin,
                $excludeReunionId
            );
            
            if ($resultado['all_available']) {
                $solicitudesValidas[] = [
                    'solicitud_id' => $solicitudId,
                    'solicitud_titulo' => $solicitud->titulo,
                    'personas_count' => count($personasCedulas),
                ];
            } else {
                $solicitudesConflictos[] = [
                    'solicitud_id' => $solicitudId,
                    'solicitud_titulo' => $solicitud->titulo,
                    'conflictos' => $resultado['resultados'],
                ];
            }
        }
        
        return [
            'all_available' => count($solicitudesConflictos) === 0,
            'solicitudes_validas' => $solicitudesValidas,
            'solicitudes_conflictos' => $solicitudesConflictos,
        ];
    }

    /**
     * Check if two time ranges overlap.
     *
     * @param string $inicio1
     * @param string $fin1
     * @param string $inicio2
     * @param string $fin2
     * @return bool
     */
    private function horariosSeSolapan(string $inicio1, string $fin1, string $inicio2, string $fin2): bool
    {
        $inicio1 = Carbon::parse($inicio1);
        $fin1 = Carbon::parse($fin1);
        $inicio2 = Carbon::parse($inicio2);
        $fin2 = Carbon::parse($fin2);
        
        // Two ranges overlap if: start1 < end2 AND start2 < end1
        return $inicio1->lt($fin2) && $inicio2->lt($fin1);
    }

    /**
     * Validar que la fecha de reunión sea futura.
     *
     * @param string $fechaReunion (Y-m-d format)
     * @return array ['valid' => bool, 'mensaje' => string]
     */
    public function validarFechaFutura(string $fechaReunion): array
    {
        $fecha = Carbon::parse($fechaReunion);
        $hoy = Carbon::today();
        
        if ($fecha->lte($hoy)) {
            return [
                'valid' => false,
                'mensaje' => 'La fecha de la reunión debe ser posterior al día de hoy.'
            ];
        }
        
        return [
            'valid' => true,
            'mensaje' => ''
        ];
    }

    /**
     * Validar frecuencia de asambleas (máximo 1 por semana).
     *
     * @param string $fechaReunion
     * @param int|null $excludeReunionId
     * @return array ['valid' => bool, 'mensaje' => string, 'asambleas' => array]
     */
    public function validarFrecuenciaAsamblea(string $fechaReunion, ?int $excludeReunionId = null): array
    {
        $fecha = Carbon::parse($fechaReunion);
        
        // Obtener inicio y fin de la semana
        $inicioSemana = $fecha->copy()->startOfWeek();
        $finSemana = $fecha->copy()->endOfWeek();
        
        // Buscar asambleas en esa semana (tipo_reunion = 1 para 'Asamblea')
        $query = Reunion::whereBetween('fecha_reunion', [$inicioSemana, $finSemana])
            ->where('tipo_reunion', 1); // ID 1 = Asamblea
        
        if ($excludeReunionId) {
            $query->where('id', '!=', $excludeReunionId);
        }
        
        $asambleasEnSemana = $query->get();
        
        if ($asambleasEnSemana->count() > 0) {
            $fechasConflicto = $asambleasEnSemana->map(function ($reunion) {
                return $reunion->fecha_reunion->format('d/m/Y') . ' - ' . $reunion->titulo;
            })->join(', ');
            
            return [
                'valid' => false,
                'mensaje' => "Ya existe una asamblea programada en esta semana ({$inicioSemana->format('d/m/Y')} - {$finSemana->format('d/m/Y')}): {$fechasConflicto}",
                'asambleas' => $asambleasEnSemana->toArray()
            ];
        }
        
        return [
            'valid' => true,
            'mensaje' => '',
            'asambleas' => []
        ];
    }

    /**
     * Validar que un concejal no tenga más de 1 reunión por día (bloqueo de 24 horas).
     *
     * @param array $concejalesCedulas Array of cedulas
     * @param string $fechaReunion
     * @param int|null $excludeReunionId
     * @return array ['valid' => bool, 'mensaje' => string, 'conflictos' => array]
     */
    public function validarDisponibilidadConcejalesPorDia(
        array $concejalesCedulas, 
        string $fechaReunion, 
        ?int $excludeReunionId = null
    ): array {
        $fecha = Carbon::parse($fechaReunion);
        $conflictos = [];
        
        foreach ($concejalesCedulas as $cedula) {
            // Buscar reuniones del concejal en el mismo día
            $query = Reunion::whereDate('fecha_reunion', $fecha)
                ->whereHas('concejales', function ($q) use ($cedula) {
                    $q->where('persona_cedula', $cedula);
                });
            
            if ($excludeReunionId) {
                $query->where('id', '!=', $excludeReunionId);
            }
            
            $reunionesExistentes = $query->get();
            
            if ($reunionesExistentes->count() > 0) {
                $persona = Personas::where('cedula', $cedula)->first();
                $nombreConcejal = $persona ? $persona->nombre . ' ' . $persona->apellido : 'Concejal ' . $cedula;
                
                $reunionesInfo = $reunionesExistentes->map(function ($reunion) {
                    return $reunion->titulo . ' (' . $reunion->hora_reunion . ')';
                })->join(', ');
                
                $conflictos[] = [
                    'cedula' => $cedula,
                    'nombre' => $nombreConcejal,
                    'reuniones' => $reunionesInfo,
                    'cantidad' => $reunionesExistentes->count()
                ];
            }
        }
        
        if (count($conflictos) > 0) {
            $nombresConflicto = collect($conflictos)->pluck('nombre')->join(', ');
            
            return [
                'valid' => false,
                'mensaje' => "Los siguientes concejales ya tienen una reunión programada para este día: {$nombresConflicto}. Un concejal solo puede participar en una reunión por día.",
                'conflictos' => $conflictos
            ];
        }
        
        return [
            'valid' => true,
            'mensaje' => '',
            'conflictos' => []
        ];
    }
}
