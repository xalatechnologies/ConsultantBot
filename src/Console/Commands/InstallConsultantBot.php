<?php

namespace XalaTechnologies\ConsultantBot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallConsultantBot extends Command
{
    protected $signature = 'consultantbot:install';
    protected $description = 'Install the Consultant Bot package and run migrations';

    public function handle()
    {
        $this->info('Installing Consultant Bot Package...');

        // Run the migrations
        Artisan::call('migrate', [
            '--path' => '/vendor/xalatechnologies/consultant-bot/database/migrations',
        ]);

        $this->info('Consultant Bot installed successfully.');
    }
}
