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

    /** Estado real: un cupón disponible cuya fecha límite de canje ya pasó se muestra como vencido. */
    public function estadoActual(): string
    {
        if ($this->estado === 'disponible' && $this->compra->oferta->fecha_limite_canje->lt(today())) {
            return 'vencido';
        }

        return $this->estado;
    }

    public function claseEstado(): string
    {
        return match ($this->estadoActual()) {
            'canjeado' => 'text-bg-secondary',
            'vencido' => 'text-bg-danger',
            default => 'text-bg-success',
        };
    }
}
