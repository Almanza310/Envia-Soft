<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dofa;
use App\Models\PhvaYear;
use Barryvdh\DomPDF\Facade\Pdf;

class DofaController extends Controller
{
    public function exportPdf(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();
        $query = $phvaYear->dofas();

        if ($request->filled('proceso')) {
            $query->where('proceso', $request->proceso);
        }
        if ($request->filled('responsable')) {
            $query->where('responsable', $request->responsable);
        }

        $dofas = $query->get();

        // Lógica de agrupación y ordenamiento
        $sortFn = function($d) {
            $score = ($d->probabilidad ?? 0) * ($d->impacto ?? 0);
            return [-$score, $d->sort_order];
        };

        $groupedDofas = $dofas->groupBy(function($item) {
            return $item->proceso . '|' . $item->responsable . '|' . $item->created_at->format('Y-m-d H:i');
        })->map(function($group) use ($sortFn) {
            return $group->sortBy($sortFn)->values();
        })->sortBy(function($group) {
            $first = $group->first();
            return -($first->probabilidad ?? 0) * ($first->impacto ?? 0);
        });

        $pdf = Pdf::loadView('phva.dofa_pdf', compact('phvaYear', 'groupedDofas'));
        
        return $pdf->download('matriz-dofa-'.$year.'.pdf');
    }

    public function store(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();

        $validated = $request->validate([
            'proceso' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'factores' => 'required|array|min:1',
            'factores.*.factor' => 'required|in:interno,externo',
            'factores.*.tipo' => 'required|string|max:255',
            'factores.*.descripcion' => 'required|string',
        ]);

        foreach ($validated['factores'] as $factor_item) {
            Dofa::create([
                'phva_year_id' => $phvaYear->id,
                'proceso' => $validated['proceso'],
                'responsable' => $validated['responsable'],
                'factor' => $factor_item['factor'],
                'tipo' => $factor_item['tipo'],
                'descripcion' => $factor_item['descripcion'],
            ]);
        }

        return redirect()->route('phva.matrices', ['year' => $year, 'category' => 'dofa'])
                        ->with('success', count($validated['factores']) . ' registros DOFA creados correctamente.');
    }

    public function update(Request $request, Dofa $dofa)
    {
        $validated = $request->validate([
            'proceso' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'factor' => 'required|in:interno,externo',
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
            'factores' => 'nullable|array',
            'factores.*.factor' => 'required|in:interno,externo',
            'factores.*.tipo' => 'required|string|max:255',
            'factores.*.descripcion' => 'required|string',
        ]);

        // Find all records in the same group BEFORE updating the current one
        $group = Dofa::where('proceso', $dofa->proceso)
                    ->where('responsable', $dofa->responsable)
                    ->where('phva_year_id', $dofa->phva_year_id)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$dofa->created_at->format('Y-m-d H:i')])
                    ->get();

        // Update process and responsible for the WHOLE group
        foreach ($group as $sibling) {
            $sibling->update([
                'proceso' => $validated['proceso'],
                'responsable' => $validated['responsable'],
            ]);
        }

        // Update specific details for the current record
        $dofa->update([
            'factor' => $validated['factor'],
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'],
        ]);

        // Create new ones if added (inheriting the same group context)
        if (!empty($validated['factores'])) {
            foreach ($validated['factores'] as $factor_item) {
                Dofa::create([
                    'phva_year_id' => $dofa->phva_year_id,
                    'proceso' => $validated['proceso'],
                    'responsable' => $validated['responsable'],
                    'factor' => $factor_item['factor'],
                    'tipo' => $factor_item['tipo'],
                    'descripcion' => $factor_item['descripcion'],
                    'created_at' => $dofa->created_at, // Inherit timestamp to keep them grouped
                ]);
            }
        }

        return redirect()->route('phva.matrices', ['year' => $dofa->phvaYear->year, 'category' => 'dofa'])
                        ->with('success', 'Registro actualizado' . (isset($validated['factores']) ? ' y ' . count($validated['factores']) . ' nuevos factores creados' : ''));
    }

    public function destroy(Request $request, Dofa $dofa)
    {
        $year = $dofa->phvaYear->year;
        $proceso = $dofa->proceso;
        $responsable = $dofa->responsable;
        $date = $dofa->created_at->format('Y-m-d H:i');
        
        $dofa->delete();

        if ($request->input('source') === 'prioritize') {
            return redirect()->route('phva.dofa.prioritize', [
                'year' => $year,
                'proceso' => $proceso,
                'responsable' => $responsable,
                'date' => $date
            ])->with('success', 'Registro DOFA eliminado correctamente.');
        }

        return redirect()->route('phva.matrices', ['year' => $year, 'category' => 'dofa'])
                        ->with('success', 'Registro DOFA eliminado correctamente.');
    }

    public function evaluate(Request $request, Dofa $dofa)
    {
        $validated = $request->validate([
            'probabilidad' => 'required|integer|min:1|max:5',
            'impacto' => 'required|integer|min:1|max:5',
            'color' => 'required|string',
            'proceso' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'factor' => 'required|in:interno,externo',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'factores' => 'nullable|array',
            'factores.*.factor' => 'required|in:interno,externo',
            'factores.*.tipo' => 'required|string|max:255',
            'factores.*.descripcion' => 'required|string',
        ]);

        // Update current record with evaluation
        $dofa->update($validated);

        return redirect()->route('phva.dofa.prioritize', [
            'year' => $dofa->phvaYear->year,
            'proceso' => $dofa->proceso,
            'responsable' => $dofa->responsable,
            'date' => $dofa->created_at->format('Y-m-d H:i')
        ])->with('success', 'Evaluación registrada correctamente.');
    }
    public function showEvaluate(Dofa $dofa)
    {
        $group = Dofa::where('proceso', $dofa->proceso)
                    ->where('responsable', $dofa->responsable)
                    ->where('phva_year_id', $dofa->phva_year_id)
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$dofa->created_at->format('Y-m-d H:i')])
                    ->get();
                    
        return view('phva.dofa_evaluate', compact('dofa', 'group'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:dofas,id',
        ]);

        foreach ($request->order as $index => $id) {
            Dofa::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function prioritize(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();
        $query = $phvaYear->dofas();

        if ($request->filled('proceso')) {
            $query->where('proceso', $request->proceso);
        }
        if ($request->filled('responsable')) {
            $query->where('responsable', $request->responsable);
        }
        if ($request->filled('date')) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$request->date]);
        }

        $dofas = $query->get();

        $sortFn = function($d) {
            $score = ($d->probabilidad ?? 0) * ($d->impacto ?? 0);
            // We want score DESC (multiplied by -1 for sortBy) and sort_order ASC
            return [-$score, $d->sort_order];
        };

        $matrix = [
            'fortalezas' => $dofas->filter(fn($d) => strtolower($d->tipo) === 'fortaleza')->sortBy($sortFn)->values(),
            'debilidades' => $dofas->filter(fn($d) => strtolower($d->tipo) === 'debilidad')->sortBy($sortFn)->values(),
            'oportunidades' => $dofas->filter(fn($d) => strtolower($d->tipo) === 'oportunidad')->sortBy($sortFn)->values(),
            'amenazas' => $dofas->filter(fn($d) => strtolower($d->tipo) === 'amenaza')->sortBy($sortFn)->values(),
        ];

        return view('phva.dofa_prioritize', compact('phvaYear', 'matrix', 'request'));
    }
}
