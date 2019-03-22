<?php

namespace App\Providers;

use App\Http\Service\ChatService;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ChatService', function ($app) {
            return new ChatService();
        });


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //registering model observer
        User::observe(UserObserver::class);
    }
}
