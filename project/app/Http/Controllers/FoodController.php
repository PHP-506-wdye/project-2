<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\FoodInfo;

class FoodController extends Controller
{
    public function index($id = '0') {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }
        
        $user_id = session('user_id');

        // 사용자 등록 음식 목록
        $result = DB::table('food_infos')
        ->select('food_name', 'food_id', 'user_id')
        ->where('user_id', $user_id)
        ->get();

        // 세부 등록 음식 정보 획득
        if ($id > 0) {
            $food = DB::table('food_infos')
            ->select('food_name', 'food_id', 'user_id', 'kcal', 'carbs', 'protein', 'fat', 'sugar', 'sodium', 'serving', 'ser_unit')
            ->where('food_id', $id)
            ->get();

            // todo 사용자 id가 다른 음식 조회&수정 방지
            if ($food[0]->user_id !== $user_id) {
                return redirect()->route('food.index');
            }

            return view('/foodManage')->with('data', $result)->with('food', $food[0]);
        }
        

        return view('/foodManage')->with('data', $result);
    }


    public function create() {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        return view('/foodCreate');
    }

    public function store(Request $req) {

        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // todo 유효성 검사, 영양 정보 값이 없으면 0으로 처리

        $id = session('user_id');

        // 음식 정보 테이블 인서트
        DB::table('food_infos')
            ->insert([
                'user_id'       => $id
                ,'food_name'    => $req->foodName
                ,'kcal'         => $req->kcal
                ,'carbs'        => $req->carbs
                ,'protein'      => $req->protein
                ,'fat'          => $req->fat
                ,'sugar'        => $req->sugar
                ,'sodium'       => $req->sodium
                ,'serving'      => $req->serving
                ,'ser_unit'     => $req->ser_unit
                ,'created_at'   => now()
            ]);

        return redirect()->route('food.index');
    }

    public function update(Request $req, $id) {
        // todo 유효성 검사

        // 음식 테이블 정보 수정
        DB::table('food_infos')
            ->where('food_id', $id)
            ->update([
                'food_name'     => $req->foodName
                ,'kcal'         => $req->kcal
                ,'carbs'        => $req->carbs
                ,'protein'      => $req->protein
                ,'fat'          => $req->fat
                ,'sugar'        => $req->sugar
                ,'sodium'       => $req->sodium
                ,'serving'      => $req->serving
                ,'ser_unit'     => $req->ser_unit
                ,'updated_at'   => now()
            ]);

        return redirect()->route('food.show', ['food' => $id]);
    }

    public function destroy($id) {
        // todo 유효성 검사

        // 게시글 삭제 처리
        FoodInfo::destroy($id);

        // todo 에러처리, 트랜잭션 처리
        return redirect()->route('food.index');
    }
}
