<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Medidores - ENVIA</title>
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
        .summary-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
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
            color: #000;
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
        .badge-agua { background-color: #eff6ff; color: #1d4ed8; }
        .badge-luz { background-color: #fffbeb; color: #b45309; }
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
        <h1>Reporte de Consumo de Medidores</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
        <div style="margin-top: 10px; font-size: 8px; color: #666; text-transform: uppercase; tracking-widest;">
            Filtros: 
            Bodega: {{ $selectedWarehouse === 'all' ? 'Todas' : $selectedWarehouse }} | 
            Servicio: {{ $selectedService === 'all' ? 'Ambos' : $selectedService }} |
            Periodo: {{ strtoupper($period) }}
            @if($startDate || $endDate)
                ({{ $startDate ?? 'Inicio' }} - {{ $endDate ?? 'Fin' }})
            @endif
        </div>
    </div>

    <table class="summary-grid">
        <tr>
            <td width="33%" style="border: none; padding: 5px;">
                <div class="summary-card">
                    <h3>Consumo Total Agua</h3>
                    <p>{{ number_format($totalWater, 2) }} m³</p>
                </div>
            </td>
            <td width="33%" style="border: none; padding: 5px;">
                <div class="summary-card">
                    <h3>Consumo Total Energía</h3>
                    <p>{{ number_format($totalEnergy, 2) }} kWh</p>
                </div>
            </td>
            <td width="33%" style="border: none; padding: 5px;">
                <div class="summary-card" style="border-left: 3px solid #C12026;">
                    <h3>Consumo Total General</h3>
                    <p>{{ number_format($totalConsumption, 2) }} units</p>
                </div>
            </td>
        </tr>
    </table>

    <h3 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 10px; border-left: 3px solid #C12026; padding-left: 10px; margin-top: 20px;">Detalle de Lecturas</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Bodega</th>
                <th>Servicio</th>
                <th class="text-right">Lectura</th>
                <th class="text-right">Consumo</th>
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($readings as $reading)
                <tr>
                    <td>{{ Carbon\Carbon::parse($reading->date)->format('d/m/Y') }}</td>
                    <td><strong>{{ $reading->warehouse }}</strong></td>
                    <td>
                        <span class="badge {{ $reading->type == 'Agua' ? 'badge-agua' : 'badge-luz' }}">
                            {{ $reading->type }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($reading->value, 2) }}</td>
                    <td class="text-right" style="color: {{ $reading->consumption > 0 ? '#dc2626' : '#000' }}; font-weight: bold;">
                        {{ $reading->consumption > 0 ? '+' : '' }}{{ number_format($reading->consumption, 2) }}
                    </td>
                    <td style="font-size: 8px; text-transform: uppercase;">{{ $reading->user->name }}</td>
                </tr>
            @endforeach
            @if($readings->isEmpty())
                <tr>
                    <td colspan="6" class="text-center" style="padding: 40px; color: #999;">No hay registros coincidentes.</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if($charts['warehouse'] || $charts['type'])
    <div style="margin-top: 30px;">
        <h3 style="text-transform: uppercase; font-size: 10px; color: #666; margin-bottom: 15px; border-left: 3px solid #C12026; padding-left: 10px;">Análisis Gráfico</h3>
        <table width="100%">
            <tr>
                <td width="60%" style="border: none; text-align: center; vertical-align: top;">
                    @if($charts['warehouse'])
                        <img src="{{ $charts['warehouse'] }}" style="width: 100%; max-width: 400px; height: auto; border: 1px solid #eee; border-radius: 8px;">
                    @endif
                </td>
                <td width="40%" style="border: none; text-align: center; vertical-align: top;">
                    @if($charts['type'])
                        <img src="{{ $charts['type'] }}" style="width: 100%; max-width: 250px; height: auto; border: 1px solid #eee; border-radius: 8px;">
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
