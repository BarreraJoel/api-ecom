<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Alimentos'
        ]);
        Category::create([
            'name' => 'Tecnología'
        ]);
        Category::create([
            'name' => 'Limpieza'
        ]);

        Role::create( [
            'name' => 'administrador'
        ]);

        Role::create( [
            'name' => 'cliente'
        ]);

    }
}
