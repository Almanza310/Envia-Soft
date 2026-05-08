<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Módulo de Medidores (Agua y Luz)') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 rounded-xl bg-green-50 border border-green-100 flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">¡Éxito!</span> <span class="ml-1">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 rounded-xl bg-red-50 border border-red-100 shadow-sm" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span class="font-bold">Se encontraron errores:</span>
                    </div>
                    <ul class="list-disc pl-5 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-md transition-all duration-300">
                    <div class="p-4 rounded-xl bg-red-50 text-[#C12026] mr-4 group-hover:bg-[#C12026] group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Consumo Total Registrado</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1 tracking-tight">{{ number_format($totalConsumption, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Modal for Registration Form -->
            <x-modal name="add-reading" focusable>
                <div class="p-8 text-black bg-white rounded-2xl">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Registrar Nuevo Consumo</h3>
                        <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('readings.store') }}">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <x-input-label for="date" :value="__('Fecha de Lectura')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="date" class="block mt-1 w-full border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl" type="date" name="date" :value="old('date', date('Y-m-d'))" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="warehouse" :value="__('Bodega')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="warehouse" name="warehouse" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                        <option value="Bodega 1" {{ old('warehouse') == 'Bodega 1' ? 'selected' : '' }}>Bodega 1</option>
                                        <option value="Bodega 2" {{ old('warehouse') == 'Bodega 2' ? 'selected' : '' }}>Bodega 2</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="type" :value="__('Tipo de Contador')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="type" name="type" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                        <option value="Agua" {{ old('type') == 'Agua' ? 'selected' : '' }}>Agua</option>
                                        <option value="Luz" {{ old('type') == 'Luz' ? 'selected' : '' }}>Luz</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="value" :value="__('Valor del Contador')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="value" class="block mt-1 w-full border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl" type="number" step="0.01" name="value" :value="old('value')" required placeholder="0.00" />
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-3 pt-4">
                                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-200">
                                    Registrar Lectura
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- Table Actions (Outside) -->
            <div class="flex justify-end mb-4">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-reading')" class="inline-flex items-center px-6 py-3 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Registro
                </button>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 pb-6 border-b border-gray-50">
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Historial de Lecturas</h3>
                        
                        <!-- Filtros -->
                        <div class="flex flex-wrap items-center gap-3">
                            <form method="GET" action="{{ route('readings.index') }}" class="flex flex-wrap items-center gap-3">
                                <select name="warehouse" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" onchange="this.form.submit()">
                                    <option value="">Bodegas</option>
                                    <option value="Bodega 1" {{ request('warehouse') == 'Bodega 1' ? 'selected' : '' }}>Bodega 1</option>
                                    <option value="Bodega 2" {{ request('warehouse') == 'Bodega 2' ? 'selected' : '' }}>Bodega 2</option>
                                </select>
                                <select name="type" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" onchange="this.form.submit()">
                                    <option value="">Servicios</option>
                                    <option value="Agua" {{ request('type') == 'Agua' ? 'selected' : '' }}>Agua</option>
                                    <option value="Luz" {{ request('type') == 'Luz' ? 'selected' : '' }}>Luz</option>
                                </select>
                                <input type="date" name="start_date" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" value="{{ request('start_date') }}" onchange="this.form.submit()" placeholder="Desde" />
                                <input type="date" name="end_date" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" value="{{ request('end_date') }}" onchange="this.form.submit()" placeholder="Hasta" />
                                @if(request()->anyFilled(['warehouse', 'type', 'start_date', 'end_date']))
                                    <a href="{{ route('readings.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-red-600 flex items-center transition-colors uppercase tracking-widest ml-2">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Limpiar
                                    </a>
                                @endif
                            </form>
                            
                            <a href="{{ route('readings.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-[#C12026] text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-[#a01a1f] transition-all duration-300 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Fecha</th>
                                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Bodega</th>
                                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Servicio</th>
                                    <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Lectura</th>
                                    <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Consumo</th>
                                    <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Registrado por</th>
                                    <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($readings as $reading)
                                <tr class="hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($reading->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-bold rounded-lg bg-gray-100 text-gray-700 uppercase tracking-wider">
                                            {{ $reading->warehouse }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reading->type == 'Agua')
                                            <span class="text-blue-700 font-bold bg-blue-50 px-3 py-1 rounded-lg flex items-center inline-flex text-[10px] uppercase tracking-wider">
                                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.984 3.984 0 01-3.143-1.5z"></path></svg>
                                                {{ $reading->type }}
                                            </span>
                                        @else
                                            <span class="text-amber-700 font-bold bg-amber-50 px-3 py-1 rounded-lg flex items-center inline-flex text-[10px] uppercase tracking-wider">
                                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg>
                                                {{ $reading->type }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900 tracking-tight">{{ number_format($reading->value, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        @if($reading->consumption > 0)
                                            <span class="text-red-600 font-bold">+{{ number_format($reading->consumption, 2) }}</span>
                                        @else
                                            <span class="text-gray-900 font-bold">{{ number_format($reading->consumption, 2) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">{{ $reading->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Ver Detalles Action -->
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-reading-{{ $reading->id }}')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" title="Ver Detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            
                                            <!-- Editar Action -->
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-reading-{{ $reading->id }}')" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all duration-200" title="Editar Registro">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>

                                            <!-- Eliminar Action -->
                                            <form id="delete-form-{{ $reading->id }}" method="POST" action="{{ route('readings.destroy', $reading) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('{{ $reading->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="Eliminar Registro">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Modal Detalles -->
                                        <x-modal name="view-reading-{{ $reading->id }}" focusable>
                                            <div class="p-8 text-left bg-white rounded-2xl">
                                                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                                                    <h3 class="text-xl font-bold text-gray-900">Detalles del Registro</h3>
                                                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-6">
                                                    <div class="col-span-1 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Servicio</p>
                                                        <p class="text-base font-bold text-gray-900 flex items-center">
                                                            @if($reading->type == 'Agua') 
                                                                <span class="text-blue-600 mr-2">💧</span> Agua 
                                                            @else 
                                                                <span class="text-amber-500 mr-2">⚡</span> Luz 
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-span-1 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha</p>
                                                        <p class="text-base font-bold text-gray-900">{{ Carbon\Carbon::parse($reading->date)->format('d F Y') }}</p>
                                                    </div>
                                                    
                                                    <div class="col-span-2 bg-gray-50 rounded-xl p-5 border border-gray-100 flex items-center justify-between">
                                                        <div>
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ubicación</p>
                                                            <p class="text-lg font-bold text-gray-900 uppercase tracking-tight">{{ $reading->warehouse }}</p>
                                                        </div>
                                                        <div class="h-12 w-12 bg-red-50 rounded-xl flex items-center justify-center text-[#C12026]">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                        </div>
                                                    </div>

                                                    <div class="col-span-1 border-l-4 border-gray-900 pl-4 py-2">
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lectura Base</p>
                                                        <p class="text-2xl font-bold text-gray-900 tracking-tight">{{ number_format($reading->value, 2) }}</p>
                                                    </div>
                                                    <div class="col-span-1 border-l-4 border-[#C12026] pl-4 py-2">
                                                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Consumo Generado</p>
                                                        <p class="text-2xl font-bold text-[#C12026] tracking-tight">
                                                            @if($reading->consumption > 0) +{{ number_format($reading->consumption, 2) }} @ else 0.00 @endif
                                                        </p>
                                                    </div>

                                                    <div class="col-span-2 pt-6 border-t border-gray-50 flex items-center">
                                                        <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#C12026] font-bold text-sm mr-4 border border-red-100">
                                                            {{ substr($reading->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Registrado por</p>
                                                            <p class="text-sm font-bold text-gray-700 mt-1 uppercase tracking-tight">{{ $reading->user->name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-8 flex justify-end pt-4 border-t border-gray-50">
                                                    <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-gray-900 transition-all duration-200 shadow-md">
                                                        Cerrar Detalle
                                                    </button>
                                                </div>
                                            </div>
                                        </x-modal>

                                        <!-- Modal Editar -->
                                        <x-modal name="edit-reading-{{ $reading->id }}" focusable>
                                            <div class="p-8 text-left bg-white rounded-2xl">
                                                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Modificar Consumo
                                                    </h3>
                                                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form id="edit-form-{{ $reading->id }}" method="POST" action="{{ route('readings.update', $reading) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="space-y-5">
                                                        <div>
                                                            <x-input-label for="edit-date-{{ $reading->id }}" :value="__('Fecha de Lectura')" class="text-gray-700 font-semibold mb-1" />
                                                            <x-text-input id="edit-date-{{ $reading->id }}" class="block mt-1 w-full border-gray-200 rounded-xl" type="date" name="date" :value="old('date', Carbon\Carbon::parse($reading->date)->format('Y-m-d'))" required />
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <x-input-label for="edit-warehouse-{{ $reading->id }}" :value="__('Bodega')" class="text-gray-700 font-semibold mb-1" />
                                                                <select id="edit-warehouse-{{ $reading->id }}" name="warehouse" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                                                    <option value="Bodega 1" {{ old('warehouse', $reading->warehouse) == 'Bodega 1' ? 'selected' : '' }}>Bodega 1</option>
                                                                    <option value="Bodega 2" {{ old('warehouse', $reading->warehouse) == 'Bodega 2' ? 'selected' : '' }}>Bodega 2</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <x-input-label for="edit-type-{{ $reading->id }}" :value="__('Tipo de Contador')" class="text-gray-700 font-semibold mb-1" />
                                                                <select id="edit-type-{{ $reading->id }}" name="type" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                                                    <option value="Agua" {{ old('type', $reading->type) == 'Agua' ? 'selected' : '' }}>Agua</option>
                                                                    <option value="Luz" {{ old('type', $reading->type) == 'Luz' ? 'selected' : '' }}>Luz</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <x-input-label for="edit-value-{{ $reading->id }}" :value="__('Valor del Contador')" class="text-gray-700 font-semibold mb-1" />
                                                            <x-text-input id="edit-value-{{ $reading->id }}" class="block mt-1 w-full border-gray-200 rounded-xl" type="number" step="0.01" name="value" :value="old('value', $reading->value)" required placeholder="0.00" />
                                                        </div>
                                                        
                                                        <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-50">
                                                            <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                                                Cancelar
                                                            </button>
                                                            <button type="button" onclick="confirmUpdate('{{ $reading->id }}')" class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20 transition-all duration-200">
                                                                Actualizar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-10 py-24 whitespace-nowrap text-center bg-white rounded-xl">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="font-bold text-gray-400 uppercase tracking-widest text-sm">No hay lecturas registradas</p>
                                            <p class="text-xs text-gray-300 mt-1">Haga clic en 'Nuevo Registro' para comenzar.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $readings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>   </div>
</x-app-layout>


