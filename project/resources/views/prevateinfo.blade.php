@extends('layout.userinfoNav')

@section('title', 'prevateinfo')

@section('prevateinfocontents')
<div class="col-md-8 offset-lg-1 pb-5 mb-lg-2 mb-lg-4 pt-md-5 mt-n3 mt-md-0">
    <form action="{{route('user.userKcaledit')}}" method="post">
        @csrf
        <div class="shadowYellow">
            <div>
                <h1 class="h2 pt-xl-1 pb-3">Diet Setting</h1>
            <table>
                <tr><th class="diechk-td" rowspan="5"><label for="nutrition_ratio">Diet Sellect</label></th></tr>
                <tr><td colspan="2"><input type="radio" name="nutrition_ratio" id="nutrition_ratio" value="0" required <?php echo  isset($selectedValue) && $selectedValue === '0' ? 'checked' : ''; ?>> 일반식단(탄수화물 50, 단백질 30, 지방 20)</td></tr>
                <tr><td colspan="2"><input type="radio" name="nutrition_ratio" id="nutrition_ratio" value="1" required <?php echo isset($selectedValue) && $selectedValue === '1' ? 'checked' : ''; ?>> 운동식단(탄수화물 40, 단백질 40, 지방 20)</td></tr>
                <tr><td colspan="2"><input type="radio" name="nutrition_ratio" id="nutrition_ratio" value="2" required <?php echo isset($selectedValue) && $selectedValue === '2' ? 'checked' : ''; ?>> 키토식단(탄수화물 8, 단백질 22, 지방 70)</td></tr>
                <tr><td colspan="2"><input type="radio" name="nutrition_ratio" id="nutrition_ratio" value="3" required <?php echo isset($selectedValue) && $selectedValue === '3' ? 'checked' : ''; ?>> 비건식단(탄수화물 50, 단백질 30, 지방 20)</td></tr>
                    <th class="diechk-td">
                        <label for="goal_kcal">Goal Kcal</label>
                    </th>
                    <td colspan="2" class="goal_kcal_input">
                        <input type="number" name="goal_kcal" id="goal_kcal" placeholder="목표칼로리를 입력해 주세요" step="1" min="0" max="99999" value ="{{old('goal_kcal') !== null ? old('goal_kcal') : $data->goal_kcal}}" required>Kcal
                    </td>
                        <td>
                        {{-- <div class="hover-btn">
                            <span> 
                                <div class="button btn-stlye">   
                                    <button type="button" id="suggest"  class="greenBtn">추천받기</button>
                                </div>
                            </span>
                            <p class="arrow_box">추천이 필요 하시면 아래 정보를 입력해주세요</p>
                        </div>  --}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <div class="d-flex mb-3">
            <button type="button" id="suggest"  class="btn greenBtn me-3">추천받기
            <span class="arrow_box">칼로리 추천이 필요 하시면 아래 정보를 입력해주세요</span>
            </button>
            <button type ="submit" id="modal" class="btn greenBtn">설정완료</button>
    </div> 

    <div class="modal_wrap">
        <div class="shadowYellow">
            <table>
                <tr>
                    <td><label for="gender">성별</label></td>
                    <td>
                        @if($data->user_gen == 1)
                            <input type="radio" name="gender" id="gender" value="0" class="genman" readonly>남자<img src="{{ asset('img/manmark.png') }}">
                            <input type="radio" name="gender" id="gender" value="1" class="genwoman" readonly checked>여자<img src="{{asset('img/womanmark.png')}}">
                        @else
                            <input type="radio" name="gender" id="gender" value="0" class="genman" readonly checked>남자<img src="{{ asset('img/manmark.png') }}">
                            <input type="radio" name="gender" id="gender" value="1" class="genwoman" readonly>여자<img src="{{asset('img/womanmark.png')}}">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><label for="user_birth">나이</label></td>
                    <td><input type="date" name="user_birth" id="user_birth" max="{{ now()->toDateString() }}" value="{{$data->user_birth}}">
                        <span id="maxdate"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="tall">키(cm)</label></td>
                    <td><input type="number" name="tall" id="tall" value="{{$data->user_tall}}"></td>
                </tr>
                <tr>
                    <td><label for="weight">몸무게(Kg)</label></td>
                    <td><input type="number" name="weight" id="weight" value="{{$data->user_weight}}"></td>
                </tr>
                <tr>
                    <div class="form_radio_btn">
                        
                        <td><label for="acctivaty">활동량</label></td>
                        <td>    
                            <input type="radio" name="acctivaty" id="acctivaty0" value="0">적음
                            <input type="radio" name="acctivaty" id="acctivaty1" value="1">보통
                            <input type="radio" name="acctivaty" id="acctivaty2" value="2">많음
                        </td>
                    </div>
                </tr>
                <tr>
                    <td><label for="calcul-kcal">추천칼로리  </label></td>
                    <td><input type="text" name="calcul-kcal" id="calcul-kcal" placeholder="추천 칼로리" min="0" step="1">Kcal</td>
                </tr>
                <tr> 
                    {{-- <td><div><button type="button" id="suggest-cal" class="greenBtn">계산</button></div></td>
                    <td><div><button type="button" id="suggest-insert" class="greenBtn">적용</button></div></td> --}}
                </tr>    
            </table>
                <div class="exit">X</div>
            </div>
        </form>
        <div class="d-flex mb-3">
            <button type="button" id="suggest-cal" class="btn greenBtn me-3">계 산</button>
            <button type="button" id="suggest-insert" class="btn greenBtn">적 용</button>
        </div>  
    </div>
</div>

@endsection

@section('subjs')
    <script src="{{ asset('js/suggestkcal.js') }}"></script>
@endsection


