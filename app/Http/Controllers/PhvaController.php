<?php

namespace App\Http\Controllers;

use App\Models\PhvaYear;
use App\Models\PhvaMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PhvaController extends Controller
{
    public function exportMatricesPdf(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();
        $phase = $request->query('phase', 'planear');
        $category = $request->query('category');

        $query = $phvaYear->matrices()->where('phase', $phase);
        if ($category && $category !== 'matrices') {
            $query->where('category', $category);
        }
        $matrices = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('phva.matrices_pdf', compact('phvaYear', 'matrices', 'phase', 'category'));
        
        return $pdf->download('reporte-documentos-'.$phase.'-'.$year.'.pdf');
    }

    public function index()
    {
        $years = PhvaYear::orderBy('year', 'desc')->pluck('year');
        $allYears = PhvaYear::orderBy('year', 'desc')->get();
        return view('phva.index', compact('years', 'allYears'));
    }

    public function show($year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();
        $matrices = $phvaYear->matrices()->orderBy('created_at', 'desc')->get();
        
        return view('phva.show', compact('phvaYear', 'matrices'));
    }

    public function matrices(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();
        $matrices = $phvaYear->matrices()->orderBy('created_at', 'desc')->get();
        $dofas = $phvaYear->dofas()->orderBy('created_at', 'desc')->get();
        $areas = [
            'Planeación estratégica',
            'Planificación del SGI y mejora continua',
            'Medio ambiente',
            'Calidad',
            'Comercial',
            'Recolección',
            'Despacho',
            'Reparto',
            'Facturación',
            'Servicio al cliente',
            'Gestión humana',
            'Mantenimiento de vehículos',
            'Compras',
            'Jurídica',
            'Seguridad',
            'Tecnología',
            'Mercadeo'
        ];
        
        return view('phva.matrices', compact('phvaYear', 'matrices', 'dofas', 'areas'));
    }

    public function storeMatrix(Request $request, $year)
    {
        $phvaYear = PhvaYear::where('year', $year)->firstOrFail();

        $request->validate([
            'matrix_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:30720', // 30MB max
            'drive_link' => 'nullable|url|max:2048',
            'name' => 'required|string|max:255',
            'phase' => 'required|string|in:planear,hacer,verificar,actuar',
            'category' => 'nullable|string|max:255',
        ]);

        if (!$request->hasFile('matrix_file') && !$request->filled('drive_link')) {
            return back()->withErrors(['error' => 'Debes subir un archivo o proporcionar un enlace de Drive.'])->withInput();
        }

        $path = null;
        $extension = null;
        $driveLink = $request->input('drive_link');

        if ($request->hasFile('matrix_file')) {
            $file = $request->file('matrix_file');
            $extension = $file->getClientOriginalExtension();
            $path = $file->store('phva/matrices', 'public');
        } elseif ($driveLink) {
            $extension = 'link'; // Custom extension for links
        }

        PhvaMatrix::create([
            'phva_year_id' => $phvaYear->id,
            'name' => $request->name,
            'file_path' => $path,
            'drive_link' => $driveLink,
            'extension' => $extension,
            'phase' => $request->phase,
            'category' => $request->category,
        ]);

        return redirect()->route('phva.matrices', ['year' => $year, 'category' => $request->category])
                       ->with('success', 'Documento subido correctamente.');
    }

    public function downloadMatrix(PhvaMatrix $matrix)
    {
        if ($matrix->drive_link) {
            return redirect()->away($matrix->drive_link);
        }

        if (!$matrix->file_path || !Storage::disk('public')->exists($matrix->file_path)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        return Storage::disk('public')->download($matrix->file_path, $matrix->name . '.' . $matrix->extension);
    }

    public function destroyMatrix(PhvaMatrix $matrix)
    {
        if ($matrix->file_path && Storage::disk('public')->exists($matrix->file_path)) {
            Storage::disk('public')->delete($matrix->file_path);
        }

        $matrix->delete();

        return redirect()->back()->with('success', 'Documento eliminado correctamente.');
    }

    public function storeYear(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100|unique:phva_years,year',
        ]);

        PhvaYear::create($validated);

        return redirect()->back()->with('success', 'Año creado exitosamente.');
    }

    public function destroyYear(PhvaYear $year)
    {
        $year->delete();
        return redirect()->back()->with('success', 'Año eliminado exitosamente.');
    }
}
