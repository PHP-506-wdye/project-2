{{-- v001 delete code @extends('layout.userdietinfo') --}}
@extends('layout.layout')

@section('title','favdiet')
@section('css')
    <link rel="stylesheet" href="{{asset('css/favdiet.css')}}">
@endsection

@section('contents')


<div class="fav-dietlist">

    {{-- v001 delete code <div></div> --}}
    <div class="favbox">
        <h2 class="fav_title">자주먹는 식단</h2>
        <div class="favmaintitle">
            <button type="button" id="greenBtn" onclick="location.href='{{route('recom.get')}}'">식단 추천</button>
        </div>
        <div class="box1">
            <div class="dietname">
            {{-- v001 delete code : table → ul > li 
                <table> --}}
                {{-- v001 delete code <tr> --}}
                {{-- 아래 구문은 for문을 가지고 컨트롤러의 favname에서 받은 select결과 값의 수만큼을 
                    반복해서 출력하도록 한다. : 지금은 favdiet테이블의 해당 user의 fav_name,fav_id를 user_id컬럼에 있는 유저id 수만큼 가져와서 반복해서 
                    출력 하도록 함(0부터 시작 favname수만큼 하려면 '<'로 표시)--}}
                @for($i = 0; $i < count($favname); $i++)
                    {{-- <th> --}}
                    {{-- 삭제플레그를 위해서 fav_id값을 hidden처리해서 가져옴 --}}
                    {{-- 로그인 계정의 fav_id와 fav_name을 반복해서 출력 fav_name은 a링크로 식단안에 있는 음식정보를 표시 하기 위함 --}}
                    <div>
                        <span hidden>{{$favname[$i]->fav_id}}</span>
                        <a href="{{route('fav.favfoodinfo', ['fav_id' => $favname[$i]->fav_id])}}" class ="favdietname">{{$favname[$i]->fav_name}}</a>
                        {{-- </th> --}}
                        {{-- <td> --}}
                        {{-- favfood - 음식이름도 같은 방식으로 count만큼 반복 출력 --}}
                        @foreach ($favfood[$i] as $item)
                        <span>{{$item->food_name}}</span>
                        @endforeach
                    </div>
                        {{-- </td> --}}
                    {{-- </tr> --}}
                @endfor
                {{-- </table> --}}
            </div>

            <div class="foodnutri">
            {{-- 자주 찾는 식단의 인분(먹은양)수정을 위한 form테그 부분 --}}
                <form action="{{route('fav.intakeupdate')}}" method="post">
                @csrf
            {{-- foodinfo 데이터가 있으면...... --}}
                    @if (isset($foodinfo))
                        @foreach ($foodinfo as $item)
                        {{-- foodinfo데이터에 있는 값들을 배열로 받아와 반복해서 출력. --}}
                        {{-- food_id를 input에 담아서 배열로 보내기 위해서 name을 빈배열식으로 주고 hidden으로 받은 food_id 값을 보내준다. 배열로 되어 있는 이유는 지금 받아오는 값들이 배열형식의 다수의 값을 가져 오는 foreach구문이기 때문에 다수의 값들을 한가지 input에 담아서 배열로 보내주기 위해 food_id[]로 만들어야 한다. 지금 오는 값은 food_id=[123,124,2943]식으로 여러개가 오는중 --}}
                        {{-- 아래 input은 food_id에 해당하는 섭취량 수정을 위해 food_id값들을 배열로 받아와서 그 내용들중 하나를 수정하기 위한 준비를 시키는 구문 food_id는 안에 각 영양정보들이 같이 배열로 들어 있어서 그 값들을 한번에 가져와야 된다. 그리고 나서 바꾸려고 하는 값으(테이블의 컬럼)을 input으로 지정해서 변경 시키도록 한다. >input안에 input을 사용해서 수정하도록함 hidden을 주어서 food_id값을 수정하지 못하도록 만듬 --}}
                        {{-- <div class="detailfood">    --}}
                        <input type="hidden" name="food_id[]" value="{{$item->food_id}}">
                            <span hidden>{{$item->fav_f_id}}</span>
                            <span hidden>{{$item->food_id}}</span>
                        {{-- <table>
                            <tr>
                                <th> --}}
                                    <span class="favfoodname">{{$item->food_name}}</span>
                                {{-- </th>
                                <td> --}}
                                    <br>
                                    <div class="nutdiv">
                                    <span>
                                        {{-- v001 delete code : <i class="bi bi-asterisk"></i> --}}
                                        칼로리 : {{$item->kcal}}
                                    </span>
                                {{-- </td>  
                                <td> --}}
                                    <span>
                                        {{-- <i class="bi bi-asterisk"></i> --}}
                                        탄수화물 : {{$item->carbs}}
                                    </span>
                                {{-- </td>
                                <td> --}}
                                    <span>
                                        {{-- <i class="bi bi-asterisk"></i> --}}
                                        지방 : {{$item->fat}}
                                    </span>
                                {{-- </td> 
                                <td> --}}
                                    <span>
                                        {{-- <i class="bi bi-asterisk"></i> --}}
                                        단백질 : {{$item->protein}}
                                    </span>
                                {{-- </td>             
                                <td> --}}
                                    <span>
                                        당분 : {{$item->sugar}}
                                    </span>
                                {{-- </td>                                                  
                                <td> --}}
                                    <span>
                                        나트륨 : {{$item->sodium}}
                                    </span>
                                </div>
                                    {{-- <br> --}}
                                    {{-- </td>--}}
                                    <label for="intake">
                                    {{-- <td> --}}
                                    {{-- 인분수 수정값을 입력하기 위한 구문 intake[]를 배열로 해서 여러 값들이 한번에 들어 갈수 있도록 함. 기존 섭취 량을 받아오기 위해서{{$item->fav_f_intake}} 를 사용하고 required를 주어서 필수 입력값으로 설정  --}}
                                    <input type="number" name="intake[]" id="intake" min="0" value={{$item->fav_f_intake}}  required>인분</label>
                                    {{-- </td> --}}
                                    {{-- <td> --}}
                                    <span class="del-food" id="del-food">
                                        <a href="{{route('fav.del', $item->fav_f_id)}}" class="del-food">
                                            <i class="fa-regular fa-circle-xmark"></i>
                                            {{-- X --}}
                                        </a>
                                        <p class="arrow_box">등록하신 음식이 삭제 됩니다.</p>
                                    </span>
                                    <br><br>
                                    {{-- </td> --}}
                                {{-- </tr> --}}
                        {{-- </table> --}}
                        {{-- </div>  --}}
                        @endforeach
                        <div class="submitbtn">
                            <button type="button" onclick="location.href='{{route('fav.delete', $id)}}'">삭  제</button>
                            <button type="submit" id="greenBtn">수  정</button>
                        </div>
                    @endif
                    </div>
                </div>    
                    
                </form>
                {{-- form문 끝부분! --}}
        {{-- <div></div> --}}
    </div>

@endsection

