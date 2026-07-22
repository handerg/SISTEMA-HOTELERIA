<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center animate-fade-in">
            <div class="modal-backdrop absolute inset-0" wire:click="closeModal"></div>
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl w-full max-w-lg relative z-10 animate-slide-in">
                <div class="flex items-center justify-between p-6 border-b border-zinc-800">
                    <h3 class="text-lg font-bold text-zinc-100">
                        {{ $huespedId ? 'Editar Huésped' : 'Nuevo Huésped' }}
                    </h3>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <input wire:model="nombre" type="text" class="input-dark" required>
                        @error('nombre') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Cédula / Documento <span class="text-red-500">*</span></label>
                            <input wire:model="cedula" type="text" class="input-dark" required>
                            @error('cedula') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-1">Edad</label>
                            <input wire:model="edad" type="number" class="input-dark" min="0" max="120">
                            @error('edad') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Teléfono</label>
                        <input wire:model="telefono" type="text" class="input-dark">
                        @error('telefono') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-400 mb-1">Correo Electrónico</label>
                        <input wire:model="email" type="email" class="input-dark">
                        @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-zinc-800">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancelar</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Guardar Huésped</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
