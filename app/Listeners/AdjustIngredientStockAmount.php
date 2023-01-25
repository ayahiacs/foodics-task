<?php

namespace App\Listeners;

use App\Contracts\Services\IIngredientsCalculator;
use App\Events\OrderPlaced;
use App\Models\Ingredient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AdjustIngredientStockAmount
{
    public $ingredientsCalculator;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(IIngredientsCalculator $ingredientsCalculator)
    {
        $this->ingredientsCalculator = $ingredientsCalculator;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $grams = $this->ingredientsCalculator->getGramsByOrder($event->order);

        foreach ($grams as $key => $value) {
            $ingredient = Ingredient::find($key);
            $ingredient->decrement('stock_grams', $value);
        }
    }
}
