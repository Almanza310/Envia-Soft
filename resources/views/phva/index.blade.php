<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div>
                <h2 class="font-black text-3xl text-black tracking-tight leading-none uppercase">
                    {{ __('Ciclo PHVA') }}
                </h2>
                <p class="text-[12px] text-black mt-2 font-bold uppercase tracking-widest leading-relaxed">Seleccione el año de gestión para visualizar los procesos.</p>
            </div>
            <div class="flex space-x-4">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'manage-years')" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-50 transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Gestionar Años
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Modal for Year Management -->
    <x-modal name="manage-years" focusable>
        <div class="p-8 text-black bg-white rounded-2xl">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h3 class="text-xl font-bold text-gray-900 uppercase">Gestionar Años PHVA</h3>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Form to add new year -->
            <form method="POST" action="{{ route('phva.years.store') }}" class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                @csrf
                <div class="flex gap-3">
                    <div class="flex-1">
                        <x-input-label for="new_year" :value="__('Nuevo Año Fiscal')" class="text-gray-700 font-semibold mb-1" />
                        <x-text-input id="new_year" class="block mt-1 w-full border-gray-200 rounded-xl" type="number" name="year" required placeholder="Ej. 2027" min="2000" max="2100" />
                    </div>
                    <div class="flex items-end">
                        <button type="submit" style="background-color: #E30613;" class="px-6 py-2.5 border border-transparent rounded-xl font-bold text-sm text-white hover:opacity-90 transition-all duration-200 shadow-md">
                            Agregar
                        </button>
                    </div>
                </div>
            </form>

            <!-- List of existing years -->
            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Años Registrados</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($allYears as $y)
                        <div class="flex justify-between items-center p-3 bg-white border border-gray-100 rounded-xl hover:border-gray-200 transition-all">
                            <span class="font-black text-gray-900 text-lg tracking-tighter">{{ $y->year }}</span>
                            <form id="delete-form-year-{{ $y->id }}" method="POST" action="{{ route('phva.years.destroy', $y) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteYear('{{ $y->id }}')" class="text-gray-300 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-modal>

    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Contextual Breadcrumb -->
            <div class="mb-10 flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center text-[10px] font-black text-black uppercase tracking-[0.3em]">
                    <svg class="w-4 h-4 mr-2 text-[#E30613]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard / Ciclo PHVA / Selección de Año
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($years as $year)
                    <a href="{{ route('phva.show', $year) }}" class="group bg-white border-2 border-black hover:border-[#E30613] hover:shadow-xl transition-all duration-300 flex flex-col w-full h-full min-h-[160px] focus:outline-none rounded-[2rem] overflow-hidden">
                        
                        <div class="p-6 flex flex-col h-full items-center justify-center relative">
                            <!-- Year Display (Static black text) -->
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-5xl font-black text-black tracking-tighter">
                                    {{ $year }}
                                </span>
                                
                                <div class="mt-6 flex flex-col items-center">
                                    <span class="text-[9px] font-black text-black uppercase tracking-[0.4em]">Gestión Anual</span>
                                    <div class="h-1.5 w-8 bg-black mt-3 rounded-full group-hover:bg-[#E30613] transition-colors duration-300"></div>
                                </div>
                            </div>
                        </div>
                        
                    </a>
                @endforeach
            </div>

            <!-- Additional Info -->
            <div class="mt-16 bg-gray-50 rounded-3xl p-10 border border-gray-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1">
                        <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-[#E30613] rounded-full mr-3 animate-pulse"></span>
                            Sobre el Ciclo PHVA
                        </h4>
                        <p class="text-sm text-gray-600 leading-relaxed font-medium">
                            El ciclo **Planear - Hacer - Verificar - Actuar** es la base de la mejora continua. Seleccione un año para acceder a la planeación estratégica, ejecución de procesos, auditorías internas y acciones de mejora correspondientes a dicho periodo.
                        </p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center min-w-[150px]">
                            <p class="text-2xl font-black text-gray-900">{{ count($years) }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-1">Periodos</p>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center min-w-[150px]">
                            <p class="text-2xl font-black text-[#E30613]">ACTIVO</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-1">Estado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDeleteYear(id) {
            Swal.fire({
                title: 'PIN de Seguridad Requerido',
                text: "Para eliminar este año, ingrese el PIN de seguridad.",
                icon: 'warning',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                inputPlaceholder: 'Ingrese el PIN',
                showCancelButton: true,
                confirmButtonColor: '#C12026',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Confirmar Eliminación',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                preConfirm: (pin) => {
                    if (pin === '2026') { // Pin de seguridad: 2026
                        return true;
                    } else {
                        Swal.showValidationMessage('PIN incorrecto. Intente de nuevo.');
                        return false;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-year-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
