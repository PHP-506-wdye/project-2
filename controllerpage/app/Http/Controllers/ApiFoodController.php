<?php
/*****************************************************
 * 프로젝트명   : project-2
 * 디렉토리     : Controllers
 * 파일명       : ApiFoodcontroller.php
 * 이력         : v001 0717 BJ.Kwon new
 *****************************************************/
namespace App\Http\Controllers;

use App\Models\FoodInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ApiFoodController extends Controller
{
    public function userfoodDel($id){
        
        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        // FoodInfo::destroy($id);

        $foodinfo = FoodInfo::find($id);

        if(!$foodinfo){
            $arr['errorcode'] = '1';
            $arr['msg'] = '데이터없음';
        }else{
            $arr['errorcode'] = '0';
            $arr['msg'] = '성공';

            $foodinfo->deleted_at = now();
            $foodinfo->save();
        }
        
        return $arr;
    }

    public function foodinsert(Request $req){

        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        //유효성 검사
        $rules = [
            'food_name'     => 'required|min:2|max:20|regex:/^[가-힣0-9]+$/'
            ,'serving'      => 'required|integer|min:1|max:1000'
            ,'ser_unit'     => 'required'
            ,'kcal'         => 'required|integer|min:0|max:10000'
            ,'carbs'        => 'required|integer|min:0|max:10000'
            ,'protein'      => 'required|integer|min:0|max:10000'
            ,'fat'          => 'required|integer|min:0|max:10000'
            ,'sugar'        => 'integer|max:10000|nullable'
            ,'sodium'       => 'integer|max:10000|nullable'
        ];

        $messages = [
            'food_name'         => '음식명은 2~20자 한글과 숫자만 입력 가능합니다.',
            'serving'           => '제공량은 필수 입력 항목입니다.',
            'ser_unit'          => 'g, ml을 선택해주세요.',
            'kcal'              => '칼로리는 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'carbs'             => '탄수화물은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'protein'           => '단백질은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'fat'               => '지방은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'sugar'             => '당은 0~10000 사이의 숫자로 입력해주세요',
            'sodium'            => '나트륨은 0~10000 사이의 숫자로 입력해주세요',
        ];

        $validator = Validator::make($req->only(
            'food_name'
            ,'kcal'
            ,'carbs'
            ,'protein'
            ,'fat'
            ,'sugar'
            ,'sodium'
            ,'ser_unit'
            ,'serving'
        ), $rules, $messages);

        // 관리자 id와 userfood_flg
        $id = 0;

        // 같은 이름으로 등록 불가능(삭제된 음식일 경우는 등록가능)
        // $foods = FoodInfo::where('user_id', $id)->where('userfood_flg', $id)
        // ->get();
        $foods = FoodInfo::where('user_id', $id)
                ->where('userfood_flg', $id)
                ->whereNull('deleted_at')
                ->get();

        $dupliCheck = false;

        foreach ($foods as $val) {
            if ($val->food_name === $req->food_name) {
                // return back()
                //     ->withErrors(['food_name' => '이미 등록된 이름입니다.'])
                //     ->withInput();
                $dupliCheck = true;
            }
        }

        if ($validator->fails()) {
            // 유효성 검사에 걸린 항목과 해당 에러 메시지를 가져옵니다.
            $errors = $validator->errors()->all();
            $arr['errorcode'] = '2';
            $arr['msg'] = $errors;
        }
        else{
            if($dupliCheck){
                $arr['errorcode'] = '1';
                $arr['msg'] = '이미 등록된 이름입니다.';
                $arr['data'] = $foods;
            }
            else{
                $result = new FoodInfo([
                    'user_id'       => $id
                    ,'food_name'    => $req->food_name
                    ,'kcal'         => $req->kcal
                    ,'carbs'        => $req->carbs
                    ,'protein'      => $req->protein
                    ,'fat'          => $req->fat
                    ,'sugar'        => $req->sugar ?? 0
                    ,'sodium'       => $req->sodium ?? 0
                    ,'serving'      => $req->serving
                    ,'ser_unit'     => $req->ser_unit
                    ,'userfood_flg' => $req->userfood_flg
                    ,'created_at'   => now()
                ]);
        
                $result->save();
                
                $arr['errorcode'] = '0';
                $arr['msg'] = '성공';
                $arr['data'] = $result;
            }
        }

        return $arr;
    }

    public function foodedit(Request $req, $id){

        $arr = [
            'errorcode' => '0'
            ,'msg'      => ''
        ];

        //유효성 검사
        $rules = [
            'food_name'     => 'required|min:2|max:20|regex:/^[가-힣0-9]+$/'
            ,'serving'      => 'required|integer|min:1|max:1000'
            ,'ser_unit'     => 'required'
            ,'kcal'         => 'required|integer|min:0|max:10000'
            ,'carbs'        => 'required|integer|min:0|max:10000'
            ,'protein'      => 'required|integer|min:0|max:10000'
            ,'fat'          => 'required|integer|min:0|max:10000'
            ,'sugar'        => 'integer|max:10000|nullable'
            ,'sodium'       => 'integer|max:10000|nullable'
        ];

        $messages = [
            'food_name'         => '음식명은 2~20자 한글과 숫자만 입력 가능합니다.',
            'serving'           => '제공량은 필수 입력 항목입니다.',
            'ser_unit'          => 'g, ml을 선택해주세요.',
            'kcal'              => '칼로리는 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'carbs'             => '탄수화물은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'protein'           => '단백질은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'fat'               => '지방은 필수 입력사항입니다. 0~10000 사이 숫자를 입력해주세요',
            'sugar'             => '당은 0~10000 사이의 숫자로 입력해주세요',
            'sodium'            => '나트륨은 0~10000 사이의 숫자로 입력해주세요',
        ];

        $validator = Validator::make($req->only(
            'food_name'
            ,'kcal'
            ,'carbs'
            ,'protein'
            ,'fat'
            ,'sugar'
            ,'sodium'
            ,'ser_unit'
            ,'serving'
        ), $rules, $messages);

        // 관리자 id와 userfood_flg
        $user_id = 0;

        // $id = food_id 
        $foodinfo = FoodInfo::find($id);

        // 같은 이름으로 등록 불가능
        $foods = FoodInfo::where('user_id', $user_id)->where('userfood_flg', $user_id)->whereNull('deleted_at')
        ->get();


        // 음식이름 중복체크 중복일 경우 duplicheck의 값 변경
        $dupliCheck = false;

        // 수정하기전 갖고있던 기존의 이름과 같지 않은경우에만 중복체크
        if($foodinfo->food_name !== $req->input('food_name')){
            foreach ($foods as $val) {
                if ($val->food_name === $req->input('food_name')) {
                    $dupliCheck = true;
                }
            }
        }

        if ($validator->fails()) {
            // 유효성 검사에 걸린 항목과 해당 에러 메시지를 가져옵니다.
            $errors = $validator->errors()->all();
            $arr['errorcode'] = '2';
            $arr['msg'] = $errors;
        }
        else{
            if($dupliCheck){
                $arr['errorcode'] = '1';
                $arr['msg'] = '이미 등록된 이름입니다.';
            }
            else{
        
                // 관리자음식 수정
                $foodinfo->food_name = $req->input('food_name');
                $foodinfo->kcal = $req->input('kcal');
                $foodinfo->carbs = $req->input('carbs');
                $foodinfo->protein = $req->input('protein');
                $foodinfo->fat = $req->input('fat');
                $foodinfo->sugar = $req->input('sugar') ?? 0;
                $foodinfo->sodium = $req->input('sodium') ?? 0;
                $foodinfo->ser_unit = $req->input('ser_unit');
                $foodinfo->serving = $req->input('serving');
                $foodinfo->updated_at = now();

                $foodinfo->save();
                
                $arr['errorcode'] = '0';
                $arr['msg'] = '성공';
                $arr['data'] = $foodinfo;
            }
        }

        return $arr;
    }
}
