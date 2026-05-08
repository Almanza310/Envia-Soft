<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhvaYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(2014, 2026) as $year) {
            \App\Models\PhvaYear::firstOrCreate(['year' => $year]);
        }
    }
}
