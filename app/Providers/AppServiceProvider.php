<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // prevent lazy loading
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();

        // https://spatie.be/docs/laravel-permission/v5/prerequisites#content-schema-limitation-in-mysql
        Schema::defaultStringLength(125);
    }
}
