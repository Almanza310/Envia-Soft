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
            'malla', 'mt', 'pds', 'pitalito', 'pt', 'garzon', 
            'financiera administracion', 'gestion humana', 'laplaza', 'despacho'
        ];

        foreach ($areas as $area) {
            \App\Models\Area::firstOrCreate(['name' => $area]);
        }
    }
}
