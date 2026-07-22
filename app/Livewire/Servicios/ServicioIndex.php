<?php

namespace App\Livewire\Servicios;

use App\Models\Servicio;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ServicioIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroCategoria = '';

    // Modal state
    public bool $isOpen = false;
    public $servicioId;
    public string $nombre = '';
    public $precio = null;
    public $stock = 0;
    public string $categoria = 'otros';
    public bool $activo = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroCategoria()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['servicioId', 'nombre', 'precio', 'stock', 'categoria', 'activo']);

        if ($id) {
            $servicio = Servicio::findOrFail($id);
            $this->servicioId = $servicio->id;
            $this->nombre = $servicio->nombre;
            $this->precio = $servicio->precio;
            $this->stock = $servicio->stock;
            $this->categoria = $servicio->categoria;
            $this->activo = $servicio->activo;
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        $rules = [
            'nombre'    => 'required|string|max:255',
            'precio'    => 'required|numeric|min:0',
            'stock'     => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:100',
            'activo'    => 'boolean',
        ];

        $validatedData = $this->validate($rules);

        if ($this->servicioId) {
            $servicio = Servicio::findOrFail($this->servicioId);
            $servicio->update($validatedData);
            session()->flash('success', 'Servicio actualizado correctamente.');
        } else {
            Servicio::create($validatedData);
            session()->flash('success', 'Servicio creado correctamente.');
        }

        $this->closeModal();
    }

    public function deleteServicio($id)
    {
        $servicio = Servicio::findOrFail($id);
        
        if ($servicio->consumos()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un servicio que tiene consumos registrados.');
            return;
        }

        $servicio->delete();
        session()->flash('success', 'Servicio eliminado correctamente.');
    }

    public function render()
    {
        $servicios = Servicio::when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroCategoria, fn($q) => $q->deCategoria($this->filtroCategoria))
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.servicios.servicio-index', compact('servicios'))
            ->layout('components.layouts.app', ['title' => 'Gestión de Servicios', 'subtitle' => 'Inventario de productos y servicios extra']);
    }
}
