<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in">
            <div class="modal-backdrop absolute inset-0" wire:click="closeModal"></div>
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl w-full max-w-lg relative z-10 animate-slide-in">
                <div class="flex items-center justify-between p-6 border-b border-zinc-800">
                    <h3 class="text-lg font-bold text-zinc-100">
                        {{ $habitacionId ? 'Editar Habitación' : 'Nueva Habitación' }}
                    </h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Nombre / Número <span class="text-red-500">*</span></label>
                        <input wire:model="nombre" type="text" class="input-dark" placeholder="Ej: Suite 201" required>
                        @error('nombre') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Tipo <span class="text-red-500">*</span></label>
                            <select wire:model="tipo" class="input-dark" required>
                                <option value="simple">Simple</option>
                                <option value="doble">Doble</option>
                                <option value="suite">Suite</option>
                            </select>
                            @error('tipo') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Precio Base <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-zinc-500">$</span>
                                </div>
                                <input wire:model="precio_base" type="number" step="0.01" min="0" class="input-dark pl-8" required>
                            </div>
                            @error('precio_base') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Estado <span class="text-red-500">*</span></label>
                        <select wire:model="estado" class="input-dark" required>
                            <option value="disponible">Disponible</option>
                            <option value="ocupada">Ocupada</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                        @error('estado') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Descripción</label>
                        <textarea wire:model="descripcion" class="input-dark" rows="3" placeholder="Detalles de la habitación..."></textarea>
                        @error('descripcion') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Guardar Habitación</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
