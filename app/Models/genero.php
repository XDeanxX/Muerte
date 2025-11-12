<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class genero extends Model
{   
    
    protected $fillable = [
        'genero',
        'description', 

    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }
}
