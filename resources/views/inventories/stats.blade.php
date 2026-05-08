<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div>
                <h2 class="font-black text-3xl text-black tracking-tight leading-none">
                    {{ __('Análisis de Inventario') }}
                </h2>
                <p class="text-[12px] text-black mt-2 font-bold uppercase tracking-widest leading-relaxed">Control estratégico de suministros y flujo de stock.</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('inventories.export', request()->all()) }}" class="inline-flex items-center px-8 py-3 bg-[#C12026] text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-[#a01a1f] transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Exportar Reporte
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pt-6 pb-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Contextual Navigation -->
            <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4">
                <a href="{{ route('inventories.index') }}" class="inline-flex items-center text-[10px] font-black text-black hover:text-[#E30613] transition-all duration-500 uppercase tracking-[0.3em] group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Listado
                </a>
                <span class="text-[10px] font-black text-black  uppercase tracking-widest whitespace-nowrap uppercase">Dashboard / Estadísticas / Inventario</span>
            </div>

            <!-- KPI Cards -->
            <!-- KPI Summary Grid -->
            <div class="flex flex-wrap gap-3 sm:gap-4 mb-10">
                <!-- Total Consumed -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Consumido</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none">{{ number_format($totalConsumed) }}</p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-blue-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight leading-tight">Consumo verificado</p>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>

                <!-- Star Product -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Producto Estrella</p>
                        <p class="text-xl font-extrabold text-gray-900 tracking-tighter truncate uppercase leading-none mb-1">{{ $topProduct->consumption ?? 'N/A' }}</p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-amber-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-amber-600 font-bold uppercase tracking-tight italic leading-tight">Máxima Rotación</p>
                        </div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>

                <!-- High Consumption Area -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Área Crítica</p>
                        <p class="text-xl font-extrabold text-gray-900 tracking-tighter truncate uppercase leading-none mb-1">{{ $topArea->area ?? 'N/A' }}</p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-emerald-500 animate-pulse" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-tight leading-tight">Zona de alto flujo</p>
                        </div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Filters Form (Below Cards) -->
            <div class="mb-6">
                <form method="GET" action="{{ route('inventories.stats') }}" class="flex flex-wrap items-center gap-3 bg-white py-2 px-4 rounded-xl shadow-sm border border-gray-100 justify-end">
                    <div class="flex items-center border-r border-gray-200 pr-3">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <select name="period" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 border-none bg-transparent focus:ring-0 cursor-pointer p-0 pr-6">
                            <option value="all" {{ request('period', 'all') == 'all' ? 'selected' : '' }}>Todo el Tiempo</option>
                            <option value="day" {{ request('period') == 'day' ? 'selected' : '' }}>Hoy</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Esta Semana</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Este Mes</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Este Año</option>
                            @if(request('start_date') || request('end_date'))
                                <option value="custom" selected>Personalizado</option>
                            @endif
                        </select>
                    </div>

                    <div class="flex items-center border-r border-gray-200 pr-3">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mr-2">Desde:</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 border-none bg-transparent focus:ring-0 p-0 pr-2 cursor-pointer" />
                    </div>

                    <div class="flex items-center border-r border-gray-200 pr-3">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mr-2">Hasta:</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 border-none bg-transparent focus:ring-0 p-0 pr-2 cursor-pointer" />
                    </div>

                    <div class="flex items-center">
                        <select name="area" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 border-none bg-transparent focus:ring-0 cursor-pointer p-0 pr-6 border-r border-gray-200">
                            <option value="all" {{ request('area', 'all') == 'all' ? 'selected' : '' }}>Todas las Áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->name }}" {{ request('area') == $area->name ? 'selected' : '' }}>{{ ucfirst($area->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center pl-3">
                        <select name="consumption" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 border-none bg-transparent focus:ring-0 cursor-pointer p-0 pr-6">
                            <option value="all" {{ request('consumption', 'all') == 'all' ? 'selected' : '' }}>Todos los Productos</option>
                            <option value="cinta" {{ request('consumption') == 'cinta' ? 'selected' : '' }}>Cinta</option>
                            <option value="resmas" {{ request('consumption') == 'resmas' ? 'selected' : '' }}>Resmas</option>
                            <option value="vinipel" {{ request('consumption') == 'vinipel' ? 'selected' : '' }}>Vinipel</option>
                            <option value="toner" {{ request('consumption') == 'toner' ? 'selected' : '' }}>Toner</option>
                        </select>
                    </div>

                    @if(request()->anyFilled(['area', 'consumption', 'start_date', 'end_date']) || (request('period') && request('period') !== 'all'))
                        <a href="{{ route('inventories.stats') }}" class="ml-2 p-1 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-lg transition-colors" title="Limpiar Filtros">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <!-- Consumption Trends -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-8 px-2 flex items-center justify-between">
                        <h3 class="text-[10px] font-black text-black uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-6 bg-[#C12026] rounded-full mr-3"></span>
                            Consumo por Producto
                        </h3>
                    </div>
                    <div class="h-[350px]">
                        <canvas id="productChart"></canvas>
                    </div>
                </div>

                <!-- Area Analysis -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-8 px-2 flex items-center justify-between">
                        <h3 class="text-[10px] font-black text-black uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-6 bg-black rounded-full mr-3"></span>
                            Distribución de Áreas
                        </h3>
                    </div>
                    <div class="h-[350px]">
                        <canvas id="areaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Movement Summary -->
            <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-10 mb-12 overflow-hidden">
                <div class="flex items-center justify-between mb-10 px-2">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Resumen de Movimientos</h3>
                    <a href="#" class="inline-flex items-center px-6 py-2 bg-white border border-gray-200 text-xs font-bold text-gray-700 rounded-xl hover:bg-[#C12026] hover:text-white hover:border-[#C12026] transition-all duration-300 shadow-sm">
                        Ver Auditoría
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Identificador</th>
                                <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Suministro</th>
                                <th class="px-6 py-4 text-left text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Área Destino</th>
                                <th class="px-6 py-4 text-center text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Volumen</th>
                                <th class="px-6 py-4 text-right text-[11px] font-extrabold text-gray-900 uppercase tracking-widest whitespace-nowrap">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentMovements as $movement)
                            <tr class="group hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-black text-gray-900">#INV-{{ str_pad($movement->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-black uppercase tracking-tight">{{ $movement->consumption }}</td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></div>
                                        <span class="text-sm font-bold text-black uppercase">{{ $movement->area }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-black">{{ number_format($movement->quantity) }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold ml-1 uppercase">Unidades</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold tracking-widest uppercase border border-gray-200 text-gray-500 bg-gray-50">
                                        {{ $movement->quantity > 10 ? 'ESTÁNDAR' : 'REVISIÓN' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
            // Product Chart (Bar)
            const productCtx = document.getElementById('productChart').getContext('2d');
            new Chart(productCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($byProduct->pluck('consumption')->map(fn($v) => strtoupper($v))) !!},
                    datasets: [{
                        label: 'Cant. Consumida',
                        data: {!! json_encode($byProduct->pluck('total')) !!},
                        backgroundColor: 'rgba(193, 32, 38, 0.85)',
                        hoverBackgroundColor: '#a01a1f',
                        borderRadius: 8,
                        barThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#111827',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 14,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.parsed.y.toLocaleString() + ' unidades';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                            ticks: { font: { size: 11, weight: '600', family: 'Inter' }, color: '#9CA3AF' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '700', family: 'Inter' }, color: '#374151' }
                        }
                    }
                }
            });

            // Area Chart (Doughnut)
            const areaCtx = document.getElementById('areaChart').getContext('2d');
            new Chart(areaCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($byArea->pluck('area')->map(fn($v) => strtoupper($v))) !!},
                    datasets: [{
                        data: {!! json_encode($byArea->pluck('total')) !!},
                        backgroundColor: ['#C12026', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'],
                        hoverOffset: 16,
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: { weight: '600', size: 11, family: 'Inter' },
                                color: '#374151'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#111827',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 14,
                            cornerRadius: 12
                        }
                    }
                }
            });
    </script>
    @endpush
</x-app-layout>

