<?php

/*****************************************************
 * 프로젝트명   : project-2
 * 디렉토리     : Controllers
 * 파일명       : SocialController.php
 * 이력         : v001 0718 AR.Choe new
 *                v002 0727 SJ.Chae add
 *****************************************************/

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($social) {
        return Socialite::driver($social)->redirect();
    }

    public function back($social) {
        $user = Socialite::driver($social)->user();
        
        // 가입 안된 유저인 경우 회원가입 페이지로 이동
        $social_flg = '';
        switch ($social) {
            case 'kakao':
                $social_flg = '0';
                break;
                
                case 'naver':
                $social_flg = '1';
                break;
                
                case 'google':
                $social_flg = '2';
                break;
        }

        // 카카오 이메일과 일치하는 유저정보 획득
        $userBase = UserInfo::where('user_email', $user->getEmail())->first();
        
        // 일치하는 이메일이 있는 경우
        if(isset($userBase)) {
            // 기존 회원이 소셜 회원인지 확인
            if($userBase->social === null) {
                $userBase->social = $social_flg;
                $userBase->save();
            }

            // 로그인 처리
            Auth::login($userBase);
            if(Auth::check()){
                session($userBase->only('user_id'));
                return redirect()->intended(route('home'));
            } else{
                $error = '인증작업 에러.';
                return redirect()->route('user.login')->with('error',$error);
            }
        }
        

        $userInfo = [
            'email'   => $user->getEmail(),
            'name'    => $user->getNickname(),
            'social'  => $social_flg,
        ];

        // ------- v002 add -------
        // 네이버 로그인용 데이터 
        if($social == 'naver') {
            $userphone = $user->user['response']['mobile'];
            $strToArr = explode('-', $userphone);
            $arrToPhone = implode('',$strToArr);

            $userInfo['name'] = $user->name;
            $userInfo['phonenum'] = $arrToPhone;
        }
        // ------- v002 add -------

        // 세션에 유저 정보 저장
        session()->flash('userInfo', $userInfo);

        return redirect()->route('user.regist');
    }
}
