<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Model $model): void
    {
        AuditLog::registrar(
            accion: 'crear',
            modelo: class_basename($model),
            modeloId: $model->id,
            datosNuevos: $model->toArray(),
            descripcion: 'Registro creado: ' . class_basename($model) . " #{$model->id}",
        );
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Model $model): void
    {
        $cambios = $model->getChanges();
        $originales = collect($model->getOriginal())
            ->only(array_keys($cambios))
            ->toArray();

        // No registrar si solo se actualizó updated_at
        if (count($cambios) === 1 && isset($cambios['updated_at'])) {
            return;
        }

        AuditLog::registrar(
            accion: 'actualizar',
            modelo: class_basename($model),
            modeloId: $model->id,
            datosAnteriores: $originales,
            datosNuevos: $cambios,
            descripcion: 'Registro actualizado: ' . class_basename($model) . " #{$model->id}",
        );
    }

    /**
     * Handle the "deleted" event (includes soft deletes).
     */
    public function deleted(Model $model): void
    {
        AuditLog::registrar(
            accion: 'eliminar',
            modelo: class_basename($model),
            modeloId: $model->id,
            datosAnteriores: $model->toArray(),
            descripcion: 'Registro eliminado: ' . class_basename($model) . " #{$model->id}",
        );
    }
}
