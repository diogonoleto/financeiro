<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\ServiceProvider;
use Faker\Generator as FakerGenerator;
use Faker\Factory as FakerFactory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function (QueryExecuted $query) {
            \Log::info($query->sql);
            \Log::info($query->bindings);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create('pt_BR');
        });
    }
}
