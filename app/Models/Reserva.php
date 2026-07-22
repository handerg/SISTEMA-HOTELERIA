<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reserva extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservas';

    protected $fillable = [
        'huesped_id',
        'habitacion_id',
        'user_id',
        'fecha_inicio',
        'fecha_fin',
        'precio_acordado',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_inicio'    => 'date',
        'fecha_fin'       => 'date',
        'precio_acordado' => 'decimal:2',
    ];

    /**
     * Huésped titular de la reserva.
     */
    public function huesped(): BelongsTo
    {
        return $this->belongsTo(Huesped::class, 'huesped_id');
    }

    /**
     * Habitación reservada.
     */
    public function habitacion(): BelongsTo
    {
        return $this->belongsTo(Habitacion::class, 'habitacion_id');
    }

    /**
     * Usuario que registró la reserva.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Acompañantes de la reserva (tabla pivot).
     */
    public function acompanantes(): BelongsToMany
    {
        return $this->belongsToMany(Huesped::class, 'reserva_huesped')
                    ->withTimestamps();
    }

    /**
     * Consumos de la reserva.
     */
    public function consumos(): HasMany
    {
        return $this->hasMany(Consumo::class, 'reserva_id');
    }

    /**
     * Factura de la reserva.
     */
    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'reserva_id');
    }

    /**
     * Total de consumos (calculado dinámicamente).
     */
    protected function totalConsumos(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->consumos()->sum('subtotal'),
        );
    }

    /**
     * Días de estadía.
     */
    protected function diasEstadia(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha_inicio && $this->fecha_fin
                ? max(1, $this->fecha_inicio->diffInDays($this->fecha_fin))
                : 0,
        );
    }

    /**
     * Costo total de la estadía (precio × días).
     */
    protected function totalEstadia(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->precio_acordado * $this->dias_estadia,
        );
    }

    /**
     * Total general (estadía + consumos).
     */
    protected function totalGeneral(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_estadia + $this->total_consumos,
        );
    }

    /**
     * Scope: reservas activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope: reservas finalizadas.
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }
}
