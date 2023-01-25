<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $beef = Ingredient::create([
            'id' => 1,
            'name' => "Beef",
            'stock_grams' => 20000,
            'stock_minimum_grams' => 10000,
            'stock_minimum_notification_enabled' => true,
        ]);
        $cheese = Ingredient::create([
            'id' => 2,
            'name' => "cheese",
            'stock_grams' => 5000,
            'stock_minimum_grams' => 2500,
            'stock_minimum_notification_enabled' => true,
        ]);
        $onion = Ingredient::create([
            'id' => 3,
            'name' => "onion",
            'stock_grams' => 1000,
            'stock_minimum_grams' => 500,
            'stock_minimum_notification_enabled' => true,
        ]);

        $burger = Product::create([
            'id' => 1,
            'name' => "Burger",
        ]);

        $burger->ingredients()->sync([
            $beef->id => ['grams' => 150],
            $cheese->id => ['grams' => 30],
            $onion->id => ['grams' => 20]
        ]);

    }
}
