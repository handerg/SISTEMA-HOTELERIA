<div>
    {{-- Top actions --}}
    <x-slot name="actions">
        <button wire:click="openModal" class="btn-primary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Servicio
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
            <input wire:model.live.debounce.300ms="search" type="text" class="input-dark pl-10" placeholder="Buscar servicio...">
        </div>
        <div class="w-64">
            <select wire:model.live="filtroCategoria" class="input-dark">
                <option value="">Todas las categorías</option>
                <option value="minibar">Minibar</option>
                <option value="restaurante">Restaurante</option>
                <option value="lavandería">Lavandería</option>
                <option value="otros">Otros</option>
            </select>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-dark">
                <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Categoría</th>
                        <th class="text-right">Precio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr class="{{ !$servicio->activo ? 'opacity-50' : '' }}">
                            <td class="font-medium">{{ $servicio->nombre }}</td>
                            <td><span class="text-xs uppercase tracking-wider text-zinc-500">{{ $servicio->categoria ?? 'General' }}</span></td>
                            <td class="text-right font-mono">${{ number_format($servicio->precio, 2) }}</td>
                            <td class="text-center">
                                @if($servicio->stock > 10)
                                    <span class="text-green-400 font-medium">{{ $servicio->stock }}</span>
                                @elseif($servicio->stock > 0)
                                    <span class="text-yellow-400 font-medium">{{ $servicio->stock }}</span>
                                @else
                                    <span class="text-red-400 font-medium">Agotado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($servicio->activo)
                                    <span class="badge badge-activa">Activo</span>
                                @else
                                    <span class="badge badge-cancelada">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-right space-x-2">
                                <button wire:click="openModal({{ $servicio->id }})" class="text-blue-400 hover:text-blue-300 p-1 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button wire:click="deleteServicio({{ $servicio->id }})" wire:confirm="¿Estás seguro de eliminar este servicio?" class="text-red-400 hover:text-red-300 p-1 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-zinc-500">
                                No se encontraron servicios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($servicios->hasPages())
            <div class="p-4 border-t border-zinc-800">
                {{ $servicios->links() }}
            </div>
        @endif
    </div>

    {{-- Form Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in p-4">
            <div class="modal-backdrop absolute inset-0" wire:click="closeModal"></div>
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl w-full max-w-md relative z-10 animate-slide-in">
                <div class="flex items-center justify-between p-6 border-b border-zinc-800">
                    <h3 class="text-lg font-bold text-zinc-100">
                        {{ $servicioId ? 'Editar Servicio' : 'Nuevo Servicio' }}
                    </h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input wire:model="nombre" type="text" class="input-dark" required>
                        @error('nombre') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Precio <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-zinc-500">$</span></div>
                                <input wire:model="precio" type="number" step="0.01" min="0" class="input-dark pl-8" required>
                            </div>
                            @error('precio') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Stock Inicial <span class="text-red-500">*</span></label>
                            <input wire:model="stock" type="number" min="0" class="input-dark" required>
                            @error('stock') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Categoría</label>
                        <select wire:model="categoria" class="input-dark">
                            <option value="minibar">Minibar</option>
                            <option value="restaurante">Restaurante</option>
                            <option value="lavandería">Lavandería</option>
                            <option value="otros">Otros</option>
                        </select>
                        @error('categoria') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center mt-4">
                        <input wire:model="activo" type="checkbox" id="activo" class="w-4 h-4 rounded bg-zinc-800 border-zinc-600 text-blue-500 focus:ring-blue-500">
                        <label for="activo" class="ml-2 text-sm text-zinc-300">Servicio Activo (Disponible para venta)</label>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Guardar Servicio</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
