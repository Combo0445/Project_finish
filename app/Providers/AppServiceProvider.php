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

        // Only force the https scheme when explicitly opted in — e.g. behind a
        // TLS-terminating proxy that doesn't forward X-Forwarded-Proto. Most
        // reverse proxies (Nginx, most PaaS) do forward it, and TrustProxies
        // already trusts that header, so URLs are generated correctly without
        // this. Forcing it unconditionally in production broke plain-HTTP
        // deployments (e.g. `docker compose up` with no proxy in front),
        // where it produced https:// redirects the app could never serve.
        if (filter_var(env('FORCE_HTTPS_SCHEME', false), FILTER_VALIDATE_BOOLEAN)) {
            URL::forceScheme('https');
        }
    }
}
