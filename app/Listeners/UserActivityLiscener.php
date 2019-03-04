<?php

namespace App\Listeners;



use App\Events\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class UserActivityLiscener
{


    /**
     * @param UserActivity $event
     */
    public function handle(UserActivity $event)
    {
        // Access the order using $event->order...
        $event->user->last_activity = date ("Y-m-d H:i:s", time());;
        $event->user->save();
    }
}