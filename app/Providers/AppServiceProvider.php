<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\BarthelAdl;
use App\Observers\BarthelAdlObserver;

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
        BarthelAdl::observe(BarthelAdlObserver::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
