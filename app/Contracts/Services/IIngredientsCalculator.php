<?php

namespace App\Contracts\Services;

use App\Models\Order;
use App\Models\Product;

interface IIngredientsCalculator
{
    /**
     * given an order it returns an associated array where the key is the ingredient id and value is the grams amount
     */
    public function getGramsByOrder(Order $order): array;

    /**
     * given an order data it returns an associated array where the key is the ingredient id and value is the grams amount
     */
    public function getGramsByOrderData(array $orderData): array;
}
