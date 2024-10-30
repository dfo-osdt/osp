<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

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
        Model::preventLazyLoading(! App::isProduction());
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();

        $this->configureCommands();

        // https://spatie.be/docs/laravel-permission/v5/prerequisites#content-schema-limitation-in-mysql
        Schema::defaultStringLength(125);

        // Laravel Pulse view gate
        Gate::define('viewPulse', function ($user) {
            return $user->can('view_pulse');
        });

        // Add Spatie Health Checks
        Health::checks([
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(60)->failWhenUsedSpaceIsAbovePercentage(80),
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
            HorizonCheck::new(),
            PingCheck::new()->url('https://api.orcid.org/')->timeout(2)->name('Orcid Api'),
            RedisCheck::new(),
        ]);
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            App::isProduction()
        );
    }
}
