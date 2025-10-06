<?php

declare(strict_types=1);

namespace Bensedev\RequestBag;

use Illuminate\Support\ServiceProvider;

class RequestBagServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register as scoped so it's recreated per request
        $this->app->scoped(RequestBag::class, function () {
            return new RequestBag();
        });
    }

    public function boot(): void
    {
        //
    }
}
