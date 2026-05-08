<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;700;900&display=swap');
        
        :root {
            --brand-red: #E30613;
            --brand-red-dark: #C12026;
            --premium-black: #0F172A;
            --premium-gray: #64748B;
        }

        .premium-font { font-family: 'Outfit', 'Inter', sans-serif; }
        
        .bento-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            transform: translateY(-2px);
        }


        .upload-zone {
            border: 2px dashed #E2E8F0;
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            border-color: var(--brand-red);
            background: rgba(227, 6, 19, 0.02);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .animate-float { animation: float 3s ease-in-out infinite; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E1; }
        
        .cursor-grab { cursor: grab !important; }
        .cursor-grab:active { cursor: grabbing !important; }
        .sortable-ghost { opacity: 0.4; background: #f1f5f9 !important; }
    </style>

    <div class="bg-[#FDFDFD] min-h-screen premium-font pb-24" x-data="{ 
        activeCategory: (new URLSearchParams(window.location.search)).get('category') || null,
        activePhase: (new URLSearchParams(window.location.search)).get('phase') || 'planear',
        fileName: '',
        driveLink: '',
        uploadType: 'file',
        dofaFactor: 'interno',
        dofaTipo: '',
        categoryNames: {
            'matrices': 'Matrices',
            'dofa': 'DOFA',
            'partes_interesadas': 'Matriz Partes Interesadas',
            'riesgos_oportunidades': 'Matriz R y O'
        },
        contextSubCategories: {
            'dofa': 'DOFA',
            'partes_interesadas': 'Matriz Partes Interesadas',
            'riesgos_oportunidades': 'Matriz R y O'
        },
        filterProceso: '',
        filterResponsable: '',
        filterFecha: '',
        filterFactores: '',
        filterDescripcion: '',
        filterEvaluacion: '',
        isContextGroup() {
            return ['dofa', 'partes_interesadas', 'riesgos_oportunidades'].includes(this.activeCategory);
        }
    }">
        <!-- Header Dashboard Section -->
        <div class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-[1600px] mx-auto px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="{{ route('phva.show', $phvaYear->year) }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center group-hover:bg-black transition-all duration-500 shadow-sm">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] leading-none mb-1">Volver al</p>
                            <p class="text-[11px] font-black text-black uppercase tracking-widest">Dashboard</p>
                        </div>
                    </a>
                    
                    <div class="h-8 w-px bg-gray-100"></div>

                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                            <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Centro de Documentación</h2>
                        </div>
                        <h1 class="text-2xl font-black text-black tracking-tighter uppercase">
                            <span x-text="activePhase"></span> <span class="text-red-600">{{ $phvaYear->year }}</span>
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3 px-5 py-2.5 bg-gray-50 rounded-2xl border border-gray-100/50 shadow-sm">
                        <div class="relative">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <div class="absolute inset-0 w-2 h-2 bg-green-400 rounded-full animate-ping opacity-75"></div>
                        </div>
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Gestión Operativa Activa</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto px-8 pt-12">
            <!-- Breadcrumbs Premium -->
            <div class="mb-12 flex items-center gap-3 text-xs font-bold text-gray-400 uppercase tracking-widest">
                <a href="{{ route('phva.show', $phvaYear->year) }}" class="hover:text-black transition-colors">PHVA</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-black capitalize" x-text="activePhase + ' {{ $phvaYear->year }}'"></span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-red-600" x-text="categoryNames[activeCategory] || 'Selección'"></span>
            </div>

            <!-- Main Dashboard Content (Only for Planear) -->
            <div x-show="activePhase === 'planear'">
                <!-- Category Selector Buttons -->
                <div class="flex items-center gap-6 mb-12 w-full">
                <button @click="activeCategory = 'matrices'; fileName = ''" 
                        class="flex-1 flex items-center justify-center gap-4 px-8 py-5 rounded-[2rem] text-[13px] font-black uppercase tracking-[0.25em] transition-all duration-500 shadow-sm border border-transparent"
                        :class="activeCategory === 'matrices' ? 'bg-black text-white shadow-2xl shadow-black/20 translate-y-[-4px]' : 'bg-white text-gray-400 hover:text-black border-gray-100 hover:bg-gray-50'">
                    <div class="w-2 h-2 rounded-full shadow-sm" :class="activeCategory === 'matrices' ? 'bg-red-600' : 'bg-gray-300'"></div>
                    Matrices de Gestión
                </button>
                <button @click="activeCategory = isContextGroup() ? activeCategory : 'dofa'; fileName = ''" 
                        class="flex-1 flex items-center justify-center gap-4 px-8 py-5 rounded-[2rem] text-[13px] font-black uppercase tracking-[0.25em] transition-all duration-500 shadow-sm border border-transparent"
                        :class="isContextGroup() ? 'bg-black text-white shadow-2xl shadow-black/20 translate-y-[-4px]' : 'bg-white text-gray-400 hover:text-black border-gray-100 hover:bg-gray-50'">
                    <div class="w-2 h-2 rounded-full shadow-sm" :class="isContextGroup() ? 'bg-red-600' : 'bg-gray-300'"></div>
                    Contexto de la Organización
                </button>
            </div>

            <!-- Sub-category Selector (Contexto) con estilo refinado -->
            <div x-show="isContextGroup()" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap gap-4 mb-12">
                <template x-for="(name, key) in contextSubCategories" :key="key">
                    <button @click="activeCategory = key; fileName = ''"
                            class="px-6 py-2.5 rounded-xl font-bold text-[11px] uppercase tracking-widest transition-all duration-300 shadow-sm border"
                            :class="activeCategory === key ? 'bg-black text-white border-black scale-105' : 'bg-white text-gray-400 hover:text-black border-gray-100'">
                        <span x-text="name"></span>
                    </button>
                </template>
            </div>

            <!-- Content Area (Only visible when activeCategory is set) -->
            <div x-show="activeCategory" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-16">
                <div class="flex flex-col gap-16">
                    
                    <!-- Top: Upload Section (Bento Style) -->
                    <div class="w-full">
                        <div class="bento-card p-10 rounded-[2.5rem] relative overflow-hidden border border-gray-100 shadow-xl shadow-gray-200/20">
                            <!-- Subtle decorative elements -->
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-red-50 rounded-full opacity-50 blur-3xl"></div>
                            
                            <div class="relative z-10">
                                <!-- Upload Form (Hidden for Context Group) -->
                                <div x-show="!isContextGroup()">
                                    <div class="flex items-center gap-5 mb-10">
                                        <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center text-white shadow-2xl rotate-3">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-black leading-none uppercase tracking-tighter">Nueva Carga</h3>
                                            <p class="text-[11px] font-bold text-red-600 uppercase tracking-widest mt-1" x-text="categoryNames[activeCategory]"></p>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('phva.matrices.store', $phvaYear->year) }}" enctype="multipart/form-data" class="space-y-8" @submit="if(uploadType === 'file' && !fileName) { Swal.fire('Error', 'Selecciona un archivo primero', 'error'); $event.preventDefault(); } else if (uploadType === 'link' && !driveLink) { Swal.fire('Error', 'Ingresa un enlace de Drive primero', 'error'); $event.preventDefault(); }">
                                        @csrf
                                        <input type="hidden" name="phase" value="planear">
                                        <input type="hidden" name="category" :value="activeCategory">
                                        
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre del Documento</label>
                                            <input type="text" name="name" required placeholder="EJ: MATRIZ DE RIESGOS..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-5 px-6 font-bold text-sm focus:ring-4 focus:ring-red-50 focus:bg-white transition-all uppercase placeholder:text-gray-300 outline-none" />
                                        </div>

                                        <!-- Modern Upload Type Toggle -->
                                        <div class="flex bg-gray-100/50 p-1 rounded-2xl border border-gray-100">
                                            <button type="button" @click="uploadType = 'file'" class="flex-1 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all" :class="uploadType === 'file' ? 'bg-white shadow-md text-black' : 'text-gray-400 hover:text-gray-600'">Archivo Local</button>
                                            <button type="button" @click="uploadType = 'link'" class="flex-1 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all" :class="uploadType === 'link' ? 'bg-white shadow-md text-black' : 'text-gray-400 hover:text-gray-600'">Enlace Drive</button>
                                        </div>

                                        <!-- File Drop Zone -->
                                        <div x-show="uploadType === 'file'" x-transition class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Archivo Digital</label>
                                            <div class="relative group">
                                                <input type="file" name="matrix_file" :required="uploadType === 'file'" 
                                                       @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                                <div class="upload-zone bg-gray-50/50 rounded-3xl py-10 text-center border-gray-200"
                                                     :class="fileName ? 'border-green-500 bg-green-50/20' : ''">
                                                    <template x-if="!fileName">
                                                        <div class="flex flex-col items-center gap-3">
                                                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-300 group-hover:text-red-600 group-hover:scale-110 transition-all duration-500">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                            </div>
                                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Arrastra y suelta o <span class="text-black underline">selecciona un archivo</span></p>
                                                        </div>
                                                    </template>
                                                    <template x-if="fileName">
                                                        <div class="flex flex-col items-center gap-2 px-4">
                                                            <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg animate-bounce">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                            </div>
                                                            <p class="text-[11px] font-black text-green-700 truncate w-full" x-text="fileName"></p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="uploadType === 'link'" x-transition class="space-y-3">
                                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Enlace Institucional</label>
                                            <div class="relative">
                                                <input type="url" name="drive_link" x-model="driveLink" :required="uploadType === 'link'" placeholder="HTTPS://DRIVE.GOOGLE.COM/..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-5 px-6 font-bold text-sm focus:ring-4 focus:ring-blue-50 focus:bg-white transition-all outline-none" />
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full py-6 bg-black text-white rounded-3xl font-black text-xs uppercase tracking-[0.4em] hover:bg-red-600 hover:shadow-2xl hover:shadow-red-500/20 transition-all duration-500 active:scale-95 group flex items-center justify-center gap-4">
                                            Procesar Documento
                                            <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </button>
                                    </form>
                                </div>

                                <!-- Specialized Interface for Context Group -->
                                <div x-show="isContextGroup()" class="flex items-center justify-center py-4">
                                    <template x-if="activeCategory === 'dofa'">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('phva.dofa.prioritize', ['year' => $phvaYear->year]) }}" 
                                               class="px-8 py-4 bg-gray-50 text-gray-500 border border-gray-100 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-50 hover:text-red-600 hover:border-red-200 hover:shadow-lg transition-all duration-300 active:scale-95 group flex items-center gap-3">
                                                <div class="w-6 h-6 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-300">
                                                    <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                                                </div>
                                                Ver Priorización Global
                                            </a>
                                            <button @click="$dispatch('open-modal', 'add-dofa')" 
                                                    class="px-8 py-4 bg-black text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-red-600 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-500 active:scale-95 group flex items-center gap-3">
                                                <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                </div>
                                                Nuevo Registro DOFA
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="activeCategory !== 'dofa'">
                                        <div class="text-center py-4">
                                            <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest">Gestión administrativa habilitada próximamente</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom: Records Section (Table/List) -->
                    <div class="w-full mt-20">
                        <div class="flex flex-col md:flex-row items-end md:items-center justify-between gap-6 mb-4">
                            <div>
                            </div>
                            
                            <div cla                                <!-- CSS to force hide native select arrows -->
                                <style>
                                    select {
                                        -webkit-appearance: none !important;
                                        -moz-appearance: none !important;
                                        appearance: none !important;
                                    }
                                    select::-ms-expand {
                                        display: none !important;
                                    }
                                </style>

                                <!-- DOFA Horizontal Sharp Rectangular Toolbar -->
                                <template x-if="activeCategory === 'dofa'">
                                    <div class="animate-slide-up">
                                        <!-- Physical Spacer -->
                                        <div class="h-20"></div>

                                        <!-- Unified Horizontal Row (Perfect Centering) -->
                                        <div class="w-full overflow-x-auto no-scrollbar pb-6 px-6">
                                            <div class="flex items-center justify-center gap-5 min-w-max mx-auto">
                                                
                                                <!-- Filter: Fecha -->
                                                <div class="flex-none flex items-center bg-white border border-gray-200 rounded-md px-5 h-12 shadow-sm hover:border-red-300 transition-all cursor-pointer group">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-3">Fecha</span>
                                                    <input type="date" x-model="filterFecha" 
                                                        class="bg-transparent border-none outline-none text-[10px] font-bold text-black uppercase tracking-tight cursor-pointer w-[130px] p-0 focus:ring-0">
                                                </div>

                                                <!-- Filter: Área -->
                                                <div class="flex-none flex items-center bg-white border border-gray-200 rounded-md px-5 h-12 shadow-sm hover:border-red-300 transition-all group relative">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-3">Área</span>
                                                    <div class="relative flex items-center">
                                                        <select x-model="filterProceso" 
                                                            class="bg-transparent border-none outline-none text-[10px] font-bold text-black uppercase tracking-tight cursor-pointer min-w-[150px] pr-8 p-0 appearance-none focus:ring-0 relative z-10">
                                                            <option value="">TODAS</option>
                                                            @foreach($areas as $area)
                                                                <option value="{{ $area }}">{{ $area }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="w-2 h-2 text-gray-400 absolute right-1 top-1/2 -translate-y-1/2 pointer-events-none z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                    </div>
                                                </div>

                                                <!-- Filter: Resp -->
                                                <div class="flex-none flex items-center bg-white border border-gray-200 rounded-md px-5 h-12 shadow-sm hover:border-red-300 transition-all group relative">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-3">Resp</span>
                                                    <div class="relative flex items-center">
                                                        <select x-model="filterResponsable" 
                                                            class="bg-transparent border-none outline-none text-[10px] font-bold text-black uppercase tracking-tight cursor-pointer min-w-[130px] pr-8 p-0 appearance-none focus:ring-0 relative z-10">
                                                            <option value="">TODOS</option>
                                                            @foreach($dofas->pluck('responsable')->unique() as $responsable)
                                                                <option value="{{ $responsable }}">{{ $responsable }}</option>
                                                            @endforeach
                                                        </select>
                                                        <svg class="w-2 h-2 text-gray-400 absolute right-1 top-1/2 -translate-y-1/2 pointer-events-none z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                    </div>
                                                </div>

                                                <!-- Filter: Tipo -->
                                                <div class="flex-none flex items-center bg-white border border-gray-200 rounded-md px-5 h-12 shadow-sm hover:border-red-300 transition-all group relative">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-3">Tipo</span>
                                                    <div class="relative flex items-center">
                                                        <select x-model="filterFactores" 
                                                            class="bg-transparent border-none outline-none text-[10px] font-bold text-black uppercase tracking-tight cursor-pointer min-w-[120px] pr-8 p-0 appearance-none focus:ring-0 relative z-10">
                                                            <option value="">TODOS</option>
                                                            <option value="FORTALEZA">FORTALEZAS</option>
                                                            <option value="OPORTUNIDAD">OPORTUNIDADES</option>
                                                            <option value="DEBILIDAD">DEBILIDADES</option>
                                                            <option value="AMENAZA">AMENAZAS</option>
                                                        </select>
                                                        <svg class="w-2 h-2 text-gray-400 absolute right-1 top-1/2 -translate-y-1/2 pointer-events-none z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                    </div>
                                                </div>

                                                <!-- Clear -->
                                                <button x-show="filterFecha || filterProceso || filterResponsable || filterFactores"
                                                    @click="filterFecha = ''; filterProceso = ''; filterResponsable = ''; filterFactores = ''"
                                                    class="flex-none w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-md hover:bg-red-500 hover:text-white transition-all border border-red-100 shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template></template></template> </template>  </template>  </template>

                                <!-- Action Buttons (Removed Export buttons as requested) -->
                                <div class="flex items-center gap-2">
                                </div>
                            </div>
                        </div>

                        <!-- List Content -->
                        <div class="space-y-4">
                            @foreach($matrices->where('phase', 'planear') as $matrix)
                                <div x-show="activeCategory === '{{ $matrix->category ?: 'matrices' }}'" 
                                     class="group bg-white p-5 rounded-3xl border border-gray-100 hover:border-black/20 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-500 flex items-center gap-4 animate-slide-up">
                                    
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-black group-hover:text-white group-hover:rotate-6 transition-all duration-500 shadow-sm relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                                        @if($matrix->extension == 'pdf')
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        @elseif($matrix->extension == 'link')
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        @else
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6M10 21h4"></path></svg>
                                        @endif
                                    </div>

                                    <!-- Content (shrinks to fit) -->
                                    <div class="flex-1 min-w-0 flex items-center gap-6">
                                        <!-- Date -->
                                        <div class="flex-shrink-0 flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[11px] font-black text-black uppercase tracking-[0.15em] whitespace-nowrap">{{ $matrix->created_at->format('d M, Y') }}</span>
                                        </div>

                                        <div class="flex-shrink-0 h-5 w-px bg-gray-100"></div>

                                        <!-- Category -->
                                        <span class="flex-shrink-0 text-[11px] font-black text-black uppercase tracking-[0.15em]">
                                            {{ $matrix->category ?: 'Matrices' }}
                                        </span>

                                        <div class="flex-shrink-0 h-5 w-px bg-gray-100"></div>

                                        <!-- Name + Extension -->
                                        <div class="min-w-0 flex items-center gap-3">
                                            <h5 class="text-[11px] font-black text-black uppercase tracking-[0.15em] truncate group-hover:text-red-600 transition-colors">{{ $matrix->name }}</h5>
                                            <span class="flex-shrink-0 px-2 py-0.5 bg-gray-50 border border-gray-100 rounded-lg text-[9px] font-black uppercase text-gray-400 tracking-widest">
                                                {{ $matrix->extension }}
                                            </span>
                                        </div>

                                        <div class="flex-shrink-0 h-5 w-px bg-gray-100"></div>
                                        
                                        <!-- Status -->
                                        <div class="flex-shrink-0 flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none whitespace-nowrap">Acceso Restringido</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons (never shrink) -->
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        @if($matrix->extension == 'link')
                                            <a href="{{ $matrix->drive_link }}" target="_blank" class="w-10 h-10 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/20 hover:scale-110 transition-all duration-300" title="Abrir enlace">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <a href="{{ route('phva.matrices.download', $matrix) }}" class="w-10 h-10 bg-green-50 border border-green-200 rounded-xl flex items-center justify-center text-green-600 hover:bg-green-600 hover:text-white hover:border-green-600 hover:shadow-lg hover:shadow-green-500/20 hover:scale-110 transition-all duration-300" title="Descargar archivo">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                        <button type="button" onclick="confirmDelete('matrix-{{ $matrix->id }}')" class="w-10 h-10 bg-red-50 border border-red-200 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-lg hover:shadow-red-500/20 hover:scale-110 transition-all duration-300" title="Eliminar">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <form id="delete-form-matrix-{{ $matrix->id }}" action="{{ route('phva.matrices.destroy', $matrix) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                                                     <!-- DOFA Records List (Table Style) -->
                        <template x-if="activeCategory === 'dofa'">
                            <div class="bento-card rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden animate-slide-up">
                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left border-collapse min-w-[1000px]">
                                        <thead>
                                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em]">Fecha</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em]">Área / Proceso</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em]">Responsable</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em]">Factores</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em]">Descripción</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em] text-center">Evaluación</th>
                                                <th class="px-6 py-6 text-[11px] font-black text-black uppercase tracking-[0.2em] text-right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody x-init="
                                            $nextTick(() => {
                                                document.querySelectorAll('tr.dofa-row').forEach(row => {
                                                    row.querySelectorAll('.sortable-column').forEach(container => {
                                                        new Sortable(container, {
                                                            group: 'row-' + row.rowIndex,
                                                            animation: 150,
                                                            ghostClass: 'sortable-ghost',
                                                            onMove: (evt) => {
                                                                if (!evt.related) return true;
                                                                const s1 = Number(evt.dragged.getAttribute('data-score'));
                                                                const s2 = Number(evt.related.getAttribute('data-score'));
                                                                return s1 === s2;
                                                            },
                                                            onEnd: (evt) => {
                                                                const container = evt.from;
                                                                const rowEl = container.closest('tr');
                                                                const oldIndex = evt.oldIndex;
                                                                const newIndex = evt.newIndex;
                                                                const draggedId = evt.item.dataset.id;
                                                                
                                                                rowEl.querySelectorAll('.sortable-column').forEach(otherContainer => {
                                                                    if (otherContainer !== container) {
                                                                        const itemToMove = otherContainer.querySelector(`[data-id='${draggedId}']`);
                                                                        if (itemToMove) {
                                                                            const items = Array.from(otherContainer.children);
                                                                            if (newIndex > oldIndex) {
                                                                                // Mover hacia abajo: insertar después del elemento en newIndex
                                                                                otherContainer.insertBefore(itemToMove, items[newIndex].nextSibling);
                                                                            } else {
                                                                                // Mover hacia arriba: insertar antes del elemento en newIndex
                                                                                otherContainer.insertBefore(itemToMove, items[newIndex]);
                                                                            }
                                                                        }
                                                                    }
                                                                });

                                                                const fullOrder = Array.from(rowEl.querySelector('.sortable-column[data-group=desc]').children).map(el => el.dataset.id);
                                                                fetch('{{ route('phva.dofa.reorder') }}', {
                                                                    method: 'POST',
                                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                    body: JSON.stringify({ order: fullOrder })
                                                                });
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        " class="divide-y divide-gray-50">
                                            @php
                                                $groupedDofas = $dofas->groupBy(function($item) {
                                                    return $item->proceso . '|' . $item->responsable . '|' . $item->created_at->format('Y-m-d H:i');
                                                })->map(function($group) {
                                                    return $group->sortBy('sort_order')->sortBy(function($item) {
                                                        return ($item->probabilidad ?? 0) * ($item->impacto ?? 0);
                                                    })->values();
                                                })->sortByDesc(function($group) {
                                                    return $group->first()->created_at;
                                                });
                                            @endphp

                                            @foreach ($groupedDofas as $group)
                                                @php 
                                                    $first = $group->first(); 
                                                    $tiposStr = strtoupper($group->pluck('tipo')->implode(' '));
                                                    $descStr = strtoupper($group->pluck('descripcion')->implode(' '));
                                                    $evals = $group->map(function($item) {
                                                        $pts = ($item->probabilidad ?? 0) * ($item->impacto ?? 0);
                                                        if ($pts >= 12) return 'ALTO';
                                                        if ($pts >= 6) return 'MEDIO';
                                                        if ($pts >= 1) return 'BAJO';
                                                        return 'PENDIENTE';
                                                    })->implode(' ');
                                                    $puntosStr = $group->map(fn($i) => ($i->probabilidad ?? 0) * ($i->impacto ?? 0))->implode(' ');
                                                @endphp
                                                <tr x-show="
                                                    (!filterProceso || '{{ strtoupper($first->proceso) }}'.includes(filterProceso.toUpperCase())) && 
                                                    (!filterResponsable || '{{ strtoupper($first->responsable) }}'.includes(filterResponsable.toUpperCase())) &&
                                                    (!filterFecha || '{{ $first->created_at->format('Y-m-d') }}' === filterFecha) &&
                                                    (!filterFactores || '{{ $tiposStr }}'.includes(filterFactores.toUpperCase())) &&
                                                    (!filterDescripcion || '{{ $descStr }}'.includes(filterDescripcion.toUpperCase())) &&
                                                    (!filterEvaluacion || '{{ $evals }}'.includes(filterEvaluacion.toUpperCase()) || '{{ $puntosStr }}'.includes(filterEvaluacion))
                                                " 
                                                    class="hover:bg-gray-50/30 transition-colors group group/row dofa-row">
                                                    
                                                    <!-- Fecha -->
                                                    <td class="px-6 py-8 align-top">
                                                        <span class="text-[11px] font-black text-black uppercase tracking-widest">
                                                            {{ $first->created_at->format('d M, Y') }}
                                                        </span>
                                                    </td>

                                                    <!-- Área -->
                                                    <td class="px-6 py-8 align-top">
                                                        <span class="text-[11px] font-black text-black uppercase tracking-widest">{{ $first->proceso }}</span>
                                                    </td>

                                                    <!-- Responsable -->
                                                    <td class="px-6 py-8 align-top">
                                                        <span class="text-xs font-black text-black tracking-tightest leading-tight uppercase">{{ $first->responsable }}</span>
                                                    </td>

                                                    <!-- Factores -->
                                                    <td class="px-6 py-8 align-top">
                                                        <div class="space-y-4 sortable-column" data-group="factors">
                                                            @foreach($group as $item)
                                                                @php $score = ($item->probabilidad ?? 0) * ($item->impacto ?? 0); @endphp
                                                                <div class="flex items-center gap-3 h-8 cursor-grab active:cursor-grabbing" data-id="{{ $item->id }}" data-score="{{ $score }}">
                                                                    <div class="flex-shrink-0 w-1.5 h-1.5 rounded-full {{ in_array(strtolower($item->tipo), ['fortaleza', 'oportunidad']) ? 'bg-green-500' : 'bg-red-500' }} shadow-sm"></div>
                                                                    <span class="text-[11px] font-black uppercase tracking-widest {{ in_array(strtolower($item->tipo), ['fortaleza', 'oportunidad']) ? 'text-green-600' : 'text-red-600' }}">
                                                                        {{ $item->tipo }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                    <!-- Descripción -->
                                                    <td class="px-6 py-8 align-top">
                                                        <div class="space-y-4 sortable-column" data-group="desc">
                                                            @foreach($group as $item)
                                                                @php $score = ($item->probabilidad ?? 0) * ($item->impacto ?? 0); @endphp
                                                                <div class="flex items-center h-8 cursor-grab active:cursor-grabbing" data-id="{{ $item->id }}" data-score="{{ $score }}">
                                                                    <p class="text-[11px] font-black text-black uppercase tracking-widest truncate max-w-[300px]" title="{{ $item->descripcion }}">
                                                                        {{ $item->descripcion }}
                                                                    </p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                    <!-- Scores -->
                                                    <td class="px-6 py-8 align-top text-center">
                                                        <div class="flex flex-col gap-4 items-center sortable-column" data-group="scores">
                                                            @foreach($group as $item)
                                                                @php
                                                                    $puntos = ($item->probabilidad ?? 0) * ($item->impacto ?? 0);
                                                                    $nivel = 'N/A';
                                                                    $color = '#000000';
                                                                    
                                                                    if ($puntos > 0) {
                                                                        $isOportunidad = in_array(strtolower($item->tipo), ['oportunidad', 'fortaleza']);
                                                                        if (!$isOportunidad) {
                                                                            if ($puntos >= 16) { $nivel = 'EXTREMO'; $color = '#ef4444'; }
                                                                            elseif ($puntos >= 12) { $nivel = 'ALTO'; $color = '#f97316'; }
                                                                            elseif ($puntos >= 5) { $nivel = 'MODERADO'; $color = '#eab308'; }
                                                                            else { $nivel = 'BAJO'; $color = '#22c55e'; }
                                                                        } else {
                                                                            if ($puntos >= 16) { $nivel = 'MUY ALTO'; $color = '#22c55e'; }
                                                                            elseif ($puntos >= 12) { $nivel = 'ALTO'; $color = '#eab308'; }
                                                                            elseif ($puntos >= 5) { $nivel = 'MEDIO'; $color = '#f97316'; }
                                                                            else { $nivel = 'BAJO'; $color = '#ef4444'; }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <div class="h-8 flex items-center justify-center cursor-grab active:cursor-grabbing" data-id="{{ $item->id }}" data-score="{{ $puntos }}">
                                                                    @if($puntos > 0)
                                                                        <div class="px-3 py-1 rounded-lg text-[11px] font-black uppercase tracking-wider flex items-center gap-2 border shadow-sm"
                                                                             style="background: {{ $color }}10; color: {{ $color }}; border-color: {{ $color }}30;">
                                                                            <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $color }}"></span>
                                                                            {{ $nivel }} ({{ $puntos }})
                                                                        </div>
                                                                    @else
                                                                        <span class="text-[11px] font-black text-black/30 uppercase tracking-widest italic">Pendiente</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="px-6 py-8 align-top text-right">
                                                        <div class="flex flex-col gap-4 items-end sortable-column" data-group="actions">
                                                            @foreach($group as $item)
                                                                @php $score = ($item->probabilidad ?? 0) * ($item->impacto ?? 0); @endphp
                                                                <div class="flex items-center justify-end gap-1.5 h-8 cursor-grab active:cursor-grabbing" data-id="{{ $item->id }}" data-score="{{ $score }}">
                                                                    <a href="{{ route('phva.dofa.prioritize', ['year' => $phvaYear->year, 'proceso' => $item->proceso, 'responsable' => $item->responsable, 'date' => $item->created_at->format('Y-m-d H:i')]) }}" 
                                                                       class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-gray-200 hover:text-black rounded-xl transition-all shadow-sm" title="Priorizar">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                                                    </a>
                                                                    <a href="{{ route('phva.dofa.evaluate', $item->id) }}" 
                                                                       class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition-all shadow-sm" title="Evaluar">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                    </a>
                                                                    <button x-on:click="$dispatch('open-modal', 'edit-dofa-{{ $item->id }}')" 
                                                                            class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all shadow-sm" title="Editar">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                    </button>
                                                                    <button type="button" onclick="confirmDelete('dofa-{{ $item->id }}')" 
                                                                            class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all shadow-sm" title="Eliminar">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                    <form action="{{ route('phva.dofa.destroy', $item) }}" method="POST" class="hidden" id="delete-form-dofa-{{ $item->id }}">
                                                                        @csrf @method('DELETE')
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if($dofas->isEmpty())
                                        <div class="py-32 text-center bg-gray-50/30">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.3em]">No se han registrado factores estratégicos</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Landing State -->
            <div x-show="!activeCategory" class="py-32 text-center animate-slide-up" style="animation-delay: 0.2s">
                <div class="w-40 h-40 bg-white rounded-[2.5rem] flex items-center justify-center mx-auto mb-12 shadow-2xl shadow-gray-200/50 border border-gray-50 relative group">
                    <div class="absolute inset-0 bg-red-600 rounded-[2.5rem] scale-0 group-hover:scale-100 transition-transform duration-700 ease-out"></div>
                    <svg class="w-16 h-16 text-gray-200 group-hover:text-white group-hover:rotate-12 transition-all duration-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-black uppercase tracking-tighter leading-none mb-4">Esperando selección</h3>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.4em] max-w-sm mx-auto leading-relaxed">Elige una categoría superior para desbloquear las herramientas de gestión administrativa</p>
            </div>
        </div>

        <!-- Empty State for other Phases (Hacer, Verificar, Actuar) -->
        <div x-show="activePhase !== 'planear'" class="max-w-[1600px] mx-auto px-8 pt-32 text-center animate-slide-up">
            <div class="w-48 h-48 bg-white rounded-[3rem] flex items-center justify-center mx-auto mb-12 shadow-2xl shadow-gray-200/50 border border-gray-50 relative group">
                <div class="absolute inset-0 bg-gray-900 rounded-[3rem] scale-0 group-hover:scale-100 transition-transform duration-700 ease-out"></div>
                <svg class="w-20 h-20 text-gray-100 group-hover:text-white transition-all duration-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-4xl font-black text-black uppercase tracking-tighter leading-none mb-6">Módulo en Desarrollo</h3>
            <p class="text-[12px] font-black text-gray-400 uppercase tracking-[0.5em] max-w-xl mx-auto leading-relaxed opacity-60">
                La fase de <span class="text-black" x-text="activePhase"></span> se encuentra actualmente en proceso de configuración técnica y diseño administrativo.
            </p>
            <div class="mt-12 flex justify-center">
                <a href="{{ route('phva.show', $phvaYear->year) }}" class="px-10 py-4 bg-black text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] hover:bg-red-600 transition-all duration-500 shadow-xl shadow-black/10">
                    Volver al Ciclo PHVA
                </a>
            </div>
        </div>

            <!-- DOFA Modal -->
            <x-modal name="add-dofa" focusable>
                <div class="bg-white flex flex-col rounded-3xl overflow-hidden shadow-2xl" style="max-height: 90vh;">
                    <!-- Modal Header (Sticky) -->
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-20">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 tracking-tight leading-none">Registro de Factores</h3>
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-1">Gestión de Factores DOFA</p>
                            </div>
                        </div>
                        <button x-on:click="$dispatch('close')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="flex-1 overflow-y-auto bg-white" style="scrollbar-width: thin; scrollbar-color: #CBD5E0 #F7FAFC;">
                        <form id="add-form" action="{{ route('phva.dofa.store', $phvaYear->year) }}" method="POST" x-data="{ 
                            factores: [{ descripcion: '', factor: '', tipo: '' }] 
                        }">
                            @csrf
                            <div class="p-8 space-y-8">
                                <!-- Context Area -->
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Proceso / Área</label>
                                        <select name="proceso" required class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none cursor-pointer">
                                            <option value="" disabled selected>SELECCIONAR...</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area }}">{{ $area }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Responsable</label>
                                        <input type="text" name="responsable" required placeholder="Nombre del responsable" class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none" />
                                    </div>
                                </div>

                                <!-- Dynamic Findings List -->
                                <div class="space-y-6">
                                    <template x-for="(item, index) in factores" :key="index">
                                        <div class="p-8 bg-gray-50/50 border border-gray-100 rounded-3xl space-y-6 relative hover:border-blue-200 transition-colors">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-6 h-6 bg-gray-900 text-white text-[10px] font-black flex items-center justify-center rounded-lg" x-text="index + 1"></span>
                                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Definición del Factor</span>
                                                </div>
                                                <button type="button" @click="factores.splice(index, 1)" x-show="factores.length > 1" class="px-3 py-1 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg text-[9px] font-black uppercase transition-all">Eliminar</button>
                                            </div>

                                            <div class="grid grid-cols-2 gap-6">
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Factor Estratégico</label>
                                                    <select :name="'factores[' + index + '][factor]'" x-model="item.factor" @change="item.tipo = ''" class="w-full px-5 py-4 bg-white border-gray-200 rounded-2xl text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all cursor-pointer">
                                                        <option value="" disabled selected>SELECCIONAR...</option>
                                                        <option value="interno">INTERNO</option>
                                                        <option value="externo">EXTERNO</option>
                                                    </select>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Categoría DOFA</label>
                                                    <select :name="'factores[' + index + '][tipo]'" x-model="item.tipo" class="w-full px-5 py-4 bg-white border-gray-200 rounded-2xl text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all cursor-pointer">
                                                        <option value="" disabled selected>SELECCIONAR...</option>
                                                        <template x-if="item.factor === 'interno'">
                                                            <optgroup label="Factores Internos">
                                                                <option value="debilidad">DEBILIDAD</option>
                                                                <option value="fortaleza">FORTALEZA</option>
                                                            </optgroup>
                                                        </template>
                                                        <template x-if="item.factor === 'externo'">
                                                            <optgroup label="Factores Externos">
                                                                <option value="oportunidad">OPORTUNIDAD</option>
                                                                <option value="amenaza">AMENAZA</option>
                                                            </optgroup>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Detallada</label>
                                                <textarea :name="'factores[' + index + '][descripcion]'" x-model="item.descripcion" required rows="3" placeholder="Describa la situación encontrada..." class="w-full px-6 py-4 bg-white border-gray-200 rounded-2xl text-sm font-medium text-gray-600 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all resize-none outline-none shadow-sm"></textarea>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="flex justify-center pt-2">
                                        <button type="button" @click="factores.push({ descripcion: '', factor: '', tipo: '' })" class="group flex items-center gap-3 px-6 py-4 border-2 border-dashed border-gray-200 rounded-2xl text-xs font-black text-gray-400 uppercase hover:border-blue-400 hover:text-blue-600 transition-all">
                                            <div class="w-6 h-6 bg-gray-50 group-hover:bg-blue-50 rounded-lg flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            </div>
                                            Añadir otro factor
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer (Sticky) -->
                    <div class="p-6 border-t border-gray-100 bg-gray-50/50 sticky bottom-0 z-20 flex justify-center gap-4">
                        <button type="button" x-on:click="$dispatch('close')" class="px-10 py-4 bg-white text-gray-500 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" form="add-form" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all active:scale-95">
                            Guardar Todo
                        </button>
                    </div>
                </div>
            </x-modal>
        </div>
    </div>

            <!-- DOFA Modals (Moved out of table to fix positioning) -->
            @foreach ($dofas as $dofa)
                <!-- Edit Modal -->
                <x-modal name="edit-dofa-{{ $dofa->id }}" focusable>
                    <div class="bg-white flex flex-col rounded-3xl overflow-hidden shadow-2xl" style="max-height: 90vh;">
                        <!-- Modal Header (Sticky) -->
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-20">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 tracking-tight leading-none">Modificar Registro</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Gestión de Factores DOFA</p>
                                </div>
                            </div>
                            <button x-on:click="$dispatch('close')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-all">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <!-- Scrollable Content -->
                        <div class="flex-1 overflow-y-auto bg-white" style="scrollbar-width: thin; scrollbar-color: #CBD5E0 #F7FAFC;">
                            <form id="edit-form-{{ $dofa->id }}" action="{{ route('phva.dofa.update', $dofa) }}" method="POST" x-data="{ 
                                factor: '{{ $dofa->factor }}', 
                                tipo: '{{ $dofa->tipo }}',
                                factores: [] 
                            }">
                                @csrf @method('PUT')
                                <div class="p-8 space-y-8">
                                    <!-- Context Area -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Proceso</label>
                                            <select name="proceso" required class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none cursor-pointer">
                                                @foreach($areas as $area)
                                                    <option value="{{ $area }}" {{ $dofa->proceso == $area ? 'selected' : '' }}>{{ $area }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Responsable</label>
                                            <input type="text" name="responsable" required value="{{ $dofa->responsable }}" class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none" />
                                        </div>
                                    </div>

                                    <!-- Main Entry (Current) -->
                                    <div class="p-8 bg-gray-50/50 rounded-3xl border border-gray-100 space-y-6">
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 py-1 bg-blue-600 text-white text-[9px] font-black rounded-lg uppercase tracking-wider">Factor Principal</span>
                                            <div class="h-px flex-1 bg-gray-200"></div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Factor Estratégico</label>
                                                <select name="factor" x-model="factor" class="w-full px-5 py-4 bg-white border-gray-200 rounded-2xl text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all cursor-pointer">
                                                    <option value="interno">INTERNO</option>
                                                    <option value="externo">EXTERNO</option>
                                                </select>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Categoría DOFA</label>
                                                <select name="tipo" x-model="tipo" class="w-full px-5 py-4 bg-white border-gray-200 rounded-2xl text-sm font-bold text-gray-800 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all cursor-pointer">
                                                    <template x-if="factor === 'interno'">
                                                        <optgroup label="Factores Internos">
                                                            <option value="debilidad">DEBILIDAD</option>
                                                            <option value="fortaleza">FORTALEZA</option>
                                                        </optgroup>
                                                    </template>
                                                    <template x-if="factor === 'externo'">
                                                        <optgroup label="Factores Externos">
                                                            <option value="oportunidad">OPORTUNIDAD</option>
                                                            <option value="amenaza">AMENAZA</option>
                                                        </optgroup>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Detallada</label>
                                            <textarea name="descripcion" required rows="4" class="w-full px-6 py-5 bg-white border-gray-200 rounded-2xl text-sm font-medium text-gray-600 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all resize-none shadow-sm">{{ $dofa->descripcion }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Dynamic Findings List -->
                                    <div class="space-y-6">
                                        <template x-for="(item, index) in factores" :key="index">
                                            <div class="p-8 bg-white border-2 border-dashed border-gray-200 rounded-3xl space-y-6 relative hover:border-blue-200 transition-colors">
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-6 h-6 bg-gray-900 text-white text-[11px] font-black flex items-center justify-center rounded-lg" x-text="index + 1"></span>
                                                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Nuevo Factor Adicional</span>
                                                    </div>
                                                    <button type="button" @click="factores.splice(index, 1)" class="px-3 py-1 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg text-[11px] font-black uppercase transition-all">Eliminar</button>
                                                </div>

                                                <div class="grid grid-cols-2 gap-6">
                                                    <div class="space-y-2">
                                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Factor</label>
                                                        <select :name="'factores[' + index + '][factor]'" x-model="item.factor" class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800">
                                                            <option value="" disabled>SELECCIONAR...</option>
                                                            <option value="interno">INTERNO</option>
                                                            <option value="externo">EXTERNO</option>
                                                        </select>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Categoría</label>
                                                        <select :name="'factores[' + index + '][tipo]'" x-model="item.tipo" class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-bold text-gray-800">
                                                            <option value="" disabled>SELECCIONAR...</option>
                                                            <template x-for="opt in (item.factor === 'interno' ? ['debilidad', 'fortaleza'] : ['oportunidad', 'amenaza'])">
                                                                <option :value="opt" x-text="opt.toUpperCase()"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción</label>
                                                    <textarea :name="'factores[' + index + '][descripcion]'" x-model="item.descripcion" required rows="2" class="w-full px-6 py-4 bg-gray-50 border-transparent rounded-2xl text-sm font-medium text-gray-600 focus:bg-white transition-all resize-none outline-none"></textarea>
                                                </div>
                                            </div>
                                        </template>

                                        <div class="flex justify-center pt-2">
                                            <button type="button" @click="factores.push({ descripcion: '', factor: '', tipo: '' })" class="group flex items-center gap-3 px-6 py-4 border-2 border-dashed border-gray-200 rounded-2xl text-xs font-black text-gray-400 uppercase hover:border-blue-400 hover:text-blue-600 transition-all">
                                                <div class="w-6 h-6 bg-gray-50 group-hover:bg-blue-50 rounded-lg flex items-center justify-center transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                </div>
                                                Añadir otro factor
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Modal Footer (Sticky) -->
                        <div class="p-6 border-t border-gray-100 bg-gray-50/50 sticky bottom-0 z-20 flex justify-center gap-4">
                            <button type="button" x-on:click="$dispatch('close')" class="px-10 py-4 bg-white text-gray-500 border border-gray-200 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-all">
                                Cancelar
                            </button>
                            <button type="submit" form="edit-form-{{ $dofa->id }}" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all active:scale-95">
                                Actualizar Todo
                            </button>
                        </div>
                    </div>
                </x-modal>


            @endforeach

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#F3F4F6',
                confirmButtonText: 'SÍ, ELIMINAR',
                cancelButtonText: 'CANCELAR',
                customClass: {
                    confirmButton: 'rounded-xl font-bold text-xs uppercase tracking-widest px-8 py-4',
                    cancelButton: 'rounded-xl font-bold text-xs uppercase tracking-widest px-8 py-4 text-gray-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        });
    </script>
</x-app-layout>
