<?php
/*****************************************************
 * 컨트롤러명   : SearchController
 * 디렉토리     : Contrllers
 * 파일명       : SearchController.php
 * 이력         : v001 0615 Sj.Chae new
 *                v002 0713 AR.Choe add, delete
 *****************************************************/
namespace App\Http\Controllers;

use App\Models\Diet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FoodInfo;
use App\Models\FavDiet;
use App\Models\FavDietFood;
use App\Models\DietFood;
use Illuminate\support\Facades\Session;
use Illuminate\Support\Eloquent\SoftDeletes;
use App\Models\FoodCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SearchController extends Controller
{
    // ! 검색 및 데이터 조회
    public function searchselect(Request $req, $id) {
        $usersearch = $req->search_input;

        $req->flashOnly('search_input');
        
        // * 즐겨찾는 식단 정보
        $dietnames = FavDiet::select('fav_diets.fav_id', 'fav_diets.fav_name', 'fav_diets.user_id')
        // ->leftJoin('food_carts', function($join) {
        //     $join->on('food_carts.fav_id', 'fav_diets.fav_id');
        // })
        // ->whereNull('food_carts.fav_id')
        ->where('fav_diets.user_id', $id)
        ->get();

        // * 즐겨찾는 식단에 포함된 개별 음식 정보 획득
        $dietfoods = DB::table('food_infos')
        ->select('fav_diet_food.fav_id', 'fav_diet_food.fav_f_intake', 'food_infos.food_name', 'food_infos.kcal', 'food_infos.carbs', 'food_infos.protein', 'food_infos.fat', 'food_infos.sugar', 'food_infos.sodium')
        ->join('fav_diet_food', 'food_infos.food_id', '=', 'fav_diet_food.food_id')
        ->join('fav_diets', 'fav_diets.fav_id', '=', 'fav_diet_food.fav_id')
        ->where('fav_diets.user_id', $id)
        ->get();

        // * 선택된 음식
        $seleted = DB::table('food_carts')
        ->select('food_carts.cart_id', 'food_carts.user_id', 'food_carts.amount', 'food_infos.food_name', 'food_carts.food_id')
        ->join('food_infos', 'food_carts.food_id', 'food_infos.food_id')
        ->where('food_carts.user_id', $id)
        ->where('food_carts.d_flg', $req->time)
        ->where('food_carts.d_date', $req->date)
        ->get();

        // * 선택된 식단
        $seleted_diet = DB::table('food_carts')
        ->select('food_carts.cart_id', 'food_carts.user_id', 'food_carts.fav_id', 'fav_diets.fav_name')
        ->join('fav_diets', 'fav_diets.fav_id', '=', 'food_carts.fav_id')
        ->where('food_carts.user_id', $id)
        ->where('food_carts.d_flg', $req->time)
        ->where('food_carts.d_date', $req->date)
        ->get();

        $data = [
            'date' => $req->date,
            'time' => $req->time
        ];

        // * 음식 검색 결과
        if(!empty($usersearch)){
            $diet = Diet::select('d_id')
            ->where('user_id', $id)
            ->where('d_flg', $req->time)
            ->where('d_date', $req->date)
            ->first();

            $foods = DB::table('food_infos')
            ->select('food_infos.food_id', 'food_infos.user_id', 'food_infos.food_name', 'food_infos.kcal', 'food_infos.carbs', 'food_infos.protein', 'food_infos.fat', 'food_infos.sugar', 'food_infos.sodium')
            ->leftJoin('food_carts', function($join) use($req, $id){
                $join->on('food_infos.food_id', 'food_carts.food_id')
                ->where('food_carts.d_flg', $req->time)
                ->where('food_carts.d_date', $req->date)
                ->where('food_carts.user_id', $id);
            })
            ->whereNull('food_carts.food_id')
            ->when(isset($diet), function($query) use($diet){
                $query->leftJoin('diet_food', function($join) use ($diet){
                    $join->on('food_infos.food_id', 'diet_food.food_id')
                    ->where('diet_food.d_id', $diet->d_id);
                })
                ->whereNull('diet_food.food_id');
            })
            ->where('food_name', 'like', '%'.$usersearch.'%')
            ->where(function($query) use($id){
                $query->where('food_infos.user_id', $id)
                ->orWhere('food_infos.user_id', 0);
            })
            ->whereNull('food_infos.deleted_at')
            ->get();

            return view('FoodList')
            ->with('foods', $foods)
            ->with('dietname', $dietnames)
            ->with('dietfood', $dietfoods)
            ->with('seleted', $seleted)
            ->with('seleted_diet', $seleted_diet)
            ->with('data', $data);
        }

        return view('FoodList')
        ->with('dietname', $dietnames)
        ->with('dietfood', $dietfoods)
        ->with('seleted', $seleted)
        ->with('seleted_diet', $seleted_diet)
        ->with('data', $data);
    }

    // ! 선택한 음식/식단 입력
    public function searchinsert($date, $time) {
        // 유저 id 획득
        $id = Auth::user()->user_id;

        // 세션에 날짜 추가(홈에서 사용하기 때문에 필요)
        Session::put('d_date',$date);

        // 개별 음식 장바구니 정보 획득
        $foodCart = DB::table('food_carts')
            ->select('amount', 'food_id')
            ->where('user_id', $id)
            ->whereNull('fav_id')
            ->get();

        // 장바구니 즐겨찾는 식단 음식 상세 정보 획득
        $favCart = DB::table('food_carts')
            ->select('fav_diet_food.fav_f_intake', 'fav_diet_food.food_id')
            ->join('fav_diet_food', 'fav_diet_food.fav_id', 'food_carts.fav_id')
            ->join('food_infos', 'food_infos.food_id', 'fav_diet_food.food_id')
            ->where('food_carts.user_id', $id)
            ->where('fav_diet_food.fav_id', '>' , 0)
            ->get();

        // 해당 식단 정보 획득
        $selectDiet = Diet::select('d_id')
        ->where('user_id', $id)
        ->where('d_flg', $time)
        ->where('d_date', $date)
        ->first();
        
        if (!isset($selectDiet)) {  // 입력된 식단 정보가 없을 때
            // 새 식단 정보 입력
            $insertDietId = DB::table('diets')->insertGetId([
                'user_id' => $id,
                'd_date' => $date,
                'd_flg' => $time
            ]);
        } else {
            $insertDietId = $selectDiet->d_id;
        }
        
        // 한 유저가 한 식단에 음식 10개 이상 입력 안되게 처리
        $foodCount = DietFood::select('df_if')
        ->where('d_id', $insertDietId)
        ->count();
        
        if( $foodCount + $foodCart->count() + $favCart->count() > 10 ) {
            // 장바구니 삭제 후 홈으로 리턴
            DB::table('food_carts')
                ->where('user_id', $id)
                ->where('d_date', $date)
                ->where('d_flg', $time)
                ->delete();

            Alert::error('10개 초과로 입력할 수 없습니다.', '');
            return redirect()->route('home');
        }

        DB::transaction(function () use ($foodCart, $insertDietId, $favCart, $id, $date, $time) {
            // 검색 음식 입력
            if (!$foodCart->isEmpty()) {
                for ($i=0; $i < $foodCart->count(); $i++) { 
                    DB::table('diet_food')->insert([
                        'food_id'     => $foodCart[$i]->food_id,
                        'd_id'        => $insertDietId,
                        'df_intake'   => $foodCart[$i]->amount,
                    ]);
                }
            }
            
            // 즐겨찾는 식단 입력
            if (!$favCart->isEmpty()) {
                for ($i=0; $i < $favCart->count(); $i++) { 
                    DB::table('diet_food')->insert([
                        'food_id'     => $favCart[$i]->food_id,
                        'd_id'        => $insertDietId,
                        'df_intake'   => $favCart[$i]->fav_f_intake,
                    ]);
                }
            }

            // 음식 장바구니 삭제
            DB::table('food_carts')
                ->where('user_id', $id)
                ->where('d_date', $date)
                ->where('d_flg', $time)
                ->delete();
        });
        
        return redirect()->route('home');

        // * v002 delete : 더이상 쓰지 않는 부분 삭제

        // // 같은 식단에 속한 음식을 배열로 전환
        // $arraydiet = [];
        // foreach ($diet_food as $key) {
        //     $arraydiet[] = [
        //         $key->fav_id,
        //         $key->food_id,
        //         $key->fav_f_intake
        //     ];
        // }

        // // * collection 객체로 반환됨 -> foreach를 통해 2차원 배열로 바꿈]
        // // ? 2차원 배열의 이유 : 1차원 배열으로 할 경우 값이 여러 개 담기지 않고 마지막 값만 배열에 담김
        // foreach ($cart as $key) {
        //     $arr_cart[] = [
        //         $key->food_id, 
        //         $key->amount
        //     ];
        // }

        // // 식단 정보를 배열로 변환
        // $selectD = Diet::select('d_id', 'user_id', 'd_flg', 'd_date')
        // ->where('user_id', $id)
        // ->get();

        // foreach ($selectD as $key) {
        //     $arrayd = [
        //         $key->d_id,
        //         $key->user_id,
        //         $key->d_date,
        //         $key->d_flg
        //     ];
        // }

        // // 하루가 지난 날의 데이터 삭제
        // DB::table('food_carts')->where('created_at', '>', 'now()')->delete();



        // if(empty($arraydiet) && !empty($arrayd)){
        // // ! 음식 입력
        //     if($arrayd[1] === $id && $arrayd[2] === $date && $arrayd[3] === $time){ // 이미 입력한 식단이 있을 때
        //         $sum = $arr_cart[0][1]; // 섭취량
        //         for ($i=0; $i < count($arr_cart); $i++) { 
        //             // 식단 음식 삽입
        //             $insertDF = new DietFood([
        //                 'food_id' => $arr_cart[$i][0],
        //                 'd_id' => $arrayd[0],
        //                 'df_intake' => $sum
        //             ]);
        //             $insertDF->save();
        //         }
        //     }else{ // 입력한 식단이 없을 때
        //         // 식단 번호 입력
        //         $insertD = DB::table('diets')->insertGetId([
        //             'user_id' => $id,
        //             'd_date' => $date,
        //             'd_flg' => $time
        //         ]);

        //         // 입력한 식단 id 획득
        //         $selete_d_id = $insertD;

        //         $sum = $arr_cart[0][1];
        //         // ? 음식 인분 수 계산식
        //         for ($i=0; $i < count($arr_cart); $i++) { 
        //             // ? 데이터베이스 입력
        //             $insertDF = new DietFood([
        //                 'food_id' => $arr_cart[$i][0],
        //                 'd_id' => $selete_d_id,
        //                 'df_intake' => $sum
        //             ]);
        //             $insertDF->save();
        //         }
        //     }
        // } else {
        // // ! 즐겨찾는 식단 입력
        //     if($arr_cart[0][0] === 0 && $arr_cart[0][1] === '0.0'){
        //         if($arrayd[1] === $id && $arrayd[2] === $date && $arrayd[3] === $time){
        //             for($e=0; $e<count($arraydiet); $e++){
        //                 $insertDF = new DietFood([
        //                     'food_id' => $arraydiet[$e][1],
        //                     'd_id' => $arrayd[0],
        //                     'df_intake' => $arraydiet[$e][2]
        //                 ]);
        //                 $insertDF->save();
        //             }
        //         }else{
        //             $insertD = new Diet([
        //                 'user_id' => $id,
        //                 'd_date' => $date,
        //                 'd_flg' => $time
        //             ]);
        //             $insertD->save();

        //             $selectD = Diet::select('d_id')
        //                 ->where('user_id', $id)
        //                 ->where('d_date', $date)
        //                 ->where('d_flg', $time)
        //             ->get();

        //             foreach ($selectD as $key) {
        //                 $selete_d_id = $key->d_id;
        //             }

        //             for($e=0; $e<count($arraydiet); $e++){
        //                 $insertDF = new DietFood([
        //                     'food_id' => $arraydiet[$e][1],
        //                     'd_id' => $selete_d_id,
        //                     'df_intake' => $arraydiet[$e][2]
        //                 ]);
        //                 $insertDF->save();
        //             }
        //         }
        //     }
        // }

        // // 음식 장바구니 삭제
        // DB::table('food_carts')->where('user_id', $id)->delete();

        // return redirect()->route('home');
    }

    // * v002 delete : 더이상 쓰지 않는 함수 삭제
    // // ! 선택한 음식 탭에서 음식 삭제
    // public function fooddelete(Request $req, $f_id, $c_id){
    //     $id = Auth::user()->user_id;

    //     $data = [
    //         'date' => $req->date,
    //         'time' => $req->time
    //     ];

    //     DB::table('food_carts')->where('user_id', $id)->where('food_id', $f_id)->where('cart_id', $c_id)->delete();
    //     return redirect()->route('home');
    //     // return redirect()->route('search.list.get' , ['id', $id])->with($data);
    // }

    // // ! 선택한 음식 탭에서 자주먹는 식단 삭제
    // public function dietdelete(Request $req, $d_id, $c_id){
    //     $id = Auth::user()->user_id;

    //     $data = [
    //         'date' => $req->date,
    //         'time' => $req->time
    //     ];

    //     DB::table('food_carts')->where('user_id', $id)->where('fav_id', $d_id)->where('cart_id', $c_id)->delete();
    //     return redirect()->route('home');
    //     // return redirect()->route('search.list.get', ['id' => $id])->with($data);
    // }
    // * -------------------- v002 delete --------------------

    // ! 취소 버튼 누를 시 임시 저장 데이터베이스 내용 삭제
    public function searchdelete($date, $time) {
        $id = Auth::user()->user_id;

        DB::table('food_carts')
            ->where('user_id', $id)
            ->where('food_carts.d_flg', $time)
            ->where('food_carts.d_date', $date)
            ->delete();
        return redirect()->route('home');
    }
}
