@extends('layout.layout')

@section('title', '식단입력')

@section('css')
    <link rel="stylesheet" href="{{asset('css/search.css')}}">
@endsection

@section('contents')
<div class="searchContainer">
    <div class="search_div">
        <form action="{{route('search.list.get', ['id' => Auth::user()->user_id])}}" method="post" class="searchform">
            @csrf
            <div class="searchdiv">
                <input type="hidden" name="date" id="date" value="{{$data['date']}}">
                <input type="hidden" name="time" id="timeFlg" value="{{$data['time']}}">
                <input type="text" name="search_input" class="searchip" placeholder="검색할 단어를 입력하세요." value="{{ old('search_input')}}">
                <button type="submit" class="searchbtn"><i class="fa-solid fa-magnifying-glass" style="color: #000000;"></i></button>
            </div>
        </form>

        <div class="searchtab">
            <div class="tab1">
                <i class="fa-solid fa-utensils" style="color: #ee6666;"></i>
                자주먹는 식단
            </div>
            <div class="tab2">
                <i class="fa-solid fa-cart-shopping" style="color: #538e04;"></i>
                선택된 음식
                <span id="selectedFood">
                    @if (count($seleted) > 0 || count($seleted_diet) > 0)
                        new
                    @endif
                </span>
            </div>
            <div class="tab3" onclick="location.href='{{route('food.index')}}'">
                <i class="fa-solid fa-mortar-pestle" style="color: #6799e4;"></i>
                등록 음식 관리
            </div>
        </div>
    </div>
    <div class="searchTabContainer">
        {{-- <hr> --}}
        @if (!empty($foods))
        <div class="user_search">
            @forelse ($foods as $item)
                <div class="search_result">
                    <span id="food_name">{{$item->food_name}}</span>
                    @if (!empty($item->user_id))
                        <span class="usercreate">사용자 등록</span>
                    @endif
                    <br>
                    <span>인분 수</span>
                    <input type="number" name="userving" id="userving" min="0.5" step="0.5" max="100" value="1">
                    <input type="checkbox" name="usercheck" id="usercheck" value="{{$item->food_id}}" onclick='getFoodValue(event, {{Auth::user()->user_id}})'>
                    <br>
                    <div class="searchInfo">
                        <span>칼로리 : {{$item->kcal}}</span>
                        <span>탄수화물 : {{$item->carbs}}</span>
                        <span>단백질 : {{$item->protein}}</span>
                        <span>지방 : {{$item->fat}}</span>
                        <span>당 : {{$item->sugar}}</span>
                        <span>나트륨 : {{$item->sodium}}</span>
                    </div>
                    <hr class="search_hr">
                </div>
            @empty
                <div>검색한 음식이 없습니다.</div>
                <div>등록 음식 관리를 클릭해 음식을 입력하세요.</div>
            @endforelse
        </div>
        @else
            <div class="nosearch"></div>
        @endif
        
        <div class="fav_diets">
        @if (!empty($dietname))
            <h3>자주먹는 식단</h3>
            <div class="fav_scroll" id="fav_scroll">
            @forelse ($dietname as $names)
            <div
                id="{{'favId-'.$names->fav_id}}"
                @foreach ($seleted_diet as $diet)
                    @if($names->fav_id === $diet->fav_id)
                        style="display: none"
                    @endif
                @endforeach
            >
                <input type="checkbox" name="userdiet" id="{{'input-'.$names->fav_id}}" onclick='getDietValue(event, {{Auth::user()->user_id}}, {{$names->fav_id}})'>
                <span class="favname"> {{$names->fav_name}} </span>
                <div class="diet_div">
                    @foreach ($dietfood as $foods)
                        @if($foods->fav_id === $names->fav_id)
                            <div class="dietinfo">
                                <div> {{$foods->food_name}}</div>
                                <div class="dietinfonotname">
                                    <strong>영양성분</strong>
                                    <span> > </span>
                                    <span>칼로리 : {{$foods->kcal}}, </span>
                                    <span>탄수화물 : {{$foods->carbs}}, </span>
                                    <span>단백질 : {{$foods->protein}}, </span>
                                    <span>지방 : {{$foods->fat}}, </span>
                                    <span>당 : {{$foods->sugar}}, </span>
                                    <span>나트륨 : {{$foods->sodium}}</span>
                                    <span> > {{$foods->fav_f_intake}} 인분</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <br>
            </div>
            @endforeach
        </div>
        @else
            <p>자주먹는 식단이 없습니다.</p>
        @endif
        </div>
            
        <div class="user_select">
            @if (!empty($seleted))
                <h3>선택된 음식 / 식단</h3>
                <br>
                <h4>음식</h4>
                {{-- <hr class="select_food"> --}}
                <div id="fav_food">
                    <input type="hidden" name="date" value="{{$data['date']}}">
                    <input type="hidden" name="time" value="{{$data['time']}}">
                    @foreach ($seleted as $food)
                        <div>
                            <span>{{$food->food_name}}</span>
                            <span>{{$food->amount}} 인분</span>
                            <button type="button" id="delete_btn" onclick="deletefood({{Auth::user()->user_id.','.$food->food_id.','.$food->cart_id}})">X</button>
                        </div>
                    @endforeach
                </div>
                <br>
                <h4>식단</h4>
                {{-- <hr class="select_diet"> --}}
                <div class="fav_diet" id="fav_diet">
                    @foreach ($seleted_diet as $diet)
                        @csrf
                        <div>
                            <span>{{$diet->fav_name}}</span>
                            <input type="hidden" name="date" value="{{$data['date']}}">
                            <input type="hidden" name="time" value="{{$data['time']}}">
                            <button id="delete_btn" type="button" onclick="deletediet({{Auth::user()->user_id . ',' . $diet->cart_id . ',' . $diet->fav_id}})">X</button>
                        </div>
                    @endforeach
                </div>
                <br>
                <br>
                @else
                    <h3>선택한 음식 / 식단</h3>
                    <br>
                    <h4>음식</h4>
                    <hr class="select_food">
                        <div id="fav_food">
                        </div>
                    <br>
                    <h4>식단</h4>
                    <hr class="select_diet">
                        <div id="fav_diet">
                        </div>
                @endif
            <div class="btn">
                <form action="{{route('search.delete', ['date' => $data['date'], 'time' => $data['time']])}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit">취소</button>
                </form>
                <button type="button" id="greenBtn" onclick="location.href='{{route('search.insert', 
                ['date' => $data['date'], 'time' => $data['time']])}}'">입력</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://kit.fontawesome.com/8c69259d3d.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{asset('js/search.js')}}"></script>
@endsection