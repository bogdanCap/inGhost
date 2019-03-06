<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\MessageBrodcast;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function fetchMessages()
    {
        return MessageBrodcast::with('user')->get();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function sendMessage(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var MessageBrodcast $message */
        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();


        return ['status' => 'Message Sent!'];
    }

    public function pusherAuth(Request $request)
    {
        $appId = getenv('PUSHER_APP_ID');
        $appKey = getenv('PUSHER_APP_KEY');
        $appSecret = getenv('c2fbb6231384974a23d5');
        $appCluster = getenv('PUSHER_APP_CLUSTER');
        $pusher = new Pusher( $appKey, $appSecret, $appId, array( 'cluster' => $appCluster, 'useTLS' => true, 'curl_options' => array( CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4 ) ) );
        $pusher->socket_auth($request->get('channel_name'),$request->get('socket_id'));

        return response()->json([
            false
        ]);
    }
}
