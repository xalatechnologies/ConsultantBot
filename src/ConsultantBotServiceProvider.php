<?php

namespace XalaTechnologies\ConsultantBot;

use Illuminate\Support\ServiceProvider;
use XalaTechnologies\ConsultantBot\Console\Commands\InstallConsultantBot;

class ConsultantBotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load migrations from the package
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'consultantbot');

        // Register the Artisan command
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallConsultantBot::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/consultantbot.php', 'consultantbot');
    }
}
