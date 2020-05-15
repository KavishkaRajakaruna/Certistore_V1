<?php

namespace App\Listeners;

use App\Events\NewUserCreatedEvent;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class CreateUserDiretoryListener
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
     * @param  NewUserCreatedEvent  $event
     * @return void
     */
    public function handle(NewUserCreatedEvent $event)
    {
        $user = User::find($event->user->id);
        Storage::makeDirectory('/userAssets/'. $event->user->userId, 0777, true);
        Storage::makeDirectory('/userAssets/'.$event->user->userId.'/profile' ,0777,true);
        Storage::makeDirectory('/userAssets/'.$event->user->userId.'/certificates', 0777,true);


    }
}
