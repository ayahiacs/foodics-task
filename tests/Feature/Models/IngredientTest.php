<?php

namespace Tests\Feature\Models;

use App\Events\OrderPlaced;
use App\Listeners\AdjustIngredientStockAmount;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\IngredientAmountBelowMinimumNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\OrderController
 */
class IngredientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * @test
     */
    public function updated_event_sends_notification_and_disables_the_notification()
    {
        Notification::fake();
        
        $ingredient = Ingredient::find(1);

        $ingredient->stock_grams = $ingredient->minimum_stock_grams - 1;
        $ingredient->save();
        $ingredient->refresh();

        $this->assertEquals(false, $ingredient->stock_minimum_notification_enabled);

        Notification::assertSentTimes(IngredientAmountBelowMinimumNotification::class, 1);
    }

    /**
     * @test
     */
    public function updated_event_does_not_send_notification_if_stock_minimum_notification_disabled()
    {
        Notification::fake();
        
        $ingredient = Ingredient::find(1);
        $ingredient->stock_minimum_notification_enabled = false;
        $ingredient->stock_grams = $ingredient->minimum_stock_grams - 1;
        $ingredient->save();
        $ingredient->refresh();

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function updated_event_sends_stock_minimum_notification_only_one_time()
    {
        Notification::fake();
        
        $ingredient = Ingredient::find(1);
        $ingredient->stock_grams = $ingredient->minimum_stock_grams - 1;
        $ingredient->save();

        $this->assertEquals(false, $ingredient->stock_minimum_notification_enabled);

        $ingredient->stock_grams = $ingredient->minimum_stock_grams + 1;
        $ingredient->save();

        Notification::assertSentTimes(IngredientAmountBelowMinimumNotification::class, 1);
    }

    /**
     * @test
     */
    public function updated_event_enables_the_stock_minimum_notification_when_stock_grams_updated_to_be_more_than_minimum()
    {
        Notification::fake();
        
        $ingredient = Ingredient::find(1);
        $ingredient->stock_grams = $ingredient->minimum_stock_grams - 1;
        $ingredient->save();
        $this->assertEquals(false, $ingredient->stock_minimum_notification_enabled);
        $ingredient->stock_grams = $ingredient->stock_minimum_grams;
        $ingredient->save();
        $this->assertEquals(true, $ingredient->stock_minimum_notification_enabled);
    }
}
