<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectoresSistema extends Model
{
    protected $table = 'sectores_sistema';

    protected $primaryKey = 'sector';
    
    protected $keyType = 'string';

    public $fillablel = [
        'sector'
    ];

    public function sector(): HasMany
    {
        return $this->hasMany(Estatus::class, 'sector', 'sector_sistema');
    }

    public function getSectorFormattedAttribute()
    {
        return match($this->sector) {
            'visitas' => 'Visitas',
            'reuniones' => 'Reuniones',
            'solicitudes' => 'Solicitudes',
            default => $this->sector
        };
    }
}
