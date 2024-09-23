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

        // Automatically trigger when the chat session starts
        $botman->hears('start', function (BotMan $bot) {
            $locale = session('locale', 'en'); // Retrieve locale from session or use default
            $services = Service::all(); // Fetch all services

            // Create a question with buttons for each service
            $question = Question::create('Welcome! Please select a service or choose one of the following options:');

            foreach ($services as $service) {
                $question->addButton(Button::create($service->getTitleByLocale($locale))->value('service_' . $service->id));
            }

            // Add custom buttons for Share Your Project and General Inquiry
            $question->addButton(Button::create('Share Your Project')->value('share_project'));
            $question->addButton(Button::create('General Inquiry')->value('general_inquiry'));

            // Display the question with the buttons
            $bot->reply($question);
        });

        // Handle other messages as usual (like service selection, project inquiries)
        $botman->hears('service_(\d+)', function (BotMan $bot, $id) {
            $service = Service::find($id);
            $locale = session('locale', 'en'); // Retrieve locale

            if ($service) {
                // Reply with the service description based on locale
                $bot->reply($service->getDescriptionByLocale($locale));
            } else {
                $bot->reply('Sorry, I couldn’t find that service.');
            }
        });

        // Handle "Share Your Project" button
        $botman->hears('share_project', function (BotMan $bot) {
            $bot->reply('Please share your project details with us!');
        });

        // Handle "General Inquiry" button
        $botman->hears('general_inquiry', function (BotMan $bot) {
            $bot->ask('Please provide details about your inquiry:', function ($answer, $bot) {
                $inquiry = $answer->getText();
                $bot->reply('Thank you for your inquiry: ' . $inquiry);
            });
        });

        // Fallback message
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I didn’t understand that. Please select a service or inquiry.');
        });

        $botman->listen();
    }
}
