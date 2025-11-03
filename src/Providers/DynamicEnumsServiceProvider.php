<?php

namespace Bhry98\LaravelDynamicEnums\Providers;

use Illuminate\Support\ServiceProvider;

class DynamicEnumsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bhry98-dynamic-enums.php', 'bhry98-dynamic-enums');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/bhry98-dynamic-enums.php' => config_path('bhry98-dynamic-enums.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations/bhry98-dynamic-enums'),
        ], 'migrations');
        $this->loadMigrationsFrom(database_path('migrations/bhry98-dynamic-enums'));
    }
}
