<div class="animate-fade-in">
    {{-- Logo --}}
    <div class="text-center mb-10">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-2xl shadow-blue-500/20 mb-6">
            <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-zinc-100">HotelPMS</h1>
        <p class="text-zinc-500 text-sm mt-1">Sistema de Gestión Hotelera</p>
    </div>

    {{-- Login Form --}}
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-8 shadow-2xl">
        <h2 class="text-lg font-semibold text-zinc-200 mb-6">Iniciar Sesión</h2>

        <form wire:submit="login" class="space-y-5">
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-400 mb-2">Correo Electrónico</label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    class="input-dark"
                    placeholder="admin@hotel.com"
                    autofocus
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-400 mb-2">Contraseña</label>
                <input
                    wire:model="password"
                    type="password"
                    id="password"
                    class="input-dark"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center">
                <input
                    wire:model="remember"
                    type="checkbox"
                    id="remember"
                    class="w-4 h-4 rounded bg-zinc-800 border-zinc-600 text-blue-500 focus:ring-blue-500 focus:ring-offset-zinc-900"
                >
                <label for="remember" class="ml-2 text-sm text-zinc-400">Recordarme</label>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="btn-primary w-full justify-center py-3"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-wait"
            >
                <span wire:loading.remove>Ingresar</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Verificando...
                </span>
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <p class="text-center text-zinc-600 text-xs mt-8">
        &copy; {{ date('Y') }} HotelPMS. Todos los derechos reservados.
    </p>
</div>
