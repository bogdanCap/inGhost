<?php

namespace App\Http\Service;

use App\MessageBrodcast;

class ChatService {

    public function getPublicMessages()
    {
        $messages = MessageBrodcast::with('user')->orderBy('id', 'desc')->take(5)->get()->toArray();
        //sort result
        usort($messages, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        return $messages;
    }
}