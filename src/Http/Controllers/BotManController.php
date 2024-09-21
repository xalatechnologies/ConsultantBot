<?php

namespace XalaTechnologies\ConsultantBot\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use XalaTechnologies\ConsultantBot\Models\Service;
use Illuminate\Support\Facades\App;

class BotManController extends Controller
{
    public function handle()
    {
        // Get the locale from the session or use default locale
        $locale = session('locale', config('consultantbot.conversation_defaults.locale'));
        App::setLocale($locale);

        $botman = app('botman');

        // Handle "hello" or any initial messages
        $botman->hears('hello|hi', function (BotMan $bot) use ($locale) {
            $services = Service::all();
            $question = Question::create(__('consultantbot::bot.welcome'));

            // Generate service buttons dynamically
            foreach ($services as $service) {
                $question->addButton(Button::create($service->getTitleByLocale($locale))->value('service_' . $service->id));
            }

            // Add custom options (like "Share Your Project")
            $question->addButton(Button::create(__('consultantbot::bot.share_project'))->value('share_project'))
                     ->addButton(Button::create(__('consultantbot::bot.general_inquiry'))->value('general_inquiry'));

            $bot->reply($question);
        });

        // Handle service selection dynamically based on locale
        $botman->hears('service_(\d+)', function (BotMan $bot, $id) use ($locale) {
            $service = Service::find($id);

            if ($service) {
                $bot->reply($service->getDescriptionByLocale($locale));
                $bot->startConversation(new \XalaTechnologies\ConsultantBot\Conversations\ProjectConsultationConversation());
            } else {
                $bot->reply(__('consultantbot::bot.service_not_found'));
            }
        });

        // Handle "Share Your Project"
        $botman->hears('share_project', function (BotMan $bot) {
            $bot->startConversation(new \XalaTechnologies\ConsultantBot\Conversations\ShareProjectConversation());
        });

        // Handle General Inquiry
        $botman->hears('general_inquiry', function (BotMan $bot) {
            $bot->ask(__('consultantbot::bot.general_inquiry_prompt'), function ($answer, $bot) {
                $inquiry = $answer->getText();
                $bot->reply(__('consultantbot::bot.thank_you_inquiry', ['inquiry' => $inquiry]));
            });
        });

        // Fallback for unrecognized messages
        $botman->fallback(function (BotMan $bot) {
            $bot->reply(__('consultantbot::bot.fallback'));
        });

        $botman->listen();
    }
}
