<div>
    <x-slot name="actions">
        <a href="/facturas-historial" wire:navigate class="btn-secondary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            Historial de Facturas
        </a>
    </x-slot>

    @if($reserva)
        <div class="mb-4 flex justify-between items-center bg-blue-500/10 border border-blue-500/30 p-4 rounded-xl text-blue-400 text-sm">
            <span>Mostrando la cuenta específica de la reserva <strong>#{{ str_pad($reserva, 5, '0', STR_PAD_LEFT) }}</strong>.</span>
            <a href="/facturacion" wire:navigate class="underline font-medium hover:text-blue-300">Ver todas las cuentas activas</a>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 animate-fade-in">
        @forelse($reservasParaFacturar as $reservaItem)
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 shadow-xl overflow-hidden flex flex-col">
                {{-- Card Header --}}
                <div class="p-5 border-b border-zinc-800 bg-zinc-900/50 flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-zinc-100 leading-tight">{{ $reservaItem->habitacion->nombre }}</h3>
                            <p class="text-sm text-zinc-400 mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                {{ $reservaItem->huesped->nombre }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-zinc-500 font-mono">RES-{{ str_pad($reservaItem->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-xs text-zinc-400 mt-1">{{ $reservaItem->fecha_inicio->format('d M') }} — {{ $reservaItem->fecha_fin->format('d M') }}</p>
                    </div>
                </div>

                {{-- Breakdowns --}}
                <div class="p-5 flex-1 grid grid-cols-2 gap-6 relative">
                    <div class="absolute left-1/2 top-4 bottom-4 w-px bg-zinc-800 -translate-x-1/2"></div>
                    
                    {{-- Estadia --}}
                    <div>
                        <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Estadía</h4>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm text-zinc-300">Precio x Noche</span>
                            <span class="font-mono text-zinc-400">${{ number_format($reservaItem->precio_acordado, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm text-zinc-300">Noches</span>
                            <span class="font-mono text-zinc-400">× {{ $reservaItem->diasEstadia }}</span>
                        </div>
                        <div class="mt-4 pt-3 border-t border-zinc-800 flex justify-between items-end">
                            <span class="font-medium text-zinc-200">Subtotal</span>
                            <span class="font-mono text-lg text-zinc-200">${{ number_format($reservaItem->total_estadia, 2) }}</span>
                        </div>
                    </div>

                    {{-- Consumos --}}
                    <div>
                        <h4 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3 flex justify-between">
                            Consumos
                            <span class="bg-zinc-800 text-zinc-400 px-2 py-0.5 rounded text-[10px]">{{ $reservaItem->consumos->sum('cantidad') }} items</span>
                        </h4>
                        @if($reservaItem->consumos->count() > 0)
                            <div class="space-y-2 max-h-[100px] overflow-y-auto pr-2">
                                @foreach($reservaItem->consumos->take(3) as $consumo)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-zinc-400 truncate pr-2">{{ $consumo->cantidad }}x {{ $consumo->servicio->nombre }}</span>
                                        <span class="font-mono text-zinc-500">${{ number_format($consumo->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                                @if($reservaItem->consumos->count() > 3)
                                    <div class="text-xs text-blue-400 italic">... y {{ $reservaItem->consumos->count() - 3 }} más</div>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-zinc-500 italic mt-2">Sin consumos extra.</p>
                        @endif

                        <div class="mt-4 pt-3 border-t border-zinc-800 flex justify-between items-end">
                            <span class="font-medium text-zinc-200">Subtotal</span>
                            <span class="font-mono text-lg text-zinc-200">${{ number_format($reservaItem->total_consumos, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Footer (Total & Actions) --}}
                <div class="p-5 border-t border-zinc-800 bg-zinc-950 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Total a Pagar</p>
                        <p class="text-2xl font-bold font-mono text-blue-400">${{ number_format($reservaItem->total_general, 2) }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="/consumos?reserva={{ $reservaItem->id }}" class="btn-secondary py-2" wire:navigate>+ Consumos</a>
                        <button wire:click="facturarReserva({{ $reservaItem->id }})" wire:confirm="¿Confirmas el checkout de esta habitación y generar la factura definitiva?" class="btn-primary py-2 shadow-blue-500/20">
                            Facturar & Checkout
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-zinc-900 rounded-2xl border border-zinc-800">
                <svg class="w-16 h-16 mx-auto mb-4 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="text-lg font-medium text-zinc-300 mb-1">No hay cuentas abiertas</h3>
                <p class="text-zinc-500 text-sm">No hay reservas activas pendientes de facturación en este momento.</p>
            </div>
        @endforelse
    </div>

    @if($reservasParaFacturar->hasPages())
        <div class="mt-6">
            {{ $reservasParaFacturar->links() }}
        </div>
    @endif
</div>
