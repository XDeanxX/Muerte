<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trabajador extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'trabajadores';
    protected $primaryKey = 'persona_cedula';
    public $incrementing = false;
    
    
    protected $keyType = 'string'; 


    protected $fillable = [
        'persona_cedula', 
        'cargo_id', 
        'zona_trabajo'
    ];

    
    public function persona()
    {
        return $this->belongsTo(Personas::class, 'persona_cedula', 'cedula');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'cargo_id');
    }
}