<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight tracking-tight uppercase">
            {{ __('Gestión de Usuarios') }}
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

            <!-- KPIs Summary -->
            <!-- KPIs Summary -->
            <div class="flex flex-wrap gap-4 mb-10">
                <!-- Total Usuarios -->
                <div class="flex-1 min-w-[220px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Usuarios</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $totalUsers }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-red-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Sincronización total</p>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl text-[#C12026] group-hover:bg-[#C12026] group-hover:text-white group-hover:scale-125 transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                </div>

                <!-- Administradores -->
                <div class="flex-1 min-w-[220px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Administradores</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $admins }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-blue-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Privilegios de sistema</p>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-125 transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

                <!-- Supervisores -->
                <div class="flex-1 min-w-[220px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Supervisores</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $supervisors }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-yellow-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Gestión de equipos</p>
                        </div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600 group-hover:bg-yellow-400 group-hover:text-yellow-950 transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                    </div>
                </div>

                <!-- Operarios -->
                <div class="flex-1 min-w-[220px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Operarios</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $operators }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-gray-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Fuerza de trabajo</p>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-600 group-hover:bg-gray-200 group-hover:text-gray-900 transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

                <!-- Inactivos -->
                <div class="flex-1 min-w-[220px] bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Inactivos</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ $inactive }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-orange-500 animate-pulse" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-orange-600 font-bold uppercase tracking-tight">Acceso suspendido</p>
                        </div>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-xl text-orange-600 group-hover:bg-orange-300 group-hover:text-orange-900 transition-all duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 3.03a.75.75 0 01.456.696v12.548a.75.75 0 01-1.28.53L10 14.207 7.347 16.803a.75.75 0 01-1.28-.53V3.726a.75.75 0 01.456-.696L10 1.25l3.477 1.78zM10 4.118L7.565 5.37v7.508l2.435-2.384 2.435 2.384V5.37L10 4.118zM14.5 18a.5.5 0 01.5-.5h2.5a.5.5 0 010 1H15a.5.5 0 01-.5-.5z" clip-rule="evenodd"></path><path d="M3 18.5a.5.5 0 01.5-.5H6a.5.5 0 010 1H3.5a.5.5 0 01-.5-.5z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Table Actions -->
            <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Listado de Usuarios</h3>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-user')" class="inline-flex items-center px-6 py-3 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Nuevo Usuario
                </button>
            </div>

            <!-- Modal for User Registration -->
            <x-modal name="add-user" focusable>
                <div class="p-8 text-black bg-white rounded-2xl">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Registrar Usuario</h3>
                        <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('Nombre Completo')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="name" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="text" name="name" :value="old('name')" required placeholder="Ej. Juan Pérez" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="email" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="email" name="email" :value="old('email')" required placeholder="correo@ejemplo.com" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="role" :value="__('Rol del Sistema')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="role" name="role" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium px-4 py-3 bg-gray-50" required>
                                        <option value="" disabled selected>Seleccione un rol</option>
                                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operador</option>
                                    </select>
                                </div>
                                <div class="hidden md:block"></div> {{-- Spacer for layout alignment --}}
                                
                                <div>
                                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 font-semibold mb-1" />
                                    <x-text-input id="password" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-700 font-semibold mb-1" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-100">
                                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                    Cancelar
                                </button>
                                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-200">
                                    Registrar Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- User Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Usuario</th>
                                    <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Correo</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Rol</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Estado</th>
                                    <th class="px-6 py-4 text-right text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Fecha Registro</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#C12026] font-bold text-sm mr-4 border border-red-100">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($user->role == 'admin')
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-lg bg-red-50 text-[#C12026] uppercase tracking-wider border border-red-100">Administrador</span>
                                        @elseif($user->role == 'supervisor')
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-lg bg-yellow-50 text-yellow-700 uppercase tracking-wider border border-yellow-100">Supervisor</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-bold rounded-lg bg-blue-50 text-blue-700 uppercase tracking-wider border border-blue-100">Operario</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($user->is_active)
                                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg bg-green-50 text-green-700 uppercase tracking-wider border border-green-100 flex items-center justify-center mx-auto w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg bg-gray-50 text-gray-400 uppercase tracking-wider border border-gray-200 flex items-center justify-center mx-auto w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button 
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'edit-user'); $dispatch('fill-edit-form', { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}', is_active: {{ $user->is_active ? '1' : '0' }} })"
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" 
                                                title="Editar Usuario">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>

                                            <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('users.destroy', $user) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('{{ $user->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="Eliminar Usuario">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-10 py-24 text-center bg-white">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="font-bold text-gray-400 uppercase tracking-widest text-sm">No hay otros usuarios registrados</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
            <!-- Modal for User Editing -->
            <x-modal name="edit-user" focusable>
                <div 
                    x-data="{ 
                        id: '', 
                        name: '', 
                        email: '', 
                        role: '', 
                        is_active: 1,
                        action: ''
                    }" 
                    x-on:fill-edit-form.window="
                        id = $event.detail.id;
                        name = $event.detail.name;
                        email = $event.detail.email;
                        role = $event.detail.role;
                        is_active = $event.detail.is_active;
                        action = '{{ url('users') }}/' + id;
                    "
                    class="p-8 text-black bg-white rounded-2xl"
                >
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-xl font-bold text-gray-900">Editar Usuario</h3>
                        <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form id="edit-form-user-shared" method="POST" x-bind:action="action">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="edit_name" :value="__('Nombre Completo')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="edit_name" name="name" x-model="name" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="text" required />
                            </div>

                            <div>
                                <x-input-label for="edit_email" :value="__('Correo Electrónico')" class="text-gray-700 font-semibold mb-1" />
                                <x-text-input id="edit_email" name="email" x-model="email" class="block mt-1 w-full border-gray-200 rounded-xl px-4 py-3" type="email" required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="edit_role" :value="__('Rol del Sistema')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="edit_role" name="role" x-model="role" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium px-4 py-3 bg-gray-50" required>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="operator">Operador</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="edit_status" :value="__('Estado de Cuenta')" class="text-gray-700 font-semibold mb-1" />
                                    <select id="edit_status" name="is_active" x-model="is_active" class="border-gray-200 focus:border-[#C12026] focus:ring-[#C12026] rounded-xl shadow-sm block mt-1 w-full font-medium px-4 py-3 bg-gray-50" required>
                                        <option value="1">Habilitado</option>
                                        <option value="0">Inhabilitado</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-3 pt-6 border-t border-gray-100">
                                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-100 transition-all duration-200">
                                    Cancelar
                                </button>
                                <button type="button" onclick="confirmUpdate('user-shared')" class="inline-flex justify-center items-center px-6 py-2.5 bg-[#C12026] border border-transparent rounded-xl font-bold text-sm text-white hover:bg-[#a01a1f] shadow-lg shadow-red-900/20 transition-all duration-200">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
    </div>
</x-app-layout>
