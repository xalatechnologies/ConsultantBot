<?php

namespace XalaTechnologies\ConsultantBot\Http\Controllers;

use App\Http\Controllers\Controller;  // Extending Laravel's base Controller
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use XalaTechnologies\ConsultantBot\Models\Service;
use Illuminate\Support\Facades\App;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $locale = session('locale', 'en'); // Retrieve locale from session or use default

        // Start the conversation
        $bot->startConversation(new ServiceConversation($locale));

        $botman->listen();
    }
}
