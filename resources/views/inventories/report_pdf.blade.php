<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario - ENVIA</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #C12026;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #C12026;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-weight: bold;
        }
        .summary-card {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
            margin-bottom: 20px;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
        }
        .summary-card p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #C12026;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8fafc;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
            text-align: left;
        }
        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-resmas { background-color: #fef2f2; color: #b91c1c; }
        .badge-cinta { background-color: #eff6ff; color: #1d4ed8; }
        .badge-toner { background-color: #ecfdf5; color: #047857; }
        .badge-vinipel { background-color: #f3f4f6; color: #374151; }
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
        <h1>Reporte Detallado de Inventario</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary-card">
        <h3>Total de Artículos Registrados</h3>
        <p>{{ number_format($totalConsumed) }} unidades</p>
    </div>

    <h3 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 10px; border-left: 3px solid #C12026; padding-left: 10px; margin-top: 20px;">Detalle de Inventario</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre / Descripción</th>
                <th>Área</th>
                <th>Tipo de Consumo</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Registrado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $item)
                <tr>
                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                    <td style="text-transform: uppercase; font-weight: bold;">{{ $item->name ?: '-' }}</td>
                    <td style="text-transform: uppercase;">{{ $item->area }}</td>
                    <td>
                        <span class="badge badge-{{ $item->consumption }}">
                            {{ $item->consumption }}
                        </span>
                    </td>
                    <td class="text-center" style="font-weight: bold; font-size: 12px;">{{ $item->quantity }}</td>
                    <td class="text-right" style="font-size: 8px; text-transform: uppercase;">{{ $item->user->name }}</td>
                </tr>
            @endforeach
            @if($inventories->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="padding: 40px; color: #999;">No hay registros en el inventario.</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if($charts['product'] || $charts['area'])
    <div style="margin-top: 30px;">
        <h3 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 15px; border-left: 3px solid #C12026; padding-left: 10px;">Análisis Gráfico</h3>
        <table width="100%">
            <tr>
                <td width="60%" style="border: none; text-align: center; vertical-align: top;">
                    @if($charts['product'])
                        <img src="{{ $charts['product'] }}" style="width: 100%; max-width: 400px; height: auto; border: 1px solid #eee; border-radius: 8px;">
                    @endif
                </td>
                <td width="40%" style="border: none; text-align: center; vertical-align: top;">
                    @if($charts['area'])
                        <img src="{{ $charts['area'] }}" style="width: 100%; max-width: 250px; height: auto; border: 1px solid #eee; border-radius: 8px;">
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Página <script type="text/php">echo $PAGE_NUM . " de " . $PAGE_COUNT;</script>
    </div>
</body>
</html>
