<x-app-layout>
    <style>
        @keyframes drawProgressive {
            from { stroke-dashoffset: 1100; }
            to { stroke-dashoffset: 0; }
        }
        
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .segment-animate {
            stroke-dasharray: 1100;
            stroke-dashoffset: 1100;
            animation: drawProgressive 2.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            transform-origin: 200px 200px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .group\/phase:hover .segment-animate {
            transform: scale(1.06);
            filter: drop-shadow(0 0 20px rgba(0,0,0,0.15));
            z-index: 50;
        }

        [x-cloak] { display: none !important; }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div class="flex items-center gap-4">
                <a href="{{ route('phva.index') }}" class="group p-2.5 bg-gray-50 rounded-2xl hover:bg-red-50 transition-all duration-300">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-[#E30613]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-black text-2xl text-gray-900 tracking-tight uppercase">
                    Contexto de la Organización <span class="text-gray-300 mx-2">/</span> <span class="text-[#E30613]">{{ $phvaYear->year }}</span>
                </h2>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <a href="{{ route('phva.matrices', $phvaYear->year) }}" class="flex items-center gap-2.5 px-6 py-2.5 bg-gray-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-[#E30613] hover:shadow-2xl transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Matrices
                </a>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center gap-3">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Estado</span>
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-white min-h-[80vh] flex flex-col items-center justify-center p-8 relative overflow-hidden" 
         x-data="{ 
            activePhase: null, 
            showModal: false, 
            hoverPhase: null,
            descriptions: {
                planear: 'Establecer objetivos y procesos necesarios.',
                hacer: 'Implementar los procesos planificados.',
                verificar: 'Medir y realizar el seguimiento.',
                actuar: 'Mejorar el desempeño continuamente.'
            }
         }">
        
        <!-- Main Dashboard Container -->
        <div class="w-full max-w-6xl mx-auto flex flex-col items-center justify-center animate-[fadeInScale_1s_ease-out]">
            
            <!-- Interaction Hint -->
            <div class="mb-10 flex items-center gap-6 opacity-80">
                <span class="w-16 h-px bg-black"></span>
                <p class="text-[13px] font-black text-black uppercase tracking-[0.2em] lg:tracking-[0.5em]">Contexto de la Organización</p>
                <span class="w-16 h-px bg-black"></span>
            </div>

            <!-- TOOLTIP -->
            <div x-show="hoverPhase" x-cloak
                 class="fixed z-[110] bg-gray-900 text-white px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-widest shadow-xl pointer-events-none transition-opacity duration-200"
                 :style="`top: ${$event.clientY - 35}px; left: ${$event.clientX + 15}px;`"
                 x-text="descriptions[hoverPhase]">
            </div>            <!-- RESPONSIVE PHVA CIRCLE -->
            <div class="relative w-full flex items-center justify-center">
                <div class="w-full max-w-[min(65%,500px)] aspect-square flex items-center justify-center" 
                     style="width: clamp(250px, 50vw, 450px);">
                    
                    <svg viewBox="0 0 400 400" class="w-full h-full drop-shadow-[0_35px_60px_rgba(0,0,0,0.08)]">
                        <defs>
                            <filter id="inner-shadow">
                                <feOffset dx="0" dy="2"/>
                                <feGaussianBlur stdDeviation="3" result="offset-blur"/>
                                <feComposite operator="out" in="SourceGraphic" in2="offset-blur" result="inverse"/>
                                <feFlood flood-color="black" flood-opacity="0.1" result="color"/>
                                <feComposite operator="in" in="color" in2="inverse" result="shadow"/>
                                <feComposite operator="over" in="shadow" in2="SourceGraphic"/>
                            </filter>
                            <filter id="subtle-shadow">
                                <feDropShadow dx="0" dy="4" stdDeviation="10" flood-color="#000" flood-opacity="0.06" />
                            </filter>
                            <filter id="glow-phase">
                                <feGaussianBlur stdDeviation="15" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over" />
                            </filter>

                            <!-- TEXT PATHS -->
                            <path id="path-planear" d="M 200,88 A 112,112 0 0,1 312,200" fill="none" />
                            <path id="path-hacer" d="M 312,200 A 112,112 0 0,1 200,312" fill="none" />
                            <path id="path-verificar" d="M 200,312 A 112,112 0 0,1 88,200" fill="none" />
                            <path id="path-actuar" d="M 88,200 A 112,112 0 0,1 200,88" fill="none" />
                        </defs>

                        <!-- Background Ring -->
                        <circle cx="200" cy="200" r="190" fill="none" stroke="#f1f1f1" stroke-width="1" stroke-dasharray="2 6" />
                        
                        <!-- QUADRANTS -->
                        <!-- PLANEAR -->
                        <g @mouseenter="hoverPhase = 'planear'" @mouseleave="hoverPhase = null" 
                           @click="window.location.href='{{ route('phva.matrices', $phvaYear->year) }}'" class="cursor-pointer group/phase">
                            <path d="M 200,200 L 200,25 A 175,175 0 0,1 375,200 Z" 
                                  fill="#F9A825" class="segment-animate group-hover/phase:brightness-105" 
                                  stroke="white" stroke-width="6" filter="url(#inner-shadow)" />
                            <text fill="black" class="font-black text-[16px] uppercase tracking-[0.15em] pointer-events-none select-none opacity-90">
                                <textPath href="#path-planear" startOffset="50%" text-anchor="middle">Planear</textPath>
                            </text>
                        </g>
                        
                        <!-- HACER -->
                        <g @mouseenter="hoverPhase = 'hacer'" @mouseleave="hoverPhase = null" 
                           @click="window.location.href='{{ route('phva.matrices', [$phvaYear->year, 'phase' => 'hacer']) }}'" class="cursor-pointer group/phase">
                            <path d="M 200,200 L 375,200 A 175,175 0 0,1 200,375 Z" 
                                  fill="#00B0FF" class="segment-animate group-hover/phase:brightness-105" 
                                  stroke="white" stroke-width="6" style="animation-delay: 0.2s;" filter="url(#inner-shadow)" />
                            <text fill="black" class="font-black text-[16px] uppercase tracking-[0.15em] pointer-events-none select-none opacity-90">
                                <textPath href="#path-hacer" startOffset="50%" text-anchor="middle">Hacer</textPath>
                            </text>
                        </g>
                        
                        <!-- VERIFICAR -->
                        <g @mouseenter="hoverPhase = 'verificar'" @mouseleave="hoverPhase = null" 
                           @click="window.location.href='{{ route('phva.matrices', [$phvaYear->year, 'phase' => 'verificar']) }}'" class="cursor-pointer group/phase">
                            <path d="M 200,200 L 200,375 A 175,175 0 0,1 25,200 Z" 
                                  fill="#E30613" class="segment-animate group-hover/phase:brightness-105" 
                                  stroke="white" stroke-width="6" style="animation-delay: 0.4s;" filter="url(#inner-shadow)" />
                            <text fill="black" class="font-black text-[16px] uppercase tracking-[0.15em] pointer-events-none select-none opacity-90">
                                <textPath href="#path-verificar" startOffset="50%" text-anchor="middle">Verificar</textPath>
                            </text>
                        </g>
                        
                        <!-- ACTUAR -->
                        <g @mouseenter="hoverPhase = 'actuar'" @mouseleave="hoverPhase = null" 
                           @click="window.location.href='{{ route('phva.matrices', [$phvaYear->year, 'phase' => 'actuar']) }}'" class="cursor-pointer group/phase">
                            <path d="M 200,200 L 25,200 A 175,175 0 0,1 200,25 Z" 
                                  fill="#76FF03" class="segment-animate group-hover/phase:brightness-105" 
                                  stroke="white" stroke-width="6" style="animation-delay: 0.6s;" filter="url(#inner-shadow)" />
                            <text fill="black" class="font-black text-[16px] uppercase tracking-[0.15em] pointer-events-none select-none opacity-90">
                                <textPath href="#path-actuar" startOffset="50%" text-anchor="middle">Actuar</textPath>
                            </text>
                        </g>

                        <!-- Inner Branding -->
                        <circle cx="200" cy="200" r="95" fill="white" />
                        <g class="pointer-events-none">
                            <circle cx="200" cy="200" r="82" fill="white" filter="url(#subtle-shadow)" />
                            <text x="200" y="160" text-anchor="middle" fill="#E0E0E0" class="font-black text-[11px] uppercase tracking-[0.8em]">Ciclo</text>
                            <text x="200" y="212" text-anchor="middle" fill="#000000" class="font-black text-[42px] tracking-[-2px]">PHVA</text>
                            <rect x="165" y="232" width="70" height="7" rx="3.5" fill="#E30613" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
