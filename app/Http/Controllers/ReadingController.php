<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Reading::with('user')->orderBy('date', 'desc');

        if ($request->filled('warehouse')) {
            $query->where('warehouse', $request->warehouse);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $readings = $query->paginate(15);
        $totalConsumption = \App\Models\Reading::sum('consumption');

        return view('readings.index', compact('readings', 'totalConsumption'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'warehouse' => 'required|in:Bodega 1,Bodega 2',
            'type' => 'required|in:Agua,Luz',
            'value' => 'required|numeric|min:0',
        ]);

        // Evitar duplicados
        $exists = \App\Models\Reading::where('date', $validated['date'])
            ->where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['error' => 'Ya existe un registro para esta fecha, bodega y tipo.'])->withInput();
        }

        // Buscar el último registro para calcular el consumo
        $previousReading = \App\Models\Reading::where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->where('date', '<', $validated['date'])
            ->orderBy('date', 'desc')
            ->first();

        if ($previousReading && $validated['value'] < $previousReading->value) {
            return back()->withErrors(['value' => 'El valor no puede ser menor a la lectura anterior ('.$previousReading->value.').'])->withInput();
        }

        // Buscar el registro posterior para evitar valores mayores
        $nextReading = \App\Models\Reading::where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->where('date', '>', $validated['date'])
            ->orderBy('date', 'asc')
            ->first();

        if ($nextReading && $validated['value'] > $nextReading->value) {
            return back()->withErrors(['value' => 'El valor no puede ser mayor a la lectura posterior ('.$nextReading->value.').'])->withInput();
        }

        $consumption = 0;
        if ($previousReading) {
            $consumption = $validated['value'] - $previousReading->value;
        }

        \App\Models\Reading::create([
            'date' => $validated['date'],
            'warehouse' => $validated['warehouse'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'consumption' => $consumption,
            'user_id' => auth()->id(),
        ]);

        $this->recalculateConsumptions($validated['warehouse'], $validated['type']);

        return redirect()->route('readings.index')->with('success', 'Lectura registrada correctamente.');
    }

    public function update(Request $request, \App\Models\Reading $reading)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'warehouse' => 'required|in:Bodega 1,Bodega 2',
            'type' => 'required|in:Agua,Luz',
            'value' => 'required|numeric|min:0',
        ]);

        // Validate uniqueness excluding current reading
        $exists = \App\Models\Reading::where('date', $validated['date'])
            ->where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->where('id', '!=', $reading->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['error' => 'Ya existe un registro para esta fecha, bodega y tipo.'])->withInput();
        }

        // Fetch previous reading for calculation
        $previousReading = \App\Models\Reading::where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->where('date', '<', $validated['date'])
            ->where('id', '!=', $reading->id)
            ->orderBy('date', 'desc')
            ->first();

        if ($previousReading && $validated['value'] < $previousReading->value) {
            return back()->withErrors(['value' => 'El valor no puede ser menor a la lectura anterior ('.$previousReading->value.').'])->withInput();
        }

        // Fetch next reading for boundary validation
        $nextReading = \App\Models\Reading::where('warehouse', $validated['warehouse'])
            ->where('type', $validated['type'])
            ->where('date', '>', $validated['date'])
            ->where('id', '!=', $reading->id)
            ->orderBy('date', 'asc')
            ->first();

        if ($nextReading && $validated['value'] > $nextReading->value) {
            return back()->withErrors(['value' => 'El valor no puede ser mayor a la lectura posterior ('.$nextReading->value.').'])->withInput();
        }

        $consumption = 0;
        if ($previousReading) {
            $consumption = $validated['value'] - $previousReading->value;
        }

        $oldWarehouse = $reading->warehouse;
        $oldType = $reading->type;

        $reading->update([
            'date' => $validated['date'],
            'warehouse' => $validated['warehouse'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'consumption' => $consumption,
        ]);

        $this->recalculateConsumptions($oldWarehouse, $oldType);
        if ($oldWarehouse !== $validated['warehouse'] || $oldType !== $validated['type']) {
            $this->recalculateConsumptions($validated['warehouse'], $validated['type']);
        }

        return redirect()->route('readings.index')->with('success', 'Lectura actualizada correctamente.');
    }

    public function destroy(\App\Models\Reading $reading)
    {
        $warehouse = $reading->warehouse;
        $type = $reading->type;
        
        $reading->delete();
        
        $this->recalculateConsumptions($warehouse, $type);
        
        return redirect()->route('readings.index')->with('success', 'Lectura eliminada exitosamente.');
    }

    private function recalculateConsumptions($warehouse, $type)
    {
        $readings = \App\Models\Reading::where('warehouse', $warehouse)
            ->where('type', $type)
            ->orderBy('date', 'asc')
            ->get();

        $previousValue = null;
        foreach ($readings as $reading) {
            $consumption = 0;
            if ($previousValue !== null) {
                $consumption = $reading->value - $previousValue;
            }
            if ($reading->consumption != $consumption) {
                DB::table('readings')->where('id', $reading->id)->update(['consumption' => $consumption]);
            }
            $previousValue = $reading->value;
        }
    }

    public function stats(Request $request)
    {
        $period = $request->get('period', 'all');
        $selectedWarehouse = $request->get('warehouse', 'all');
        $selectedService = $request->get('service', 'all');

        // Extract distinctive warehouses to populate filter
        $warehouses = \App\Models\Reading::select('warehouse')->distinct()->orderBy('warehouse')->pluck('warehouse');

        $baseQuery = \App\Models\Reading::query();

        if ($selectedWarehouse !== 'all') {
            $baseQuery->where('warehouse', $selectedWarehouse);
        }

        if ($selectedService !== 'all') {
            $baseQuery->where('type', $selectedService);
        }
        
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate || $endDate) {
            $period = 'custom';
            if ($startDate) $baseQuery->where('date', '>=', $startDate);
            if ($endDate) $baseQuery->where('date', '<=', $endDate);
        } else {
            if ($period === 'day') {
                $baseQuery->whereDate('date', now()->toDateString());
            } elseif ($period === 'week') {
                $baseQuery->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($period === 'month') {
                $baseQuery->whereYear('date', now()->year)
                          ->whereMonth('date', now()->month);
            } elseif ($period === 'year') {
                $baseQuery->whereYear('date', now()->year);
            }
        }

        // Totales por tipo
        $byType = (clone $baseQuery)->select('type', DB::raw('SUM(consumption) as total'))
            ->groupBy('type')
            ->get();

        // Totales por Bodega (Para separarlo en barras Agua vs Luz)
        $byWarehouse = (clone $baseQuery)->select('warehouse', 'type', DB::raw('SUM(consumption) as total'))
            ->groupBy('warehouse', 'type')
            ->get();

        // Tendencia Histórica (agrupación dinámica en base al filtro global)
        $dateFormat = "'%Y-%m'"; // Default (todo, año) agrupa por mes
        
        if ($period === 'day') {
            $dateFormat = "'%Y-%m-%d %H:00'"; // Hoy agrupa por hora
        } elseif ($period === 'week' || $period === 'month') {
            $dateFormat = "'%Y-%m-%d'"; // Semana y mes agrupa por día
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
            if ($days <= 1) {
                $dateFormat = "'%Y-%m-%d %H:00'";
            } elseif ($days <= 45) {
                $dateFormat = "'%Y-%m-%d'";
            } else {
                $dateFormat = "'%Y-%m'";
            }
        }

        $trendData = (clone $baseQuery)->select(
                DB::raw("DATE_FORMAT(date, $dateFormat) as period_label"),
                'type',
                DB::raw('SUM(consumption) as total')
            )
            ->groupBy('period_label', 'type')
            ->orderBy('period_label', 'asc')
            ->get();

        // KPIs
        $totalWater = (clone $baseQuery)->where('type', 'Agua')->sum('consumption');
        $totalEnergy = (clone $baseQuery)->where('type', 'Luz')->sum('consumption');
        $topWarehouse = (clone $baseQuery)->select('warehouse', DB::raw('SUM(consumption) as total'))
            ->groupBy('warehouse')
            ->orderByDesc('total')
            ->first();

        return view('readings.stats', compact('byType', 'byWarehouse', 'trendData', 'period', 'totalWater', 'totalEnergy', 'topWarehouse', 'warehouses', 'selectedWarehouse', 'selectedService'));
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', 'all');
        $selectedWarehouse = $request->get('warehouse', 'all');
        $selectedService = $request->get('service') ?: $request->get('type', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $baseQuery = \App\Models\Reading::query();

        if ($selectedWarehouse && $selectedWarehouse !== 'all') {
            $baseQuery->where('warehouse', $selectedWarehouse);
        }
        if ($selectedService && $selectedService !== 'all') {
            $baseQuery->where('type', $selectedService);
        }
        
        if ($startDate || $endDate) {
            if ($startDate) $baseQuery->where('date', '>=', $startDate);
            if ($endDate) $baseQuery->where('date', '<=', $endDate);
        } else {
            if ($period === 'day') {
                $baseQuery->whereDate('date', now()->toDateString());
            } elseif ($period === 'week') {
                $baseQuery->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($period === 'month') {
                $baseQuery->whereYear('date', now()->year)
                          ->whereMonth('date', now()->month);
            } elseif ($period === 'year') {
                $baseQuery->whereYear('date', now()->year);
            }
        }

        $readings = (clone $baseQuery)->with('user')->orderBy('date', 'asc')->get();
        $totalConsumption = $readings->sum('consumption');
        $totalWater = $readings->where('type', 'Agua')->sum('consumption');
        $totalEnergy = $readings->where('type', 'Luz')->sum('consumption');

        // Distribution by Warehouse
        $byWarehouse = (clone $baseQuery)->select('warehouse', DB::raw('SUM(consumption) as total'))
            ->groupBy('warehouse')
            ->get();

        // Distribution by Type
        $byType = (clone $baseQuery)->select('type', DB::raw('SUM(consumption) as total'))
            ->groupBy('type')
            ->get();

        // Generate Charts using QuickChart (Base64 for DomPDF compatibility)
        $charts = [];
        try {
            $warehouseConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $byWarehouse->pluck('warehouse')->toArray(),
                    'datasets' => [[
                        'label' => 'Consumo Total',
                        'data' => $byWarehouse->pluck('total')->toArray(),
                        'backgroundColor' => '#C12026'
                    ]]
                ],
                'options' => [
                    'title' => ['display' => true, 'text' => 'CONSUMO POR BODEGA'],
                    'legend' => ['display' => false]
                ]
            ];

            $typeConfig = [
                'type' => 'doughnut',
                'data' => [
                    'labels' => $byType->pluck('type')->toArray(),
                    'datasets' => [[
                        'data' => $byType->pluck('total')->toArray(),
                        'backgroundColor' => ['#3b82f6', '#f59e0b']
                    ]]
                ],
                'options' => [
                    'title' => ['display' => true, 'text' => 'DISTRIBUCIÓN POR SERVICIO'],
                    'plugins' => [
                        'datalabels' => ['display' => true]
                    ]
                ]
            ];

            $charts['warehouse'] = 'data:image/png;base64,' . base64_encode(file_get_contents('https://quickchart.io/chart?width=400&height=220&c=' . urlencode(json_encode($warehouseConfig))));
            $charts['type'] = 'data:image/png;base64,' . base64_encode(file_get_contents('https://quickchart.io/chart?width=300&height=250&c=' . urlencode(json_encode($typeConfig))));
        } catch (\Exception $e) {
            $charts['warehouse'] = null;
            $charts['type'] = null;
        }

        $pdf = \PDF::loadView('readings.report_pdf', compact(
            'readings', 
            'totalConsumption', 
            'totalWater', 
            'totalEnergy', 
            'period', 
            'selectedWarehouse', 
            'selectedService', 
            'startDate', 
            'endDate',
            'byWarehouse',
            'byType',
            'charts'
        ));
        
        return $pdf->download('reporte-medidores-'.now()->format('Y-m-d').'.pdf');
    }
}
