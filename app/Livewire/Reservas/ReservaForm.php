<?php

namespace App\Livewire\Reservas;

use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class ReservaForm extends Component
{
    // Datos Huesped
    public $huespedId;
    public string $searchHuesped = '';
    public $huespedSeleccionado = null;

    // Datos Habitación
    public $habitacionId;
    public $habitacionSeleccionada = null;

    // Datos Reserva
    public $fecha_inicio;
    public $fecha_fin;
    public $precio_acordado;
    public $notas = '';

    // Acompañantes
    public array $acompanantes = []; // [id, nombre, cedula]
    public string $searchAcompanante = '';

    public bool $isOpen = false;

    #[On('abrirModalReserva')]
    public function openModal()
    {
        $this->reset([
            'huespedId', 'searchHuesped', 'huespedSeleccionado',
            'habitacionId', 'habitacionSeleccionada',
            'fecha_inicio', 'fecha_fin', 'precio_acordado', 'notas',
            'acompanantes', 'searchAcompanante'
        ]);

        $this->fecha_inicio = now()->format('Y-m-d');
        $this->fecha_fin = now()->addDays(1)->format('Y-m-d');
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    // --- Búsqueda de Huésped Titular ---
    public function seleccionarHuesped($id)
    {
        $this->huespedSeleccionado = Huesped::find($id);
        $this->huespedId = $id;
        $this->searchHuesped = '';
    }

    public function limpiarHuesped()
    {
        $this->huespedSeleccionado = null;
        $this->huespedId = null;
    }

    // --- Búsqueda de Acompañantes ---
    public function agregarAcompanante($id)
    {
        // Verificar que no sea el titular
        if ($id == $this->huespedId) {
            $this->addError('acompanantes', 'El titular no puede ser agregado como acompañante.');
            return;
        }

        // Verificar que no esté ya en la lista
        if (collect($this->acompanantes)->where('id', $id)->count() > 0) {
            $this->addError('acompanantes', 'El huésped ya está en la lista de acompañantes.');
            return;
        }

        $huesped = Huesped::find($id);
        if ($huesped) {
            $this->acompanantes[] = [
                'id' => $huesped->id,
                'nombre' => $huesped->nombre,
                'cedula' => $huesped->cedula
            ];
        }
        $this->searchAcompanante = '';
    }

    public function removerAcompanante($index)
    {
        unset($this->acompanantes[$index]);
        $this->acompanantes = array_values($this->acompanantes); // reindex
    }

    // --- Selección de Habitación ---
    public function seleccionarHabitacion($id)
    {
        $this->habitacionSeleccionada = Habitacion::find($id);
        $this->habitacionId = $id;
        $this->precio_acordado = $this->habitacionSeleccionada->precio_base;
    }

    public function save()
    {
        $this->validate([
            'huespedId' => 'required|exists:huespedes,id',
            'habitacionId' => 'required|exists:habitaciones,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'precio_acordado' => 'required|numeric|min:0',
        ]);

        $habitacion = Habitacion::findOrFail($this->habitacionId);

        if ($habitacion->estado !== 'disponible') {
            $this->addError('habitacionId', 'La habitación seleccionada ya no está disponible.');
            return;
        }

        try {
            DB::transaction(function () use ($habitacion) {
                // 1. Crear Reserva
                $reserva = Reserva::create([
                    'huesped_id' => $this->huespedId,
                    'habitacion_id' => $this->habitacionId,
                    'user_id' => auth()->id(),
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'precio_acordado' => $this->precio_acordado,
                    'estado' => 'activa',
                    'notas' => $this->notas,
                ]);

                // 2. Asociar Acompañantes
                if (!empty($this->acompanantes)) {
                    $acompanantesIds = collect($this->acompanantes)->pluck('id')->toArray();
                    $reserva->acompanantes()->attach($acompanantesIds);
                }

                // 3. Cambiar estado de Habitación
                $habitacion->update(['estado' => 'ocupada']);
            });

            session()->flash('success', 'Reserva creada exitosamente.');
            $this->closeModal();
            $this->redirect(route('reservas.index'), navigate: true);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $resultadosHuesped = [];
        if (strlen($this->searchHuesped) >= 2) {
            $resultadosHuesped = Huesped::buscar($this->searchHuesped)->take(5)->get();
        }

        $resultadosAcompanante = [];
        if (strlen($this->searchAcompanante) >= 2) {
            $resultadosAcompanante = Huesped::buscar($this->searchAcompanante)->take(5)->get();
        }

        $habitacionesDisponibles = Habitacion::disponibles()->orderBy('nombre')->get();

        return view('livewire.reservas.reserva-form', [
            'resultadosHuesped' => $resultadosHuesped,
            'resultadosAcompanante' => $resultadosAcompanante,
            'habitacionesDisponibles' => $habitacionesDisponibles
        ]);
    }
}
