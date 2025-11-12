<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personas extends Model
{
    protected $primaryKey = 'cedula';
    public $incrementing = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'segundo_nombre',
        'segundo_apellido',
        'nacionalidad',
        'genero',
        'cedula',
        'nacimiento',
        'direccion',
        'telefono',
        'email'
    ];

    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nacimiento' => 'date',
        ];
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(User::class,  'persona_cedula', 'cedula');
    }

    public function nacionalidad()
    {

        return $this->belongsTo(nacionalidad::class);
    }

    public function genero()
    {
        return $this->belongsTo(genero::class);
    }


    public function persona()
    {
        // 🚨 ATENCIÓN: Debes saber las claves usadas. 🚨

        // Supuesto 1: La solicitud tiene una columna 'cedula_solicitante'
        // que apunta a la PK 'cedula' del modelo Personas.
        return $this->belongsTo(Personas::class, 'cedula_solicitante', 'cedula');

        /* Si el supuesto 1 falla, y asumiendo que la columna se llama diferente en Solicitud:
        
        // Supuesto 2: Usa otra columna como clave foránea en la tabla 'solicitudes'
        // return $this->belongsTo(Personas::class, 'foreign_key_en_solicitudes', 'cedula');
        */
    }

    public function trabajador(): HasOne
    {
        return $this->hasOne(Trabajador::class, 'persona_cedula', 'cedula');
    }

    public function nacionalidadTransform()
    {
        return match ($this->nacionalidad) {
            1 => 'V',
            2 => 'E',
            default => $this->nacionalidad
        };
    }
}
