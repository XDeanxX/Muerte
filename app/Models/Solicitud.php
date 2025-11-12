<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Solicitud extends Model
{
    use SoftDeletes;

    protected $table = 'solicitudes';

    protected $primaryKey = 'solicitud_id';

    protected $keyType = 'string';
    
    public $timestamps = false;

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = null;

    protected $fillable = [
        'solicitud_id',
        'titulo',
        'descripcion',
        'estatus',
        'observaciones_admin',
        'fecha_actualizacion_usuario',
        'fecha_actualizacion_super_admin',
        'fecha_creacion',
        'persona_cedula',
        'derecho_palabra',
        'subcategoria',
        'tipo_solicitud',
        'pais',
        'estado_region',
        'municipio',
        'comunidad',
        'direccion_detallada',
        'asignada_visita',
        'asignada_reunion',
        'reunion_id_asignacion_visita'
    ];

    protected $casts = [
        'derecho_palabra' => 'boolean',
        'fecha_eliminacion' => 'datatime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion_usuario' => 'datetime',
        'fecha_actualizacion_super_admin' => 'datetime',
    ];

    const TIPO_SOLICITUD = [
        'individual',
        'colectivo_institucional'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_cedula', 'cedula');
    }

    public function comunidadRelacion(): BelongsTo
    {
        return $this->belongsTo(Comunidades::class, 'comunidad', 'comunidad');
    }

    public function subcategoriaRelacion(): BelongsTo
    {
        return $this->belongsTo(SubCategorias::class, 'subcategoria', 'subcategoria');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'persona_cedula', 'persona_cedula');
    }

    public function estatusRelacion(): BelongsTo
    {
        return $this->belongsTo(Estatus::class, 'estatus', 'estatus_id');
    }

    public function visitasRelacion(): HasMany
    {
        return $this->hasMany(Visita::class, 'persona_cedula', 'persona_cedula');
    }

    public function visita()
    {
        return $this->hasOne(VisitasVisita::class, 'solicitud_id', 'solicitud_id');
    }

    public function reunionRelacion(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, (new SolicitudesReuniones())->getTable(), 'solicitud_id', 'reunion_id')
            ->withPivot('citar_solicitante','asistencia_solicitante');
    }

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id_asignacion_visita', 'id');
    }

    public static function generateSolicitudId($userCedula)
    {
        $datePrefix = date('Ymd');
        $hash = substr(md5($userCedula . time() . uniqid()), 0, 6);
        return $datePrefix . strtoupper($hash);
    }

    public function getTipoSolicitudFormattedAttribute()
    {
        return match($this->tipo_solicitud) {
            'individual' => 'Individual',
            'colectivo_institucional' => 'Colectivo/Institucional',
            default => $this->tipo_solicitud
        };
    }   

    public function getComunidadFormattedAttribute()
    {
        return $this->comunidad = Str::title($this->comunidad);
    }

    public function getParroquiaFormattedAttribute()
    {
        return $this->parroquia = Str::title($this->parroquia);
    }

    public function getCategoriaFormattedAttribute()
    {
        return $this->categoria = Str::title($this->categoria);
    }

    public function getSubcategoriaFormattedAttribute()
    {
        return $this->subcategoria = Str::title($this->subcategoria);
    }

    public function getEstatusFormattedAttribute()
    {
        return $this->estatus = Str::title(Estatus::where('estatus_id',$this->estatus)->value('estatus'));
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($modelo) {
            if (Auth::check()) {
                if (Auth::user()->isSuperAdministrador()) {
                    $modelo->fecha_actualizacion_super_admin = now();
                } else {
                    $modelo->fecha_actualizacion_usuario = now();
                }
            } else {
                $modelo->fecha_actualizacion_usuario = now();
            }
        });
    }
}