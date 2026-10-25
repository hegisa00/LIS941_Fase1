<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrador extends Model
{
    protected $table = 'administradores';

    protected $fillable = ['user_id', 'nombre_completo'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
