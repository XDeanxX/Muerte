<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Parroquias extends Model
{
    
    protected $table = 'parroquias';

    public $fillable = [
        'parroquia'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function comunidades() : HasMany {
        return $this->hasMany(Comunidades::class, 'parroquia', 'parroquia');
    }

    public function getParroquiaFormattedAttribute()
    {
        return $this->parroquia = Str::title($this->parroquia);
    }
}
