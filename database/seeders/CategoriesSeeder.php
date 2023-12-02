<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // You can adjust the number of categories you want to create
        $numberOfCategories = 10;

        for ($i = 1; $i <= $numberOfCategories; $i++) {
            Category::create([
                'name' => 'Category ' . $i,
            ]);
        }
    }
}
