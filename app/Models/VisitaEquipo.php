<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaEquipo extends Model
{

    protected $table = 'visita_equipos';
    
    protected $fillable = [
        'solicitud_id',
        'cedula',
    ];


    public function visita()
    {
        return $this->belongsTo(
            VisitasVisita::class,       
            'solicitud_id',      
            'solicitud_id'    
        );
    }


    public function User()
    {
        return $this->belongsTo(
            Personas::class,     
            'cedula',            
            'cedula'             
        );
    }
}

