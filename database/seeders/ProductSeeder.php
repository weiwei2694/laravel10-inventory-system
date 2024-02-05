<?php

namespace Database\Seeders;

use App\Models\{Product, User, Category};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 1)->first();
        $category = Category::where('name', 'test')->first();

        Product::create([
            'name' => 'test',
            'description' => 'test',
            'price' => 1000,
            'quantity_in_stock' => 100,
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);
    }
}
