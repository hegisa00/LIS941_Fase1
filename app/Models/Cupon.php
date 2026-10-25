<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = ['compra_id', 'codigo', 'estado', 'fecha_canje'];

    protected function casts(): array
    {
        return [
            'fecha_canje' => 'datetime',
        ];
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }
}
