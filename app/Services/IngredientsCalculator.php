<?php

namespace App\Services;

use App\Contracts\Services\IIngredientsCalculator;
use App\Models\Order;
use App\Models\Product;

class IngredientsCalculator implements IIngredientsCalculator
{
    public function getGramsByOrder(Order $order): array
    {
        $grams = [];
        foreach ($order->orderProducts as $orderProduct) {
            $product = $orderProduct->product;
            foreach ($product->productIngredients as $productIngredient) {
                $grams[$productIngredient->ingredient_id] = isset($grams[$productIngredient->ingredient_id]) ? $grams[$productIngredient->ingredient_id] += $productIngredient->grams * $orderProduct->quantity: $productIngredient->grams * $orderProduct->quantity;
            }
        }

        return $grams;
    }

    public function getGramsByOrderData(array $orderData): array
    {
        $grams = [];
        foreach ($orderData['products'] as $orderProduct) {
            $product = Product::find($orderProduct['product_id']);
            foreach ($product->productIngredients as $productIngredient) {
                $grams[$productIngredient->ingredient_id] = isset($grams[$productIngredient->ingredient_id]) ? $grams[$productIngredient->ingredient_id] += $productIngredient->grams * $orderProduct['quantity']: $productIngredient->grams * $orderProduct['quantity'];
            }
        }

        return $grams;
    }
}
