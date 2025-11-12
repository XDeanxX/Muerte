<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConcejalesReuniones extends Model
{
    use SoftDeletes;

    protected $table = 'concejales_reuniones';

    public $fillable = [
        'concejal_id',
        'reunion_id',
        'asistencia'
    ];

    public function concejales(): BelongsTo
    {
        return $this->belongsTo(Concejales::class, 'concejal_id');
    }

    public function reuniones(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    public function datosConcejal()
    {
        return Personas::where('cedula', $this->concejal_id )->first();
    }
}
