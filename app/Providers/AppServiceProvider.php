<?php

namespace App\Providers;

use App\Contracts\Services\IIngredientsStockChecker;
use App\Contracts\Services\IIngredientsCalculator;
use App\Services\IngredientsStockChecker;
use App\Services\IngredientsCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IIngredientsCalculator::class, IngredientsCalculator::class);
        $this->app->bind(IIngredientsStockChecker::class, function(){
            return new IngredientsStockChecker($this->app->make(IIngredientsCalculator::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
