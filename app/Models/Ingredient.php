<?php

namespace App\Models;

use App\Notifications\IngredientAmountBelowMinimumNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

use function Illuminate\Events\queueable;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'stock_grams',
        'stock_minimum_grams',
        'stock_minimum_notification_enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'stock_minimum_notification_enabled' => 'boolean',
    ];

    public function productIngredients()
    {
        return $this->hasMany(Product::class);
    }


    protected static function booted()
    {
        static::updated(function ($ingredient) {
            if($ingredient->stock_minimum_notification_enabled && $ingredient->stock_grams < $ingredient->stock_minimum_grams){
                // send notification and set notification enabled to false not to send multiple notifications for the same ingredient.
                $ingredient->stock_minimum_notification_enabled = false;
                $ingredient->save();
                Notification::send(new User(['email' => 'admin@foodics.com']), new IngredientAmountBelowMinimumNotification($ingredient));
            } else if ($ingredient->stock_minimum_notification_enabled == false && $ingredient->stock_grams >= $ingredient->stock_minimum_grams){
                // if it is greater than the minimum; re-enable the notification flag to recieve notifications again.
                $ingredient->stock_minimum_notification_enabled = true;
                $ingredient->save();
            }
        });
    }

}
