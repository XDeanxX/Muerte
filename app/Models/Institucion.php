<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{

    protected $table = 'instituciones';

    protected $fillable = [
        'titulo',
        'descripcion'
    ];

    public function reuniones(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, (new InstitucionesReuniones())->getTable(), 'institucion_id', 'reunion_id')
                ->using(InstitucionesReuniones::class);
    }
}