<?php

namespace Tests\Feature\Listeners;

use App\Events\OrderPlaced;
use App\Listeners\AdjustIngredientStockAmount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\OrderController
 */
class AdjustIngredientStockAmountTest extends TestCase
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
    public function test_is_attached_to_event()
    {
        Event::fake();
        Event::assertListening(
            OrderPlaced::class,
            AdjustIngredientStockAmount::class
        );
    }

    /**
     * @test
     */
    public function handle_updates_ingredients_stock_correctly()
    {
        $order = Order::factory()->create();
        $order->products()->sync(Arr::keyBy([
            ['product_id' => 1, 'quantity' => 1],
        ], 'product_id'));

        $event = new OrderPlaced($order);
        $listener = $this->app->make(AdjustIngredientStockAmount::class);
        $listener->handle($event);
 
        $this->assertDatabaseHas('ingredients', ['id' => 1, 'stock_grams' => 19850]);
        $this->assertDatabaseHas('ingredients', ['id' => 2, 'stock_grams' => 4970]);
        $this->assertDatabaseHas('ingredients', ['id' => 3, 'stock_grams' => 980]);

    }

    /**
     * @test
     */
    public function handle_updates_ingredients_stock_correctly_quantity()
    {
        $order = Order::factory()->create();
        $order->products()->sync(Arr::keyBy([
            ['product_id' => 1, 'quantity' => 2],
        ], 'product_id'));

        $event = new OrderPlaced($order);
        $listener = $this->app->make(AdjustIngredientStockAmount::class);
        $listener->handle($event);
 
        $this->assertDatabaseHas('ingredients', ['id' => 1, 'stock_grams' => 19700]);
        $this->assertDatabaseHas('ingredients', ['id' => 2, 'stock_grams' => 4940]);
        $this->assertDatabaseHas('ingredients', ['id' => 3, 'stock_grams' => 960]);

    }    

    /**
     * @test
     */
    public function handle_updates_ingredients_stock_correctly_multiple_products()
    {
        $burger = Product::create([
            'id' => 2,
            'name' => "Burger Double Beef No Onion",
        ]);

        $burger->ingredients()->sync([
            1 => ['grams' => 300],
            2 => ['grams' => 30],
        ]);

        $order = Order::factory()->create();


        $order->products()->sync(Arr::keyBy([
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 1],
        ], 'product_id'));

        $event = new OrderPlaced($order);
        $listener = $this->app->make(AdjustIngredientStockAmount::class);
        $listener->handle($event);
 
        $this->assertDatabaseHas('ingredients', ['id' => 1, 'stock_grams' => 19400]);
        $this->assertDatabaseHas('ingredients', ['id' => 2, 'stock_grams' => 4910]);
        $this->assertDatabaseHas('ingredients', ['id' => 3, 'stock_grams' => 960]);

    }    

}
