<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Matcher\HasValue;

class Concejales extends Model
{
    use SoftDeletes;

    protected $table = 'concejales';

    protected $primaryKey = 'persona_cedula';

    protected $fillable = [
        'persona_cedula',
        'cargo_concejal'
    ];

    public function reuniones(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, (new ConcejalesReuniones())->getTable(), 'concejal_id', 'reunion_id')
                ->using(ConcejalesReuniones::class);
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_cedula', 'cedula');
    }
}
