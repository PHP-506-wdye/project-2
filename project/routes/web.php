<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

// ---------------------------------------------
// 섹션명       : 게시판(Board)
// 기능         : 게시판 관련 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\BoardController;
Route::resource('/board', BoardController::class);

Route::get('/board/{board}/detail', [BoardController::class, 'showDetail'])->name('board.showDetail');
Route::get('/board/{board}/list', [BoardController::class, 'indexNum'])->name('board.indexNum');
// Route::get('/board/{board}?page={pages}', [BoardController::class, 'show'])->name('board.shows');
Route::post('/board/reply', [BoardController::class, 'replyPost'])->name('board.replyPost');
Route::delete('/board/reply/{board}/{id}', [BoardController::class, 'replyDelete'])->name('board.replyDelete');

Route::post('/downdiet/{favid}', [BoardController::class, 'dietdownload'])->name('board.dietdownload');

// Route::post('/board', [BoardController::class, 'store'])->name('board.store');
// Route::get('/board/create', [BoardController::class, 'create'])->name('board.create');
// Route::get('/board/{board}', [BoardController::class, 'show'])->name('board.show');
// Route::put('/board/{board}', [BoardController::class, 'update'])->name('board.update');
// Route::delete('/board/{board}', [BoardController::class, 'destroy'])->name('board.destroy');
// Route::get('/board/{board}/edit', [BoardController::class, 'edit'])->name('board.edit');

// ---------------------------------------------
// 섹션명       : 퀘스트(Quest)
// 기능         : 퀘스트 관련 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\QuestController;

Route::get('/quest', [QuestController::class,'index'])->name('quest.index');
Route::post('/quest', [QuestController::class, 'store'])->name('quest.store');
Route::get('/quest/log', [QuestController::class,'show'])->name('quest.show');
Route::put('/quest/log/{id}', [QuestController::class,'update'])->name('quest.update');
Route::put('/quest/alaram/{id}', [QuestController::class,'alarmUpdate'])->name('quest.alarmUpdate');
Route::delete('/quest/log/{id}', [QuestController::class,'destroy'])->name('quest.destroy');
Route::get('/quest/achieve', [QuestController::class,'questAchieve'])->name('quest.questAchieve');
Route::get('/quest/achieve/{id}', [QuestController::class,'repFlgUpdate'])->name('quest.repFlgUpdate');


// ---------------------------------------------
// 섹션명       : 음식(Food)
// 기능         : 사용자 등록 음식 관련 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\FoodController;

Route::get('/food/manage', [FoodController::class,'index'])->name('food.index');
Route::get('/food/manage/{food}', [FoodController::class,'index'])->name('food.show');
Route::get('/food/create', [FoodController::class,'create'])->name('food.create');
Route::post('/food/store', [FoodController::class,'store'])->name('food.store');
Route::put('/food/{food}', [FoodController::class,'update'])->name('food.update');
Route::delete('/food/{food}', [FoodController::class,'destroy'])->name('food.destroy');

// ---------------------------------------------
// 섹션명       : 알림(Alarm)
// 기능         : 알림 관련 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-07-20
// ---------------------------------------------
use App\Http\Controllers\AlarmController;
Route::put('/alarm/{alarm}', [AlarmController::class,'flgUpdate'])->name('alarm.flgUpdate');

// ---------------------------------------------
// 섹션명       : 유저(User)
// 기능         : 로그인, 회원가입 등 라우트 설정
// 관리자       : 박상준
// 생성일       : 2023-06-15
// 라우트수      : 총 12개 
// ---------------------------------------------
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;

Route::get('/user/login', [UserController::class,'login'])->name('user.login')->middleware('guest');// 로그인 페이지 이동 라우트
Route::get('/user/regist',[UserController::class, 'regist'])->name('user.regist');//회원가입 페이지 이동 라우트


Route::post('/user/loginpost', [UserController::class,'loginpost'])->name('user.loginpost');//로그인 작동라우트
Route::post('/user/registpost',[UserController::class,'registpost'])->name('user.registpost');//회원가입 진행 라우트

Route::get('/user/userinfoedit',[UserController::class, 'userinfoedit'])->name('user.userinfoedit');//유저 정보 수정 페이지 이동 라우트
Route::post('/user/userinfoPost',[UserController::class,'userinfoeditPost'])->name('user.userinfoeditPost');//유저 정보 수정 입력 라우트

Route::get('/user/userfindE',[UserController::class, 'userfindE'])->name('user.userfindE'); //유저 아이디를 찬는 구문
Route::post('/user/userfindEPost',[UserController::class,'userfindEPost'])->name('user.userfindEPost'); //유저 아이디를 찾는 값을 요청하는 구문


Route::get('user/userpsedit',[UserController::class, 'userpsedit'])->name('user.userpsedit');//유저 비밀번호 수정 이동 라우트
Route::post('user/userpseditpost',[UserController::class,'userpseditpost'])->name('user.userpseditpost');//변경 비밀번호 적용 라우트
Route::post('user/userKcalup',[UserController::class, 'userKcalup'])->name('user.userKcalup');//유저 칼로리 정보 입력 라우트 - 버튼은 기본자료 수정 버튼 구동
Route::get('users/logout', [UserController::class, 'logout'])->name('user.logout');

Route::get('user/userKcal',[UserController::class,'userKcalinfo'])->name('user.prevateinfo'); // 유저의 식단변경과 목표 칼로리 변경 페이지로 이동
Route::post('user/userKcaledit',[UserController::class,'userKcaledit'])->name('user.userKcaledit'); //유저의 식단과 목표 칼로리 변경부분 입력을 진행하는 라우트

Route::get('user/userwithdraw',[UserController::class, 'userwithdraw'])->name('user.userwithdraw'); // 회원 탈퇴 페이지 이동
Route::post('user/userdraw',[UserController::class, 'userdraw'])->name('user.userdraw'); //회원 탈퇴 진행

//메일 전송
Route::get('/user/emailverifypage',[UserController::class, 'emailverifypage'])->name('user.emailverifypage'); //메일 전송페이지 이동

//메일 인증
Route::post('/users/verify', [UserController::class, 'emailverifypost'])->name('users.verify');
Route::get('/resend-email', [UserController::class, 'resend_email'])->name('resend.email');

//인증 번호 입력확인
Route::post('/users/accessnum', [UserController::class, 'accessok'])->name('users.accessok');

// 사용자의 작성글, 댓글
Route::get('/user/userBoard', [UserController::class, 'myboard'])->name('user.board');
Route::get('/user/userReply', [UserController::class, 'myreply'])->name('user.reply');

//----------------테스트용--------------------------------------
// use App\Http\Controllers\ButtonController;
// Route::post('/button-click', [ButtonController::class, 'handleButtonClick']);
// Route::post('/user/registdup', [UserController::class, 'chdeckEmail'])->name('user.registdup');
//-------------------------------------------------------------

// ---------------------------------------------
// 섹션명       : 즐겨찾는 식단(Fav)
// 기능         : 즐겨찾는 식단 관련 라우트 설정
// 관리자       : 박상준
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\FavController;

Route::get('/userfav/favdiet',[FavController::class,'favdiet'])->name('fav.favdiet'); //즐겨찾는 식단 정보 페이지 이동
Route::get('/userfav/favfoodinfo/{fav_id}',[FavController::class,'favdiet'])->name('fav.favfoodinfo'); //즐겨 찾는 식단 정보의 식단별 음식들의 영양 정보 확인 하는 구문의 라우터
Route::post('/userfav/intakeupdate',[FavController::class,'intakeupdate'])->name('fav.intakeupdate'); // 즐겨 찾는 식단 정보에서 수량조절(인분서 - 먹은양)하는 라우터
Route::get('userfav/favfooddiet/{fav_id}',[FavController::class,'favdietDel'])->name('fav.delete'); //즐겨 찾는 식단을 삭제하는 구문
Route::get('userfav/favfoodDel/{fav_f_id}',[FavController::class,'favfoodDel'])->name('fav.del'); //즐겨 찾는 식단 정보에서 음식 삭제 기능

// ---------------------------------------------
// 섹션명       : 검색(Search)
// 기능         : 음식 검색, 인서트 등 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\SearchController;
Route::get('/search/list/{id}', [SearchController::class, 'searchselect'])->name('search.list');
Route::post('/search/list/{id}', [SearchController::class, 'searchselect'])->name('search.list.get');
Route::get('/search/list/{date}/{time}', [SearchController::class, 'searchinsert'])->name('search.insert');
Route::delete('/search/list/{date}/{time}', [SearchController::class, 'searchdelete'])->name('search.delete');
Route::post('/search/{f_id}/{c_id}', [SearchController::class, 'fooddelete'])->name('food.delete');
Route::post('/search/{d_id}/{c_id}', [SearchController::class, 'dietdelete'])->name('diet.delete');

// ---------------------------------------------
// 섹션명       : 신고(Report)
// 기능         : 게시글, 댓글 신고 등 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-07-20
// ---------------------------------------------
use App\Http\Controllers\ReportController;
Route::post('/report', [ReportController::class, 'report'])->name('report');

// ---------------------------------------------
// 섹션명       : 홈(Home)
// 기능         : 홈페이지 관련 라우트 설정
// 관리자       : 권봉정
// 생성일       : 2023-06-15
// ---------------------------------------------
use App\Http\Controllers\HomeController;
Route::get('/home', [HomeController::class, 'homepost'])->name('home');
Route::post('/home', [HomeController::class, 'homepost'])->name('home.post');
Route::post('/home/{df_id}', [HomeController::class, 'homeupdate'])->name('home.update');
Route::delete('/home/{df_id}', [HomeController::class, 'homedelete'])->name('home.delete');
Route::get('/userfav', [HomeController::class, 'favinsert'])->name('fav.insert');
Route::put('/home/{d_id}',[HomeController::class, 'imgEdit'])->name('img.edit');

// ---------------------------------------------
// 섹션명       : 추천(Recommend)
// 기능         : 식단 추천 관련 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-07-12
// ---------------------------------------------
use App\Http\Controllers\RecommendController;

Route::get('/recom', [RecommendController::class, 'rview'])->name('recom.get');
Route::post('/recom', [RecommendController::class, 'recommned'])->name('recom.post');
Route::post('/setdiet', [RecommendController::class, 'setdiet'])->name('recom.setdiet');


// ---------------------------------------------
// 섹션명       : 카카오 로그인
// 기능         : 카카오 로그인 관련 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-07-18
// ---------------------------------------------
use App\Http\Controllers\SocialController;
Route::get('social/{social}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('social/{social}/back', [SocialController::class, 'back']);

// ---------------------------------------------
// 섹션명       : 비밀번호 찾기
// 기능         : 비밀번호 찾기
// 관리자       : 권봉정
// 생성일       : 2023-07-18
// ---------------------------------------------
use App\Http\Controllers\PwController;
Route::get('/user/userfindpw', [PwController::class, 'findpwget'])->name('findpw.get');
Route::post('/user/userfindpw', [PwController::class, 'findpwpost'])->name('findpw.post');