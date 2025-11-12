<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sectores extends Model
{
    protected $table = 'sectores';

    protected $primaryKey = 'sector';
    
    protected $keyType = 'string';

    protected $fillable = [
        'sector',
        'parroquia'
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'sector');
    }
}
