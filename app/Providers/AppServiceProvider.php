<?php

namespace App\Providers;

use App\Models\Consumo;
use App\Models\Habitacion;
use App\Models\Huesped;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Observers\AuditObserver;
use App\Policies\ReservaPolicy;
use App\Policies\ServicioPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Observers para auditoría
        Huesped::observe(AuditObserver::class);
        Habitacion::observe(AuditObserver::class);
        Reserva::observe(AuditObserver::class);
        Servicio::observe(AuditObserver::class);
        Consumo::observe(AuditObserver::class);

        // Registrar Policies (Laravel 11+ via Gate)
        Gate::policy(Reserva::class, ReservaPolicy::class);
        Gate::policy(Servicio::class, ServicioPolicy::class);
    }
}
