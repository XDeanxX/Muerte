<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubCategorias extends Model
{
    protected $table = 'sub_categorias';

    public $fillable = [
        'subcategoria',
        'descripcion',
        'categoria'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function categoria() : BelongsTo {
        return $this->belongsTo(Categorias::class, 'categoria', 'categoria');
    }

    public function solicitud(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'subcategoria', 'subcategoria');
    }

    public function getSubcategoriaFormattedAttribute()
    {
        return $this->subcategoria = Str::title($this->subcategoria);
    }

    public function getCategoriaFormattedAttribute()
    {
        return $this->categoria = Str::title($this->categoria);
    }
}
