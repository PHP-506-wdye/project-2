<?php

use App\Http\Controllers\ApiController;
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
// 섹션명       : 게시판(Board)
// 기능         : 게시판 관련 api 라우트 설정
// 관리자       : 최아란
// 생성일       : 2023-06-27
// ---------------------------------------------
use App\Http\Controllers\ApiBoardController;

Route::put('/boards/likes', [ApiBoardController::class, 'likeUpDown']);
// v002 add
Route::get('/boards/diets/{favId}', [ApiBoardController::class, 'selectDiets']);

// ---------------------------------------------
// 섹션명       : 회원(User)
// 기능         : 회원 관리 api 라우트 설정
// 관리자       : 박상준 / 권봉정
// 생성일       : 2023-06-19 / 2023-07-11 edit
// ---------------------------------------------

use App\Http\Controllers\ApiUserController;

// Route::get('/user/useremailedt/{user_email}',[ApiUserController::class, 'chdeckEmail']);
// Route::get('/user/usernkchk/{nkname}',[ApiUserController::class, 'chdecknkname']);
// Route::get('/user/userphchk/{user_phone_num}',[ApiUserController::class, 'chdeckphone']);
// Route::post('/user/userpsedt', [ApiUserController::class, 'chdeckpassword']);
Route::get('/users/emails/{user_email}',[ApiUserController::class, 'checkEmail']);
// Route::get('/user/usernkchk/{nkname}',[ApiUserController::class, 'checkNkname']);
// Route::get('/user/userphchk/{user_phone_num}',[ApiUserController::class, 'checkPhone']);
Route::post('/users/passwords', [ApiUserController::class, 'checkPassword']);
Route::delete('/users/{user_id}', [ApiUserController::class, 'userdrawing']);

// ---------------------------------------------
// 섹션명       : 카트(Cart)
// 기능         : 사용자 체크 음식 관련 api 라우트 설정
// 관리자       : 채수지
// 생성일       : 2023-06-22
// ---------------------------------------------
// Route::get('/data', [ApiController::class, 'apisearch']);
Route::post('/carts/foods', [ApiController::class, 'postFoodCart']);
Route::post('/carts/diets', [ApiController::class, 'postFoodCart2']);
Route::delete('/carts/foods', [ApiController::class, 'foodDelete']);
Route::delete('/carts/diets', [ApiController::class, 'dietDelete']);

// ---------------------------------------------
// 섹션명       : 홈(Home)
// 기능         : 홈 섭취량, 음식수정 Api 라우트 설정
// 관리자       : 박상준 / 권봉정
// 생성일       : 2023-07-12 
// ---------------------------------------------
use App\Http\Controllers\ApiHomeController;

Route::put('/intakes/{df_id}',[ApiHomeController::class, 'intakeupdate'])->name('home.intakeupdate');
Route::delete('/foods',[ApiHomeController::class, 'intakedel'])->name('home.intakedel');