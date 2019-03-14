<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Facades\ChatServiceFacade;
use App\MessageBrodcast;
use App\Models\ChatSession;
use App\Models\ChatSessionGroup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatsController extends Controller
{
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
        return response()->json([
            'message' => ChatServiceFacade::getPublicMessages(),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOnlineUsers()
    {
        return response()->json(ChatServiceFacade::getActiveUsers(true));
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

        $toUser = $request->input('toUser');
        $messageTo = '';

        if (isset($toUser['id'])) {
            /** @var User $messageTo */
            $messageTo = User::find($toUser['id']);
            $message->parent_user_id = $messageTo->getId();
            $message->save();



            // session -> this is for private chat room
            //create chat session for private messages
            //assign user to sessionGroup - if chat more than 1 person
            /*
            $chatsSessionGroup = ChatSessionGroup::whereIn('user_id', [$user->getId(), $messageTo->getId()])->first();


            if(!$chatsSessionGroup) {
                $sessionGroup1 = $user->sessionGroup()->create([]);
                $sessionGroup2 = $messageTo->sessionGroup()->create([]);

                //create chat session
                //session need only if more than 1 person is chat
                $chatSession = new \App\Models\ChatSession();
                $chatSession->session_name = 'test_session_name';
                $chatSession->session_hash = str_random(50);
                $chatSession->save();
                //assign chatSession to message and to user
                $chatSession->messages()->save($message);


                $chatSession->sessionGroup()->save($sessionGroup1);
                $chatSession->sessionGroup()->save($sessionGroup2);
            } else {
                $chatSession = ChatSession::find($chatsSessionGroup->getChatSessionId());
            }
            */




           // $messageTo->getId()
            $channel = 'private-chat'.$messageTo->getId();
        } else {
            $channel = 'private-chat';
        }

        //need to understand this line
      //  broadcast(new MessageSent($user, $message))->toOthers();
    //    event(new MessageSent($user, $message));
        
        

        

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
        $data['message'] = $message;
        $data['user'] = $user;
        //is message private
        $data['parent_user_id'] = $message->getParentUserId();

        $pusher->trigger($channel, 'my-event', $data);

        return [
            'status' => 'Message Sent!',
            'chatChannel' => $channel
        ];
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
    
    public function getUserChatSession()
    {
        /** @var User $user */
        $user = Auth::user();
        /*
        $chatsSessionGroup = ChatSessionGroup::where('user_id', '=', $user->getId())->get();
        $chatSession = ($chatsSessionGroup) ? $chatsSessionGroup->toArray() : [];
        $chatSession = array_map(function ($a) {
            return $a['chat_session_id'];
        }, $chatSession);
        */

        $activeUser = ChatServiceFacade::getActiveUsers();

        //$chatsSessionGroup = User::where('user_id', '=', $user->getId())->get();
        //$chatSession = ($chatsSessionGroup) ? $chatsSessionGroup->toArray() : [];
        $activeUser = array_map(function ($a) {
            return $a['id'];
        }, $activeUser);

      //  var_dump([$activeUser, $user->getAuthIdentifier()]);
      //  die();
        
        //return response()->json(['channels' => $activeUser]);

        return response()->json(['channels' => [$user->getAuthIdentifier()]]);

    }
}
