@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading headerBorder">Chats</div>

                    <div class="panel-body">
                        <chat-messages :messages="messages" :user="{{ Auth::user() }}"></chat-messages>
                    </div>
                    <div class="panel-footer">
                        <chat-form
                                v-on:messagesent="addMessage"

                                :user="{{ Auth::user() }}" :to-user="toUser"
                        ></chat-form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading headerBorder">User online</div>

                    <div class="panel-body">
                        <users-list ref="userlist" v-on:touser="sendPrivateMessage" :chat-users="chatUsers" :active-item="activeItem"></users-list>

                        <!--
                        <chat-messages :messages="messages"></chat-messages>
                        -->
                    </div>
                    <div class="panel-footer">
                        <!--
                        <chat-form
                                v-on:messagesent="addMessage"
                                :user="{{ Auth::user() }}"
                        ></chat-form>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection