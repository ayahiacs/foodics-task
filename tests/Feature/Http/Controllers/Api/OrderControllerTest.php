<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Events\OrderPlaced;
use App\Mail\IngredientAmountBelowMinimum;
use App\Models\Ingredient;
use App\Models\Order;
use App\Notifications\IngredientAmountBelowMinimumNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\OrderController
 */
class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Event::fake();
    }

    /**
     * @test
     */
    public function store_saves_and_responds_with_204()
    {
        $response = $this->json('post', route('order.store'), [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 1,
                ]
            ]
        ]);

        Event::assertDispatched(OrderPlaced::class);
        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function store_responds_with_validation_error_if_product_does_not_exist()
    {
        $response = $this->json('post', route('order.store'), [
            "products" => [
                [
                    "product_id" => 999999, // id doesn't exist
                    "quantity" => 1,
                ]
            ]
        ]);

        Event::assertNotDispatched(OrderPlaced::class);
        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function store_responds_with_conflict_if_insufficient_ingredients()
    {
        $response = $this->json('post', route('order.store'), [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 1000,
                ]
            ]
        ]);

        Event::assertNotDispatched(OrderPlaced::class);
        $response->assertStatus(409);
    }

}
