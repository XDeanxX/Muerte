<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


class Categorias extends Model
{

    protected $table = 'categorias';

    public $fillable = [
        'categoria',
        'descripcion'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function subcategorias() : HasMany {
        return $this->hasMany(SubCategorias::class, 'categoria', 'categoria');
    }

    public function getCategoriaFormattedAttribute()
    {
        return $this->categoria = Str::ucfirst($this->categoria);
    }

    public function solicitudes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Solicitud::class,
            SubCategorias::class,
            'categoria',
            'subcategoria',
            'categoria',
            'subcategoria'
        );
    }

    /**
     * @param string
     * @return int
     */
    public static function contarSolicitudesPorCategoria(string $categoria_slug): int
    {
        $categoria = self::where('categoria', $categoria_slug)
                        ->withCount('solicitudes')
                        ->first();

        return $categoria ? $categoria->solicitudes_count : 0;
    }
}
