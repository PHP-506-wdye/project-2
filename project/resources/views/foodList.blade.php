@extends('layout.layout')

@section('title', 'FoodSearch')

@section('css')
    <link rel="stylesheet" href="{{asset('css/search.css')}}">
@endsection

@section('contents')
    <form action="{{route('search.list.get', ['id' => Auth::user()->user_id])}}" method="get" class="searchform">
        @csrf
        <input type="text" name="search_input">
        <button type="submit">검색</button>
    </form>

    <ul>
        <li class="tab1">
            저장된 식단
        </li>
        <li class="tab2">
            선택된 음식
        </li>
        <li class="tab3" onclick="location.href='{{route('food.create')}}'">
            음식등록
        </li>
    </ul>

    <div>
        <div id='result'></div>
        @if (!empty($foods))
            <div class="user_search">
                @foreach ($foods as $item)
                    <input type="checkbox" name="usercheck" id="usercheck" value="{{$item->food_id}}" onclick='getCheckboxValue()'>
                    <span>{{$item->food_name}}</span>
                    <br>
                @endforeach
            </div>
        @else
            <p class="nosearch">검색어</p>
        @endif
        
        @if (!empty($fav_diets))
            <div id="fav_diets" class="d-none">
                <h2>자주먹는 식단</h2>
                @foreach ($diets as $diet)
                    <input type="checkbox" name="usercheck" id="usercheck" value="{{$diet->fav_id}}" onclick='getCheckboxValue()'>
                    <span> {{$diet->fav_name}}</span>
                    <br>
                @endforeach
            </div>
        @endif
        @if (!empty($select_food))
            <div id="user_select" class="d-none">
                <h2>user-select</h2>
                
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/search.js')}}"></script>
@endsection