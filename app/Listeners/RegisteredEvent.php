<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class RegisteredEvent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //Cada usuario registrado tendra el rol de guest(2)
        $event->user->roles()->sync([2]);
    }
}