<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'modelo',
        'modelo_id',
        'datos_anteriores',
        'datos_nuevos',
        'ip_address',
        'descripcion',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos'     => 'array',
        'created_at'       => 'datetime',
    ];

    /**
     * Usuario que realizó la acción.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Registrar una acción de auditoría.
     */
    public static function registrar(
        string $accion,
        string $modelo,
        ?int $modeloId = null,
        ?array $datosAnteriores = null,
        ?array $datosNuevos = null,
        ?string $descripcion = null,
    ): self {
        return self::create([
            'user_id'          => auth()->id(),
            'accion'           => $accion,
            'modelo'           => $modelo,
            'modelo_id'        => $modeloId,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos'     => $datosNuevos,
            'ip_address'       => request()?->ip(),
            'descripcion'      => $descripcion,
        ]);
    }
}
