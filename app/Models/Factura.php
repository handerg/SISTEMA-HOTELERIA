<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;
    // NO SoftDeletes — las facturas son inmutables

    protected $table = 'facturas';

    protected $fillable = [
        'reserva_id',
        'user_id',
        'subtotal_habitacion',
        'subtotal_consumos',
        'total',
        'fecha_emision',
        'notas',
    ];

    protected $casts = [
        'subtotal_habitacion' => 'decimal:2',
        'subtotal_consumos'   => 'decimal:2',
        'total'               => 'decimal:2',
        'fecha_emision'       => 'datetime',
    ];

    /**
     * Reserva asociada.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    /**
     * Usuario que emitió la factura.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
