<?php

namespace App\Livewire\Facturacion;

use App\Models\Factura;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class FacturacionIndex extends Component
{
    use WithPagination;

    #[Url]
    public $reserva = ''; // ID de reserva para filtrar desde otras vistas

    public function mount()
    {
        // Si hay una reserva específica en la URL, asegurarnos de que la vea
    }

    public function facturarReserva($reservaId)
    {
        $reserva = Reserva::with(['consumos'])->findOrFail($reservaId);

        if ($reserva->estado !== 'activa') {
            session()->flash('error', 'Solo se pueden facturar reservas activas.');
            return;
        }

        try {
            DB::transaction(function () use ($reserva) {
                // 1. Calcular totales
                $subtotalHabitacion = $reserva->total_estadia;
                $subtotalConsumos = $reserva->total_consumos;
                $total = $subtotalHabitacion + $subtotalConsumos;

                // 2. Generar Factura
                $factura = Factura::create([
                    'reserva_id' => $reserva->id,
                    'user_id' => auth()->id(),
                    'subtotal_habitacion' => $subtotalHabitacion,
                    'subtotal_consumos' => $subtotalConsumos,
                    'total' => $total,
                    'fecha_emision' => now(),
                    'notas' => 'Factura generada automáticamente al finalizar la estadía.',
                ]);

                // 3. Finalizar Reserva
                $reserva->update(['estado' => 'finalizada']);

                // 4. Liberar Habitación
                $reserva->habitacion->update(['estado' => 'disponible']);

                session()->flash('success', "Factura #{$factura->id} generada correctamente. La reserva ha sido finalizada y la habitación liberada.");
            });

        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar la facturación: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Traer reservas activas con cálculos
        $query = Reserva::activas()->with(['huesped', 'habitacion', 'consumos']);

        if ($this->reserva) {
            $query->where('id', $this->reserva);
        }

        $reservasParaFacturar = $query->latest()->paginate(10);

        return view('livewire.facturacion.facturacion-index', compact('reservasParaFacturar'))
            ->layout('components.layouts.app', ['title' => 'Cuentas Abiertas', 'subtitle' => 'Facturación y Checkout de habitaciones']);
    }
}
