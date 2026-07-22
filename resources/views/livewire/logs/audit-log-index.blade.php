<div>
    {{-- Filters --}}
    <div class="mb-6 flex gap-4 bg-zinc-900 p-4 rounded-2xl border border-zinc-800">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="input-dark pl-10" placeholder="Buscar por descripción o usuario...">
        </div>
        <div class="w-48">
            <select wire:model.live="filtroModelo" class="input-dark">
                <option value="">Todos los Módulos</option>
                @foreach($modelos as $mod)
                    <option value="{{ $mod }}">{{ $mod }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <select wire:model.live="filtroAccion" class="input-dark">
                <option value="">Todas las Acciones</option>
                <option value="crear">Crear</option>
                <option value="actualizar">Actualizar</option>
                <option value="eliminar">Eliminar</option>
            </select>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-2xl animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-dark text-sm">
                <thead>
                    <tr>
                        <th class="text-left w-48">Fecha</th>
                        <th class="text-left w-32">Usuario</th>
                        <th class="text-center w-24">Acción</th>
                        <th class="text-left w-32">Módulo</th>
                        <th class="text-left">Descripción / Cambios</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-zinc-800/50 transition-colors" x-data="{ expanded: false }">
                            <td class="align-top py-3 px-4">
                                <div class="text-zinc-200">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-zinc-500 font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="align-top py-3 px-4">
                                <div class="font-medium text-zinc-300">{{ $log->user->name ?? 'Sistema' }}</div>
                            </td>
                            <td class="align-top py-3 px-4 text-center">
                                @if($log->accion === 'crear')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-500/10 text-green-400 border border-green-500/20">Crear</span>
                                @elseif($log->accion === 'actualizar')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-500/10 text-blue-400 border border-blue-500/20">Actualizar</span>
                                @elseif($log->accion === 'eliminar')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-red-500/10 text-red-400 border border-red-500/20">Eliminar</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-zinc-800 text-zinc-400">{{ $log->accion }}</span>
                                @endif
                            </td>
                            <td class="align-top py-3 px-4 font-mono text-xs text-zinc-400">
                                {{ $log->modelo }} #{{ $log->modelo_id }}
                            </td>
                            <td class="align-top py-3 px-4">
                                <div class="flex justify-between items-start">
                                    <p class="text-zinc-300">{{ $log->descripcion }}</p>
                                    @if(!empty($log->datos_nuevos) || !empty($log->datos_anteriores))
                                        <button @click="expanded = !expanded" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                            <span x-text="expanded ? 'Ocultar JSON' : 'Ver JSON'"></span>
                                            <svg class="w-3 h-3 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    @endif
                                </div>
                                
                                @if(!empty($log->datos_nuevos) || !empty($log->datos_anteriores))
                                    <div x-show="expanded" x-collapse class="mt-3 grid grid-cols-2 gap-4">
                                        @if(!empty($log->datos_anteriores))
                                            <div class="bg-zinc-950 rounded-lg border border-zinc-800 p-3">
                                                <p class="text-[10px] uppercase font-bold text-zinc-500 mb-2 tracking-wider">Datos Anteriores</p>
                                                <pre class="text-xs text-red-300/80 font-mono whitespace-pre-wrap overflow-x-auto">{{ json_encode($log->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                        @if(!empty($log->datos_nuevos))
                                            <div class="bg-zinc-950 rounded-lg border border-zinc-800 p-3 {{ empty($log->datos_anteriores) ? 'col-span-2' : '' }}">
                                                <p class="text-[10px] uppercase font-bold text-zinc-500 mb-2 tracking-wider">Datos Nuevos</p>
                                                <pre class="text-xs text-green-300/80 font-mono whitespace-pre-wrap overflow-x-auto">{{ json_encode($log->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-zinc-500">
                                No se encontraron registros de auditoría.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-4 border-t border-zinc-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
