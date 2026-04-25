<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Mail\AppointmentConfirmed;
use Illuminate\Support\Facades\Mail;

class SendAppointmentConfirmationEmail
{
    public function handle(AppointmentCreated $event): void
    {
        // Send the email to the patient associated with the appointment
        Mail::to($event->appointment->patient->email)
            ->send(new AppointmentConfirmed($event->appointment));
    }
}