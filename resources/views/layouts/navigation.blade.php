<nav x-data="{ open: false }" class="bg-[#E30613] border-b border-red-800 sticky top-0 z-30" style="background-color: #E30613 !important;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex flex-1">

            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-6">

                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col items-end mr-3 text-right">
                                <div class="text-xs font-bold text-white uppercase tracking-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[9px] font-medium text-white/70 uppercase tracking-[0.2em] mt-0.5">Gestión de Operativos</div>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-white/20 p-0.5 overflow-hidden ring-1 ring-white/30">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=E30613&bold=true" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover rounded-full">
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

