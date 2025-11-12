<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitasVisita extends Model
{
    protected $primaryKey = 'solicitud_id';

   
    protected $keyType = 'string';


    public $incrementing = false;

    protected $fillable = [
        'solicitud_id',
        'fecha_inicial',
        'fecha_final',
        'estatus_id',
        'observacion',
    ];

   
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'solicitud_id');
    }

   
    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'estatus_id', 'estatus_id');
    }

    public function asistente()
    {
        return $this->hasMany(VisitaEquipo::class, 'solicitud_id', 'solicitud_id');
    }

    
}
