<?php

namespace App\Livewire\Facturacion;

use App\Models\Factura;
use Livewire\Component;
use Livewire\Attributes\On;

class FacturaDetalle extends Component
{
    public $factura;
    public bool $isOpen = false;

    #[On('abrirModalFactura')]
    public function openModal($id)
    {
        $this->factura = Factura::with([
            'reserva.huesped', 
            'reserva.habitacion', 
            'reserva.consumos.servicio', 
            'user'
        ])->findOrFail($id);
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->factura = null;
    }

    public function render()
    {
        return view('livewire.facturacion.factura-detalle');
    }
}
