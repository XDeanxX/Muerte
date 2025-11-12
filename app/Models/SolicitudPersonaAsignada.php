<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudPersonaAsignada extends Model
{
    protected $table = 'solicitud_personas_asignadas';

    protected $primaryKey = 'solicitud_id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'solicitud_id',
        'persona_cedula',
        'fecha_asignacion',
        'nota',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_cedula');
    }
}
