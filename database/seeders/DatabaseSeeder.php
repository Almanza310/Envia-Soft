<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrador ENVIA',
            'email' => 'admin@envia.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Supervisor ENVIA',
            'email' => 'supervisor@envia.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor',
        ]);

        User::factory()->create([
            'name' => 'Operador ENVIA',
            'email' => 'operador@envia.com',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $this->call([
            AreaSeeder::class,
        ]);
    }
}
