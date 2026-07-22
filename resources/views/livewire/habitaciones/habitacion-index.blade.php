<div>
    {{-- Top actions --}}
    <x-slot name="actions">
        <button @click="$dispatch('abrirModalHabitacion')" class="btn-primary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Habitación
        </button>
    </x-slot>

    {{-- Filters --}}
    <div class="mb-6 flex gap-4 bg-zinc-900 p-4 rounded-2xl border border-zinc-800">
        <div class="flex-1">
            <select wire:model.live="filtroTipo" class="input-dark">
                <option value="">Todos los tipos</option>
                <option value="simple">Simple</option>
                <option value="doble">Doble</option>
                <option value="suite">Suite</option>
            </select>
        </div>
        <div class="flex-1">
            <select wire:model.live="filtroEstado" class="input-dark">
                <option value="">Todos los estados</option>
                <option value="disponible">Disponible</option>
                <option value="ocupada">Ocupada</option>
                <option value="mantenimiento">Mantenimiento</option>
            </select>
        </div>
    </div>

    {{-- Room Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in">
        @forelse($habitaciones as $habitacion)
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-xl hover:shadow-2xl hover:border-zinc-700 transition-all group flex flex-col">
                {{-- Card Header / Status Line --}}
                <div class="h-2 w-full 
                    {{ $habitacion->estado === 'disponible' ? 'bg-green-500' : '' }}
                    {{ $habitacion->estado === 'ocupada' ? 'bg-red-500' : '' }}
                    {{ $habitacion->estado === 'mantenimiento' ? 'bg-yellow-500' : '' }}
                "></div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-zinc-100 group-hover:text-blue-400 transition-colors">{{ $habitacion->nombre }}</h3>
                            <p class="text-xs text-zinc-500 uppercase tracking-wider font-semibold mt-1">{{ $habitacion->tipo }}</p>
                        </div>
                        <span class="badge badge-{{ $habitacion->estado }}">{{ ucfirst($habitacion->estado) }}</span>
                    </div>

                    <div class="mt-auto pt-4 border-t border-zinc-800/50 flex items-end justify-between">
                        <div>
                            <p class="text-xs text-zinc-500 mb-1">Precio Base</p>
                            <p class="text-lg font-bold text-zinc-200">${{ number_format($habitacion->precio_base, 2) }}<span class="text-xs font-normal text-zinc-500">/noche</span></p>
                        </div>
                        <div class="flex gap-2">
                            <button @click="$dispatch('abrirModalHabitacion', { id: {{ $habitacion->id }} })" class="p-2 text-zinc-400 hover:text-blue-400 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button wire:click="deleteHabitacion({{ $habitacion->id }})" wire:confirm="¿Estás seguro de eliminar esta habitación?" class="p-2 text-zinc-400 hover:text-red-400 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-zinc-500 bg-zinc-900 rounded-2xl border border-zinc-800">
                <svg class="w-12 h-12 mx-auto mb-4 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                </svg>
                <p>No se encontraron habitaciones.</p>
            </div>
        @endforelse
    </div>

    @if($habitaciones->hasPages())
        <div class="mt-6">
            {{ $habitaciones->links() }}
        </div>
    @endif

    {{-- Form Modal --}}
    @livewire('habitaciones.habitacion-form')
</div>
