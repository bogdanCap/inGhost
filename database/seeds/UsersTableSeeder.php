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
    }
}
