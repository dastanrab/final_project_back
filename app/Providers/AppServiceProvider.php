<?php

namespace App\Providers;

use App\Models\Deliver;
use App\Models\Fail;
use App\Models\Raw;
use App\Services\Cart\CartService;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $this->app->bind('Cart',function(){
            return new CartService();
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Relation::morphMap([
            'raw' => Raw::class,
            'deliver' => Deliver::class,
            'fail'=> Fail::class
        ]);
    }
}
