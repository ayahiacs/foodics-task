<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\IIngredientsStockChecker;
use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderStoreRequest;
use App\Mail\IngredientAmountBelowMinimum;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\OrderStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStoreRequest $request, IIngredientsStockChecker $ingredientsStockChecker)
    {
        // check stock availability
        $validated = $request->validated();
        $isIngredientsAvailable = $ingredientsStockChecker->checkAvailabilityByOrderData($validated);
        if(!$isIngredientsAvailable){
            return response()->json(['error' => 'Insufficiant ingredients in stock'], 409);
        }

        // save the order
        $order = Order::create();
        $order->products()->sync(Arr::keyBy($validated['products'], 'product_id'));

        // order placed event
        event(new OrderPlaced($order));

        // respond
        return response()->noContent();
    }
}
