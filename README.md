# ConsultantBot Laravel Package

ConsultantBot is a Laravel chatbot package for consulting services. It provides dynamic service options and localized conversations.

## Installation

You can install this package via composer:

```bash
composer require xalatechnologies/consultant-bot

## Database Setup

Please ensure that the `services` table exists in your database. You can create the migration in your main project like this:

```bash
php artisan make:migration create_services_table

Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->string('title_en');        // English title
    $table->string('title_nb');        // Norwegian title
    $table->text('description_en');    // English description
    $table->text('description_nb');    // Norwegian description
    $table->timestamps();
});

use Illuminate\Database\Eloquent\Model;
