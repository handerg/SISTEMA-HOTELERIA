<div>
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        {{-- Facturación del día --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 kpi-card kpi-blue animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
                <span class="text-xs text-zinc-500 font-medium">Hoy</span>
            </div>
            <p class="text-3xl font-bold text-zinc-100">${{ number_format($facturacionHoy, 2) }}</p>
            <p class="text-sm text-zinc-500 mt-1">Facturación del día</p>
        </div>

        {{-- Habitaciones Ocupadas --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 kpi-card kpi-rose animate-fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                    </svg>
                </div>
                <span class="badge badge-ocupada">{{ $habitacionesOcupadas }}/{{ $totalHabitaciones }}</span>
            </div>
            <p class="text-3xl font-bold text-zinc-100">{{ $habitacionesOcupadas }}</p>
            <p class="text-sm text-zinc-500 mt-1">Habitaciones ocupadas</p>
        </div>

        {{-- Reservas Activas --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 kpi-card kpi-green animate-fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-100">{{ $reservasActivas }}</p>
            <p class="text-sm text-zinc-500 mt-1">Reservas activas</p>
        </div>

        {{-- Huéspedes Registrados --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 kpi-card kpi-violet animate-fade-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-100">{{ $huespedesRegistrados }}</p>
            <p class="text-sm text-zinc-500 mt-1">Huéspedes registrados</p>
        </div>
    </div>

    {{-- Charts + Recent --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        {{-- Occupancy Chart --}}
        <div class="xl:col-span-2 bg-zinc-900 rounded-2xl border border-zinc-800 p-6 animate-fade-in" style="animation-delay: 0.4s">
            <h3 class="text-lg font-semibold text-zinc-200 mb-4">Ocupación — Últimos 7 días</h3>
            <div class="h-64">
                <canvas id="occupancyChart"></canvas>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 animate-fade-in" style="animation-delay: 0.5s">
            <h3 class="text-lg font-semibold text-zinc-200 mb-4">Facturación Diaria</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Room Status Grid --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 mb-8 animate-fade-in" style="animation-delay: 0.5s">
        <h3 class="text-lg font-semibold text-zinc-200 mb-4">Estado de Habitaciones</h3>
        <div class="flex gap-4 mb-4 text-xs">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Disponible ({{ $habitacionesDisponibles }})</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Ocupada ({{ $habitacionesOcupadas }})</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-500"></span> Mantenimiento ({{ $habitacionesMantenimiento }})</span>
        </div>
    </div>

    {{-- Recent Reservations --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 animate-fade-in" style="animation-delay: 0.6s">
        <h3 class="text-lg font-semibold text-zinc-200 mb-4">Últimas Reservas</h3>
        @if($ultimasReservas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full table-dark">
                    <thead>
                        <tr>
                            <th class="text-left rounded-tl-lg">Huésped</th>
                            <th class="text-left">Habitación</th>
                            <th class="text-left">Fechas</th>
                            <th class="text-left">Estado</th>
                            <th class="text-right rounded-tr-lg">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasReservas as $reserva)
                            <tr>
                                <td class="font-medium">{{ $reserva->huesped->nombre ?? 'N/A' }}</td>
                                <td>{{ $reserva->habitacion->nombre ?? 'N/A' }}</td>
                                <td class="text-zinc-400">
                                    {{ $reserva->fecha_inicio->format('d/m') }} — {{ $reserva->fecha_fin->format('d/m') }}
                                </td>
                                <td><span class="badge badge-{{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span></td>
                                <td class="text-right font-mono">${{ number_format($reserva->precio_acordado, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 text-zinc-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <p>No hay reservas registradas aún.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:navigated', () => { initCharts(); });
document.addEventListener('DOMContentLoaded', () => { initCharts(); });

function initCharts() {
    const labels = @json($chartLabels);
    const ocupadas = @json($chartOcupadas);
    const disponibles = @json($chartDisponibles);
    const facturacion = @json($chartFacturacion);

    // Destroy existing charts
    Chart.helpers.each(Chart.instances, (instance) => { instance.destroy(); });

    const chartDefaults = {
        color: '#a1a1aa',
        borderColor: '#27272a',
        font: { family: 'Inter' }
    };

    // Occupancy Chart
    const occCtx = document.getElementById('occupancyChart');
    if (occCtx) {
        new Chart(occCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Ocupadas',
                        data: ocupadas,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Disponibles',
                        data: disponibles,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#a1a1aa', font: { family: 'Inter', size: 12 } }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: { color: '#71717a', font: { family: 'Inter' } },
                        grid: { color: 'rgba(39, 39, 42, 0.5)' }
                    },
                    y: {
                        stacked: true,
                        ticks: { color: '#71717a', font: { family: 'Inter' }, stepSize: 1 },
                        grid: { color: 'rgba(39, 39, 42, 0.5)' }
                    }
                }
            }
        });
    }

    // Revenue Chart
    const revCtx = document.getElementById('revenueChart');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Facturación ($)',
                    data: facturacion,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#1e3a5f',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#a1a1aa', font: { family: 'Inter', size: 12 } }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#71717a', font: { family: 'Inter' } },
                        grid: { color: 'rgba(39, 39, 42, 0.5)' }
                    },
                    y: {
                        ticks: {
                            color: '#71717a',
                            font: { family: 'Inter' },
                            callback: function(value) { return '$' + value; }
                        },
                        grid: { color: 'rgba(39, 39, 42, 0.5)' }
                    }
                }
            }
        });
    }
}
</script>
@endpush
