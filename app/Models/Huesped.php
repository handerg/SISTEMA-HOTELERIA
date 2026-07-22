<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Huesped extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'huespedes';

    protected $fillable = [
        'nombre',
        'cedula',
        'edad',
        'telefono',
        'email',
    ];

    /**
     * Reservas donde este huésped es el titular.
     */
    public function reservasTitular(): HasMany
    {
        return $this->hasMany(Reserva::class, 'huesped_id');
    }

    /**
     * Reservas donde este huésped es acompañante (pivot).
     */
    public function reservasAcompanante(): BelongsToMany
    {
        return $this->belongsToMany(Reserva::class, 'reserva_huesped')
                    ->withTimestamps();
    }

    /**
     * Frecuencia: número total de reservas (como titular).
     * Se calcula dinámicamente, nunca se almacena.
     */
    protected function frecuencia(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reservasTitular()->count(),
        );
    }

    /**
     * Scope para buscar por nombre o cédula.
     */
    public function scopeBuscar($query, ?string $term)
    {
        if (empty($term)) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('nombre', 'like', "%{$term}%")
              ->orWhere('cedula', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
