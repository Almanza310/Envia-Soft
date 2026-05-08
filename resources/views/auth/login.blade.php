<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-[#111827] tracking-tight">Acceso Operativo</h2>
        <p class="text-sm font-medium text-gray-500 mt-2">Bienvenido de nuevo al panel de administración ENVIA</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-5">
            <label for="email" class="block font-bold text-xs text-gray-700 uppercase tracking-widest mb-2 px-1">Correo Electrónico</label>
            <input id="email" class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C12026] focus:bg-white transition-all shadow-sm" type="email" name="email" value="{{ old('email') }}" placeholder="usuario@envia.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block font-bold text-xs text-gray-700 uppercase tracking-widest mb-2 px-1">Contraseña</label>
            <input id="password" class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C12026] focus:bg-white transition-all shadow-sm tracking-widest"
                            type="password"
                            name="password"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mb-8 px-1">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 text-[#C12026] focus:ring-[#C12026] border-gray-300 rounded transition-all" name="remember">
                <label for="remember_me" class="ml-2 block text-xs font-semibold text-gray-600">
                    {{ __('Recordarme') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <div>
                    <a href="{{ route('password.request') }}" class="font-bold text-xs text-[#C12026] hover:text-[#a01a1f] transition-colors">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                </div>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-[#c12026]/20 text-sm font-bold text-white bg-gradient-to-r from-[#C12026] to-[#a01a1f] hover:from-[#a01a1f] hover:to-[#801418] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C12026] transition-all duration-300 transform hover:-translate-y-0.5 active:scale-[0.98]">
                Iniciar Sesión
            </button>
        </div>
    </form>
    </form>
</x-guest-layout>

