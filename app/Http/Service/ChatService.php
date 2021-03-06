<?php

namespace App\Http\Service;

use App\MessageBrodcast;
use App\User;
use Illuminate\Support\Facades\Auth;

class ChatService {

    public function getPublicMessages()
    {
        $userId = Auth::user()->getAuthIdentifier();
        $messages = MessageBrodcast::with('user')
           // ->where('parent_user_id', '=', $userId)
            ->where('parent_user_id', '=', null)
            ->orWhere('user_id', '=', $userId)
            ->orWhere('parent_user_id', '=', $userId)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->toArray();
        //sort result
        usort($messages, function($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        return $messages;
    }

    /**
     * @param bool $notMe
     * @return mixed
     */
    public function getActiveUsers(bool $notMe = false)
    {
        $userId = Auth::user()->getAuthIdentifier();
        $date = date ("Y-m-d H:i:s", time());
        $currentDate = strtotime($date);
        $futureDate = $currentDate-(60*5);
        $formatDate = date("Y-m-d H:i:s", $futureDate);
        if (!$notMe) {
            return User::where([
                //['last_activity', '>', $formatDate],
                //['id', '!=', $userId]
            ])->whereBetween('last_activity', [$formatDate, $date])
                ->get()
                ->toArray();
        } else {
            return User::where([
                /*
                ['last_activity', '>', $formatDate],
                ['last_activity', '<', $formatDate],*/
                ['id', '!=', $userId]
            ])->whereBetween('last_activity', [$formatDate, $date])
                ->get()
                ->toArray();
        }
    }
}