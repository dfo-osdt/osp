<?php

namespace App\Providers;

use App\Enums\Permissions\UserPermission;
use App\Policies\MediaPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureVite();
        $this->configureModel();
        $this->configureHealth();
        $this->configureGates();
        $this->configureEvents();
        $this->configureServices();

        // https://spatie.be/docs/laravel-permission/v5/prerequisites#content-schema-limitation-in-mysql
        Schema::defaultStringLength(125);

    }

    private function configureGates(): void
    {
        Gate::policy(Media::class, MediaPolicy::class);
        // Laravel Pulse view gate
        Gate::define('viewPulse', fn ($user) => $user->can(UserPermission::VIEW_PULSE));

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

    private function configureVite(): void
    {
        Vite::useCspNonce();
        Vite::useAggressivePrefetching();
    }

    private function configureModel(): void
    {
        Model::preventLazyLoading(! App::isProduction());
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();
    }

    private function configureHealth(): void
    {
        Health::checks([
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(75)->failWhenUsedSpaceIsAbovePercentage(90),
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
            HorizonCheck::new(),
            PingCheck::new()->url('https://api.orcid.org/v3/status')->timeout(5)->retryTimes(5)->name('ORCID Api'),
            RedisCheck::new(),
        ]);
    }

    private function configureEvents(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event): void {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });
    }

    private function configureServices(): void
    {
        // Microsoft Graph Service
        if (config('osp.azure.enable_auth')) {
            $this->app->singleton(\App\Services\MicrosoftGraphService::class, fn ($app): \App\Services\MicrosoftGraphService => new \App\Services\MicrosoftGraphService(
                config('services.azure.tenant'),
                config('services.azure.client_id'),
                config('services.azure.client_secret')
            ));
        }

    }
}
