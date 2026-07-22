<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'categoria',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock'  => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Consumos de este servicio.
     */
    public function consumos(): HasMany
    {
        return $this->hasMany(Consumo::class, 'servicio_id');
    }

    /**
     * Scope: solo servicios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: filtrar por categoría.
     */
    public function scopeDeCategoria($query, ?string $categoria)
    {
        if (empty($categoria)) return $query;
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope: solo servicios con stock disponible.
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
