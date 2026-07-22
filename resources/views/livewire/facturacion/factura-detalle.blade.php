<div>
    @if($isOpen && $factura)
        <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in p-4 no-print">
            <div class="modal-backdrop absolute inset-0" wire:click="closeModal"></div>
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto relative z-10 animate-slide-in">
                
                {{-- Header (No Print) --}}
                <div class="flex items-center justify-between p-6 border-b border-zinc-800 sticky top-0 bg-zinc-900/95 backdrop-blur z-20 no-print">
                    <h3 class="text-xl font-bold text-zinc-100 flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Detalle de Factura #{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <div class="flex gap-2">
                        <button onclick="window.print()" class="btn-primary btn-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 15.272A9 9 0 0 1 17.272 4.728M17.272 15.272A9 9 0 0 1 6.728 4.728M15 15h3.75M9 15H5.25M15 9h3.75M9 9H5.25M12 21V3" />
                            </svg>
                            Imprimir
                        </button>
                        <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-300 p-1">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                {{-- Contenido Imprimible --}}
                <div class="p-8 print:p-0 print:text-black print:bg-white bg-zinc-950/50" id="factura-print">
                    
                    {{-- Encabezado Factura --}}
                    <div class="flex justify-between items-start mb-10 pb-6 border-b border-zinc-800 print:border-gray-300">
                        <div>
                            <h1 class="text-2xl font-bold text-zinc-100 print:text-black mb-1">HOTEL PMS S.A.</h1>
                            <p class="text-sm text-zinc-500 print:text-gray-600">Av. Principal 123, Ciudad</p>
                            <p class="text-sm text-zinc-500 print:text-gray-600">Tel: +1 234 567 8900</p>
                            <p class="text-sm text-zinc-500 print:text-gray-600">Email: info@hotelpms.com</p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-3xl font-light text-blue-500 print:text-gray-800 mb-2">FACTURA</h2>
                            <p class="text-sm text-zinc-300 print:text-gray-700"><span class="text-zinc-500 print:text-gray-500">Nº:</span> INV-{{ str_pad($factura->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-zinc-300 print:text-gray-700"><span class="text-zinc-500 print:text-gray-500">Fecha:</span> {{ $factura->fecha_emision->format('d/m/Y H:i') }}</p>
                            <p class="text-sm text-zinc-300 print:text-gray-700"><span class="text-zinc-500 print:text-gray-500">Reserva Nº:</span> RES-{{ $factura->reserva_id }}</p>
                        </div>
                    </div>

                    {{-- Datos Cliente y Estadia --}}
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <h4 class="text-xs font-bold text-zinc-500 print:text-gray-500 uppercase tracking-wider mb-2">Facturar a:</h4>
                            <p class="text-lg font-medium text-zinc-200 print:text-black">{{ $factura->reserva->huesped->nombre ?? 'N/A' }}</p>
                            <p class="text-sm text-zinc-400 print:text-gray-700">C.I / Doc: {{ $factura->reserva->huesped->cedula ?? 'N/A' }}</p>
                            <p class="text-sm text-zinc-400 print:text-gray-700">Tel: {{ $factura->reserva->huesped->telefono ?? 'N/A' }}</p>
                            <p class="text-sm text-zinc-400 print:text-gray-700">Email: {{ $factura->reserva->huesped->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-500 print:text-gray-500 uppercase tracking-wider mb-2">Detalles Estadía:</h4>
                            <p class="text-sm text-zinc-300 print:text-gray-800"><span class="text-zinc-500 print:text-gray-600">Habitación:</span> {{ $factura->reserva->habitacion->nombre ?? 'N/A' }} ({{ $factura->reserva->habitacion->tipo ?? '' }})</p>
                            <p class="text-sm text-zinc-300 print:text-gray-800"><span class="text-zinc-500 print:text-gray-600">Check-in:</span> {{ $factura->reserva->fecha_inicio->format('d/m/Y') }}</p>
                            <p class="text-sm text-zinc-300 print:text-gray-800"><span class="text-zinc-500 print:text-gray-600">Check-out:</span> {{ $factura->reserva->fecha_fin->format('d/m/Y') }}</p>
                            <p class="text-sm text-zinc-300 print:text-gray-800"><span class="text-zinc-500 print:text-gray-600">Noches:</span> {{ max(1, $factura->reserva->fecha_inicio->diffInDays($factura->reserva->fecha_fin)) }}</p>
                        </div>
                    </div>

                    {{-- Tabla de Detalles --}}
                    <div class="mb-10">
                        <table class="w-full text-sm text-left print:text-black">
                            <thead class="bg-zinc-900 border-y border-zinc-800 print:bg-gray-100 print:border-gray-300 text-zinc-400 print:text-gray-600">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Descripción</th>
                                    <th class="py-3 px-4 font-semibold text-center w-24">Cant.</th>
                                    <th class="py-3 px-4 font-semibold text-right w-32">Precio Unit.</th>
                                    <th class="py-3 px-4 font-semibold text-right w-32">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800 print:divide-gray-200">
                                {{-- Fila de Estadía --}}
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="font-medium text-zinc-200 print:text-black">Estadía - {{ $factura->reserva->habitacion->nombre ?? 'Habitación' }}</p>
                                        <p class="text-xs text-zinc-500 print:text-gray-500">Del {{ $factura->reserva->fecha_inicio->format('d/m') }} al {{ $factura->reserva->fecha_fin->format('d/m') }}</p>
                                    </td>
                                    <td class="py-4 px-4 text-center text-zinc-300 print:text-black">{{ max(1, $factura->reserva->fecha_inicio->diffInDays($factura->reserva->fecha_fin)) }}</td>
                                    <td class="py-4 px-4 text-right font-mono text-zinc-300 print:text-black">${{ number_format($factura->reserva->precio_acordado, 2) }}</td>
                                    <td class="py-4 px-4 text-right font-mono text-zinc-200 print:text-black font-medium">${{ number_format($factura->subtotal_habitacion, 2) }}</td>
                                </tr>

                                {{-- Filas de Consumos --}}
                                @foreach($factura->reserva->consumos as $consumo)
                                    <tr>
                                        <td class="py-3 px-4 text-zinc-300 print:text-gray-800">
                                            {{ $consumo->servicio->nombre ?? 'Servicio' }}
                                        </td>
                                        <td class="py-3 px-4 text-center text-zinc-300 print:text-black">{{ $consumo->cantidad }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-zinc-400 print:text-gray-700">${{ number_format($consumo->precio_unitario, 2) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-zinc-300 print:text-black">${{ number_format($consumo->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totales --}}
                    <div class="flex justify-end mb-8">
                        <div class="w-64 space-y-3">
                            <div class="flex justify-between text-sm text-zinc-400 print:text-gray-600">
                                <span>Subtotal Estadía:</span>
                                <span class="font-mono">${{ number_format($factura->subtotal_habitacion, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-zinc-400 print:text-gray-600">
                                <span>Subtotal Consumos:</span>
                                <span class="font-mono">${{ number_format($factura->subtotal_consumos, 2) }}</span>
                            </div>
                            <div class="pt-3 border-t border-zinc-800 print:border-gray-300 flex justify-between items-center">
                                <span class="font-bold text-zinc-100 print:text-black text-lg">TOTAL:</span>
                                <span class="font-bold font-mono text-blue-500 print:text-black text-2xl">${{ number_format($factura->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center pt-8 border-t border-zinc-800 print:border-gray-300 text-sm text-zinc-500 print:text-gray-500">
                        <p>Emitido por: {{ $factura->user->name ?? 'Sistema' }}</p>
                        <p class="mt-2 text-xs italic">Gracias por su preferencia.</p>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
