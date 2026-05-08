<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden gap-4">
                <!-- Decorative subtle brand accent -->
                <div class="absolute right-0 top-0 w-64 h-64 bg-[#C12026] opacity-5 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3"></div>
                
                <div class="relative z-10 flex items-center">
                    <div class="w-16 h-16 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center mr-6 shrink-0">
                        <svg class="w-8 h-8 text-[#C12026]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#C12026] tracking-wider uppercase mb-1">Panel de Control</p>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Bienvenido, {{ explode(' ', auth()->user()->name)[0] ?? 'Usuario' }}</h1>
                    </div>
                </div>

                <div class="relative z-10 w-full md:w-auto flex justify-end md:ml-auto">
                    <div class="bg-gray-50 px-5 py-3 rounded-xl border border-gray-100 flex items-center text-sm font-medium text-gray-600">
                        <svg class="w-5 h-5 mr-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ now()->format('d F, Y') }}
                    </div>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'supervisor']))
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Módulos de Gestión</h2>
                <span class="text-sm font-medium text-gray-500">Acceso Rápido</span>
            </div>

            <!-- Modules Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                @if(auth()->user()->role === 'admin')
                <!-- Card: Usuarios -->
                <a href="{{ route('users.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C12026] to-red-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 text-gray-500 flex items-center justify-center mb-6 group-hover:bg-[#C12026] group-hover:text-white group-hover:border-[#C12026] transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Usuarios</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 flex-grow">Gestión de accesos, roles y permisos de la plataforma.</p>
                    
                    <div class="flex items-center text-sm font-semibold text-[#C12026]">
                        Ingresar
                        <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </a>
                @endif

                <!-- Card: Medidores -->
                <a href="{{ route('readings.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C12026] to-red-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 text-gray-500 flex items-center justify-center mb-6 group-hover:bg-[#C12026] group-hover:text-white group-hover:border-[#C12026] transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Medidores</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 flex-grow">Control de lecturas e indicadores de consumo.</p>
                    
                    <div class="flex items-center text-sm font-semibold text-[#C12026]">
                        Ingresar
                        <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </a>

                <!-- Card: Inventario -->
                <a href="{{ route('inventories.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C12026] to-red-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 text-gray-500 flex items-center justify-center mb-6 group-hover:bg-[#C12026] group-hover:text-white group-hover:border-[#C12026] transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Inventario</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 flex-grow">Gestión de stock, materiales y recursos físicos.</p>
                    
                    <div class="flex items-center text-sm font-semibold text-[#C12026]">
                        Ingresar
                        <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </a>

                @if(auth()->user()->role === 'admin')
                <!-- Card: PHVA -->
                <a href="{{ route('phva.index') }}" class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C12026] to-red-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 text-gray-500 flex items-center justify-center mb-6 group-hover:bg-[#C12026] group-hover:text-white group-hover:border-[#C12026] transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ciclo PHVA</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 flex-grow">Documentación de calidad, procesos y mejora.</p>
                    
                    <div class="flex items-center text-sm font-semibold text-[#C12026]">
                        Ingresar
                        <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </a>
                @endif
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between text-gray-900 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-64 h-64 bg-[#C12026] opacity-5 blur-3xl rounded-full -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex items-center mb-4 md:mb-0">
                    <span class="flex h-4 w-4 relative mr-4">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                    </span>
                    <div>
                        <h4 class="font-bold text-lg text-gray-900">Sistema Operativo</h4>
                        <p class="text-gray-500 text-sm">Servidores conectados y encriptados.</p>
                    </div>
                </div>

                <div class="relative z-10 text-center md:text-right">
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-widest mb-1">Rol en Sistema</p>
                    <p class="text-2xl font-extrabold text-gray-900 tracking-tight uppercase">{{ auth()->user()->role }}</p>
                </div>
            </div>

            @else
            <!-- User View -->
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100 max-w-3xl mx-auto mt-10">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 border border-gray-100 mb-6 shadow-inner">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Modo de Lectura</h2>
                <p class="text-gray-500 text-lg leading-relaxed max-w-xl mx-auto">
                    Tu cuenta actualmente tiene privilegios de lectura. Usa el menú lateral para consultar la información pública del sistema ENVIA.
                </p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
