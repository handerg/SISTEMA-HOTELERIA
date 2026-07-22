<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habitacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'habitaciones';

    protected $fillable = [
        'nombre',
        'tipo',
        'precio_base',
        'estado',
        'foto_path',
        'descripcion',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    /**
     * Reservas asociadas a esta habitación.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'habitacion_id');
    }

    /**
     * Reserva activa actual (si existe).
     */
    public function reservaActiva()
    {
        return $this->hasOne(Reserva::class, 'habitacion_id')
                    ->where('estado', 'activa')
                    ->latestOfMany();
    }

    /**
     * Verificar si la habitación está ocupada.
     */
    protected function estaOcupada(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->estado === 'ocupada',
        );
    }

    /**
     * Scope: solo habitaciones disponibles.
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Scope: filtrar por tipo.
     */
    public function scopeDeTipo($query, ?string $tipo)
    {
        if (empty($tipo)) return $query;
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: filtrar por estado.
     */
    public function scopeConEstado($query, ?string $estado)
    {
        if (empty($estado)) return $query;
        return $query->where('estado', $estado);
    }
}
