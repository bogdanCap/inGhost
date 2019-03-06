<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\MessageBrodcast;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        return MessageBrodcast::with('user')->orderBy('id', 'desc')->take(5)->get();
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

        $appId = env('PUSHER_APP_ID');
        $appKey = env('PUSHER_APP_KEY');
        $appSecret = env('PUSHER_APP_SECRET');
        $appCluster = env('PUSHER_APP_CLUSTER');
        $options = array(
            'cluster' => $appCluster,
            'useTLS' => true
        );
        $pusher = new Pusher(
            $appKey,
            $appSecret,
            $appId,
            $options
        );
        //add message into pusher need only if we need to retrive message from pusher in front
        $data['message'] = $request->get('message');
        $pusher->trigger('private-chat', 'my-event', $data);

        return ['status' => 'Message Sent!'];
    }

    /**
     * @param Request $request
     */
    public function pusherAuth(Request $request)
    {
        //this method need only if we need to read message from pusher in frontend
        $appId = env('PUSHER_APP_ID');
        $appKey = env('PUSHER_APP_KEY');
        $appSecret = env('PUSHER_APP_SECRET');
        $appCluster = env('PUSHER_APP_CLUSTER');
        $options = array(
            'cluster' => $appCluster,
            'useTLS' => true
        );
        $pusher = new Pusher(
            $appKey,
            $appSecret,
            $appId,
            $options
        );

        $user = Auth::user();
        $data['message'] = $request->get('message');
        $presence_data = array('name' => $user->getAuthIdentifierName().'test');
        echo $pusher->presence_auth($_POST['channel_name'], $_POST['socket_id'], $user->getAuthIdentifier(), $presence_data);
    }
}
