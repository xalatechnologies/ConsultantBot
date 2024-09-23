<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use XalaTechnologies\ConsultantBot\Models\Service;

class ServiceConversation extends Conversation
{
    protected $locale;
    protected $selectedService;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function run()
    {
        $this->showServices();
    }

    // Show available services as buttons
    public function showServices()
    {
        $services = Service::all();
        $question = Question::create(__('consultantbot::bot.welcome'));

        foreach ($services as $service) {
            $question->addButton(Button::create($service->getTitleByLocale($this->locale))->value('service_' . $service->id));
        }

        // Add other inquiry options
        $question->addButton(Button::create(__('consultantbot::bot.share_project'))->value('share_project'));
        $question->addButton(Button::create(__('consultantbot::bot.general_inquiry'))->value('general_inquiry'));

        $this->ask($question, function ($answer) {
            $this->handleAnswer($answer->getValue());
        });
    }

    // Handle the user’s selection (service or inquiry)
    public function handleAnswer($value)
    {
        if (strpos($value, 'service_') !== false) {
            $serviceId = str_replace('service_', '', $value);
            $service = Service::find($serviceId);
            $this->selectedService = $service;
            $this->askProjectDetails();
        } elseif ($value == 'share_project') {
            $this->say(__('consultantbot::bot.share_project_details'));
            $this->askProjectDetails();
        } elseif ($value == 'general_inquiry') {
            $this->askGeneralInquiry();
        } else {
            $this->say(__('consultantbot::bot.fallback'));
        }
    }

    // Ask for project details (form-like flow)
    public function askProjectDetails()
    {
        $this->ask(__('consultantbot::bot.describe_project'), function ($answer) {
            $projectDescription = $answer->getText();
            $this->askProjectTimeline($projectDescription);
        });
    }

    // Ask for project timeline
    public function askProjectTimeline($projectDescription)
    {
        $this->ask(__('consultantbot::bot.project_timeline'), function ($answer) use ($projectDescription) {
            $projectTimeline = $answer->getText();
            $this->askProjectRequirements($projectDescription, $projectTimeline);
        });
    }

    // Ask for specific requirements
    public function askProjectRequirements($projectDescription, $projectTimeline)
    {
        $this->ask(__('consultantbot::bot.specific_requirements'), function ($answer) use ($projectDescription, $projectTimeline) {
            $projectRequirements = $answer->getText();
            $this->askProjectBudget($projectDescription, $projectTimeline, $projectRequirements);
        });
    }

    // Ask for budget
    public function askProjectBudget($projectDescription, $projectTimeline, $projectRequirements)
    {
        $this->ask(__('consultantbot::bot.budget'), function ($answer) use ($projectDescription, $projectTimeline, $projectRequirements) {
            $projectBudget = $answer->getText();
            $this->askContactDetails($projectDescription, $projectTimeline, $projectRequirements, $projectBudget);
        });
    }

    // Ask for contact details (email and phone number)
    public function askContactDetails($projectDescription, $projectTimeline, $projectRequirements, $projectBudget)
    {
        $this->ask(__('consultantbot::bot.contact_details'), function ($answer) use ($projectDescription, $projectTimeline, $projectRequirements, $projectBudget) {
            $contactDetails = $answer->getText();

            // Process the collected data (e.g., store it, send it to an admin, etc.)
            $this->say(__('consultantbot::bot.thank_you_project'));

            // Here you can store the collected details in the database, send an email, etc.
        });
    }

    // General Inquiry Flow
    public function askGeneralInquiry()
    {
        $this->ask(__('consultantbot::bot.please_provide_inquiry'), function ($answer) {
            $inquiryDetails = $answer->getText();
            $this->askContactDetailsForInquiry($inquiryDetails);
        });
    }

    // Ask for contact details for the general inquiry
    public function askContactDetailsForInquiry($inquiryDetails)
    {
        $this->ask(__('consultantbot::bot.contact_details'), function ($answer) use ($inquiryDetails) {
            $contactDetails = $answer->getText();

            // Process the inquiry (e.g., store it, send it to an admin, etc.)
            $this->say(__('consultantbot::bot.thank_you_inquiry', ['inquiry' => $inquiryDetails]));

            // Store or process the inquiry details
        });
    }
}
