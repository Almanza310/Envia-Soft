<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Módulo de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KPIs -->
            <div class="flex flex-wrap gap-3 sm:gap-4 mb-8">
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total de Registros</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $totalItems }}</p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-[#C12026]" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight leading-tight">Stock actualizado</p>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl text-[#C12026] group-hover:bg-[#C12026] group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Modal for Registration Form -->
            <x-modal name="add-inventory" focusable>
                <div class="p-8 text-black bg-white rounded-2xl">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Nuevo Registro de Inventario</h3>
                        <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="area" :value="__('Área')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="area" name="area" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                        <option value="" disabled selected>Seleccione área</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->name }}" {{ old('area') == $area->name ? 'selected' : '' }}>{{ ucfirst($area->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="consumption" :value="__('Consumo')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="consumption" name="consumption" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                        <option value="" disabled selected>Seleccione tipo</option>
                                        <option value="cinta" {{ old('consumption') == 'cinta' ? 'selected' : '' }}>Cinta</option>
                                        <option value="resmas" {{ old('consumption') == 'resmas' ? 'selected' : '' }}>Resmas</option>
                                        <option value="vinipel" {{ old('consumption') == 'vinipel' ? 'selected' : '' }}>Vinipel</option>
                                        <option value="toner" {{ old('consumption') == 'toner' ? 'selected' : '' }}>Toner</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="date" :value="__('Fecha')" class="text-gray-700 font-semibold mb-1" />
                                    <x-text-input id="date" class="block mt-1 w-full border-gray-200 rounded-xl" type="date" name="date" :value="old('date', date('Y-m-d'))" required />
                                </div>
                                <div>
                                    <x-input-label for="quantity" :value="__('Cantidad')" class="text-gray-700 font-semibold mb-1" />
                                    <x-text-input id="quantity" class="block mt-1 w-full border-gray-200 rounded-xl" type="number" name="quantity" :value="old('quantity')" required min="1" placeholder="Ej. 5" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="name" :value="__('Nombre (Opcional)')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="name" class="block mt-1 w-full border-gray-200 rounded-xl" type="text" name="name" :value="old('name')" placeholder="Ej. Resmas de papel Bond" />
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-50">
                                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-200">
                                    Guardar Registro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- Modal for Area Management -->
            <x-modal name="manage-areas" focusable>
                <div class="p-8 text-black bg-white rounded-2xl">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Gestionar Áreas</h3>
                        <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Form to add new area -->
                    <form method="POST" action="{{ route('areas.store') }}" class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        @csrf
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <x-input-label for="area_name" :value="__('Nombre de la nueva área')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="area_name" class="block mt-1 w-full border-gray-200 rounded-xl" type="text" name="name" required placeholder="Ej. Archivo" />
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="px-6 py-2.5 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] transition-all duration-200">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- List of existing areas -->
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Áreas Existentes</h4>
                        @foreach($areas as $area)
                            <div x-data="{ editing: false, areaName: '{{ strtolower($area->name) }}' }" class="p-3 bg-white border border-gray-100 rounded-xl hover:border-gray-200 transition-all">
                                <!-- View Mode -->
                                <div x-show="!editing" class="flex justify-between items-center w-full">
                                    <span class="font-bold text-gray-700 uppercase text-xs">{{ $area->name }}</span>
                                    <div class="flex items-center space-x-3">
                                        <button @click="editing = true" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form id="delete-form-area-{{ $area->id }}" method="POST" action="{{ route('areas.destroy', $area) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('area-{{ $area->id }}')" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit Mode -->
                                <form x-show="editing" method="POST" action="{{ route('areas.update', $area) }}" class="flex items-center justify-between w-full gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" x-model="areaName" class="text-xs font-bold text-gray-700 uppercase border-gray-200 rounded-lg focus:ring-[#C12026] focus:border-[#C12026] py-1.5 px-2 w-full appearance-none outline-none" required />
                                    <div class="flex items-center space-x-2">
                                        <button type="submit" class="text-green-600 hover:text-green-700 transition-colors p-1" title="Guardar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1" title="Cancelar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-modal>

            <!-- Table Actions (Outside) -->
            <div class="flex justify-end mb-4 space-x-3">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'manage-areas')" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-50 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Gestionar Áreas
                </button>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-inventory')" class="inline-flex items-center px-6 py-3 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Registro
                </button>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 pb-6 border-b border-gray-50">
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Listado de Inventario</h3>
                        
                        <!-- Filtros -->
                        <div class="flex flex-wrap items-center gap-3">
                            <form method="GET" action="{{ route('inventories.index') }}" class="flex flex-wrap items-center gap-3">
                                <select name="area" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" onchange="this.form.submit()">
                                    <option value="">Todas las áreas</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->name }}" {{ request('area') == $area->name ? 'selected' : '' }}>{{ ucfirst($area->name) }}</option>
                                    @endforeach
                                </select>
                                <select name="consumption" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" onchange="this.form.submit()">
                                    <option value="">Todos los consumos</option>
                                    <option value="cinta" {{ request('consumption') == 'cinta' ? 'selected' : '' }}>Cinta</option>
                                    <option value="resmas" {{ request('consumption') == 'resmas' ? 'selected' : '' }}>Resmas</option>
                                    <option value="vinipel" {{ request('consumption') == 'vinipel' ? 'selected' : '' }}>Vinipel</option>
                                    <option value="toner" {{ request('consumption') == 'toner' ? 'selected' : '' }}>Toner</option>
                                </select>
                                <input type="date" name="start_date" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" value="{{ request('start_date') }}" onchange="this.form.submit()" placeholder="Desde" />
                                <input type="date" name="end_date" class="text-xs border-gray-200 rounded-xl focus:border-[#C12026] focus:ring-[#C12026] bg-gray-50 font-bold text-gray-700 py-2" value="{{ request('end_date') }}" onchange="this.form.submit()" placeholder="Hasta" />
                                @if(request()->anyFilled(['area', 'consumption', 'start_date', 'end_date']))
                                    <a href="{{ route('inventories.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-red-600 flex items-center transition-colors uppercase tracking-widest ml-2">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Limpiar
                                    </a>
                                @endif
                            </form>

                            <a href="{{ route('inventories.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 bg-[#C12026] text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-[#a01a1f] transition-all duration-300 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Fecha</th>
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Nombre</th>
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Área</th>
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Consumo</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Cantidad</th>
                                    <th class="px-6 py-4 text-right text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Registrado por</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($inventories as $item)
                                <tr class="hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold uppercase tracking-tight">{{ $item->name ?: '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-[10px] font-bold rounded-lg bg-gray-100 text-gray-700 uppercase tracking-wider">
                                            {{ ucfirst($item->area) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->consumption == 'resmas')
                                            <span class="text-red-700 font-bold bg-red-50 px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider">Resmas</span>
                                        @elseif($item->consumption == 'cinta')
                                            <span class="text-blue-700 font-bold bg-blue-50 px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider">Cinta</span>
                                        @elseif($item->consumption == 'toner')
                                            <span class="text-emerald-700 font-bold bg-emerald-50 px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider">Toner</span>
                                        @else
                                            <span class="text-gray-700 font-bold bg-gray-100 px-3 py-1 rounded-lg text-[10px] uppercase tracking-wider">Vinipel</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-lg text-center font-bold text-gray-900 tracking-tighter">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">{{ $item->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Ver Action -->
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-inventory-{{ $item->id }}')" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" title="Ver Detalles">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <!-- Editar Action -->
                                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-inventory-{{ $item->id }}')" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all duration-200" title="Editar Registro">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <!-- Eliminar Action -->
                                            <form id="delete-form-item-{{ $item->id }}" method="POST" action="{{ route('inventories.destroy', $item) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('item-{{ $item->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="Eliminar Registro">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- View Modal -->
                                        <x-modal name="view-inventory-{{ $item->id }}" focusable>
                                            <div class="p-8 text-left bg-white rounded-2xl">
                                                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                                                    <h3 class="text-xl font-bold text-gray-900">Detalle de Inventario</h3>
                                                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="space-y-6">
                                                    <div class="grid grid-cols-2 gap-6">
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Fecha</p>
                                                            <p class="text-base font-bold text-gray-900">{{ Carbon\Carbon::parse($item->date)->format('d F Y') }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre</p>
                                                            <p class="text-base font-bold text-gray-900 uppercase">{{ $item->name ?: '-' }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Área</p>
                                                            <p class="text-base font-bold text-gray-900 uppercase">{{ $item->area }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Consumo</p>
                                                            <p class="text-base font-bold text-gray-900 uppercase">{{ $item->consumption }}</p>
                                                        </div>
                                                        <div class="col-span-2 bg-red-50 p-6 rounded-2xl border border-red-100 text-center shadow-inner">
                                                            <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Cantidad Total</p>
                                                            <p class="text-4xl font-bold text-[#C12026] tracking-tight">{{ $item->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="pt-6 border-t border-gray-50 flex items-center">
                                                        <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#C12026] font-bold text-sm mr-4 border border-red-100">
                                                            {{ substr($item->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Registrado por</p>
                                                            <p class="text-sm font-bold text-gray-700 mt-1 uppercase tracking-tight">{{ $item->user->name }}</p>
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

                                        <!-- Edit Modal -->
                                        <x-modal name="edit-inventory-{{ $item->id }}" focusable>
                                            <div class="p-8 text-left bg-white rounded-2xl">
                                                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                                                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Editar Registro
                                                    </h3>
                                                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('inventories.update', $item) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="space-y-5">
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <x-input-label value="Área" class="text-gray-700 font-semibold mb-1" />
                                                                <select name="area" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                                                    @foreach($areas as $area)
                                                                        <option value="{{ $area->name }}" {{ old('area', $item->area) == $area->name ? 'selected' : '' }}>{{ ucfirst($area->name) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <x-input-label value="Consumo" class="text-gray-700 font-semibold mb-1" />
                                                                <select name="consumption" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium" required>
                                                                    @foreach(['cinta', 'resmas', 'vinipel', 'toner'] as $c)
                                                                        <option value="{{ $c }}" {{ old('consumption', $item->consumption) == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <x-input-label value="Fecha" class="text-gray-700 font-semibold mb-1" />
                                                                <x-text-input name="date" type="date" class="w-full border-gray-200 rounded-xl" :value="old('date', $item->date)" required />
                                                            </div>
                                                            <div>
                                                                <x-input-label value="Cantidad" class="text-gray-700 font-semibold mb-1" />
                                                                <x-text-input name="quantity" type="number" class="w-full border-gray-200 rounded-xl" :value="old('quantity', $item->quantity)" required min="1" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <x-input-label value="Nombre (Opcional)" class="text-gray-700 font-semibold mb-1" />
                                                            <x-text-input name="name" type="text" class="w-full border-gray-200 rounded-xl" :value="old('name', $item->name)" />
                                                        </div>
                                                        <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-50">
                                                            <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20 transition-all duration-200">
                                                                Actualizar Registro
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
                                                <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <p class="font-bold text-gray-400 uppercase tracking-widest text-sm">No hay registros de inventario</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

