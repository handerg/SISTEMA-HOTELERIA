<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

// Rutas Públicas (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Rutas Protegidas (Auth)
Route::middleware('auth')->group(function () {
    // Logout normal
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Dashboard
    Route::get('/', App\Livewire\Dashboard::class)->name('dashboard');

    // Gestión General
    Route::get('/reservas', App\Livewire\Reservas\ReservaIndex::class)->name('reservas.index');
    Route::get('/huespedes', App\Livewire\Huespedes\HuespedIndex::class)->name('huespedes.index');
    Route::get('/habitaciones', App\Livewire\Habitaciones\HabitacionIndex::class)->name('habitaciones.index');
    Route::get('/servicios', App\Livewire\Servicios\ServicioIndex::class)->name('servicios.index');

    // Operativa y Caja
    Route::get('/consumos', App\Livewire\Servicios\CarritoConsumos::class)->name('consumos.index');
    Route::get('/facturacion', App\Livewire\Facturacion\FacturacionIndex::class)->name('facturacion.index');
    Route::get('/facturas-historial', App\Livewire\Facturacion\HistorialFacturas::class)->name('facturas.historial');

    // Solo Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/logs', App\Livewire\Logs\AuditLogIndex::class)->name('logs.index');
    });
});
