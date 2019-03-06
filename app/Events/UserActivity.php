<?php

namespace App\Events;


use App\User;
use Illuminate\Queue\SerializesModels;

class UserActivity
{
    use SerializesModels;

    public $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }
}