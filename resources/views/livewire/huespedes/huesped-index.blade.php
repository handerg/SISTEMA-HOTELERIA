<div>
    {{-- Top actions --}}
    <x-slot name="actions">
        <button @click="$dispatch('abrirModalHuesped')" class="btn-primary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Huésped
        </button>
    </x-slot>

    {{-- Search --}}
    <div class="mb-6 flex gap-4 bg-zinc-900 p-4 rounded-2xl border border-zinc-800">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="input-dark pl-10" placeholder="Buscar por nombre, cédula o email...">
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-dark">
                <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Cédula</th>
                        <th class="text-left">Contacto</th>
                        <th class="text-center">Frecuencia</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($huespedes as $huesped)
                        <tr>
                            <td class="font-medium">
                                {{ $huesped->nombre }}
                                @if($huesped->edad)
                                    <span class="text-xs text-zinc-500 ml-2">({{ $huesped->edad }} años)</span>
                                @endif
                            </td>
                            <td class="font-mono text-zinc-400">{{ $huesped->cedula }}</td>
                            <td>
                                @if($huesped->telefono)
                                    <div class="flex items-center gap-2 text-zinc-300">
                                        <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $huesped->telefono }}
                                    </div>
                                @endif
                                @if($huesped->email)
                                    <div class="flex items-center gap-2 text-zinc-400 text-xs mt-1">
                                        <svg class="w-4 h-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $huesped->email }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="bg-zinc-800 text-zinc-300 py-1 px-3 rounded-full text-xs font-semibold">
                                    {{ $huesped->frecuencia }} reservas
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                <button @click="$dispatch('abrirModalHuesped', { id: {{ $huesped->id }} })" class="text-blue-400 hover:text-blue-300 p-1 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button wire:click="deleteHuesped({{ $huesped->id }})" wire:confirm="¿Estás seguro de eliminar este huésped?" class="text-red-400 hover:text-red-300 p-1 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-zinc-500">
                                No se encontraron huéspedes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($huespedes->hasPages())
            <div class="p-4 border-t border-zinc-800">
                {{ $huespedes->links() }}
            </div>
        @endif
    </div>

    {{-- Form Modal --}}
    @livewire('huespedes.huesped-form')
</div>
