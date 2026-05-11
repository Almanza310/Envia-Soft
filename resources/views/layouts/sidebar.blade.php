<aside class="w-64 bg-[#E30613] border-r border-red-800 flex flex-col overflow-y-auto shadow-2xl" style="background-color: #E30613 !important;">
    <!-- Logo section -->
    <div class="px-6 py-6 border-b border-white/10 mb-4 sticky top-0 bg-[#E30613] z-10 w-full flex justify-center items-center" style="background-color: #E30613 !important;">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center transition-transform hover:scale-105 duration-300 py-4 px-5 rounded-2xl shadow-xl w-full" style="background: linear-gradient(135deg, #7A0A0A 0%, #4A0505 100%); border-bottom: 4px solid rgba(0,0,0,0.2);">
            <img src="{{ asset('images/images.png') }}" alt="ENVIA" class="h-10 w-auto object-contain drop-shadow-lg">
        </a>
    </div>

    <!-- Navigation links -->
    <nav x-data="{ activeMenu: '{{ request()->routeIs('readings.*') ? 'medidores' : (request()->routeIs('inventories.*') ? 'inventarios' : '') }}' }" class="flex-1 px-4 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group flex items-center px-4 py-3.5 text-[13px] rounded-r-lg transition-all duration-300 uppercase tracking-tighter">
            <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            Dashboard
        </a>

        @if(Auth::user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group flex items-center px-4 py-3.5 text-[13px] rounded-r-lg transition-all duration-300 uppercase tracking-tighter">
                <svg class="{{ request()->routeIs('users.*') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Gestión Usuarios
            </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'supervisor']))
            <!-- MEDIDORES -->
            <div>
                <a href="{{ route('readings.index') }}" 
                   @click="if({{ request()->routeIs('readings.index') ? 'true' : 'false' }}) { $event.preventDefault(); activeMenu = activeMenu === 'medidores' ? '' : 'medidores'; } else { activeMenu = 'medidores'; }"
                   class="w-full flex justify-between items-center px-4 py-3.5 text-[13px] {{ request()->routeIs('readings.index') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group rounded-r-lg transition-all duration-300 uppercase tracking-tighter cursor-pointer">
                    <div class="flex items-center">
                        <svg class="{{ request()->routeIs('readings.index') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        Gestión de Medidores
                    </div>
                    <svg :style="activeMenu !== 'medidores' ? 'transform: rotate(180deg);' : 'transform: rotate(0deg);'" class="w-4 h-4 text-white/70 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <div x-show="activeMenu === 'medidores'" class="mt-1" x-cloak>
                    <!-- Sub item: Estadísticas -->
                    <a href="{{ route('readings.stats') }}" class="{{ request()->routeIs('readings.stats') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group flex items-center pl-10 pr-4 py-3 text-[13px] rounded-r-lg transition-all duration-300 uppercase tracking-tighter">
                        <svg class="{{ request()->routeIs('readings.stats') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Estadísticas Med.
                    </a>
                </div>
            </div>

            <!-- INVENTARIO -->
            <div>
                <a href="{{ route('inventories.index') }}" 
                   @click="if({{ request()->routeIs('inventories.index') ? 'true' : 'false' }}) { $event.preventDefault(); activeMenu = activeMenu === 'inventarios' ? '' : 'inventarios'; } else { activeMenu = 'inventarios'; }"
                   class="w-full flex justify-between items-center px-4 py-3.5 text-[13px] {{ request()->routeIs('inventories.index') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group rounded-r-lg transition-all duration-300 uppercase tracking-tighter cursor-pointer">
                    <div class="flex items-center">
                        <svg class="{{ request()->routeIs('inventories.index') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Gestión Inventario
                    </div>
                    <svg :style="activeMenu !== 'inventarios' ? 'transform: rotate(180deg);' : 'transform: rotate(0deg);'" class="w-4 h-4 text-white/70 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </a>
                <div x-show="activeMenu === 'inventarios'" class="mt-1" x-cloak>
                    <!-- Sub item: Estadísticas -->
                    <a href="{{ route('inventories.stats') }}" class="{{ request()->routeIs('inventories.stats') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group flex items-center pl-10 pr-4 py-3 text-[13px] rounded-r-lg transition-all duration-300 uppercase tracking-tighter">
                        <svg class="{{ request()->routeIs('inventories.stats') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Estadísticas Inv.
                    </a>
                </div>
            </div>
        @endif

        @if(Auth::user()->role === 'admin')
            <!-- CICLO PHVA -->
            <div>
                <a href="{{ route('phva.index') }}" 
                   class="w-full flex items-center px-4 py-3.5 text-[13px] {{ request()->routeIs('phva.*') ? 'bg-white/20 text-white border-l-4 border-white font-bold' : 'text-white font-bold' }} group rounded-r-lg transition-all duration-300 uppercase tracking-tighter cursor-pointer">
                    <svg class="{{ request()->routeIs('phva.*') ? 'text-white' : 'text-white/90 group-hover:text-white' }} mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Ciclo PHVA
                </a>
            </div>
        @endif
    </nav>
</aside>

