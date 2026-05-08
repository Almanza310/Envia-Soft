<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Documentos - {{ strtoupper($phase) }} {{ $phvaYear->year }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f8fafc;
            padding: 10px;
            text-align: left;
            border: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 9px;
        }
        td {
            padding: 10px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Listado de Documentos - {{ strtoupper($phase) }}</h1>
        <p>Ciclo {{ $phvaYear->year }} | {{ $category ? 'Categoría: ' . strtoupper($category) : 'Todas las Categorías' }}</p>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="40%">Nombre del Documento</th>
                <th width="20%">Categoría</th>
                <th width="20%">Fecha de Carga</th>
                <th width="20%">Extensión</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matrices as $matrix)
                <tr>
                    <td><strong>{{ $matrix->name }}</strong></td>
                    <td>{{ strtoupper($matrix->category ?: 'General') }}</td>
                    <td>{{ $matrix->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ strtoupper($matrix->extension) }}</td>
                </tr>
            @endforeach
            @if($matrices->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                        No hay documentos registrados en esta sección.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Página <script type="text/php">echo $PAGE_NUM . " de " . $PAGE_COUNT;</script>
    </div>
</body>
</html>
