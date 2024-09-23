<?php

namespace XalaTechnologies\ConsultantBot;

use Illuminate\Support\ServiceProvider;

class ConsultantBotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/consultantbot.php' => config_path('consultantbot.php'),
        ], 'config');
    }

    public function register()
    {
        // Merge the package config with the app config
        $this->mergeConfigFrom(__DIR__ . '/../config/consultantbot.php', 'consultantbot');
    }
}
