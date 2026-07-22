<?php

namespace App\Livewire\Huespedes;

use App\Models\Huesped;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\On;

class HuespedForm extends Component
{
    public $huespedId;
    public string $nombre = '';
    public string $cedula = '';
    public $edad = null;
    public string $telefono = '';
    public string $email = '';

    public bool $isOpen = false;

    #[On('abrirModalHuesped')]
    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['huespedId', 'nombre', 'cedula', 'edad', 'telefono', 'email']);

        if ($id) {
            $huesped = Huesped::findOrFail($id);
            $this->huespedId = $huesped->id;
            $this->nombre = $huesped->nombre;
            $this->cedula = $huesped->cedula;
            $this->edad = $huesped->edad;
            $this->telefono = $huesped->telefono;
            $this->email = $huesped->email ?? '';
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
            'nombre'   => 'required|string|max:255',
            'cedula'   => ['required', 'string', 'max:20', Rule::unique('huespedes')->ignore($this->huespedId)],
            'edad'     => 'nullable|integer|min:0|max:120',
            'telefono' => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
        ];

        $validatedData = $this->validate($rules);

        if ($this->huespedId) {
            $huesped = Huesped::findOrFail($this->huespedId);
            $huesped->update($validatedData);
            session()->flash('success', 'Huésped actualizado correctamente.');
        } else {
            Huesped::create($validatedData);
            session()->flash('success', 'Huésped creado correctamente.');
        }

        $this->closeModal();
        $this->dispatch('huesped-guardado');
    }

    public function render()
    {
        return view('livewire.huespedes.huesped-form');
    }
}
