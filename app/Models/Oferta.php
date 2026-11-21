<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Oferta extends Model
{
    protected $table = 'ofertas';

    protected $fillable = [
        'empresa_id', 'titulo', 'precio_regular', 'precio_oferta',
        'fecha_inicio', 'fecha_fin', 'fecha_limite_canje',
        'cantidad_cupones', 'descripcion', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'precio_regular' => 'decimal:2',
            'precio_oferta' => 'decimal:2',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_limite_canje' => 'date',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    /**
     * Ofertas que el público puede ver: estado "disponible", dentro de su
     * rango de fechas y publicadas por una empresa aprobada.
     */
    public function scopeVisibles(Builder $consulta): Builder
    {
        return $consulta
            ->where('estado', 'disponible')
            ->whereDate('fecha_inicio', '<=', today())
            ->whereDate('fecha_fin', '>=', today())
            ->whereHas('empresa', fn (Builder $empresa) => $empresa->where('estado', Empresa::APROBADA));
    }

    /** Máximo de cupones de una misma oferta que puede comprar un cliente. */
    public const MAX_POR_CLIENTE = 5;

    /** Cupones vendidos. Usa el atributo "vendidos" si la consulta lo trajo (withSum) para evitar N+1. */
    public function cuponesVendidos(): int
    {
        return (int) ($this->vendidos ?? $this->compras()->sum('cantidad'));
    }

    /** Cupones que aún se pueden vender; null significa sin límite. */
    public function cuponesRestantes(): ?int
    {
        if ($this->cantidad_cupones === null) {
            return null;
        }

        return max(0, $this->cantidad_cupones - $this->cuponesVendidos());
    }

    public function agotada(): bool
    {
        return $this->cuponesRestantes() === 0;
    }

    public function porcentajeDescuento(): int
    {
        if ((float) $this->precio_regular <= 0) {
            return 0;
        }

        return (int) round((1 - ((float) $this->precio_oferta / (float) $this->precio_regular)) * 100);
    }
}
