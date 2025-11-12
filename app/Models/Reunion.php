<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reunion extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reuniones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_reunion',
        'hora_reunion',
        'duracion_reunion',
        'duracion_real',
        'hora_finalizacion_real',
        'estatus',
        'tipo_reunion',
        'resolución'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_reunion' => 'datetime'
    ];

    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class, (new SolicitudesReuniones())->getTable(), 'reunion_id', 'solicitud_id')
            ->withPivot('citar_solicitante','asistencia_solicitante','estatus_decision');
    }

    public function instituciones(): BelongsToMany
    {
        return $this->belongsToMany(Institucion::class, (new InstitucionesReuniones())->getTable(), 'reunion_id', 'institucion_id')
            ->withPivot('asistencia');
    }

    public function concejales(): BelongsToMany
    {
        return $this->belongsToMany(Concejales::class, (new ConcejalesReuniones())->getTable(), 'reunion_id', 'concejal_id')
            ->withPivot('asistencia');
    }

    // NOTA: Relación 'asistentes' comentada porque la tabla 'reunion_asistentes' no existe
    // Si en el futuro se implementa esta tabla, descomentar esta relación
    /*
    public function asistentes(): BelongsToMany
    {
        return $this->belongsToMany(Personas::class, 'reunion_asistentes', 'reunion_id', 'persona_cedula', 'id', 'cedula')
            ->withPivot('es_concejal')
            ->withTimestamps();
    }
    */

    public function estatusRelacion(): BelongsTo
    {
        return $this->belongsTo(Estatus::class, 'estatus', 'estatus_id');
    }

    public function tipoReunionRelacion(): BelongsTo
    {
        return $this->belongsTo(TipoReunion::class, 'tipo_reunion');
    }

    public function tituloTipoReunion()
    {
        return $this->tipo_reunion = TipoReunion::where('id', $this->tipo_reunion)->value('titulo');
    }

    public function tituloEstatus()
    {
        return $this->estatus = Str::title(Estatus::where('estatus_id',$this->estatus)->value('estatus'));
    }

 /*    public function datosPersonalesConcejal()
    {
        return Personas::where('cedula', $this->persona_cedula)->get();;
    } */


/*     public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class, 'reunion_solicitud', 'reunion_id', 'solicitud_id', 'id', 'solicitud_id')
                    ->withPivot('fecha_hora_citacion')
                    ->withTimestamps();
    } */
/* 
    public function personasCitadas()
    {
        return $this->solicitudes()
                    ->with(['persona', 'personasAsociadas.persona'])
                    ->get()
                    ->flatMap(function ($solicitud) {
                        // Get main persona from solicitud
                        $personas = collect([]);
                        
                        if ($solicitud->persona) {
                            $personas->push([
                                'persona' => $solicitud->persona,
                                'solicitud_id' => $solicitud->solicitud_id,
                                'solicitud_titulo' => $solicitud->titulo,
                            ]);
                        }
                        
                        // Get all associated personas
                        foreach ($solicitud->personasAsociadas as $asignada) {
                            if ($asignada->persona) {
                                $personas->push([
                                    'persona' => $asignada->persona,
                                    'solicitud_id' => $solicitud->solicitud_id,
                                    'solicitud_titulo' => $solicitud->titulo,
                                ]);
                            }
                        }
                        
                        return $personas;
                    });
    } */
}
