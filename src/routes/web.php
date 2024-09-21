<?php

use Illuminate\Support\Facades\Route;
use XalaTechnologies\ConsultantBot\Http\Controllers\BotManController;

Route::match(['get', 'post'], '/consultant-bot/handle', [BotManController::class, 'handle']);
