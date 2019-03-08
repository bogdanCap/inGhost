<?php

namespace App\Http\Facades;

use App\MessageBrodcast;
use Illuminate\Support\Facades\Facade;

class ChatServiceFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'ChatService';
    }
}