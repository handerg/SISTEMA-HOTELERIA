<?php

namespace App\Livewire\Huespedes;

use App\Models\Huesped;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class HuespedIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('huesped-guardado')]
    public function render()
    {
        $huespedes = Huesped::buscar($this->search)
            ->withCount('reservasTitular') // Para optimizar si quisieras mostrarlo directo
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.huespedes.huesped-index', compact('huespedes'))
            ->layout('components.layouts.app', ['title' => 'Gestión de Huéspedes', 'subtitle' => 'Directorio de clientes registrados']);
    }

    public function deleteHuesped($id)
    {
        $huesped = Huesped::findOrFail($id);
        
        if ($huesped->reservasTitular()->count() > 0 || $huesped->reservasAcompanante()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un huésped que tiene reservas asociadas.');
            return;
        }

        $huesped->delete();
        session()->flash('success', 'Huésped eliminado correctamente.');
    }
}
