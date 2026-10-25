<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'cliente_id', 'oferta_id', 'numero_factura', 'cantidad',
        'precio_unitario', 'total', 'tarjeta_ultimos4',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class);
    }

    public function cupones(): HasMany
    {
        return $this->hasMany(Cupon::class);
    }
}
