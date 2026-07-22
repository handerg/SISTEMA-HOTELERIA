<?php

namespace App\Livewire;

use App\Models\Factura;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Reserva;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $hoy = Carbon::today();

        // KPIs
        $facturacionHoy = Factura::whereDate('fecha_emision', $hoy)->sum('total');
        $totalHabitaciones = Habitacion::count();
        $habitacionesOcupadas = Habitacion::where('estado', 'ocupada')->count();
        $habitacionesDisponibles = Habitacion::where('estado', 'disponible')->count();
        $habitacionesMantenimiento = Habitacion::where('estado', 'mantenimiento')->count();
        $reservasActivas = Reserva::activas()->count();
        $huespedesRegistrados = Huesped::count();

        // Últimas reservas
        $ultimasReservas = Reserva::with(['huesped', 'habitacion'])
            ->latest()
            ->take(5)
            ->get();

        // Datos para gráfico de ocupación (últimos 7 días)
        $chartLabels = [];
        $chartOcupadas = [];
        $chartDisponibles = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $chartLabels[] = $fecha->format('D d');

            $ocupadasEnFecha = Reserva::where('estado', '!=', 'cancelada')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_fin', '>=', $fecha)
                ->count();

            $chartOcupadas[] = $ocupadasEnFecha;
            $chartDisponibles[] = $totalHabitaciones - $ocupadasEnFecha;
        }

        // Facturación últimos 7 días
        $chartFacturacion = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $chartFacturacion[] = (float) Factura::whereDate('fecha_emision', $fecha)->sum('total');
        }

        return view('livewire.dashboard', compact(
            'facturacionHoy',
            'totalHabitaciones',
            'habitacionesOcupadas',
            'habitacionesDisponibles',
            'habitacionesMantenimiento',
            'reservasActivas',
            'huespedesRegistrados',
            'ultimasReservas',
            'chartLabels',
            'chartOcupadas',
            'chartDisponibles',
            'chartFacturacion',
        ))->layout('components.layouts.app', ['title' => 'Dashboard', 'subtitle' => 'Resumen general y métricas']);
    }
}
