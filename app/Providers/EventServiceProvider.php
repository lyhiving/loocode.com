<?php

namespace App\Providers;

use App\Events\Post;
use App\Listeners\PostListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\QQ\QqExtendSocialite;
use SocialiteProviders\WeixinWeb\WeixinWebExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
        SocialiteWasCalled::class => [
            QqExtendSocialite::class . '@handle',
            WeixinWebExtendSocialite::class . '@handle',
        ],
        Post::class => [PostListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
