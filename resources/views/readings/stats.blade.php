<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight leading-none">
                    {{ __('Análisis de Consumo') }}
                </h2>
                <p class="text-[12px] text-gray-500 mt-2 font-medium uppercase tracking-widest leading-relaxed">Monitoreo histórico para la red de bodegas ENVIA.</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('readings.export', request()->all()) }}" class="inline-flex items-center px-8 py-3 bg-[#C12026] text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-[#a01a1f] transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Generar Reporte
                </a>
            </div>
        </div>
    </x-slot>

    <div class="pt-6 pb-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Contextual Navigation -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-4 gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('readings.index') }}" class="inline-flex items-center text-[10px] font-bold text-gray-400 hover:text-[#C12026] transition-all duration-500 uppercase tracking-[0.3em] group">
                        <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Lecturas
                    </a>
                    <span class="text-gray-300">|</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap italic">Dashboard / Estadísticas</span>
                </div>
                <form method="GET" action="" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 px-3 py-1 shadow-sm">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mr-3">Bodega:</label>
                        <select name="warehouse" onchange="this.form.submit()" class="text-xs border-transparent rounded-lg focus:border-none focus:ring-0 bg-transparent font-bold text-[#C12026] py-1 pr-8 pl-1 cursor-pointer">
                            <option value="all" {{ $selectedWarehouse === 'all' ? 'selected' : '' }}>Todas</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh }}" {{ $selectedWarehouse === $wh ? 'selected' : '' }}>{{ $wh }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 px-3 py-1 shadow-sm">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mr-3">Servicio:</label>
                        <select name="service" onchange="this.form.submit()" class="text-xs border-transparent rounded-lg focus:border-none focus:ring-0 bg-transparent font-bold text-blue-600 py-1 pr-8 pl-1 cursor-pointer">
                            <option value="all" {{ $selectedService === 'all' ? 'selected' : '' }}>Ambos</option>
                            <option value="Agua" {{ $selectedService === 'Agua' ? 'selected' : '' }}>Agua</option>
                            <option value="Luz" {{ $selectedService === 'Luz' ? 'selected' : '' }}>Energía</option>
                        </select>
                    </div>

                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 px-3 py-1 shadow-sm">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mr-3">Período:</label>
                        <select name="period" onchange="this.form.submit()" class="text-xs border-transparent rounded-lg focus:border-none focus:ring-0 bg-transparent font-bold text-gray-800 py-1 pr-8 pl-1 cursor-pointer">
                            <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Hoy</option>
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Esta Semana</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Este Mes</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Este Año</option>
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Todo</option>
                            @if($period === 'custom')
                                <option value="custom" selected>Personalizado</option>
                            @endif
                        </select>
                    </div>

                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 px-3 py-1 shadow-sm">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mr-3">Desde:</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="text-xs border-transparent rounded-lg focus:border-none focus:ring-0 bg-transparent font-bold text-gray-800 py-1 p-0 cursor-pointer" />
                    </div>

                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 px-3 py-1 shadow-sm">
                        <label class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mr-3">Hasta:</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="text-xs border-transparent rounded-lg focus:border-none focus:ring-0 bg-transparent font-bold text-gray-800 py-1 p-0 cursor-pointer" />
                    </div>

                    @if(request()->anyFilled(['warehouse', 'service', 'start_date', 'end_date']) || request('period') !== 'all')
                        <a href="{{ route('readings.stats') }}" class="p-2 text-gray-400 hover:text-red-600 bg-gray-50 hover:bg-red-50 rounded-xl transition-all duration-300" title="Limpiar Filtros">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            <!-- KPI Summary Grid -->
            <!-- KPI Summary Grid -->
            <div class="flex flex-wrap gap-3 sm:gap-4 mb-10">
                <!-- Total Water Card -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Consumo Agua</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none flex items-baseline">
                            {{ number_format($totalWater, 1) }} 
                            <span class="text-[10px] font-bold text-gray-400 ml-1.5 whitespace-nowrap uppercase tracking-wider">m³</span>
                        </p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-blue-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight leading-tight">Flujo constante</p>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.69l-.39.42C10.05 4.8 5 10.45 5 15c0 3.87 3.13 7 7 7s7-3.13 7-7c0-4.55-5.05-10.2-6.61-11.89L12 2.69z"/></svg>
                    </div>
                </div>

                <!-- Total Energy Card -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Consumo Energía</p>
                        <p class="text-3xl font-extrabold text-gray-900 tracking-tighter leading-none flex items-baseline">
                            {{ number_format($totalEnergy, 1) }} 
                            <span class="text-[10px] font-bold text-gray-400 ml-1.5 whitespace-nowrap uppercase tracking-wider">kWh</span>
                        </p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-amber-500" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight leading-tight">Suministro activo</p>
                        </div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                    </div>
                </div>

                <!-- Warehouse Spotlight Card -->
                <div class="flex-1 min-w-[280px] bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group hover:shadow-xl transition-all duration-300 border-l-4 border-l-[#C12026]">
                    <div class="flex-1 pr-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Punto Crítico</p>
                        <p class="text-xl font-extrabold text-gray-900 tracking-tighter truncate uppercase leading-none mb-1">{{ $topWarehouse->warehouse ?? 'N/A' }}</p>
                        <div class="flex items-center mt-4">
                            <svg class="w-2.5 h-2.5 mr-2 flex-shrink-0 text-[#C12026] animate-pulse" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3.5" fill="currentColor" /></svg>
                            <p class="text-[10px] text-[#C12026] font-bold uppercase tracking-tight italic leading-tight">Máximo Consumo</p>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl text-[#C12026] group-hover:bg-[#C12026] group-hover:text-white transition-all duration-300 shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <!-- Data Trends -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-8 px-2 flex items-center justify-between">
                        <h3 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-6 bg-blue-500 rounded-full mr-3"></span>
                            Tendencia Histórica
                        </h3>
                    </div>
                    <div class="h-[350px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Warehouse Benchmarking -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="mb-8 px-2 flex items-center justify-between">
                        <h3 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest flex items-center">
                            <span class="w-1.5 h-6 bg-amber-500 rounded-full mr-3"></span>
                            Comparativa Bodegas
                        </h3>
                    </div>
                    <div class="h-[350px]">
                        <canvas id="warehouseChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribution Insights -->
            <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-sm border border-gray-100 mb-12">
                <h3 class="text-[10px] font-bold text-gray-900 mb-10 text-center uppercase tracking-widest">Balance de Suministros</h3>
                <div class="h-80">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const warehouseCtx = document.getElementById('warehouseChart').getContext('2d');
            const typeCtx = document.getElementById('typeChart').getContext('2d');

            // Trend Chart
            const trendData = {!! json_encode($trendData) !!};
            const labels = [...new Set(trendData.map(d => d.period_label))];
            
            const datasets = [];
            
            if ('{{ $selectedService }}' === 'all' || '{{ $selectedService }}' === 'Agua') {
                datasets.push({
                    label: 'Agua (m³)',
                    data: labels.map(m => {
                        const entry = trendData.find(d => d.period_label === m && d.type === 'Agua');
                        return entry ? entry.total : 0;
                    }),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 4,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                });
            }

            if ('{{ $selectedService }}' === 'all' || '{{ $selectedService }}' === 'Luz') {
                datasets.push({
                    label: 'Luz (kWh)',
                    data: labels.map(m => {
                        const entry = trendData.find(d => d.period_label === m && d.type === 'Luz');
                        return entry ? entry.total : 0;
                    }),
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.05)',
                    borderWidth: 4,
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                });
            }

            new Chart(trendCtx, {
                type: 'line',
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 30, font: { weight: '600', size: 11, family: 'Inter' }, color: '#64748b' }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            usePointStyle: true,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { font: { size: 10, weight: '500' }, color: '#94a3b8', padding: 10 }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: '500' }, color: '#94a3b8', padding: 10 }
                        }
                    }
                }
            });

            // Warehouse Chart
            const whDataRaw = {!! json_encode($byWarehouse) !!};
            const whLabels = [...new Set(whDataRaw.map(w => w.warehouse))];

            const whDatasets = [];

            if ('{{ $selectedService }}' === 'all' || '{{ $selectedService }}' === 'Agua') {
                whDatasets.push({
                    label: 'Agua (m³)',
                    data: whLabels.map(w => {
                        const entry = whDataRaw.find(d => d.warehouse === w && d.type === 'Agua');
                        return entry ? entry.total : 0;
                    }),
                    backgroundColor: '#3B82F6',
                    hoverBackgroundColor: '#2563eb',
                    borderRadius: 6,
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                });
            }

            if ('{{ $selectedService }}' === 'all' || '{{ $selectedService }}' === 'Luz') {
                whDatasets.push({
                    label: 'Luz (kWh)',
                    data: whLabels.map(w => {
                        const entry = whDataRaw.find(d => d.warehouse === w && d.type === 'Luz');
                        return entry ? entry.total : 0;
                    }),
                    backgroundColor: '#F59E0B',
                    hoverBackgroundColor: '#d97706',
                    borderRadius: 6,
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                });
            }

            new Chart(warehouseCtx, {
                type: 'bar',
                data: {
                    labels: whLabels,
                    datasets: whDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 30, font: { weight: '600', size: 11, family: 'Inter' }, color: '#64748b' }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { font: { size: 10, weight: '500' }, color: '#94a3b8', padding: 10 }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: '500' }, color: '#94a3b8', padding: 10 }
                        }
                    }
                }
            });

            // Type Chart
            new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Agua', 'Luz'],
                    datasets: [{
                        data: [{{ $totalWater }}, {{ $totalEnergy }}],
                        backgroundColor: ['#3B82F6', '#F59E0B'],
                        hoverOffset: 15,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 30, font: { weight: '600', size: 11, family: 'Inter' }, color: '#64748b' }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

