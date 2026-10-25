<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    public const PENDIENTE = 'pendiente';
    public const APROBADA = 'aprobada';
    public const RECHAZADA = 'rechazada';

    protected $table = 'empresas';

    protected $fillable = [
        'user_id', 'nombre', 'nit', 'direccion', 'telefono',
        'estado', 'comision', 'motivo_rechazo', 'resuelta_por', 'fecha_resolucion',
    ];

    protected function casts(): array
    {
        return [
            'comision' => 'decimal:2',
            'fecha_resolucion' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resueltaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resuelta_por');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    public function estaAprobada(): bool
    {
        return $this->estado === self::APROBADA;
    }

    /** Clase de Bootstrap para pintar la etiqueta de estado. */
    public function claseEstado(): string
    {
        return match ($this->estado) {
            self::APROBADA => 'text-bg-success',
            self::RECHAZADA => 'text-bg-danger',
            default => 'text-bg-warning',
        };
    }
}
