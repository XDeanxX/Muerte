<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'persona_cedula';

    /**
     * The table associated with the model.
     *
     * @var int
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'persona_cedula',
        'role',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'role');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_cedula', 'cedula');
    }

    
    public function securityAnswers(): HasMany
    {
        return $this->hasMany(UserSecurityAnswer::class, 'user_cedula', 'persona_cedula');
    }

   
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'persona_cedula', 'persona_cedula');
    }

    
    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class, 'persona_cedula', 'persona_cedula');
    }

  
    public function hasRole($role)
    {
        return $this->role == $role;
    }

 
    public function isUsuario()
    {
        return $this->role == 3;
    }

  
    public function isAdministrador()
    {
        return $this->role == 2;
    }

    public function isSuperAdministrador()
    {
        return $this->role == 1;
    }


    public function getRoleName()
    {
        switch ($this->role) {
            case 1:
                return 'Administrador';
            case 2:
                return 'Asistente';
            case 3:
                return 'Solicitante';
            default:
                return 'Sin Rol';
        }
    }

    public function getUserIcon()
    {
        switch ($this->role) {
            case 1:
                return 'bx-hard-hat';
            case 2:
                return 'bxs-group';
            case 3:
                return 'bx-user';
            default:
                return 'Sin Rol';
        }
    }

    public function getRoleNameColoquela()
    {
        switch ($this->role) {
            case 1:
                return 'Administrador';
            case 2:
                return 'Asistente';
            case 3:
                return 'Solicitante';
            default:
                return 'Sin Rol';
        }
    }
}
