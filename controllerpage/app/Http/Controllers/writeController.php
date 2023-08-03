<?php

/*****************************************************
 * 프로젝트명   : project-2 controllerpage
 * 디렉토리     : Controllers
 * 파일명       : writeController.php
 * 이력         : v001 0727 SJ.Park new
 *****************************************************/


namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WriteController extends Controller
{
    // 모든 댓글들의 정보와 신고 횟수를 불러오는 부분
    public function commentlist(){
        if(!Auth::user()) {
            return redirect()->route('login.get');
        }

        $comt_list = DB::table('board_replies')->select('reply_id','user_id','board_id','rcontent','created_at','deleted_at')->orderBy('reply_id','desc')->paginate(10);

    return view('commenttem')->with('data', $comt_list);

    }

//댓글 삭제 하기
public function commentdel($id){

        BoardReply::destroy($id);


        DB::table('boards')
        ->join('board_replies', 'boards.board_id', '=', 'board_replies.board_id')
        ->where('board_replies.reply_id', $id)
        ->decrement('replies');

        DB::table('alarms')
        ->where('reply_id', $id)
        ->update(['alarm_flg' => '1']);

        return redirect()->route('comment.commentlist');
    }
    
//체크된 댓글 삭제 하기
public function massDelete(Request $request)
{
    $ids = $request->get('delchk'); 

    if ($ids) {
        BoardReply::destroy($ids);

        DB::table('boards')
            ->join('board_replies', 'boards.board_id', '=', 'board_replies.board_id')
            ->whereIn('board_replies.reply_id', $ids)
            ->decrement('replies');

        DB::table('alarms')
            ->whereIn('reply_id', $ids)
            ->update(['alarm_flg' => '1']);
    }

    return redirect()->route('comment.commentlist');
}


// 게시글 리스트 받아오는 구문
    public function boardlist(){

        if(!Auth::user()) {
            return redirect()->route('login.get');
        }

        $board_list = DB::table('boards')->select('board_id','user_id','btitle','created_at','deleted_at')->orderBy('board_id','desc')->paginate(10);


        return view('boardtem')->with('data', $board_list);
    }

// 게시글 삭제 컨트롤러 부분
    public function boarddel($id){

        if(!Auth::user()) {
            return redirect()->route('login.get');
        }


        Board::destroy($id);

        return redirect()->route('board.boardlist');
    }

//체크된 게시글 삭제 하기
public function bordmassDelete(Request $request)
{

    $ids = $request->get('delchk'); 

        Board::destroy($ids);

    return redirect()->route('board.boardlist');
}
    






}
