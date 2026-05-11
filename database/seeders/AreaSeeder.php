<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            // Inventory areas
            'malla', 'mt', 'pds', 'pitalito', 'pt', 'garzon', 
            'financiera administracion', 'gestion humana', 'laplaza', 'despacho',
            // DOFA/PHVA areas
            'planeación estratégica', 'planificación del sgi y mejora continua',
            'medio ambiente', 'calidad', 'comercial', 'recolección',
            'reparto', 'facturación', 'servicio al cliente',
            'mantenimiento de vehículos', 'compras', 'jurídica',
            'seguridad', 'tecnología', 'mercadeo'
        ];

        foreach ($areas as $area) {
            \App\Models\Area::firstOrCreate(['name' => strtolower($area)]);
        }
    }
}
