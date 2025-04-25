<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            ['name' => 'Provider 1', 'category_id' => 1],
            ['name' => 'Provider 2', 'category_id' => 1],
            ['name' => 'Provider 3', 'category_id' => 2],
            ['name' => 'Provider 4', 'category_id' => 2],
            ['name' => 'Provider 5', 'category_id' => 1],
        ];

        Provider::insert($providers);
    }
}
