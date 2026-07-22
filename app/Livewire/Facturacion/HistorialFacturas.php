<?php

namespace App\Livewire\Facturacion;

use App\Models\Factura;
use Livewire\Component;
use Livewire\WithPagination;

class HistorialFacturas extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroFecha = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroFecha()
    {
        $this->resetPage();
    }

    public function render()
    {
        $facturas = Factura::with(['reserva.huesped', 'reserva.habitacion', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('reserva.huesped', function ($q2) {
                    $q2->where('nombre', 'like', "%{$this->search}%")
                       ->orWhere('cedula', 'like', "%{$this->search}%");
                })->orWhere('id', 'like', "%{$this->search}%");
            })
            ->when($this->filtroFecha, function ($q) {
                $q->whereDate('fecha_emision', $this->filtroFecha);
            })
            ->latest('fecha_emision')
            ->paginate(15);

        return view('livewire.facturacion.historial-facturas', compact('facturas'))
            ->layout('components.layouts.app', ['title' => 'Historial de Facturas', 'subtitle' => 'Registro financiero inmutable']);
    }
}
