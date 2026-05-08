<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('phva.matrices', ['year' => $dofa->phvaYear->year, 'category' => 'dofa']) }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-black transition-all group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Ir a Matrices</span>
            </a>
            <a href="{{ route('phva.dofa.prioritize', ['year' => $dofa->phvaYear->year, 'proceso' => $dofa->proceso, 'responsable' => $dofa->responsable, 'date' => $dofa->created_at->format('Y-m-d H:i')]) }}" 
               class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-red-600 transition-all group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Volver a Priorización</span>
            </a>
        </div>
    </x-slot>

    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
    </style>

    <div class="py-6" x-data="{ 
        probabilidad: {{ $dofa->probabilidad ?? 0 }}, 
        impacto: {{ $dofa->impacto ?? 0 }},
        color: '{{ $dofa->color ?? '' }}',
        tipo: '{{ $dofa->tipo }}',
        factor: '{{ $dofa->factor }}',
        factores: [],
        get matrixType() {
            return (this.tipo === 'debilidad' || this.tipo === 'amenaza') ? 'riesgo' : 'oportunidad';
        },
        getCellColor(p, i) {
            const colors = {
                g: '#22c55e', // Green
                y: '#facc15', // Yellow
                o: '#fb923c', // Orange
                r: '#ef4444'  // Red
            };

            if (this.matrixType === 'riesgo') {
                // Lógica de Riesgo (Existente con ajustes de fidelidad)
                if (i == 1) {
                    if (p == 4) return colors.y;
                    if (p == 5) return colors.o;
                }
                if (i == 2 && p == 4) return colors.o;
                if (i == 3) {
                    if (p == 1 || p == 2) return colors.y;
                    if (p == 3) return colors.o; // 9 es Naranja según screenshot
                    if (p == 4) return colors.o;
                    if (p == 5) return colors.r;
                }
                if (i == 4) {
                    if (p == 1 || p == 2) return colors.o;
                    if (p >= 3) return colors.r;
                }
                if (i == 5) {
                    if (p == 1) return colors.o;
                    return colors.r;
                }
                
                const val = p * i;
                if (val <= 4) return colors.g;
                if (val <= 9) return colors.y;
                if (val <= 14) return colors.o;
                return colors.r;
            } else {
                // Lógica de Oportunidad (Según Imagen y correcciones del usuario)
                if (p == 1) {
                    if (i <= 2) return colors.r;
                    if (i == 3) return colors.o; 
                    return colors.y;
                }
                if (p == 2) {
                    if (i <= 2) return colors.r;
                    if (i == 3) return colors.o; 
                    if (i == 4) return colors.y;
                    return colors.g;
                }
                if (p == 3) {
                    if (i == 1) return colors.r;
                    if (i == 2) return colors.o; 
                    if (i == 3) return colors.y; 
                    return colors.g;
                }
                if (p == 4) {
                    if (i == 1) return colors.o; 
                    if (i <= 3) return colors.y;
                    return colors.g;
                }
                if (p == 5) {
                    if (i <= 2) return colors.y; 
                    return colors.g;
                }
                return colors.g;
            }
        },
        selectCell(p, i) {
            this.probabilidad = p;
            this.impacto = i;
            this.color = this.getCellColor(p, i);
        }
    }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-black text-2xl text-gray-800 leading-tight mb-8">
                Evaluación de Factor
            </h2>
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
                <div class="p-6 sm:p-10">
                    <form action="{{ route('phva.dofa.evaluate.submit', $dofa->id) }}" method="POST">
                        @csrf
                        <div class="flex flex-col gap-10">
                            
                            <!-- Top Section: Formulario y Selectores -->
                            <div class="bg-gray-50 p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-8">
                                <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                                    <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Información del Registro y Evaluación</h4>
                                </div>

                                <div class="max-w-4xl mx-auto space-y-6">
                                    <!-- Contexto del Grupo (Otros factores registrados juntos) -->
                                    @if($group->count() > 1)
                                        <div class="mb-10 p-6 bg-white rounded-3xl border border-gray-100 shadow-sm">
                                            <h5 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Otros Factores en este Grupo</h5>
                                            <div class="space-y-3">
                                                @foreach($group as $item)
                                                    <div class="flex items-center justify-between p-3 {{ $item->id == $dofa->id ? 'bg-red-50 border-red-100' : 'bg-gray-50 border-gray-100' }} border rounded-2xl transition-all">
                                                        <div class="flex items-center gap-4">
                                                            <span class="px-2 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ in_array(strtolower($item->tipo), ['fortaleza', 'oportunidad']) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                                {{ $item->tipo }}
                                                            </span>
                                                            <p class="text-[10px] font-bold text-gray-700 truncate max-w-md">{{ $item->descripcion }}</p>
                                                        </div>
                                                        @if($item->id != $dofa->id)
                                                            <a href="{{ route('phva.dofa.evaluate', $item->id) }}" class="text-[9px] font-black text-red-600 hover:underline uppercase tracking-widest">Evaluar este</a>
                                                        @else
                                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Editando actual</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Fila 1: Proceso -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Proceso / Área</label>
                                        <select name="proceso" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm cursor-pointer">
                                            <option value="Comercial" {{ $dofa->proceso == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                                            <option value="Facturación" {{ $dofa->proceso == 'Facturación' ? 'selected' : '' }}>Facturación</option>
                                            <option value="Servicio al Cliente" {{ $dofa->proceso == 'Servicio al Cliente' ? 'selected' : '' }}>Servicio al Cliente</option>
                                            <option value="Gestión Humana" {{ $dofa->proceso == 'Gestión Humana' ? 'selected' : '' }}>Gestión Humana</option>
                                            <option value="Mantenimiento de Vehículos" {{ $dofa->proceso == 'Mantenimiento de Vehículos' ? 'selected' : '' }}>Mantenimiento de Vehículos</option>
                                            <option value="Compras" {{ $dofa->proceso == 'Compras' ? 'selected' : '' }}>Compras</option>
                                            <option value="Jurídica" {{ $dofa->proceso == 'Jurídica' ? 'selected' : '' }}>Jurídica</option>
                                            <option value="Seguridad" {{ $dofa->proceso == 'Seguridad' ? 'selected' : '' }}>Seguridad</option>
                                            <option value="Tecnología" {{ $dofa->proceso == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                                            <option value="Mercadeo" {{ $dofa->proceso == 'Mercadeo' ? 'selected' : '' }}>Mercadeo</option>
                                        </select>
                                    </div>

                                    <!-- Fila 2: Responsable -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Responsable del Registro</label>
                                        <input type="text" name="responsable" value="{{ $dofa->responsable }}" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm">
                                    </div>

                                    <!-- Fila 3: Factor y Tipo (Juntos) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Origen (Interno/Externo)</label>
                                            <select name="factor" x-model="factor" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm uppercase">
                                                <option value="interno">INTERNO</option>
                                                <option value="externo">EXTERNO</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tipo de Factor</label>
                                            <select name="tipo" x-model="tipo" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm uppercase italic">
                                                <optgroup label="Factores Internos" x-show="factor === 'interno'">
                                                    <option value="fortaleza">FORTALEZA</option>
                                                    <option value="debilidad">DEBILIDAD</option>
                                                </optgroup>
                                                <optgroup label="Factores Externos" x-show="factor === 'externo'">
                                                    <option value="oportunidad">OPORTUNIDAD</option>
                                                    <option value="amenaza">AMENAZA</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Fila 4: Descripción -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Descripción Detallada</label>
                                        <textarea name="descripcion" rows="3" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm leading-relaxed resize-none">{{ $dofa->descripcion }}</textarea>
                                    </div>

                                    <!-- Fila 5: Probabilidad -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Escala de Probabilidad</label>
                                        <select name="probabilidad" x-model="probabilidad" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm">
                                            <option value="0">SELECCIONAR...</option>
                                            <option value="1">1 - RARO</option>
                                            <option value="2">2 - IMPROBABLE</option>
                                            <option value="3">3 - POSIBLE</option>
                                            <option value="4">4 - PROBABLE</option>
                                            <option value="5">5 - CASI CERTEZA</option>
                                        </select>
                                    </div>

                                    <!-- Fila 6: Impacto -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Escala de Impacto</label>
                                        <select name="impacto" x-model="impacto" class="w-full bg-white border-gray-200 rounded-2xl py-3.5 px-4 text-sm font-black text-black focus:ring-4 focus:ring-red-50 focus:border-red-500 transition-all shadow-sm">
                                            <option value="0">SELECCIONAR...</option>
                                            <optgroup label="Nivel de Impacto (Riesgo)" x-show="matrixType === 'riesgo'">
                                                <option value="1">1 - INSIGNIFICANTE</option>
                                                <option value="2">2 - MENOR</option>
                                                <option value="3">3 - MODERADO</option>
                                                <option value="4">4 - MAYOR</option>
                                                <option value="5">5 - EXTREMO</option>
                                            </optgroup>
                                            <optgroup label="Nivel de Impacto (Oportunidad)" x-show="matrixType === 'oportunidad'">
                                                <option value="1">1 - INDIFERENTE</option>
                                                <option value="2">2 - POCO FAVORABLE</option>
                                                <option value="3">3 - MODERADA</option>
                                                <option value="4">4 - FAVORABLE</option>
                                                <option value="5">5 - MUY FAVORABLE</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <input type="hidden" name="color" x-model="color">
                                </div>
                            </div>
                            <!-- Section: Botones de Acción -->
                            <div class="max-w-4xl mx-auto w-full grid grid-cols-2 gap-6 py-4">
                                <a href="{{ route('phva.dofa.prioritize', ['year' => $dofa->phvaYear->year, 'proceso' => $dofa->proceso, 'responsable' => $dofa->responsable, 'date' => $dofa->created_at->format('Y-m-d H:i')]) }}" 
                                   class="w-full flex items-center justify-center gap-3 py-5 bg-white text-gray-500 rounded-3xl font-black text-sm hover:bg-gray-50 transition-all border-2 border-gray-100 uppercase tracking-[0.2em] shadow-sm active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        class="w-full py-5 bg-red-600 text-white rounded-3xl font-black text-sm hover:bg-red-700 shadow-xl shadow-red-100 transition-all active:scale-95 uppercase tracking-[0.2em]">
                                    Guardar Evaluación
                                </button>
                            </div>

                            <!-- Main View: Matriz Completa -->
                            <div class="xl:col-span-9">
                                <div class="bg-gray-50 p-1 rounded-[2rem] border border-gray-100 shadow-inner overflow-x-auto">
                                    <table class="w-full border-collapse bg-white rounded-[1.8rem] overflow-hidden">
                                        <thead>
                                            <!-- Fila Impacto Título -->
                                            <tr class="bg-slate-700 text-white">
                                                <th rowspan="2" class="p-6 border border-gray-200 bg-gray-100 w-64 text-center align-middle">
                                                    <span class="text-sm font-black text-gray-800 uppercase tracking-[0.2em]">PROBABILIDAD</span>
                                                </th>
                                                <th colspan="5" class="py-3 px-6 border border-gray-200 bg-gray-100 text-center tracking-[0.5em] font-black text-xs uppercase text-gray-800">
                                                    IMPACTO
                                                </th>
                                            </tr>
                                            <!-- Fila Impacto Niveles -->
                                            @php
                                                $impactsRiesgo = [
                                                    ['label' => 'INSIGNIFICANTE', 'desc' => 'Si el hecho llegara a presentarse, tendría consecuencias o efectos mínimos sobre la organización.'],
                                                    ['label' => 'MENOR', 'desc' => 'Si el hecho llegara a presentarse, tendría bajo impacto o efecto sobre la organización.'],
                                                    ['label' => 'MODERADO', 'desc' => 'Si el hecho llegara a presentarse, tendría medianas consecuencias o efectos sobre la organización.'],
                                                    ['label' => 'MAYOR', 'desc' => 'El evento probablemente ocurrirá en la mayoría de las circunstancias (Al menos de una vez en el último año)'],
                                                    ['label' => 'EXTREMO', 'desc' => 'Se espera que el evento ocurra en la mayoría de las circunstancias (Más de una vez al año)']
                                                ];
                                                $impactsOportunidad = [
                                                    ['label' => 'INDIFERENTE', 'desc' => 'Si el hecho llegara a presentarse, tendría consecuencias o efectos mínimos sobre la organización.'],
                                                    ['label' => 'POCO FAVORABLE', 'desc' => 'Si el hecho llegara a presentarse, tendría bajo impacto o efecto sobre la organización.'],
                                                    ['label' => 'MODERADA', 'desc' => 'Si el hecho llegara a presentarse, tendría medianas consecuencias o efectos sobre la organización.'],
                                                    ['label' => 'FAVORABLE', 'desc' => 'El evento probablemente ocurrirá en la mayoría de las circunstancias (Al menos de una vez en el último año)'],
                                                    ['label' => 'MUY FAVORABLE', 'desc' => 'Se espera que el evento ocurra en la mayoría de las circunstancias (Más de una vez al año)']
                                                ];
                                            @endphp
                                            <tr class="bg-slate-50">
                                                @foreach(range(1, 5) as $i)
                                                    <th class="p-4 border border-gray-200 w-48 align-top">
                                                        <p class="text-[11px] font-black text-gray-800 uppercase mb-2">
                                                            <span x-text="matrixType === 'riesgo' ? '{{ $impactsRiesgo[$i-1]['label'] }}' : '{{ $impactsOportunidad[$i-1]['label'] }}'"></span>
                                                            ({{ $i }})
                                                        </p>
                                                        <p class="text-[9px] font-medium text-gray-500 leading-tight normal-case" 
                                                           x-text="matrixType === 'riesgo' ? '{{ $impactsRiesgo[$i-1]['desc'] }}' : '{{ $impactsOportunidad[$i-1]['desc'] }}'">
                                                        </p>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $probData = [
                                                    1 => ['label' => 'RARO', 'desc' => 'El evento puede ocurrir solo en circunstancias excepcionales (Guía: No se ha presentado en los últimos 5 años)'],
                                                    2 => ['label' => 'IMPROBABLE', 'desc' => 'El evento puede ocurrir en algún momento (Guía: Al menos de una vez en los últimos 5 años.)'],
                                                    3 => ['label' => 'POSIBLE', 'desc' => 'El evento podría ocurrir en algún momento (Guía: Al menos de una vez en los últimos 2 años)'],
                                                    4 => ['label' => 'PROBABLE', 'desc' => 'El evento probablemente ocurrirá en la mayoría de las circunstancias (Guía: Al menos de una vez en el último año)'],
                                                    5 => ['label' => 'CASI CERTEZA', 'desc' => 'Se espera que el evento ocurra en la mayoría de las circunstancias (Más de una vez al año)']
                                                ];

                                                $g = '#22c55e'; $y = '#facc15'; $o = '#fb923c'; $r = '#ef4444';
                                                $isRiesgo = in_array($dofa->tipo, ['debilidad', 'amenaza']);
                                                $pRange = range(1, 5);
                                                
                                                $getColorByValue = function($p, $i) use ($g, $y, $o, $r) {
                                                    $val = $p * $i;
                                                    // Overrides por columna
                                                    if ($i == 1) {
                                                        if ($p == 4) return $y;
                                                        if ($p == 5) return $o;
                                                    }
                                                    if ($i == 2 && $p == 4) return $o;
                                                    if ($i == 3) {
                                                        if ($p == 1 || $p == 2) return $y;
                                                        if ($p == 3 || $p == 4) return $o;
                                                        if ($p == 5) return $r;
                                                    }
                                                    if ($i == 4) {
                                                        if ($p == 1 || $p == 2) return $o;
                                                        if ($p >= 3) return $r;
                                                    }
                                                    if ($i == 5) {
                                                        if ($p == 1) return $o;
                                                        return $r;
                                                    }
                                                    
                                                    if ($val <= 4) return $g;
                                                    if ($val <= 9) return $y;
                                                    if ($val <= 14) return $o;
                                                    return $r;
                                                };
                                            @endphp

                                            @foreach($pRange as $pIdx)
                                                <tr>
                                                    <td class="p-4 border border-gray-100 bg-slate-50 align-top">
                                                        <p class="text-[11px] font-black text-gray-800 uppercase mb-2">{{ $probData[$pIdx]['label'] }} ({{ $pIdx }})</p>
                                                        <p class="text-[10px] text-gray-500 leading-tight italic">{{ $probData[$pIdx]['desc'] }}</p>
                                                    </td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <td class="p-0 border border-white/20 relative group cursor-pointer h-24 w-1/6"
                                                            :class="probabilidad == {{ $pIdx }} && impacto == {{ $i }} ? 'ring-4 ring-inset ring-white z-10 scale-[1.02] shadow-2xl' : 'hover:scale-[1.01] hover:z-10'"
                                                            :style="'background-color: ' + getCellColor({{ $pIdx }}, {{ $i }})"
                                                            @click="selectCell({{ $pIdx }}, {{ $i }})">
                                                            <div class="flex flex-col items-center justify-center h-full space-y-1">
                                                                <span class="text-white font-black text-3xl drop-shadow-lg group-hover:scale-125 transition-transform relative z-10">{{ $pIdx * $i }}</span>
                                                                <div x-show="probabilidad == {{ $pIdx }} && impacto == {{ $i }}" 
                                                                     class="absolute inset-0 border-[6px] border-white flex items-start justify-end p-2 bg-black/5 pointer-events-none">
                                                                    <div class="bg-white p-2 rounded-full shadow-xl animate-bounce-subtle">
                                                                        <svg class="w-5 h-5 text-slate-800" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Espaciador -->
                                <div style="height: 80px;"></div>

                                <!-- Guía de Manejo y Evaluación (Dinámica según Matriz) -->
                                <div class="overflow-x-auto">
                                    <!-- Guía para RIESGO -->
                                    <table x-show="matrixType === 'riesgo'" class="w-full border-collapse border border-black text-sm">
                                        <thead>
                                            <tr class="bg-white">
                                                <th class="border border-black py-2 px-4 text-center font-bold uppercase tracking-tight text-black w-1/3">9. EVALUACIÓN RIESGO</th>
                                                <th class="border border-black py-2 px-4 text-center font-bold uppercase tracking-tight text-black w-2/3">12. OPCIONES DE MANEJO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-black align-middle" style="background-color: #22c55e;">BAJO</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle">* Asumir el riesgo</td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-black align-middle" style="background-color: #facc15;">MODERADO</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle leading-relaxed">* Asumir el riesgo<br>* Reducir el riesgo</td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-black align-middle" style="background-color: #fb923c;">ALTO</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle leading-relaxed">* Reducir el riesgo<br>* Evitar el riesgo<br>* Compartir o transferir</td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-white align-middle" style="background-color: #ef4444;">EXTREMO</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle leading-relaxed">* Evitar el riesgo<br>* Reducir el riesgo<br>* Compartir o transferir</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- Guía para OPORTUNIDAD -->
                                    <table x-show="matrixType === 'oportunidad'" class="w-full border-collapse border border-black text-sm">
                                        <thead>
                                            <tr class="bg-white">
                                                <th class="border border-black py-2 px-4 text-center font-bold uppercase tracking-tight text-black w-1/3">9. EVALUACIÓN OPORTUNIDAD</th>
                                                <th class="border border-black py-2 px-4 text-center font-bold uppercase tracking-tight text-black w-2/3">12. OPCIONES DE MANEJO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-white align-middle" style="background-color: #22c55e;">BUENA</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle">
                                                    * Oportunidad que genera valor representativo e impacto para la Compañía
                                                </td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-black align-middle" style="background-color: #facc15;">VIABLE</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle">
                                                    * Oportunidad que podría implementarse en la Compañía
                                                </td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-black align-middle" style="background-color: #fb923c;">INDIFERENTE</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle">
                                                    * Oportunidad que no es claro el impacto en la Compañía
                                                </td>
                                            </tr>
                                            <tr class="bg-white">
                                                <td class="border border-black py-4 px-4 text-center font-bold text-white align-middle" style="background-color: #ef4444;">NO VIABLE</td>
                                                <td class="border border-black py-4 px-6 text-left font-normal text-black align-middle">
                                                    * Oportunidad que no se implementaria en la Compañía
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
