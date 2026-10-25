<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROL_ADMIN = 'admin';
    public const ROL_EMPRESA = 'empresa';
    public const ROL_CLIENTE = 'cliente';

    protected $table = 'users';

    protected $fillable = ['usuario', 'email', 'password', 'rol'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(Administrador::class);
    }

    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class);
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    /** Nombre de la ruta de login que corresponde al rol del usuario. */
    public function loginRoute(): string
    {
        return match ($this->rol) {
            self::ROL_ADMIN => 'admin.login',
            self::ROL_EMPRESA => 'empresa.login',
            default => 'login',
        };
    }

    /** Nombre de la ruta del panel que corresponde al rol del usuario. */
    public function dashboardRoute(): string
    {
        return match ($this->rol) {
            self::ROL_ADMIN => 'admin.dashboard',
            self::ROL_EMPRESA => 'empresa.dashboard',
            default => 'cliente.dashboard',
        };
    }

    /** Nombre amigable para mostrar en la barra de navegación. */
    public function nombreParaMostrar(): string
    {
        $nombre = match ($this->rol) {
            self::ROL_ADMIN => $this->administrador?->nombre_completo,
            self::ROL_EMPRESA => $this->empresa?->nombre,
            default => $this->cliente?->nombre,
        };

        return $nombre ?: $this->usuario;
    }
}
