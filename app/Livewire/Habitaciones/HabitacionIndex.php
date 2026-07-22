<?php

namespace App\Livewire\Habitaciones;

use App\Models\Habitacion;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class HabitacionIndex extends Component
{
    use WithPagination;

    public string $filtroTipo = '';
    public string $filtroEstado = '';

    public function updatingFiltroTipo()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    #[On('habitacion-guardada')]
    public function render()
    {
        $habitaciones = Habitacion::deTipo($this->filtroTipo)
            ->conEstado($this->filtroEstado)
            ->orderBy('nombre')
            ->paginate(12);

        return view('livewire.habitaciones.habitacion-index', compact('habitaciones'))
            ->layout('components.layouts.app', ['title' => 'Gestión de Habitaciones', 'subtitle' => 'Inventario y estado de habitaciones']);
    }

    public function deleteHabitacion($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        
        if ($habitacion->reservas()->count() > 0) {
            session()->flash('error', 'No se puede eliminar una habitación que tiene reservas registradas (históricas o activas).');
            return;
        }

        $habitacion->delete();
        session()->flash('success', 'Habitación eliminada correctamente.');
    }
}
