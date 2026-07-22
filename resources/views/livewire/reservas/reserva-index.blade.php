<div>
    <x-slot name="actions">
        <button @click="$dispatch('abrirModalReserva')" class="btn-primary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Reserva
        </button>
    </x-slot>

    {{-- Filters --}}
    <div class="mb-6 flex gap-4 bg-zinc-900 p-4 rounded-2xl border border-zinc-800">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="searchHuesped" type="text" class="input-dark pl-10" placeholder="Buscar huésped titular...">
        </div>
        <div class="w-64">
            <select wire:model.live="filtroEstado" class="input-dark">
                <option value="">Todos los estados</option>
                <option value="activa">Activa</option>
                <option value="finalizada">Finalizada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-dark">
                <thead>
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">Huésped Titular</th>
                        <th class="text-left">Habitación</th>
                        <th class="text-left">Fechas (Check-in / Check-out)</th>
                        <th class="text-center">Estado</th>
                        <th class="text-right">Precio Acordado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        <tr>
                            <td class="font-mono text-zinc-500">#{{ str_pad($reserva->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="font-medium text-zinc-200">{{ $reserva->huesped->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-zinc-500 font-mono">{{ $reserva->huesped->cedula ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $reserva->habitacion->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-zinc-500 uppercase">{{ $reserva->habitacion->tipo ?? '' }}</div>
                            </td>
                            <td>
                                <div class="text-zinc-300">{{ $reserva->fecha_inicio->format('d/m/Y') }}</div>
                                <div class="text-xs text-zinc-500">al {{ $reserva->fecha_fin->format('d/m/Y') }} ({{ $reserva->diasEstadia }} noches)</div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-{{ $reserva->estado }}">{{ ucfirst($reserva->estado) }}</span>
                            </td>
                            <td class="text-right font-mono font-medium text-zinc-200">
                                ${{ number_format($reserva->precio_acordado, 2) }}
                            </td>
                            <td class="text-right space-x-2">
                                @if($reserva->estado === 'activa')
                                    <button wire:click="cancelarReserva({{ $reserva->id }})" wire:confirm="¿Seguro que deseas cancelar esta reserva activa?" class="text-red-400 hover:text-red-300 p-1 transition-colors" title="Cancelar Reserva">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                @endif
                                <a href="/facturacion?reserva={{ $reserva->id }}" class="text-blue-400 hover:text-blue-300 p-1 transition-colors inline-block" title="Ver Cuenta / Facturar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-zinc-500">
                                No se encontraron reservas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservas->hasPages())
            <div class="p-4 border-t border-zinc-800">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>

    {{-- Form Modal --}}
    @livewire('reservas.reserva-form')
</div>
