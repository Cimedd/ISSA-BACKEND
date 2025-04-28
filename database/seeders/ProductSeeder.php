<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Product 1', 'description' => 'Description for Product 1', 'price' => 100000, 'provider_id' =>1],
            ['name' => 'Product 2', 'description' => 'Description for Product 2', 'price' => 200000, 'provider_id' => 2],
            ['name' => 'Product 3', 'description' => 'Description for Product 3', 'price' => 300000, 'provider_id' => 3],
            ['name' => 'Product 4', 'description' => 'Description for Product 4', 'price' => 150000, 'provider_id' => 4],
            ['name' => 'Product 5', 'description' => 'Description for Product 5', 'price' => 250000, 'provider_id' => 1],
            ['name' => 'Product 6', 'description' => 'Description for Product 6', 'price' => 250000, 'provider_id' => 5],
        ];

        Product::insert($products);
    }
}
