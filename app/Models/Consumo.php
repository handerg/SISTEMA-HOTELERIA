<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'consumos';

    protected $fillable = [
        'reserva_id',
        'servicio_id',
        'user_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'        => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    /**
     * Auto-calcular subtotal antes de guardar.
     */
    protected static function booted(): void
    {
        static::creating(function (Consumo $consumo) {
            $consumo->subtotal = $consumo->cantidad * $consumo->precio_unitario;
        });

        static::updating(function (Consumo $consumo) {
            $consumo->subtotal = $consumo->cantidad * $consumo->precio_unitario;
        });
    }

    /**
     * Reserva asociada.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    /**
     * Servicio consumido.
     */
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    /**
     * Usuario que registró el consumo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
