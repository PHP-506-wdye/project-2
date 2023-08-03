<?php
/*****************************************************
 * 프로젝트명   : project-2
 * 디렉토리     : Controllers
 * 파일명       : BoardController.php
 * 이력         : v001 0526 AR.Choe new
 *                v002 0717 Sj.Chae, AR.Choe add
 *                v003 0724 AR.Choe add
 *****************************************************/

namespace App\Http\Controllers;

use App\Models\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Board;
use App\Models\BoardCate;
use App\Models\BoardLike;
use App\Models\UserInfo;
use App\Models\BoardImg;
use App\Models\BoardReply;
use App\Models\FavDiet;
use App\Models\FavDietFood;
use App\Models\FoodInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 게시글 정보 획득
        $result = Board::join('board_cates','boards.bcate_id','=', 'board_cates.bcate_id')
            ->select('boards.board_id', 'boards.btitle', 'boards.likes', 'boards.hits', 'boards.replies', 'board_cates.bcate_name', 'boards.created_at')
            ->orderBy('boards.created_at', 'desc')
            ->paginate(10);

        return view('boardList')->with('data', $result);
    }

    public function indexNum($id)
    {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 게시글 정보 획득
        $result = Board::join('board_cates','boards.bcate_id','=', 'board_cates.bcate_id')
            ->select('boards.board_id', 'boards.btitle', 'boards.likes', 'boards.hits', 'boards.replies', 'board_cates.bcate_name', 'boards.created_at')
            ->where('boards.bcate_id', $id)
            ->orderBy('boards.created_at', 'desc')
            ->paginate(10)
            ;

        return view('boardList')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }
        // v002 add start
        $id = Auth::user()->user_id;
        $favDiet = DB::table('fav_diets')
                ->where('user_id', $id)
                ->whereNull('deleted_at')
                ->get();
        // v002 add end
        return view('boardCreate')
        ->with('favDiet', $favDiet); // v002 add
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 유효성 검사
        $rules = [
            'cate'      => 'required'
            ,'title'    => 'required|max:50'
            ,'content'  => 'required|max:4000'
            ,'picture'  => 'max:5242880|mimes:jpg,png,gif'
        ];

        $messages = [
            'cate.required'     => '카테고리는 필수 입력 항목입니다.',
            'title.required'    => '제목은 필수 입력 항목입니다.',
            'title.max'         => ':max자까지 입력 가능합니다.',
            'content.required'  => '본문은 필수 입력 항목입니다.',
            'content.max'       => ':max자까지 입력 가능합니다.',
            'picture.mimes'     => 'jpg, png, gif 파일만 업로드 가능합니다.',
            'picture.max'       => '5mb까지 업로드 가능합니다.',
        ];

        $validator = Validator::make($req->only('cate', 'title', 'content', 'picture'), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        // 게시글 테이블에 인서트 후 pk 값 획득
        $board_id = DB::table('boards')->insertGetId([
                'user_id'     => Auth::user()->user_id
                ,'bcate_id'   => $req->cate
                ,'btitle'     => $req->title
                ,'bcontent'   => $req->content
                ,'fav_id'     => $req->favdiet
                ,'created_at' => now()
            ]
            ,'board_id'
        );

        // 이미지 파일이 있다면, 이미지 경로에 저장
        if($req->hasFile('picture')){
            $fileName = time().'_'.$req->file('picture')->getClientOriginalName();
            $path = $req->file('picture')->storeAs('public/images/board', $fileName);

            DB::table('board_imgs')
                ->insert([
                    'board_id'      => $board_id
                    ,'bimg_name'    => $fileName
                    ,'bimg_path'    => $path
                ]);
        }

        return redirect()->route('board.show', ['board' => $board_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 해당 유저의 해당 글 좋아요 확인
        $like_count = DB::table('board_likes')
            ->where('user_id', Auth::user()->user_id)
            ->where('board_id', $id)
            ->first();
        
        $like_flg = 0;
        if (isset($like_count)) { // 좋아요가 있을 때
            $like_flg = 1;
        }
        
        // 조회수 증가
        $page = $request->page;
        if(!isset($page)) {
            DB::table('boards')
                ->where('board_id', $id)
                ->increment('hits');
        }
        
        // 게시글 상세 정보 획득
        $board = Board::find($id);
        $bcate = BoardCate::find($board->bcate_id);
        $user = UserInfo::find($board->user_id);
        $boardImg = DB::table('board_imgs')
            ->where('board_id', $id)
            ->select('bimg_name')
            ->first();
        $style = DB::table('quest_statuses')
        ->join('quest_cates', 'quest_cates.quest_cate_id', 'quest_statuses.quest_cate_id')
        ->where('quest_statuses.user_id', $board->user_id)
        ->where('quest_statuses.rep_flg', '1')
        ->first();

        // 댓글 관련 정보 획득
        // ------------- v003 add -------------
        $subQuery = DB::table('quest_statuses')
        ->select('quest_statuses.user_id', 'quest_cates.quest_style', 'quest_statuses.rep_flg')
        ->join('quest_cates', 'quest_cates.quest_cate_id', '=', 'quest_statuses.quest_cate_id')
        ->where('quest_statuses.rep_flg', '1');
        
        $reply = DB::table('board_replies')
            ->select(
                'board_replies.rcontent',
                'board_replies.created_at',
                'board_replies.reply_id',
                'board_replies.user_id',
                'user_infos.nkname',
                'qs.quest_style'
            )
            ->leftJoinSub($subQuery, 'qs', function ($join) {
                $join->on('qs.user_id', 'board_replies.user_id');
            })
            ->join('user_infos', 'user_infos.user_id', 'board_replies.user_id')
            ->where('board_replies.board_id', $id)
            ->whereNull('board_replies.deleted_at')
            ->where('user_infos.user_status', '1')
            ->paginate(5);
            
        // v002 add start 식단 관련 정보 획득
        $diet = DB::select('SELECT fd.fav_name, fi.food_name, fdf.fav_f_intake
                            FROM fav_diet_food AS fdf
                            INNER JOIN fav_diets AS fd
                            ON fd.fav_id = fdf.fav_id
                            INNER JOIN food_infos AS fi
                            ON fi.food_id = fdf.food_id
                            WHERE fdf.fav_id = ?', [$board->fav_id]);
        // v002 add end
        $arr = [
            'cate'        => $bcate->bcate_name
            ,'nkname'     => $user->nkname
            ,'title'      => $board->btitle
            ,'content'    => nl2br($board->bcontent)
            ,'hits'       => $board->hits
            ,'id'         => $board->board_id
            ,'like'       => $board->likes
            ,'fav_id'     => $board->fav_id // v002 add
            ,'user_id'    => $board->user_id
            ,'created_at' => $board->created_at
            ,'like_flg'   => $like_flg
        ];

        if (isset($boardImg)) {
            $arr['img'] = $boardImg->bimg_name;
        }

        if (isset($style)) {
            $arr['style'] = $style->quest_style;
        }

        return view('boardDetail')
        ->with('data', $arr)
        ->with('reply', $reply)
        ->with('diet', $diet); // v002 add
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        $user_id = Auth::user()->user_id;

        $board = Board::find($id);
        $bcate = BoardCate::orderBy('bcate_id')->get();
        
        // 사용자 id가 다른 글 수정 방지
        if ($board->user_id !== $user_id) {
            return redirect()->back();
        }

        // v002 add start
        // 식단 명 정보 획득
        $user_id = Auth::user()->user_id;
        $favDiet = DB::table('fav_diets')
                ->where('user_id', $user_id)
                ->whereNull('deleted_at')
                ->get();

        // 수정 전 식단 관련 정보 획득
        $beforeDiet = DB::select('SELECT fi.food_name, fdf.fav_f_intake
                            FROM fav_diet_food AS fdf
                            INNER JOIN fav_diets AS fd
                            ON fd.fav_id = fdf.fav_id
                            INNER JOIN food_infos AS fi
                            ON fi.food_id = fdf.food_id
                            WHERE fdf.fav_id = ?', [$board->fav_id]);
        // v002 add end

        return view('boardEdit')
        ->with('data', $board)
        ->with('cate', $bcate)
        ->with('favDiet', $favDiet)
        ->with('beforeDiet', $beforeDiet); // v002 add
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }
        // 유효성 검사
        $rules = [
            'cate'      => 'required'
            ,'title'    => 'required|max:50'
            ,'content'  => 'required|max:4000'
            ,'picture'  => 'max:5242880|mimes:jpg,png,gif'
        ];

        $messages = [
            'cate.required'     => '카테고리는 필수 입력 항목입니다.',
            'title.required'    => '제목은 필수 입력 항목입니다.',
            'title.max'         => ':max자까지 입력 가능합니다.',
            'content.required'  => '본문은 필수 입력 항목입니다.',
            'content.max'       => ':max자까지 입력 가능합니다.',
            'picture.mimes'     => 'jpg, png, gif 파일만 업로드 가능합니다.',
            'picture.max'       => '5mb까지 업로드 가능합니다.',
        ];

        $validator = Validator::make($req->only('cate', 'title', 'content', 'picture'), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        }

        // 게시글 테이블 정보 수정
        $board = Board::find($id);
        $board->bcate_id = $req->cate;
        $board->btitle = $req->title;
        $board->bcontent = $req->content;
        $board->fav_id = $req->favdiet; // v002 add
        $board->save();

        // 이미지 테이블 정보 수정
        if($req->hasFile('picture')){
            // 기존 이미지가 있는지 확인
            $img = DB::table('board_imgs')
            ->where('board_id', $id)
            ->first();

            $fileName = time().'_'.$req->file('picture')->getClientOriginalName();
            $path = $req->file('picture')->storeAs('public/images/board', $fileName);
            
            if(isset($img)) {
                DB::table('board_imgs')
                    ->where('board_id', $id)
                    ->update([
                        'bimg_name'     => $fileName
                        ,'bimg_path'    => $path
                    ]);
            } else {
                DB::table('board_imgs')
                ->insert([
                    'board_id'      => $id
                    ,'bimg_name'    => $fileName
                    ,'bimg_path'    => $path
                ]);
            }
            
        }

        return redirect()->route('board.show', ['board' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 게시글 삭제 처리
        Board::destroy($id);

        // ------------- v003 add -------------
        // 게시글에 속한 댓글 삭제 처리
        BoardReply::where('board_id', $id)
            ->delete();

        // 게시글 알림 확인
        Alarm::where('board_id', $id)
            ->update([
                'alarm_flg' => '1'
            ]);
        // ------------- v003 add -------------

        return redirect()->route('board.index');
    }

    public function replyPost(Request $req) {
        // 유효성 검사
        $rules = [
            'reply'    => 'required|max:200'
        ];

        $messages = [
            'reply.required'    => '댓글은 필수 입력 항목입니다.',
            'reply.max'         => '200자까지 입력가능합니다.',
        ];

        $validator = Validator::make($req->only('reply'), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($req) {
            $user_id = session('user_id');
            // 댓글 테이블 인서트
            $reply_id = DB::table('board_replies')->insertGetId([
                'user_id'       => $user_id
                ,'board_id'     => $req->board_id
                ,'rcontent'     => $req->reply
                ,'created_at'   => now()
            ], 'reply_id');
    
            // 게시글 테이블 댓글 수 업데이트
            DB::table('boards')
                ->where('board_id', $req->board_id)
                ->increment('replies');
    
            // ------------- v003 add -------------
            // 해당 게시글에 알림이 있는지 확인
            $alarmExist = Alarm::where('user_id', $req->user_id)
                ->where('board_id', $req->board_id)
                ->where('alarm_flg', '0')
                ->first();

            // 본인이 작성한 댓글은 알림 인서트가 되지 않게 처리
            if($req->user_id != $user_id && !isset($alarmExist)) {
                // 댓글 알림 테이블 인서트
                $alarm= new Alarm;
                $alarm->user_id = $req->user_id;
                $alarm->board_id = $req->board_id;
                $alarm->reply_id = $reply_id;
                $alarm->alarm_type = '1';  // 댓글 알림 타입
                $alarm->save();
            }
            // ------------- v003 add -------------
        });

        $replyCount = DB::table('boards')
            ->where('board_id', $req->board_id)
            ->first()
            ->replies;

        // 게시글 상세 페이지 이동
        return redirect()->route('board.show', ['board' => $req->board_id, 'page' => ceil($replyCount / 5)]);
    }

    // 댓글 삭제
    public function replyDelete($board, $id) {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        DB::transaction(function () use ($id, $board) {
            // 댓글 삭제 처리
            BoardReply::destroy($id);
    
            // 게시글 테이블 댓글 수 업데이트
            DB::table('boards')
                ->where('board_id', $board)
                ->decrement('replies');

            // ------------- v003 add -------------
            // 댓글 알림 테이블 플래그 변경
            DB::table('alarms')
                ->where('reply_id', $id)
                ->update(['alarm_flg' => '1']);
            // ------------- v003 add -------------
        });

        return redirect()->route('board.show', ['board' => $board, 'page' => '1']);
    }

    // v002 add 식단 내려받기 
    public function dietdownload(Request $req, $favid) {

        $id = Auth::user()->user_id; // 유저 id 획득
        $getsetFav = DB::table('fav_diets')->insertGetId([ // 식단 입력 및 식단 id 획득
            'user_id' => $id,
            'fav_name' => $req->fav_name
        ]);

        // favid를 통한 식단에 포함된 음식 검색 및 정보 획득
        $getFavFood = DB::select('SELECT fi.food_id, fi.food_name, fdf.fav_f_intake 
                                FROM fav_diet_food AS fdf
                                INNER JOIN fav_diets AS fd
                                ON fd.fav_id = fdf.fav_id
                                INNER JOIN food_infos AS fi
                                ON fi.food_id = fdf.food_id
                                WHERE fdf.fav_id = ?', [$favid]);

        foreach ($getFavFood as $value) { // 획득한 식단 정보를 저장
            $setFavFood = new FavDietFood([
                'fav_id' => $getsetFav,
                'food_id' => $value->food_id,
                'fav_f_intake' => $value->fav_f_intake
            ]);
            $setFavFood->save();
        }

        return redirect()->route('fav.favdiet');
    }
}
