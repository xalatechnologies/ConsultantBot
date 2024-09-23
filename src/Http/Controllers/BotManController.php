<?php

namespace XalaTechnologies\ConsultantBot\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use XalaTechnologies\ConsultantBot\Models\Service;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        // Respond to "hello" or any greeting message
        $botman->hears('hello|hi', function (BotMan $bot) {
            $locale = session('locale', 'en'); // Retrieve locale from session or use default

            // Fetch all services
            $services = Service::all();

            // Create a question to display buttons for services
            $question = Question::create('Welcome! Please select a service or choose one of the following options:');

            foreach ($services as $service) {
                // Add buttons for each service based on locale
                $question->addButton(Button::create($service->getTitleByLocale($locale))->value('service_' . $service->id));
            }

            // Add additional buttons for general inquiry, project idea, etc.
            $question->addButton(Button::create('Share Your Project')->value('share_project'));
            $question->addButton(Button::create('General Inquiry')->value('general_inquiry'));

            // Display the question with buttons to the user
            $bot->reply($question);
        });

        // Handle service selection based on button click
        $botman->hears('service_(\d+)', function (BotMan $bot, $id) {
            $service = Service::find($id);

            if ($service) {
                // Respond with service description
                $locale = session('locale', 'en');
                $bot->reply($service->getDescriptionByLocale($locale));
            } else {
                $bot->reply('Sorry, I could not find that service.');
            }
        });

        // Handle "Share Your Project" button click
        $botman->hears('share_project', function (BotMan $bot) {
            $bot->reply('Please share your project details with us!');
        });

        // Handle "General Inquiry" button click
        $botman->hears('general_inquiry', function (BotMan $bot) {
            $bot->ask('Please provide details about your inquiry:', function ($answer, $bot) {
                $inquiry = $answer->getText();
                $bot->reply('Thank you for your inquiry: ' . $inquiry);
            });
        });

        // Fallback for unrecognized input
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I didn’t understand that. Please type "hello" to get started.');
        });

        $botman->listen();
    }
}
