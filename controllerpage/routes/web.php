<?php


use App\Http\Controllers\ReportController;
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

// Route::get('/', function () {
//     return view('index');
// });


// ---------------------------------------------
// 섹션명       : 댓글관리
// 기능         : 댓글 관리 라우트 설정
// 관리자       : 박상준
// 생성일       : 2023-07-20
// 라우트수      : 총 
// ---------------------------------------------
use App\Http\Controllers\WriteController;


//전체 댓글 가져오는 라우트
Route::get('/comment/commentlist', [WriteController::class, 'commentlist'])->name('comment.commentlist');
// 댓글 삭제 라우트
Route::delete('/comment/commentdel/{id}',[WriteController::class, 'commentdel'])->name('comment.commentdel');
// 댓글 선택 삭제 라우트
Route::delete('/comment/massDelete', [WriteController::class, 'massDelete'])->name('comment.massDelete');

//전체 게시글 가져오는 라우트
Route::get('/board/boardlist', [WriteController::class, 'boardlist'])->name('board.boardlist');
//게시글 삭제 라우트
Route::delete('/board/boarddel/{id}',[WriteController::class, 'boarddel'])->name('board.boarddel');
//게시글 선택 삭제 라우트
Route::delete('/comment/bordmassDelete', [WriteController::class, 'bordmassDelete'])->name('comment.bordmassDelete');


// ---------------------------------------------
// 섹션명       : 회원관리
// 기능         : 회원 관리 라우트 설정
// 관리자       : 박상준
// 생성일       : 2023-07-20
// 라우트수      : 총 
// ---------------------------------------------


use App\Http\Controllers\MemberController;
//전체 회원 정보 가져오는 라우트
Route::get('/member/memberlist',[MemberController::class, 'memberinfo'])->name('member.memberlist');

// 해당 유저 정지 라우트
Route::post('/member/memberstop',[MemberController::class, 'memberstop'])->name('member.memberstop');

// Route::get('test',[writeController::class, 'test']);

// ---------------------------------------------
// 섹션명       : 게시글, 댓글 신고 
// 기능         : 게시글, 댓글 신고 관련 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-07-20
// ---------------------------------------------
Route::get('/report', [ReportController::class, 'returnview'])->name('report.get');
Route::post('/report', [ReportController::class, 'confirmOreport'])->name('report.post');

// ---------------------------------------------
// 섹션명       : 관리자 로그인
// 기능         : 관리자 로그인 관련 라우트 설정
// 관리자       : 권봉정
// 생성일       : 2023-07-20
// ---------------------------------------------
use App\Http\Controllers\ManagerController;

Route::get('/', [ManagerController::class, 'login'])->name('login.get');
Route::post('/', [ManagerController::class, 'loginpost'])->name('login.post');
Route::get('/manager/logout', [ManagerController::class, 'logout'])->name('manager.logout');

// ---------------------------------------------
// 섹션명       : 음식 관리 페이지
// 기능         : 회원 음식, 관리자 음식 관련 라우트 설정
// 관리자       : 권봉정
// 생성일       : 2023-07-20
// ---------------------------------------------
use App\Http\Controllers\FoodController;

Route::get('/user/food', [FoodController::class, 'userfood'])->name('user.food');
Route::get('/manager/food', [FoodController::class, 'managerfood'])->name('manager.food');
Route::post('/food/insert', [FoodController::class, 'foodinsert'])->name('food.insert');

Route::get('/manager/search', [FoodController::class, 'managerfoodSearch'])->name('manager.search');
Route::get('/food/search', [FoodController::class, 'foodSearch'])->name('food.search');





