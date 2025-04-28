<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electricity'],
            ['name' => 'Internet'],
            ['name' => 'Game'],
            ['name' => 'Food'],
            ['name' => 'Emoney'],
            ['name' => 'Data'],
        ];

        Category::insert($categories);
    }
}
