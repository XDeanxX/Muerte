<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoReunion extends Model
{   
    use SoftDeletes;

    protected $table = 'tipo_reunions';

    protected $fillable = [
        'titulo',
        'descripcion'
    ];

    public function reuniones(): HasMany
    {
        return $this->hasMany(Reunion::class);
    }
}
