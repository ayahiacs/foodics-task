<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ingredient;

class IngredientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'stock_grams' => $this->faker->numberBetween(-10000, 10000),
            'stock_minimum_grams' => $this->faker->numberBetween(-10000, 10000),
            'stock_minimum_notification_enabled' => $this->faker->boolean,
        ];
    }
}
