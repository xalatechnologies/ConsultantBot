<?php

namespace XalaTechnologies\ConsultantBot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class ProjectConsultationConversation extends Conversation
{
    protected $projectDescription;
    protected $budget;
    protected $timeline;

    public function run()
    {
        $this->askProjectDescription();
    }

    public function askProjectDescription()
    {
        $this->ask(__('consultantbot::bot.project_description'), function ($answer) {
            $this->projectDescription = $answer->getText();
            $this->askBudget();
        });
    }

    public function askBudget()
    {
        $this->ask(__('consultantbot::bot.project_budget'), function ($answer) {
            $this->budget = $answer->getText();
            $this->askTimeline();
        });
    }

    public function askTimeline()
    {
        $this->ask(__('consultantbot::bot.project_timeline'), function ($answer) {
            $this->timeline = $answer->getText();
            $this->confirmDetails();
        });
    }

    public function confirmDetails()
    {
        $this->say(__('consultantbot::bot.thank_you_project', [
            'project_description' => $this->projectDescription,
            'budget' => $this->budget,
            'timeline' => $this->timeline,
        ]));
    }
}
