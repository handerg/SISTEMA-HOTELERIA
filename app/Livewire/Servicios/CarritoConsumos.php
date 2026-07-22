<?php

namespace App\Livewire\Servicios;

use App\Models\Consumo;
use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CarritoConsumos extends Component
{
    public $reservaSeleccionadaId = null;
    public $searchServicio = '';
    public $categoriaFiltrada = '';
    
    // items del carrito: [servicio_id => ['nombre', 'precio', 'cantidad', 'subtotal']]
    public array $carrito = []; 

    public function mount()
    {
        $this->reservaSeleccionadaId = request()->query('reserva');
    }

    public function agregarAlCarrito($servicioId)
    {
        if (!$this->reservaSeleccionadaId) {
            session()->flash('error', 'Selecciona una habitación/reserva primero.');
            return;
        }

        $servicio = Servicio::findOrFail($servicioId);

        if ($servicio->stock <= 0) {
            session()->flash('error', "El servicio '{$servicio->nombre}' está agotado.");
            return;
        }

        if (isset($this->carrito[$servicioId])) {
            // Verificar stock al incrementar
            if ($this->carrito[$servicioId]['cantidad'] >= $servicio->stock) {
                session()->flash('error', "Stock máximo alcanzado para '{$servicio->nombre}'.");
                return;
            }
            $this->carrito[$servicioId]['cantidad']++;
            $this->carrito[$servicioId]['subtotal'] = $this->carrito[$servicioId]['cantidad'] * $servicio->precio;
        } else {
            $this->carrito[$servicioId] = [
                'nombre' => $servicio->nombre,
                'precio' => $servicio->precio,
                'cantidad' => 1,
                'subtotal' => $servicio->precio,
            ];
        }
    }

    public function removerDelCarrito($servicioId)
    {
        unset($this->carrito[$servicioId]);
    }

    public function actualizarCantidad($servicioId, $cantidad)
    {
        $cantidad = max(1, (int) $cantidad);
        $servicio = Servicio::findOrFail($servicioId);
        
        if ($cantidad > $servicio->stock) {
            session()->flash('error', "Solo hay {$servicio->stock} unidades de '{$servicio->nombre}'.");
            $cantidad = $servicio->stock;
        }

        if (isset($this->carrito[$servicioId])) {
            $this->carrito[$servicioId]['cantidad'] = $cantidad;
            $this->carrito[$servicioId]['subtotal'] = $cantidad * $this->carrito[$servicioId]['precio'];
        }
    }

    public function procesarConsumos()
    {
        if (empty($this->carrito) || !$this->reservaSeleccionadaId) {
            return;
        }

        try {
            DB::transaction(function () {
                foreach ($this->carrito as $servicioId => $item) {
                    // Crear consumo (el subtotal se auto-calcula en el modelo)
                    Consumo::create([
                        'reserva_id' => $this->reservaSeleccionadaId,
                        'servicio_id' => $servicioId,
                        'user_id' => auth()->id(),
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                    ]);

                    // Descontar stock
                    Servicio::where('id', $servicioId)->decrement('stock', $item['cantidad']);
                }
            });

            $this->carrito = [];
            session()->flash('success', 'Consumos cargados a la habitación exitosamente.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar consumos: ' . $e->getMessage());
        }
    }

    public function getTotalProperty()
    {
        return collect($this->carrito)->sum('subtotal');
    }

    public function render()
    {
        // Obtener habitaciones ocupadas
        $reservasActivas = Reserva::activas()->with(['habitacion', 'huesped'])->get();

        // Filtrar servicios
        $servicios = Servicio::activos()
            ->conStock()
            ->when($this->searchServicio, fn($q) => $q->where('nombre', 'like', "%{$this->searchServicio}%"))
            ->when($this->categoriaFiltrada, fn($q) => $q->deCategoria($this->categoriaFiltrada))
            ->get();

        return view('livewire.servicios.carrito-consumos', [
            'reservasActivas' => $reservasActivas,
            'servicios' => $servicios,
        ])->layout('components.layouts.app', ['title' => 'Punto de Venta / Consumos', 'subtitle' => 'Cargar servicios a las habitaciones']);
    }
}
