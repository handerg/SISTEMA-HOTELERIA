<?php

namespace App\Livewire\Habitaciones;

use App\Models\Habitacion;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\On;

class HabitacionForm extends Component
{
    public $habitacionId;
    public string $nombre = '';
    public string $tipo = 'simple';
    public $precio_base = null;
    public string $estado = 'disponible';
    public string $descripcion = '';

    public bool $isOpen = false;

    #[On('abrirModalHabitacion')]
    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['habitacionId', 'nombre', 'tipo', 'precio_base', 'estado', 'descripcion']);

        if ($id) {
            $habitacion = Habitacion::findOrFail($id);
            $this->habitacionId = $habitacion->id;
            $this->nombre = $habitacion->nombre;
            $this->tipo = $habitacion->tipo;
            $this->precio_base = $habitacion->precio_base;
            $this->estado = $habitacion->estado;
            $this->descripcion = $habitacion->descripcion ?? '';
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
            'nombre'      => 'required|string|max:100',
            'tipo'        => ['required', Rule::in(['simple', 'doble', 'suite'])],
            'precio_base' => 'required|numeric|min:0',
            'estado'      => ['required', Rule::in(['disponible', 'ocupada', 'mantenimiento'])],
            'descripcion' => 'nullable|string',
        ];

        $validatedData = $this->validate($rules);

        if ($this->habitacionId) {
            $habitacion = Habitacion::findOrFail($this->habitacionId);
            $habitacion->update($validatedData);
            session()->flash('success', 'Habitación actualizada correctamente.');
        } else {
            Habitacion::create($validatedData);
            session()->flash('success', 'Habitación creada correctamente.');
        }

        $this->closeModal();
        $this->dispatch('habitacion-guardada');
    }

    public function render()
    {
        return view('livewire.habitaciones.habitacion-form');
    }
}
