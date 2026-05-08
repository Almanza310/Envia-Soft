<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Matriz DOFA - {{ $phvaYear->year }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
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
        }
        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-interno { background-color: #eef2ff; color: #4f46e5; }
        .badge-externo { background-color: #fff7ed; color: #ea580c; }
        .badge-tipo { background-color: #fef2f2; color: #dc2626; }
        
        .item-row {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #f1f5f9;
        }
        .item-row:last-child {
            border-bottom: none;
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
        <h1>Matriz de Diagnóstico DOFA</h1>
        <p>Ciclo {{ $phvaYear->year }} | Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Fecha</th>
                <th width="15%">Proceso</th>
                <th width="15%">Responsable</th>
                <th width="10%">Factor</th>
                <th width="10%">Tipo</th>
                <th width="30%">Descripción</th>
                <th width="10%">Puntaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedDofas as $group)
                @php $first = $group->first(); @endphp
                <tr>
                    <td>{{ $first->created_at->format('d/m/Y') }}</td>
                    <td><strong>{{ $first->proceso }}</strong></td>
                    <td>{{ $first->responsable }}</td>
                    <td colspan="4" style="padding: 0;">
                        <table style="width: 100%; border: none; margin: 0;">
                            @foreach($group as $item)
                                <tr>
                                    <td width="25%" style="border-left: none; border-top: none; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                        <span class="badge {{ $item->factor === 'interno' ? 'badge-interno' : 'badge-externo' }}">
                                            {{ $item->factor }}
                                        </span>
                                    </td>
                                    <td width="25%" style="border-top: none; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                        <span class="badge badge-tipo">
                                            {{ $item->tipo }}
                                        </span>
                                    </td>
                                    <td width="35%" style="border-top: none; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                        {{ $item->descripcion }}
                                    </td>
                                    <td width="15%" style="border-right: none; border-top: none; text-align: center; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                        {{ ($item->probabilidad ?? 0) * ($item->impacto ?? 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página <script type="text/php">echo $PAGE_NUM . " de " . $PAGE_COUNT;</script>
    </div>
</body>
</html>
