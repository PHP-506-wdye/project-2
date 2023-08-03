<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Kakao\KakaoExtendSocialite;
use SocialiteProviders\Naver\NaverExtendSocialite;
use SocialiteProviders\Google\GoogleExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //     \SocialiteProviders\Kakao\KakaoExtendSocialite::class.'@handle',
        // ],
        // \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //     'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
        // ],
        // \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        //     'SocialiteProviders\\Naver\\NaverExtendSocialite@handle'
        // ]
        SocialiteWasCalled::class => [
            KakaoExtendSocialite::class,
            NaverExtendSocialite::class,
            GoogleExtendSocialite::class,
        ],
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
