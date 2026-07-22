<?php

namespace App\Livewire\Logs;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroModelo = '';
    public string $filtroAccion = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroModelo()
    {
        $this->resetPage();
    }

    public function updatingFiltroAccion()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = AuditLog::with('user')
            ->when($this->search, function ($q) {
                $q->where('descripcion', 'like', "%{$this->search}%")
                  ->orWhereHas('user', function ($q2) {
                      $q2->where('name', 'like', "%{$this->search}%");
                  });
            })
            ->when($this->filtroModelo, fn($q) => $q->where('modelo', $this->filtroModelo))
            ->when($this->filtroAccion, fn($q) => $q->where('accion', $this->filtroAccion))
            ->latest('created_at')
            ->paginate(15);

        // Modelos únicos para el filtro
        $modelos = AuditLog::select('modelo')->distinct()->orderBy('modelo')->pluck('modelo');

        return view('livewire.logs.audit-log-index', compact('logs', 'modelos'))
            ->layout('components.layouts.app', ['title' => 'Logs de Auditoría', 'subtitle' => 'Registro de actividad del sistema']);
    }
}
