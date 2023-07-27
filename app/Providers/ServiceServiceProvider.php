<?php

namespace App\Providers;

use App\Services\APIs\AggregatorNewsService;
use App\Services\APIs\GuardianService;
use App\Services\APIs\IAggregatorNewsService;
use App\Services\APIs\IGuardianService;
use App\Services\APIs\INewsAPIService;
use App\Services\APIs\INYTimesService;
use App\Services\APIs\NewsAPIService;
use App\Services\APIs\NYTimesService;
use App\Services\Auth\AuthService;
use App\Services\Auth\IAuthService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(IAuthService::class, AuthService::class);
        $this->app->singleton(IGuardianService::class, GuardianService::class);
        $this->app->singleton(INewsAPIService::class, NewsAPIService::class);
        $this->app->singleton(INYTimesService::class, NYTimesService::class);
        $this->app->singleton(IAggregatorNewsService::class, AggregatorNewsService::class);
    }
}
