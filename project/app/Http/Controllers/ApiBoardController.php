<?php

/*****************************************************
 * 프로젝트명   : project-2
 * 디렉토리     : Controllers
 * 파일명       : ApiBoardController.php
 * 이력         : v001 0526 AR.Choe new
 *                v002 0717 Sj.Chae add
 *****************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Board;

class ApiBoardController extends Controller
{
    public function likeUpDown(Request $req) {
        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        DB::beginTransaction();
        try {
            // 해당 유저의 해당 글 좋아요 확인
            $like_count = DB::table('board_likes')
                ->where('user_id', $req->id1)
                ->where('board_id', $req->id2)
                ->first();
            
            $like_flg = 0;  // 좋아요가 없을 때
            if (isset($like_count)) { 
                $like_flg = 1;  // 좋아요가 있을 때
            }

            if ($like_flg === 0) {
                DB::transaction(function () use ($req) {
                    // 좋아요 테이블 인서트
                    DB::table('board_likes')->insert([
                        'board_id'    => $req->id2
                        ,'user_id'    => $req->id1
                    ]);
        
                    // 게시판 테이블 좋아요 수 증가
                    DB::table('boards')
                    ->where('board_id', '=', $req->id2)
                    ->increment('likes');
                });
                
                $arr['msg'] = '좋아요 증가';
            } else {
                DB::transaction(function () use ($req) {
                    // 좋아요 테이블 정보 삭제
                    DB::table('board_likes')
                        ->where('user_id', $req->id1)
                        ->where('board_id', $req->id2)
                        ->delete();
        
                    // 게시판 테이블 좋아요 수 감소
                    DB::table('boards')
                    ->where('board_id', '=', $req->id2)
                    ->decrement('likes');
                });

                $arr['msg'] = '좋아요 감소';
            }

            $board = Board::find($req->id2);
            $arr['data']['likes'] = $board->likes; 
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $arr['errorcode'] = '1';
            $arr['msg'] = '오류가 발생하였습니다.';
        }
            return $arr;
    }

    // v002 add : 선택한 식단에 포함된 음식 조회 후 출력
    public function selectDiets($favId) {
        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        $favFood = DB::table('fav_diet_food')
                    ->join('food_infos', 'food_infos.food_id', 'fav_diet_food.food_id')
                    ->where('fav_diet_food.fav_id', $favId)
                    ->whereNull('fav_diet_food.deleted_at')
                    ->get();

        if(!$favFood){
            $arr['errcode'] = '1';
            $arr['msg'] = '데이터 없음';
        }else{
            $arr['errcode'] = '0';
            $arr['msg'] = '성공';
            $arr['data'] = $favFood;
        }

        return $arr;
    }
}
