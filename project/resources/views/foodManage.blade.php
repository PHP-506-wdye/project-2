@extends('layout.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/board.css') }}">  
@endsection

@section('title', '등록한 음식')

@section('contents')
    <div class="foodShadow">
        <div></div>
        <div class="shadowYellow">
            <div class="foodManage">
                <div class="foodInsertTitleGrid">
                    <h2>등록한 음식</h2>
                    <div>
                        <button type="button" id="greenBtn" onclick="location.href='{{route('food.create')}}'">음식 추가</button>
                    </div>
                </div>
                <div class = "foodInsertGrid">
                    <div class="shadowWhite">
                        <ul class="nav flex-column">
                            @forelse ($data as $item)
                                <li class="nav-item">
                                    <a href="{{route('food.show', ['food' => $item->food_id])}}">{{$item->food_name}}</a>
                                </li>
                                <li>
                                    @if (isset($error))
                                        {{$error}}
                                    @endif
                                </li>
                            @empty
                                <li>등록된 음식이 없습니다.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="foodUpdateForm">
                    @if (isset($food))
                        <form action="{{route('food.update', ['food' => $food->food_id])}}" method="post">
                            @csrf
                            @method('put')
                            <div id="foodNameGrid">
                                <h5>음식명</h5>
                                <input type="text" name="foodName" value="{{$food->food_name}}" autocomplete="off" autofocus>
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('foodName', ':message') : ''}}</div>
                            </div>
                            <div>
                                <h5>영양 성분</h5>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 칼로리(Kcal)</label>
                                <input type="text" id="kcal" name="kcal" value="{{$food->kcal}}" required autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('kcal', ':message') : ''}}</div>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 탄수화물(Carbs)</label>
                                <input type="text" id="carbs" name="carbs" value="{{$food->carbs}}" required autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('carbs', ':message') : ''}}</div>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 단백질(Protein)</label>
                                <input type="text" id="protein" name="protein" value="{{$food->protein}}" required autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('protein', ':message') : ''}}</div>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 지방(Fat)</label>
                                <input type="text" id="fat" name="fat" value="{{$food->fat}}" required autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('fat', ':message') : ''}}</div>
                                <label for="kcal">　당(Sugar)</label>
                                <input type="text" id="sugar" name="sugar" value="{{$food->sugar}}" autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('sugar', ':message') : ''}}</div>
                                <label for="kcal">　나트륨(Sodium)</label>
                                <input type="text" id="sodium" name="sodium" value="{{$food->sodium}}" autocomplete="off">
                                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('sodium', ':message') : ''}}</div>
                            </div>
                            <div>
                                <h5>1회 제공량</h5>
                                <div class="inputRadioBtnDiv">
                                    <input type="text" name="serving" value="{{$food->serving}}">
                                    <div class="form_toggle row-vh d-flex flex-row" >
                                        @if ($food->ser_unit === '0')
                                            <div class="form_radio_btn">
                                                <input id="radio-1" type="radio" name="ser_unit" value="0" checked>
                                                <label for="radio-1">g</label>
                                            </div>
                                            <div class="form_radio_btn">
                                                <input id="radio-2" type="radio" name="ser_unit" value="1">
                                                <label for="radio-2">ml</label>
                                            </div>
                                        @else
                                            <div class="form_radio_btn">
                                                <input id="radio-1" type="radio" name="ser_unit" value="0">
                                                <label for="radio-1">g</label>
                                            </div>
                                            <div class="form_radio_btn">
                                                <input id="radio-2" type="radio" name="ser_unit" value="1" checked>
                                                <label for="radio-2">ml</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="rigtTwoBtnDiv">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    삭제
                                </button>
                                <button type="submit" id="greenBtn">수정</button>
                            </div>
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">등록 음식 삭제</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    정말 삭제하시겠습니까?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-bs-dismiss="modal">취소</button>
                                        <form action="{{route('food.destroy', ['food' => $food->food_id])}}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" id="greenBtn">삭제
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- <form>
                            <div id="foodNameGrid">
                                <h5>음식명</h5>
                            </div>
                            <div>
                                <h5>영양 성분</h5>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 칼로리(Kcal)</label>
                                <input type="text" id="kcal" name="kcal" disabled>
                                <br>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 탄수화물(Carbs)</label>
                                <input type="text" id="carbs" name="carbs" disabled>
                                <br>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 단백질(Protein)</label>
                                <input type="text" id="protein" name="protein" disabled>
                                <br>
                                <label for="kcal"><i class="bi bi-asterisk"></i> 지방(Fat)</label>
                                <input type="text" id="fat" name="fat" disabled>
                                <br>
                                <label for="kcal">　당(Sugar)</label>
                                <input type="text" id="sugar" name="sugar" disabled>
                                <br>
                                <label for="kcal">　나트륨(Sodium)</label>
                                <input type="text" id="sodium" name="sodium" disabled>
                            </div>
                            <div>
                                <h5>1회 제공량</h5>
                                <div class="inputRadioBtnDiv">
                                    <input type="text" name="serving">
                                    <div class="form_toggle row-vh d-flex flex-row" >
                                        <div class="form_radio_btn">
                                            <input id="radio-1" type="radio" name="ser_unit" value="0" checked>
                                            <label for="radio-1">g</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input id="radio-2" type="radio" name="ser_unit" value="1">
                                            <label for="radio-2">ml</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form> --}}
                        <div></div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (session('flg') === 1)
            alert('수정 완료')
        @elseif (session('flg') === 2)
            alert('10개 이상 등록 불가')
        @elseif (session('flg') === 3)
            alert('저장 완료')
        @endif
    </script>
@endsection

@section('js')
    <script src="{{ asset('js/foodManage.js') }}"></script>
@endsection