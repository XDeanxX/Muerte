<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 1. Nombre de la tabla
    // Aunque Laravel lo infiere, lo definimos explícitamente.
    protected $table = 'cargos'; 

    // 2. Clave Primaria (PK)
    // Especificamos que la PK se llama 'cargo_id'.
    protected $primaryKey = 'cargo_id'; 
    
    // 3. Campos permitidos para Asignación Masiva
    // Necesario para las funciones store() y update() en el CargoController.
    protected $fillable = [
        'descripcion',
    ];

    // 4. Relaciones (Para saber qué trabajadores tienen este cargo)
    /**
     * Un Cargo puede tener muchos Trabajadores.
     */
    public function trabajadores()
    {
        // Relación Uno-a-Muchos: Cargo tiene muchos Trabajadores
        return $this->hasMany(Trabajador::class, 'cargo_id', 'cargo_id');
    }
}