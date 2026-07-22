<div>
    <x-slot name="actions">
        <a href="/facturacion" wire:navigate class="btn-secondary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Volver a Cuentas Abiertas
        </a>
    </x-slot>

    {{-- Filters --}}
    <div class="mb-6 flex gap-4 bg-zinc-900 p-4 rounded-2xl border border-zinc-800">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="input-dark pl-10" placeholder="Buscar por huésped, cédula o ID factura...">
        </div>
        <div class="w-64">
            <input wire:model.live="filtroFecha" type="date" class="input-dark">
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-dark">
                <thead>
                    <tr>
                        <th class="text-left w-24">Nº Factura</th>
                        <th class="text-left">Fecha Emisión</th>
                        <th class="text-left">Huésped Titular</th>
                        <th class="text-left">Habitación</th>
                        <th class="text-right">Estadía</th>
                        <th class="text-right">Consumos</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td class="font-mono text-zinc-300 font-bold">INV-{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="text-zinc-200">{{ $factura->fecha_emision->format('d/m/Y') }}</div>
                                <div class="text-xs text-zinc-500">{{ $factura->fecha_emision->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-zinc-200">{{ $factura->reserva->huesped->nombre ?? 'Desconocido' }}</div>
                                <div class="text-xs text-zinc-500 font-mono">{{ $factura->reserva->huesped->cedula ?? '' }}</div>
                            </td>
                            <td>
                                <div class="text-zinc-200">{{ $factura->reserva->habitacion->nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-zinc-500">RES-{{ $factura->reserva_id }}</div>
                            </td>
                            <td class="text-right font-mono text-zinc-400">${{ number_format($factura->subtotal_habitacion, 2) }}</td>
                            <td class="text-right font-mono text-zinc-400">${{ number_format($factura->subtotal_consumos, 2) }}</td>
                            <td class="text-right font-mono font-bold text-blue-400">${{ number_format($factura->total, 2) }}</td>
                            <td class="text-right">
                                <button @click="$dispatch('abrirModalFactura', { id: {{ $factura->id }} })" class="btn-secondary btn-sm" title="Ver Detalle / Imprimir">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    Ver
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-zinc-500">
                                No se encontraron facturas emitidas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facturas->hasPages())
            <div class="p-4 border-t border-zinc-800">
                {{ $facturas->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Detalle Factura --}}
    @livewire('facturacion.factura-detalle')
</div>
