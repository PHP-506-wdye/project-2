<?php

namespace App\Providers;

use App\Models\Alarm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class HeaderViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layout.header', function ($view) {
            // 유저 알림 정보 획득
            if(Auth::user()) {
                $user_id = Auth::user()->user_id;
                $alarm = Alarm::where('user_id', $user_id)
                    ->where('alarm_flg', '0')
                    ->get();

                // 확인 안된 알림이 있을 때
                if($alarm->count() > 0) {
                    // 뷰에 데이터 전달
                    $view->with('alarmData', $alarm);
                }
            }
        });
    }
}
