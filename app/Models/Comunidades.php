<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Comunidades extends Model
{

    protected $table = 'comunidades';

    public $fillable = [
        'comunidad',
        'parroquia'
    ];
    
    protected $hidden = [
        'deleted_at'
    ];

    public function parroquia() : BelongsTo {
        return $this->belongsTo(Parroquias::class, 'parroquia', 'parroquia');
    }

    public function solicitud() : BelongsTo{
        return $this->belongsTo(Solicitud::class, 'comunidad', 'comunidad');
    }

    public function getParroquiaFormattedAttribute()
    {
        return $this->parroquia = Str::title($this->parroquia);
    }

    public function getComunidadFormattedAttribute()
    {
        return $this->comunidad = Str::title($this->comunidad);
    }
}
