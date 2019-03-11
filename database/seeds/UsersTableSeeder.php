<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'bogdan',
            'email' => 'bog@ram.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);
        \App\User::create([
            'name' => 'Alex',
            'email' => 'bog@ram1.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);
        \App\User::create([
            'name' => 'Group User Name',
            'email' => 'bog@ram2.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);

        /** @var \App\User $user */
        $user = \App\User::where('email', 'bog@ram2.com')->first();

        $message = $user->messages()->create([
            'message' => 'test message tt'
        ]);
        //assign user to sessionGroup - if chat more than 1 person
        $sessionGroup = $user->sessionGroup()->create([]);
        //create chat session
        //session need only if more than 1 person is chat
        $chatSession = new \App\Models\ChatSession();
        $chatSession->session_name = 'test_session_name';
        $chatSession->session_hash = str_random(50);
        $chatSession->save();
        //assign chatSession to message and to user
        $chatSession->messages()->save($message);
        $chatSession->sessionGroup()->save($sessionGroup);
    }
}
