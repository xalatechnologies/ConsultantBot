<?php

namespace XalaTechnologies\ConsultantBot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class ShareProjectConversation extends Conversation
{
    protected $projectDetails;

    public function run()
    {
        $this->askProjectDetails();
    }

    public function askProjectDetails()
    {
        $this->ask(__('consultantbot::bot.share_project_details'), function ($answer) {
            $this->projectDetails = $answer->getText();
            $this->say(__('consultantbot::bot.thank_you_share_project', ['details' => $this->projectDetails]));
        });
    }
}
