<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class UserEventSubscriber
{
    public function subscribe($events){
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );
    }
    
    public function onUserLogin($event){
        $tokenAccess = bcrypt(date('YmdHms'));
        $user = Auth::user();
        $user->token_access = $tokenAccess;
        $user->save();

        session()->put('access_token', $tokenAccess);
    }
}
