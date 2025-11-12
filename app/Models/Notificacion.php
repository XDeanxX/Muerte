<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notificaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'persona_cedula',
        'tipo',
        'titulo',
        'mensaje',
        'reunion_id',
        'solicitud_id',
        'leida'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'leida' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con la persona que recibe la notificación
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_cedula', 'cedula');
    }

    /**
     * Relación con la reunión (si aplica)
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relación con la solicitud (si aplica)
     */
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'solicitud_id');
    }

    /**
     * Marcar la notificación como leída
     */
    public function marcarComoLeida()
    {
        $this->leida = true;
        $this->save();
    }

    /**
     * Scope para obtener solo notificaciones no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Scope para obtener notificaciones de un tipo específico
     */
    public function scopeOfTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
