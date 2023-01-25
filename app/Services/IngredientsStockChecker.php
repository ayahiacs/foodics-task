<?php

namespace App\Services;

use App\Contracts\Services\IIngredientsStockChecker;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;

class IngredientsStockChecker implements IIngredientsStockChecker
{
    protected $ingredientsCalculator;

    public function __construct($ingredientsCalculator)
    {
        $this->ingredientsCalculator = $ingredientsCalculator;
    }

    public function checkAvailabilityByOrderData(array $orderData) : Bool
    {
        $grams = $this->ingredientsCalculator->getGramsByOrderData($orderData);
        foreach ($grams as $key => $value) {
            $ingredient = Ingredient::find($key);
            if($ingredient->stock_grams < $value){
                return false;
            }
        }
        return true;
    }
}
