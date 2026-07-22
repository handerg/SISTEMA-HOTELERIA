<?php

namespace App\Livewire\Reservas;

use App\Models\Reserva;
use Livewire\Component;
use Livewire\WithPagination;

class ReservaIndex extends Component
{
    use WithPagination;

    public string $filtroEstado = '';
    public string $searchHuesped = '';

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingSearchHuesped()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reservas = Reserva::with(['huesped', 'habitacion', 'user'])
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->searchHuesped, function ($q) {
                $q->whereHas('huesped', function ($q2) {
                    $q2->where('nombre', 'like', '%' . $this->searchHuesped . '%')
                       ->orWhere('cedula', 'like', '%' . $this->searchHuesped . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.reservas.reserva-index', compact('reservas'))
            ->layout('components.layouts.app', ['title' => 'Gestión de Reservas', 'subtitle' => 'Listado y control de reservas']);
    }

    public function cancelarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);

        if ($reserva->estado !== 'activa') {
            session()->flash('error', 'Solo se pueden cancelar reservas activas.');
            return;
        }

        // Cambiar estado de reserva a cancelada
        $reserva->update(['estado' => 'cancelada']);

        // Liberar habitación
        $reserva->habitacion->update(['estado' => 'disponible']);

        session()->flash('success', 'Reserva cancelada y habitación liberada.');
    }
}
