<?php

namespace App\Contracts\Services;

use App\Models\Order;

interface IIngredientsStockChecker
{
    /**
     * given order data it returns true if ingredients are available in stock currently
     */
    public function checkAvailabilityByOrderData(array $orderData): Bool;
}
