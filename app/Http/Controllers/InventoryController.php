<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with('user')->orderBy('date', 'desc');

        if ($request->filled('area') && $request->area !== 'all') {
            $query->where('area', $request->area);
        }
        if ($request->filled('consumption') && $request->consumption !== 'all') {
            $query->where('consumption', $request->consumption);
        }
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $totalItems = (clone $query)->count();
        $inventories = $query->paginate(15);
        $areas = Area::orderBy('name')->get();

        return view('inventories.index', compact('inventories', 'totalItems', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'name' => 'nullable|string|max:255',
            'area' => 'required|string',
            'consumption' => 'required|string|in:cinta,resmas,vinipel,toner',
            'quantity' => 'required|integer|min:1',
        ]);

        Inventory::create([
            'date' => $validated['date'],
            'name' => $validated['name'],
            'area' => $validated['area'],
            'consumption' => $validated['consumption'],
            'quantity' => $validated['quantity'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('inventories.index')->with('success', 'Registro de inventario guardado exitosamente.');
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'name' => 'nullable|string|max:255',
            'area' => 'required|string',
            'consumption' => 'required|string|in:cinta,resmas,vinipel,toner',
            'quantity' => 'required|integer|min:1',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventories.index')->with('success', 'Registro de inventario actualizado correctamente.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Registro eliminado exitosamente.');
    }

    public function stats(Request $request)
    {
        $baseQuery = Inventory::query();

        if ($request->filled('area') && $request->area !== 'all') {
            $baseQuery->where('area', $request->area);
        }

        if ($request->filled('consumption') && $request->consumption !== 'all') {
            $baseQuery->where('consumption', $request->consumption);
        }

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate || $endDate) {
            if ($startDate) $baseQuery->where('date', '>=', $startDate);
            if ($endDate) $baseQuery->where('date', '<=', $endDate);
        } elseif ($request->filled('period') && $request->period !== 'all') {
            $period = $request->period;
            if ($period === 'day') {
                $baseQuery->whereDate('date', now()->toDateString());
            } elseif ($period === 'week') {
                $baseQuery->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($period === 'month') {
                $baseQuery->whereMonth('date', now()->month)
                          ->whereYear('date', now()->year);
            } elseif ($period === 'year') {
                $baseQuery->whereYear('date', now()->year);
            }
        }

        $totalConsumed = (clone $baseQuery)->sum('quantity');

        $byProduct = (clone $baseQuery)->select('consumption', DB::raw('SUM(quantity) as total'))
            ->groupBy('consumption')
            ->get();

        $byArea = (clone $baseQuery)->select('area', DB::raw('SUM(quantity) as total'))
            ->groupBy('area')
            ->get();

        $topProduct = (clone $baseQuery)->select('consumption', DB::raw('SUM(quantity) as total'))
            ->groupBy('consumption')
            ->orderByDesc('total')
            ->first();

        $topArea = (clone $baseQuery)->select('area', DB::raw('SUM(quantity) as total'))
            ->groupBy('area')
            ->orderByDesc('total')
            ->first();

        $recentMovements = (clone $baseQuery)->orderBy('date', 'desc')->take(5)->get();
        $areas = Area::orderBy('name')->get();

        return view('inventories.stats', compact('totalConsumed', 'byProduct', 'byArea', 'topProduct', 'topArea', 'recentMovements', 'areas'));
    }

    public function storeArea(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
        ]);

        Area::create([
            'name' => strtolower($validated['name']),
        ]);

        return redirect()->back()->with('success', 'Área creada exitosamente.');
    }

    public function updateArea(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
        ]);

        $oldName = $area->name;
        $newName = strtolower($validated['name']);

        $area->update([
            'name' => $newName,
        ]);

        // Propagate change to inventories
        Inventory::where('area', $oldName)->update(['area' => $newName]);

        return redirect()->back()->with('success', 'Área actualizada exitosamente y registros de inventario vinculados actualizados.');
    }

    public function destroyArea(Area $area)
    {
        $area->delete();
        return redirect()->back()->with('success', 'Área eliminada exitosamente.');
    }

    public function exportPdf(Request $request)
    {
        // Define filters to apply to fresh queries
        $area = $request->area;
        $consumption = $request->consumption;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $applyFilters = function($q) use ($area, $consumption, $startDate, $endDate) {
            if ($area && $area !== 'all') $q->where('area', $area);
            if ($consumption && $consumption !== 'all') $q->where('consumption', $consumption);
            if ($startDate) $q->where('date', '>=', $startDate);
            if ($endDate) $q->where('date', '<=', $endDate);
            return $q;
        };

        // 1. Get detailed list with order
        $inventories = $applyFilters(Inventory::with('user'))->orderBy('date', 'asc')->get();
        $totalConsumed = $inventories->sum('quantity');

        // 2. Aggregations for charts (WITHOUT ANY ORDER)
        $byProduct = $applyFilters(Inventory::query())
            ->select('consumption', DB::raw('SUM(quantity) as total'))
            ->groupBy('consumption')
            ->get();

        $byArea = $applyFilters(Inventory::query())
            ->select('area', DB::raw('SUM(quantity) as total'))
            ->groupBy('area')
            ->get();

        // Generate Charts using QuickChart
        $charts = [];
        try {
            $productConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $byProduct->pluck('consumption')->map(fn($v) => strtoupper($v))->toArray(),
                    'datasets' => [[
                        'label' => 'Cantidad',
                        'data' => $byProduct->pluck('total')->toArray(),
                        'backgroundColor' => '#C12026'
                    ]]
                ],
                'options' => [
                    'title' => ['display' => true, 'text' => 'CONSUMO POR PRODUCTO'],
                    'legend' => ['display' => false]
                ]
            ];

            $areaConfig = [
                'type' => 'doughnut',
                'data' => [
                    'labels' => $byArea->pluck('area')->map(fn($v) => strtoupper($v))->toArray(),
                    'datasets' => [[
                        'data' => $byArea->pluck('total')->toArray(),
                        'backgroundColor' => ['#C12026', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6']
                    ]]
                ],
                'options' => [
                    'title' => ['display' => true, 'text' => 'DISTRIBUCIÓN POR ÁREA']
                ]
            ];

            $charts['product'] = 'data:image/png;base64,' . base64_encode(file_get_contents('https://quickchart.io/chart?width=400&height=220&c=' . urlencode(json_encode($productConfig))));
            $charts['area'] = 'data:image/png;base64,' . base64_encode(file_get_contents('https://quickchart.io/chart?width=300&height=250&c=' . urlencode(json_encode($areaConfig))));
        } catch (\Exception $e) {
            $charts['product'] = null;
            $charts['area'] = null;
        }

        $pdf = \PDF::loadView('inventories.report_pdf', compact('inventories', 'totalConsumed', 'charts'));
        
        return $pdf->download('reporte-inventario-'.now()->format('Y-m-d').'.pdf');
    }
}
