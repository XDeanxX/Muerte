<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nacionalidad extends Model
{
protected $fillable = [
        'nacionalidad', 
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function personas()
    {
        return $this->hasMany(Personas::class);
    }

    public function nacionalidadTransform()
    {
        return match($this->nacionalidad) {
            1 => 'V',
            2 => 'E',
            default => $this->nacionalidad
        };
    }
}

