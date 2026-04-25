<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\AppointmentCreated;
use App\Listeners\SendAppointmentConfirmationEmail;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            AppointmentCreated::class,
            SendAppointmentConfirmationEmail::class,
        );
    }
}