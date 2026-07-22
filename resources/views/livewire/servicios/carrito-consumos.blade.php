<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
    {{-- Columna Izquierda: Selección de Habitación y Carrito --}}
    <div class="lg:col-span-1 space-y-6 flex flex-col h-[calc(100vh-140px)]">
        
        {{-- Selector de Reserva --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6 shadow-xl shrink-0">
            <h3 class="text-lg font-bold text-zinc-100 mb-4">Destino de Cargo</h3>
            <select wire:model.live="reservaSeleccionadaId" class="input-dark text-base py-3">
                <option value="">-- Seleccionar Habitación --</option>
                @foreach($reservasActivas as $reserva)
                    <option value="{{ $reserva->id }}">
                        {{ $reserva->habitacion->nombre }} — {{ $reserva->huesped->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Carrito Actual --}}
        <div class="bg-zinc-900 rounded-2xl border border-zinc-800 flex flex-col flex-1 shadow-xl overflow-hidden">
            <div class="p-4 border-b border-zinc-800 shrink-0 bg-zinc-900/90 flex justify-between items-center">
                <h3 class="text-lg font-bold text-zinc-100">Cuenta Actual</h3>
                <span class="text-xs font-semibold px-2 py-1 bg-zinc-800 rounded-md text-zinc-300">{{ count($carrito) }} items</span>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                @if(empty($carrito))
                    <div class="h-full flex flex-col items-center justify-center text-zinc-500 space-y-4">
                        <svg class="w-16 h-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <p class="text-center text-sm">El carrito está vacío.<br>Selecciona productos a la derecha.</p>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach($carrito as $id => $item)
                            <li class="bg-zinc-950 rounded-xl p-3 border border-zinc-800/50 flex flex-col gap-2">
                                <div class="flex justify-between items-start">
                                    <span class="font-medium text-zinc-200 text-sm leading-tight pr-4">{{ $item['nombre'] }}</span>
                                    <button wire:click="removerDelCarrito({{ $id }})" class="text-red-400 hover:text-red-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <div class="flex justify-between items-end mt-1">
                                    <div class="flex items-center bg-zinc-800 rounded-lg overflow-hidden border border-zinc-700">
                                        <button wire:click="actualizarCantidad({{ $id }}, {{ $item['cantidad'] - 1 }})" class="px-2 py-1 hover:bg-zinc-700 text-zinc-400 font-bold">-</button>
                                        <input type="number" min="1" value="{{ $item['cantidad'] }}" wire:change="actualizarCantidad({{ $id }}, $event.target.value)" class="w-10 text-center bg-transparent border-none focus:ring-0 text-sm py-1 px-0 appearance-none">
                                        <button wire:click="actualizarCantidad({{ $id }}, {{ $item['cantidad'] + 1 }})" class="px-2 py-1 hover:bg-zinc-700 text-zinc-400 font-bold">+</button>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-zinc-500">${{ number_format($item['precio'], 2) }} c/u</p>
                                        <p class="font-mono font-bold text-blue-400 mt-0.5">${{ number_format($item['subtotal'], 2) }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Checkout Footer --}}
            <div class="p-6 border-t border-zinc-800 shrink-0 bg-zinc-900">
                <div class="flex justify-between items-end mb-4">
                    <span class="text-zinc-400 font-medium">Total Consumos:</span>
                    <span class="text-3xl font-bold font-mono text-zinc-100">${{ number_format($this->total, 2) }}</span>
                </div>
                <button wire:click="procesarConsumos" class="btn-primary w-full justify-center py-3 text-base shadow-blue-500/20" @if(empty($carrito) || !$reservaSeleccionadaId) disabled @endif>
                    Cargar a Habitación
                </button>
                @if(!$reservaSeleccionadaId && !empty($carrito))
                    <p class="text-xs text-red-400 mt-2 text-center">Debes seleccionar una habitación destino.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Columna Derecha: Catálogo de Servicios --}}
    <div class="lg:col-span-2 bg-zinc-900 rounded-2xl border border-zinc-800 flex flex-col h-[calc(100vh-140px)] shadow-xl">
        {{-- Buscador y Filtros --}}
        <div class="p-6 border-b border-zinc-800 shrink-0 flex gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="searchServicio" type="text" class="input-dark pl-10" placeholder="Buscar producto o servicio...">
            </div>
            <div class="w-48">
                <select wire:model.live="categoriaFiltrada" class="input-dark">
                    <option value="">Todas</option>
                    <option value="minibar">Minibar</option>
                    <option value="restaurante">Restaurante</option>
                    <option value="lavandería">Lavandería</option>
                    <option value="otros">Otros</option>
                </select>
            </div>
        </div>

        {{-- Grid de Productos --}}
        <div class="flex-1 overflow-y-auto p-6 bg-zinc-950/50">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($servicios as $servicio)
                    <div wire:click="agregarAlCarrito({{ $servicio->id }})" class="bg-zinc-800/80 hover:bg-zinc-700/80 border border-zinc-700 hover:border-blue-500/50 rounded-xl p-4 cursor-pointer transition-all group relative flex flex-col h-full shadow-lg">
                        
                        <span class="absolute top-3 right-3 text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-zinc-900 text-zinc-400 border border-zinc-700/50">
                            {{ $servicio->categoria ?? 'General' }}
                        </span>

                        <div class="mt-4 mb-2 flex-1">
                            <h4 class="font-bold text-sm text-zinc-200 group-hover:text-blue-400 transition-colors leading-tight">{{ $servicio->nombre }}</h4>
                        </div>

                        <div class="flex items-end justify-between mt-auto">
                            <div>
                                <p class="text-xs text-zinc-500 mb-0.5">Stock: <span class="font-medium {{ $servicio->stock < 10 ? 'text-yellow-400' : 'text-green-400' }}">{{ $servicio->stock }}</span></p>
                                <p class="font-mono font-bold text-zinc-100">${{ number_format($servicio->precio, 2) }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-zinc-500">
                        <p>No se encontraron servicios disponibles o con stock.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
