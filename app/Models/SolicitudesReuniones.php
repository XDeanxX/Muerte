<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudesReuniones extends Model
{
    use SoftDeletes;

    protected $table = 'solicitudes_reuniones';

    public $fillable = [
        'solicitud_id',
        'reunion_id',
        'citar_solicitante',
        'asistencia_solicitante',
        'estatus_decision'
    ];

    protected $casts = [
        'citar_solicitante' => 'boolean',
        'asistencia_solicitante' => 'boolean'
    ];
   
    public function solicitudes(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function reuniones(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    public function datosSolicitud()
    {
        return Solicitud::where('solicitud_id', $this->solicitud_id )->first();
    }
}
