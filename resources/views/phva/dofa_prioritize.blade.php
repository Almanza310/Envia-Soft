<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
            <div class="flex items-center gap-6">
                <a href="{{ route('phva.matrices', ['year' => $phvaYear->year, 'category' => 'dofa']) }}" 
                   class="w-12 h-12 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-black hover:text-white rounded-2xl transition-all duration-300 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-black uppercase tracking-tighter leading-none">Priorización DOFA</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-2">{{ $phvaYear->year }} • Gestión Estratégica</p>
                </div>
            </div>
            <img src="{{ asset('images/images.png') }}" alt="Logo ENVIA" class="h-10 w-auto opacity-90">
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 space-y-12">
        <!-- Info Bar -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm flex items-stretch divide-x divide-gray-100 overflow-hidden no-print">
            <div class="flex-1 p-8 flex items-center gap-6">
                <div class="w-1.5 h-10 bg-red-600 rounded-full"></div>
                <div>
                    <span class="text-[9px] font-black uppercase text-gray-400 block leading-none mb-1.5 tracking-widest">Fecha Registro</span>
                    <span class="text-sm font-black text-black uppercase tracking-tight">{{ $request->date ? \Carbon\Carbon::parse($request->date)->format('d/m/Y H:i') : 'Todas las fechas' }}</span>
                </div>
            </div>
            <div class="flex-1 p-8 flex items-center gap-6">
                <div class="w-1.5 h-10 bg-black rounded-full"></div>
                <div>
                    <span class="text-[9px] font-black uppercase text-gray-400 block leading-none mb-1.5 tracking-widest">Proceso Administrativo</span>
                    <span class="text-sm font-black text-black uppercase tracking-tight">{{ $request->proceso ?? 'Todos los procesos' }}</span>
                </div>
            </div>
            <div class="flex-1 p-8 flex items-center gap-6">
                <div class="w-1.5 h-10 bg-gray-300 rounded-full"></div>
                <div>
                    <span class="text-[9px] font-black uppercase text-gray-400 block leading-none mb-1.5 tracking-widest">Responsable Asignado</span>
                    <span class="text-sm font-black text-black uppercase tracking-tight">{{ $request->responsable ?? 'Todos los responsables' }}</span>
                </div>
            </div>
        </div>

        <!-- Matrix Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-16">
            @php
                $sections = [
                    'fortalezas' => ['title' => 'Fortalezas', 'label' => 'Internal Positive', 'default' => '#10B981', 'border' => 'border-emerald-100', 'bg' => 'bg-emerald-50/50', 'icon' => 'bg-emerald-500'],
                    'debilidades' => ['title' => 'Debilidades', 'label' => 'Internal Negative', 'default' => '#F97316', 'border' => 'border-orange-100', 'bg' => 'bg-orange-50/50', 'icon' => 'bg-orange-500'],
                    'oportunidades' => ['title' => 'Oportunidades', 'label' => 'External Positive', 'default' => '#3B82F6', 'border' => 'border-blue-100', 'bg' => 'bg-blue-50/50', 'icon' => 'bg-blue-500'],
                    'amenazas' => ['title' => 'Amenazas', 'label' => 'External Negative', 'default' => '#EF4444', 'border' => 'border-red-100', 'bg' => 'bg-red-50/50', 'icon' => 'bg-red-500'],
                ];
            @endphp

            @foreach($sections as $key => $info)
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[450px]">
                    <div class="p-8 flex items-center justify-between border-b border-gray-50 {{ $info['bg'] }}">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 {{ $info['icon'] }} rounded-full text-white flex items-center justify-center shadow-lg shadow-current/20 flex-shrink-0">
                                @if($key == 'debilidades')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                                @elseif($key == 'oportunidades')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                @elseif($key == 'fortalezas')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @else
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-black uppercase tracking-tighter leading-tight">{{ $info['title'] }}</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $info['label'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 p-6">
                        <table class="w-full border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                    <th class="pb-2 px-4 text-left">Factor Estratégico</th>
                                    <th class="pb-2 px-2 text-center w-20">Orden</th>
                                    <th class="pb-2 px-4 text-center w-32">Puntaje</th>
                                    <th class="pb-2 px-4 text-center w-28">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sort-{{ $key }}">
                                @forelse($matrix[$key] as $index => $d)
                                    @php 
                                        $score = ($d->probabilidad ?? 0) * ($d->impacto ?? 0);
                                        $hasScore = $score > 0;
                                        $bgColor = $hasScore ? ($d->color ?? $info['default']) : '#ffffff';
                                        $textColor = $hasScore ? '#ffffff' : '#e5e7eb';
                                    @endphp
                                    <tr class="group row-sortable hover:bg-gray-50 transition-colors bg-white shadow-sm border border-gray-100 rounded-2xl cursor-grab active:cursor-grabbing" data-id="{{ $d->id }}" data-score="{{ $score }}">
                                        <td class="p-5 rounded-l-2xl border-y border-l border-gray-100">
                                            <p class="text-[10px] font-black text-black uppercase leading-relaxed tracking-tight">{{ $d->descripcion }}</p>
                                            @if(!$request->filled('proceso'))
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-50 text-[7px] font-black text-gray-400 uppercase tracking-widest rounded-md">{{ $d->proceso }}</span>
                                            @endif
                                        </td>
                                        <td class="p-5 text-center font-black text-gray-300 text-xs border-y border-gray-100 select-none">{{ $index + 1 }}</td>
                                        <td class="p-5 text-center font-black text-2xl transition-all shadow-inner border-y border-gray-100" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                            {{ $hasScore ? $score : '0' }}
                                        </td>
                                        <td class="p-5 text-center border-y border-r border-gray-100 rounded-r-2xl">
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="{{ route('phva.dofa.evaluate', $d->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200" title="Editar / Evaluar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form id="delete-form-{{ $d->id }}" action="{{ route('phva.dofa.destroy', $d->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="source" value="prioritize">
                                                    <button type="button" onclick="confirmDelete('{{ $d->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-24 text-center text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] italic">No hay factores registrados</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categories = ['debilidades', 'oportunidades', 'fortalezas', 'amenazas'];
            categories.forEach(cat => {
                const el = document.getElementById('sort-' + cat);
                if (!el) return;

                new Sortable(el, {
                    animation: 150,
                    ghostClass: 'bg-gray-100',
                    draggable: '.row-sortable',
                    onMove: function (evt) {
                        const s1 = evt.dragged.getAttribute('data-score');
                        const s2 = evt.related.getAttribute('data-score');
                        if (!s2) return false;
                        if (s1 !== s2) return false;
                    },
                    onEnd: function () {
                        const rows = el.querySelectorAll('.row-sortable[data-id]');
                        const order = Array.from(rows).map(r => r.dataset.id);
                        
                        rows.forEach((r, i) => {
                            if(r.cells[1]) r.cells[1].innerText = i + 1;
                        });

                        fetch('{{ route("phva.dofa.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: order })
                        }).catch(err => console.error('Error saving order:', err));
                    }
                });
            });
        });
    </script>
    @endpush

    <style>
        .row-sortable { cursor: grab; }
        .row-sortable:active { cursor: grabbing; }
        .select-none { user-select: none; }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
