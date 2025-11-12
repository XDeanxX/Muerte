<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Estatus extends Model
{
    use SoftDeletes;

    protected $table = 'estatus';

    protected $primaryKey = 'estatus_id';

    protected $fillable = [
        'estatus_id',
        'estatus',
        'descripcion',
        'sector_sistema'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function sectoresSistema() : BelongsTo{
        return $this->belongsTo(SectoresSistema::class, 'sector_sistema', 'sector');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'estatus', 'estatus_id');
    }

    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class, 'estatus_id');
    }

    public function reuniones(): HasMany
    {
        return $this->hasMany(Reunion::class, 'estatus_id', 'estatus');
    }

    /**
     * Get formatted sector_sistema name
     */
    public function getSectorSistemaFormattedAttribute()
    {
        return match($this->sector_sistema) {
            'visitas' => 'Visitas',
            'reuniones' => 'Reuniones',
            'solicitudes' => 'Solicitudes',
            default => $this->sector_sistema
        };
    }

    public function visitastable()
    {
       
        return $this->hasMany(VisitasVisita::class, 'estatus_id', 'estatus_id');
    }

    public function getEstatusFormattedAttribute()
    {
        return $this->estatus = Str::ucfirst($this->estatus);
    }
}
