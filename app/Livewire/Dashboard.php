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

        // Pre-cargar datos de los últimos 7 días para evitar múltiples consultas (N+1)
        $fechaInicioSemana = Carbon::today()->subDays(6);
        
        $reservasRango = Reserva::where('estado', '!=', 'cancelada')
            ->where('fecha_fin', '>=', $fechaInicioSemana)
            ->where('fecha_inicio', '<=', $hoy)
            ->get(['fecha_inicio', 'fecha_fin']);

        $facturasRango = Factura::where('fecha_emision', '>=', $fechaInicioSemana)
            ->get(['fecha_emision', 'total'])
            ->groupBy(fn ($f) => Carbon::parse($f->fecha_emision)->format('Y-m-d'));

        // Datos para gráficos
        $chartLabels = [];
        $chartOcupadas = [];
        $chartDisponibles = [];
        $chartFacturacion = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $fechaStr = $fecha->format('Y-m-d');
            
            $chartLabels[] = $fecha->format('D d');

            // Calcular ocupación verificando las fechas en memoria (evita 7 consultas a BD)
            $ocupadasEnFecha = $reservasRango->filter(function ($reserva) use ($fecha) {
                $inicio = Carbon::parse($reserva->fecha_inicio)->startOfDay();
                $fin = Carbon::parse($reserva->fecha_fin)->endOfDay();
                return $fecha->between($inicio, $fin);
            })->count();

            $chartOcupadas[] = $ocupadasEnFecha;
            $chartDisponibles[] = $totalHabitaciones - $ocupadasEnFecha;
            
            // Facturación de ese día (evita otras 7 consultas)
            $sumaFacturas = isset($facturasRango[$fechaStr]) 
                ? $facturasRango[$fechaStr]->sum('total') 
                : 0;
                
            $chartFacturacion[] = (float) $sumaFacturas;
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
