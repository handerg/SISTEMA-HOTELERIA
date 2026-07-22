<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in p-4">
            <div class="modal-backdrop absolute inset-0" wire:click="closeModal"></div>
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative z-10 animate-slide-in">
                <div class="flex items-center justify-between p-6 border-b border-zinc-800 sticky top-0 bg-zinc-900/95 backdrop-blur z-20">
                    <h3 class="text-xl font-bold text-zinc-100">Nueva Reserva</h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit="save" class="space-y-8">
                        
                        {{-- 1. Huésped Titular --}}
                        <section>
                            <h4 class="text-sm font-semibold text-zinc-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">1</span>
                                Huésped Titular
                            </h4>
                            
                            @if($huespedSeleccionado)
                                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-zinc-100">{{ $huespedSeleccionado->nombre }}</p>
                                        <p class="text-sm text-zinc-400">C.I: {{ $huespedSeleccionado->cedula }} | Tel: {{ $huespedSeleccionado->telefono ?? 'N/A' }}</p>
                                    </div>
                                    <button type="button" wire:click="limpiarHuesped" class="text-zinc-400 hover:text-red-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @else
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="searchHuesped" type="text" class="input-dark" placeholder="Buscar por nombre o cédula...">
                                    @if(count($resultadosHuesped) > 0)
                                        <div class="absolute z-10 w-full mt-1 bg-zinc-800 border border-zinc-700 rounded-xl shadow-lg overflow-hidden">
                                            @foreach($resultadosHuesped as $huesped)
                                                <div wire:click="seleccionarHuesped({{ $huesped->id }})" class="p-3 hover:bg-zinc-700 cursor-pointer border-b border-zinc-700/50 last:border-0">
                                                    <p class="font-medium text-zinc-200">{{ $huesped->nombre }}</p>
                                                    <p class="text-xs text-zinc-400">Cédula: {{ $huesped->cedula }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(strlen($searchHuesped) >= 2 && count($resultadosHuesped) === 0)
                                        <p class="text-xs text-zinc-500 mt-2">No se encontraron resultados. <a href="/huespedes" class="text-blue-400 hover:underline">Ir a crear huésped</a></p>
                                    @endif
                                </div>
                                @error('huespedId') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            @endif
                        </section>

                        {{-- 2. Habitación y Fechas --}}
                        <section>
                            <h4 class="text-sm font-semibold text-zinc-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">2</span>
                                Detalles de Estadía
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Check-in</label>
                                    <input wire:model="fecha_inicio" type="date" class="input-dark">
                                    @error('fecha_inicio') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Check-out</label>
                                    <input wire:model="fecha_fin" type="date" class="input-dark">
                                    @error('fecha_fin') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-zinc-400 mb-2">Seleccionar Habitación</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto pr-2">
                                    @forelse($habitacionesDisponibles as $hab)
                                        <div wire:click="seleccionarHabitacion({{ $hab->id }})" 
                                             class="p-3 rounded-xl border cursor-pointer transition-all {{ $habitacionId === $hab->id ? 'border-blue-500 bg-blue-500/10' : 'border-zinc-700 bg-zinc-800 hover:border-zinc-500' }}">
                                            <p class="font-bold text-zinc-200">{{ $hab->nombre }}</p>
                                            <div class="flex justify-between items-end mt-2">
                                                <span class="text-xs text-zinc-500 uppercase">{{ $hab->tipo }}</span>
                                                <span class="font-mono text-blue-400">${{ $hab->precio_base }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-zinc-500 text-sm py-4">No hay habitaciones disponibles.</div>
                                    @endforelse
                                </div>
                                @error('habitacionId') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4 w-1/2">
                                <label class="block text-sm font-medium text-zinc-400 mb-1">Precio Acordado (por noche)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-zinc-500">$</span>
                                    </div>
                                    <input wire:model="precio_acordado" type="number" step="0.01" class="input-dark pl-8" {{ !$habitacionId ? 'disabled' : '' }}>
                                </div>
                                <p class="text-xs text-zinc-500 mt-1">Se pre-llena con el precio base de la habitación, pero puede ser modificado.</p>
                                @error('precio_acordado') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </section>

                        {{-- 3. Acompañantes (Opcional) --}}
                        <section>
                            <h4 class="text-sm font-semibold text-zinc-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">3</span>
                                Acompañantes <span class="text-xs text-zinc-500 lowercase font-normal">(Opcional)</span>
                            </h4>

                            <div class="relative mb-3">
                                <input wire:model.live.debounce.300ms="searchAcompanante" type="text" class="input-dark" placeholder="Buscar acompañante...">
                                @if(count($resultadosAcompanante) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-zinc-800 border border-zinc-700 rounded-xl shadow-lg overflow-hidden">
                                        @foreach($resultadosAcompanante as $huesped)
                                            <div wire:click="agregarAcompanante({{ $huesped->id }})" class="p-3 hover:bg-zinc-700 cursor-pointer flex justify-between items-center border-b border-zinc-700/50 last:border-0">
                                                <div>
                                                    <p class="font-medium text-zinc-200">{{ $huesped->nombre }}</p>
                                                    <p class="text-xs text-zinc-400">Cédula: {{ $huesped->cedula }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('acompanantes') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if(count($acompanantes) > 0)
                                <div class="bg-zinc-800/50 rounded-xl p-3 space-y-2">
                                    @foreach($acompanantes as $index => $acomp)
                                        <div class="flex justify-between items-center bg-zinc-800 p-2 rounded-lg border border-zinc-700">
                                            <div>
                                                <span class="text-zinc-200 text-sm">{{ $acomp['nombre'] }}</span>
                                                <span class="text-zinc-500 text-xs ml-2">({{ $acomp['cedula'] }})</span>
                                            </div>
                                            <button type="button" wire:click="removerAcompanante({{ $index }})" class="text-red-400 hover:text-red-300 p-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        {{-- Notas --}}
                        <section>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Notas de la reserva</label>
                            <textarea wire:model="notas" class="input-dark" rows="2" placeholder="Requerimientos especiales, detalles de pago, etc."></textarea>
                        </section>

                    </form>
                </div>

                <div class="p-6 border-t border-zinc-800 bg-zinc-900/95 sticky bottom-0 z-20 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" wire:click="closeModal" class="btn-secondary">Cancelar</button>
                    <button type="button" wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Confirmar Reserva</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
