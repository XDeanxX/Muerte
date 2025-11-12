<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitucionesReuniones extends Model
{
    use SoftDeletes;

    protected $table = 'instituciones_reuniones';

    public $fillable = [
        'institucion_id',
        'reunion_id',
        'asistencia'
    ];

    public function instituciones(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'institucion_id');
    }

    public function reuniones(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }
    
    public function datosInstitucion()
    {
        return Institucion::where('id', $this->institucion_id)->first();
    }
}
