<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ---------------------------------------------
// 섹션명       : 게시글, 댓글 신고 
// 기능         : 게시글, 댓글 상세 내용 관련 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-07-21
// ---------------------------------------------
use App\Http\Controllers\ApiReportController;
Route::get('/reports/{id}/{board}', [ApiReportController::class, 'reportDetail']);

// ---------------------------------------------
// 섹션명       : 음식 관리 페이지 
// 기능         : 회원 음식, 관리자 음식 관련 라우트 설정
// 관리자       : 권봉정
// 생성일       : 2023-07-21
// ---------------------------------------------
use App\Http\Controllers\ApiFoodController;

Route::delete('/foods/{food_id}',[ApiFoodController::class, 'userfoodDel'])->name('userfood.del');
Route::post('/foods',[ApiFoodController::class, 'foodinsert'])->name('food.insert');
Route::put('/foods/{food_id}',[ApiFoodController::class, 'foodedit'])->name('food.edit');


// ---------------------------------------------
// 섹션명       : 유저 정지 복구 버튼
// 기능         : 게시글, 댓글 상세 내용 관련 라우트 설정
// 관리자       : 박상준
// 생성일       : 2023-07-21
// ---------------------------------------------

use App\Http\Controllers\ApiMemberController;
// 해당 유저 정지 복구 라우트
Route::post('/users/status/{user_id}',[ApiMemberController::class, 'memberreturn'])->name('member.memberreturn');

