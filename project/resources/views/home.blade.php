@extends('layout.layout')

@section('title')
{{Auth::user()->user_name}}'s HOME
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('css/home.css')}}">
@endsection

@section('contents')
<div id="wrap">
    <div id="dateArea">
        <div>
            <button id="prevBtn" onclick="prev();">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" fill="#538E04"
                    class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                </svg>
            </button>
        </div>
        <div class="dateBox">
            <form action="{{route('home.post')}}" method="post" id="dateForm">
                @csrf
                <input name="getDate" id="calendar" type="date" data-placeholder="" required value="{{$data['date'] ?? $data['today']}}" oninput="test();">
                <button type="submit" class="btnYg">이동</button>
            </form>
        </div>
        <div>
            <button id="nextBtn" onclick="next();">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" fill="#538E04" class="bi bi-caret-right-fill"
                    viewBox="0 0 16 16">
                    <path
                        d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                </svg>
            </button>
        </div>
    </div>    
    <hr class="fc-green">

    {{-- 통게 정보 --}}
    <div id="myDiet">
        <div class="diet1">
            <div class="box1 mt-4">
                <canvas id="doughnut-chart" style="max-width: 400px; margin:0 auto;"></canvas>
            </div>
            {{-- 먹은거 없을 때 오류 방지 --}}
            <div class="box2">
                @if($data['total']['tdgTotal'] == 0)
                    <div class="percent pt-3">
                        @if($data['userKcal']->goal_kcal === 0)
                            <p class="text-center pt-md-4 pt-xl-5 pb-md-2 pb-xl-4 fw-bold">목표칼로리를 설정해주세요</p>
                        @else
                            <h5 class="text-center pt-md-4 pt-xl-5 pb-md-2 pb-xl-4 fw-bold mt-3">영양비율</h5>
                            <p class="ps-md-3 ps-xl-4 ps-xxl-5 mt-4">
                                <span class="fc-green">●</span>
                                칼로리 0 %
                            </p>
                        @endif
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-pink">●</span>
                            탄수화물 0 %
                        </p>
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-yel">●</span>
                            단백질 0 %
                        </p>
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-blue">●</span>
                            지방 0 %
                        </p>
                    </div>
                @else
                {{-- 탄, 단, 지 비율 계산 --}}
                    <div class="percent pt-3">
                        {{-- 목표 칼로리가 0(=기본값일 경우) --}}
                        @if($data['userKcal']->goal_kcal === 0)
                            <p class="text-center pt-md-4 pt-xl-5 pb-md-2 pb-xl-4 fw-bold">목표칼로리를 설정해주세요</p>
                        @else
                            <h5 class="text-center pt-md-4 pt-xl-5 pb-md-2 pb-xl-4 fw-bold">영양비율</h5>
                            <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                                <span class="fc-green">●</span> 칼로리
                                {{(round(($data['total']['kcalTotal']*100)/($data['userKcal']->goal_kcal)))}} %
                            </p>
                        @endif
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-pink">●</span> 탄수화물
                            {{(round(($data['total']['carbTotal']*100)/($data['total']['tdgTotal'])))}} %
                        </p>
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-yel">●</span> 단백질
                            {{round(($data['total']['proteinTotal']*100)/($data['total']['tdgTotal']))}} %
                        </p>
                        <p class="ps-md-3 ps-xl-4 ps-xxl-5">
                            <span class="fc-blue">●</span> 지방
                            {{round(($data['total']['fatTotal']*100)/($data['total']['tdgTotal']))}} %
                        </p>
                    </div>
                @endif
            </div>
        </div>
        <div class="diet2 container text-center">
            <h3 class="fw-bold mt-xl-4">{{Auth::user()->user_name}}님의 식단</h3>
            <div class="box3 row row-cols-1 row-cols-xl-3">
                {{-- 일반식 또는 비건식 : 플래그 0번 또는 3번 --}}
                @if((($data['userKcal']->nutrition_ratio)=='0') || (($data['userKcal']->nutrition_ratio)=='3'))
                    <div class="col">
                        순탄수<br>
                        <progress id="carbPro" value="{{$data['total']['carbTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.5)/4)}}"></progress><br>
                        <span class="carbSpan">{{$data['total']['carbTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.5)/4)}}g
                    </div>
                    <div class="col">
                        단백질<br>
                        <progress id="proteinPro" value="{{$data['total']['proteinTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.3)/4)}}"></progress><br>
                        <span class="proteinSpan">{{$data['total']['proteinTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.3)/4)}}g
                    </div>
                    <div class="col">
                        지방<br>
                        <progress id="fatPro" value="{{$data['total']['fatTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.2)/9)}}"></progress><br>
                        <span class="fatSpan">{{$data['total']['fatTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.2)/9)}}g
                    </div>
                    
                {{-- 운동식 : 플래그 1번--}}
                @elseif(($data['userKcal']->nutrition_ratio)=='1')
                    <div class="col">
                        순탄수<br>
                        <progress id="carbPro" value="{{$data['total']['carbTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.4)/4)}}"></progress><br>
                        <span class="carbSpan">{{$data['total']['carbTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.4)/4)}}g
                    </div>
                    <div class="col">
                        단백질<br>
                        <progress id="proteinPro" value="{{$data['total']['proteinTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.4)/4)}}"></progress><br>
                        <span class="proteinSpan">{{$data['total']['proteinTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.4)/4)}}g
                    </div>
                    <div class="col">
                        지방<br>
                        <progress id="fatPro" value="{{$data['total']['fatTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.2)/9)}}"></progress><br>
                        <span class="fatSpan">{{$data['total']['fatTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.2)/9)}}g
                    </div>
                    
                {{-- 키토식 : 플래그 2번 --}}
                @elseif(($data['userKcal']->nutrition_ratio)=='2')
                    <div class="col">
                        순탄수<br>
                        <progress id="carbPro" value="{{$data['total']['carbTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.08)/4)}}"></progress><br>
                        <span class="carbSpan">{{$data['total']['carbTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.08)/4)}}g
                    </div>
                    <div class="col"> 
                        단백질<br>
                        <progress id="proteinPro" value="{{$data['total']['proteinTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.22)/4)}}"></progress><br>
                        <span class="proteinSpan">{{$data['total']['proteinTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.22)/4)}}g
                    </div>
                    <div  class="col">
                        지방<br>
                        <progress id="fatPro" value="{{$data['total']['fatTotal']}}" min="0" max="{{round((($data['userKcal']->goal_kcal)*0.7)/9)}}"></progress><br>
                        <span class="fatSpan">{{$data['total']['fatTotal']}}</span> / {{round((($data['userKcal']->goal_kcal)*0.7)/9)}}g
                    </div>
                @endif
            </div>
            <div class="box4 row row-cols-1 row-cols-md-2 row-cols-xxl-2 my-2 my-xl-4">
                {{-- 하루동안 섭취한 칼로리 출력 --}}
                <div class="todayKcal col pt-3">
                    <p>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="1.2em" viewBox="0 0 448 512" fill="#538e04"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 0C400 0 288 32 288 176V288c0 35.3 28.7 64 64 64h32V480c0 17.7 14.3 32 32 32s32-14.3 32-32V352 240 32c0-17.7-14.3-32-32-32zM64 16C64 7.8 57.9 1 49.7 .1S34.2 4.6 32.4 12.5L2.1 148.8C.7 155.1 0 161.5 0 167.9c0 45.9 35.1 83.6 80 87.7V480c0 17.7 14.3 32 32 32s32-14.3 32-32V255.6c44.9-4.1 80-41.8 80-87.7c0-6.4-.7-12.8-2.1-19.1L191.6 12.5c-1.8-8-9.3-13.3-17.4-12.4S160 7.8 160 16V150.2c0 5.4-4.4 9.8-9.8 9.8c-5.1 0-9.3-3.9-9.8-9L127.9 14.6C127.2 6.3 120.3 0 112 0s-15.2 6.3-15.9 14.6L83.7 151c-.5 5.1-4.7 9-9.8 9c-5.4 0-9.8-4.4-9.8-9.8V16zm48.3 152l-.3 0-.3 0 .3-.7 .3 .7z"/></svg>
                        </span>
                        &nbsp;총&nbsp;<span class="fc-green fw-bold">{{$data['total']['kcalTotal']}}</span> Kcal 먹었어요.
                    </p>                    
                    @if ($data['userKcal']->goal_kcal < $data['total']['kcalTotal'])
                        <p>
                            <span class="fc-red fw-bold">
                                {{($data['total']['kcalTotal']) - ($data['userKcal']->goal_kcal)}}
                            </span>
                            KCAL 만큼 초과했습니다
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#ff0000" class="bi bi-emoji-frown" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                                </svg>
                            </span>
                        </p>
                    @else
                        <p>
                            <span class="fc-blue fw-bold">
                                {{($data['userKcal']->goal_kcal) - ($data['total']['kcalTotal'])}}
                            </span>
                            KCAL 더 먹을 수 있어요
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#6799E4" class="bi bi-emoji-smile" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                                </svg>
                            </span>
                        </p>
                    @endif
                </div>
                {{-- 목표 칼로리 출력 --}}
                @if($data['userKcal']->goal_kcal === 0)
                    <div class="goalKcal col pt-3">
                        <p>정확한 식단 관리를 위해 목표칼로리와 식단을 설정해주세요.</p>
                        <p><a href="{{route('user.prevateinfo')}}" class="hoverG">목표칼로리 & 식단설정 바로가기</a></p>
                    </div>
                @else
                    <div class="goalKcal col pt-3">
                        <p>
                            <span class="fw-bold">목표 칼로리</span> {{$data['userKcal']->goal_kcal}} Kcal
                            <a href="{{route('user.prevateinfo')}}" class="test">
                                <svg id="goalIcon" xmlns="http://www.w3.org/2000/svg" height="1.2em" viewBox="0 0 576 512" fill="#ee6666"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M240 144A96 96 0 1 0 48 144a96 96 0 1 0 192 0zm44.4 32C269.9 240.1 212.5 288 144 288C64.5 288 0 223.5 0 144S64.5 0 144 0c68.5 0 125.9 47.9 140.4 112h71.8c8.8-9.8 21.6-16 35.8-16H496c26.5 0 48 21.5 48 48s-21.5 48-48 48H392c-14.2 0-27-6.2-35.8-16H284.4zM144 80a64 64 0 1 1 0 128 64 64 0 1 1 0-128zM400 240c13.3 0 24 10.7 24 24v8h96c13.3 0 24 10.7 24 24s-10.7 24-24 24H280c-13.3 0-24-10.7-24-24s10.7-24 24-24h96v-8c0-13.3 10.7-24 24-24zM288 464V352H512V464c0 26.5-21.5 48-48 48H336c-26.5 0-48-21.5-48-48zM48 320h80 16 32c26.5 0 48 21.5 48 48s-21.5 48-48 48H160c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V336c0-8.8 7.2-16 16-16zm128 64c8.8 0 16-7.2 16-16s-7.2-16-16-16H160v32h16zM24 464H200c13.3 0 24 10.7 24 24s-10.7 24-24 24H24c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>
                                <span class="iconText">목표설정하러가기</span>
                            </a>
                        </p>
                        @if(($data['userKcal']->nutrition_ratio) == '0')
                            <p><span class="fw-bold">일반식단</span> 탄50 : 단30 : 지20</p>
                        @elseif(($data['userKcal']->nutrition_ratio) == '1')
                            <p><span class="fw-bold">운동식단</span> 탄40 : 단40 : 지20</p>
                        @elseif(($data['userKcal']->nutrition_ratio) == '2')
                            <p><span class="fw-bold">키토식단</span>탄8 : 단22 : 지88</p>
                        @elseif(($data['userKcal']->nutrition_ratio) == '3')
                            <p><span class="fw-bold">비건식단</span> 탄50 : 단30 : 지20</p>
                        @else
                            <p>식단설정을 확인해주세요.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <hr class="fc-green">

    {{-- 식단 정보 --}}
    <div class="dietArea">
        <div id="brfArea">
        {{-- 아침 식단 --}}
            {{-- 아침 식단이 존재하는 경우 --}}
            @if(isset($data['dietFood']['dietBrf'][0]->d_id))
                <div class="flgBox text-center" id="brfBtn" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    아침
                    <span class="fc-green frBtn">▼</span>
                    <span class="fc-green frBtn off">▲</span>
                </div>
                <div class="diet">
                    <div class="food container pb-3">
                        <div class="row row-cols-2 row-cols-md-4 mx-auto p-3 p-xl-4 text-md-center">
                            <div class="col"><span class="fc-green">■</span>칼로리 {{$data['brfSum']['kcalSum']}} Kcal</div>
                            <div class="col"><span class="fc-pink">■</span>탄수화물 {{$data['brfSum']['carbSum']}} g</div>
                            <div class="col"><span class="fc-yel">■</span>단백질 {{$data['brfSum']['proteinSum']}} g</div>
                            <div class="col"><span class="fc-blue">■</span>지방 {{$data['brfSum']['fatSum']}} g</div>
                        </div>
                    </div>
                </div>
                <div class="dietDetail">
                    <div class="collapse" id="collapseExample">
                        <div class="card mb-3">
                            <div class="row g-0">
                                {{-- 식단이 있는지 체크 --}}
                                    <div class="col-md-3 mx-auto mx-md-0 p-1 pb-md-5 text-center" style="max-width:350px;">
                                        <form action="{{route('img.edit',['d_id' => $data['dietFood']['dietBrf'][0]->d_id])}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            {{-- 식단은 있지만 사진이 있는지 체크 --}}
                                            @if (isset($data['dietFood']['dietBrf'][0]->d_img_path))
                                                <div class="imgbox brfImg" style="position: relative">
                                                    <img src="{{asset($data['dietFood']['dietBrf'][0]->d_img_path)}}" class="img-fluid rounded-start" alt="...">
                                                </div>
                                            @else
                                                <div class="imgbox brfImg ms-2 ms-sm-3 my-5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}">
                                            <div class="filebox my-2 text-center">
                                                <input class="upload-name fileBrfName" value="첨부파일" placeholder="첨부파일" readonly>
                                                <label for="fileBrf">파일찾기</label>
                                                <input type="file" id="fileBrf" name="dietImg" accept="image/*">
                                                <button type="submit" class="btnYg my-2">사진등록</button>
                                            </div>
                                        </form>                        
                                    </div>
                                {{-- @endif --}}
                                <div class="col-md-9 mx-auto">
                                    <div class="card-body">
                                        <div class="table-responsive text-center">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">음식명</th>
                                                        <th>칼로리</th>
                                                        <th>탄수화물</th>
                                                        <th>단백질</th>
                                                        <th>지방</th>
                                                        <th>당</th>
                                                        <th>나트륨</th>
                                                        <th>섭취량</th>
                                                        <th>
                                                            <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post" class="favInsert">
                                                                @csrf
                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                                                                <input type="hidden" name="time" value="0">
                                                                <button type="submit" class="btnYg">음식추가</button>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            {{-- 식단이 존재할 경우에만 즐겨찾기 등록 버튼 출력 --}}
                                                            <button type="button" class="btnYg" data-bs-toggle="modal" data-bs-target="#exampleModal0">
                                                            즐겨찾기
                                                            </button>
                                                            <!-- 즐겨찾기 등록 Modal -->
                                                            <div class="modal fade" id="exampleModal0" tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">식단 즐겨찾기에 추가하기</h1>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="fw-bold">음식 목록</p>                                     
                                                                            @foreach($data['dietFood']['dietBrf'] as $val)
                                                                                <p>{{$val->food_name}}<p>
                                                                            @endforeach
                                                                            <form action="{{route('fav.insert')}}">
                                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}" >
                                                                                <input type="hidden" name="d_flg" value="0">
                                                                                <input type="text" name="fav_name" required placeholder="식단명을 입력해주세요." autocomplete="off" maxlength="10">
                                                                                <button type="submit" class="btnYg">등록하기</button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btnYg" data-bs-dismiss="modal">닫기</button>
                                                                        </div>
                                                                    </div>
                                                                </div>                    
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                          
                                                    @forelse($data['dietFood']['dietBrf'] as $val)
                                        
                                                        <tr id="foodlist{{$val->df_id}}">
                                                            <form class="editForm" method="POST" style="display:inline-block" id="editForm">
            
                                                            {{-- <form action="{{route('home.update', ['df_id' => $val->df_id])}}" method="POST" style="display:inline-block"> --}}
                                                                @csrf
                                                                <td colspan="2">{{$val->food_name}}</td>
                                                                <td>{{$val->kcal}}</td>
                                                                <td>{{$val->carbs}}</td>
                                                                <td>{{$val->protein}}</td>
                                                                <td>{{$val->fat}}</td>
                                                                <td>{{$val->sugar}}</td>
                                                                <td>{{$val->sodium}}</td>
                                                                <td><input name="df_intake" value="{{$val->df_intake}}" type="number" min="0.5" max="100" step="0.5" id ="df_intake{{$val->df_id}}" required ></td>
                                                                <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}" class ="intakdate">
                                                                <td>
                                                                    <button type="button" class="editBtn" onclick="updateIntake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 0 512 512" class="greenIcon"><style>svg.greenIcon{fill:#195f1c}</style><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                                                    </button>
                                                                </td>
                                                            </form>
                                                            <td>
                                                                {{-- <form action="{{route('home.intakedel', ['df_id' => $val->df_id])}}" method="post" style="display:inline-block" id="delintakeform"> --}}
                                                                    {{-- @csrf
                                                                    @method('delete') --}}
                                                                    {{-- <input type="text" value="{{$val->df_id}}" name="df_id"> --}}
                                                                    {{-- <input type="hidden" value="{{$data['date'] ?? $data['today']}}" name="date" id="deldate"> --}}
                                                                    <button type="button" class="delBtn" onclick="delintake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"      height="25" fill="#195F1C" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                                        </svg>
                                                                    {{-- </button> --}}
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>정보가없어요</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- 아침 식단이 존재하지 않는 경우 음식 추가버튼만 출력 --}}  
                <div class="flgBox text-center">
                    아침&nbsp;
                    <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                        <input type="hidden" name="time" value="0">
                        <button type="submit" class="plusBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div id="lunchArea">
        {{-- 점심 식단 --}}
            {{-- 점심 식단이 존재하는 경우 --}}
            @if(isset($data['dietFood']['dietLunch'][0]->d_id))
                <div class="flgBox text-center" id="lunchBtn" data-bs-toggle="collapse" data-bs-target="#collapseExampleTwo" aria-expanded="false" aria-controls="collapseExampleTwo">
                    점심
                    <span class="fc-green frBtn">▼</span>
                    <span class="fc-green frBtn off">▲</span>
                </div>
                <div class="diet">
                    <div class="food container pb-3">
                        <div class="row row-cols-2 row-cols-md-4 mx-auto p-3 p-xl-4 text-md-center">
                            <div class="col"><span class="fc-green">■</span>칼로리 {{$data['lunchSum']['kcalSum']}} Kcal</div>
                            <div class="col"><span class="fc-pink">■</span>탄수화물 {{$data['lunchSum']['carbSum']}} g</div>
                            <div class="col"><span class="fc-yel">■</span>단백질 {{$data['lunchSum']['proteinSum']}} g</div>
                            <div class="col"><span class="fc-blue">■</span>지방 {{$data['lunchSum']['fatSum']}} g</div>
                        </div>
                    </div>
                </div>
                <div class="dietDetail">
                    <div class="collapse" id="collapseExampleTwo">
                        <div class="card mb-3">
                            <div class="row g-0">
                                {{-- 식단이 있는지 체크 --}}
                                <div class="col-md-3 mx-auto mx-md-0 p-1 pb-md-5 text-center" style="max-width:350px;">
                                    <form action="{{route('img.edit',['d_id' => $data['dietFood']['dietLunch'][0]->d_id])}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        {{-- 식단은 있지만 사진이 있는지 체크 --}}
                                        @if (isset($data['dietFood']['dietLunch'][0]->d_img_path))
                                            <div class="imgbox lunchImg">
                                                <img src="{{asset($data['dietFood']['dietLunch'][0]->d_img_path)}}" class="img-fluid rounded-start" alt="점심식단사진">
                                            </div>
                                        @else
                                            <div class="imgbox lunchImg ms-2 ms-sm-3 my-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}">
                                        <div class="filebox my-2 text-center">
                                            <input class="upload-name fileLunchName" value="첨부파일" placeholder="첨부파일" readonly>
                                            <label for="fileLunch">파일찾기</label>
                                            <input type="file" id="fileLunch" name="dietImg" accept="image/*">
                                            <button type="submit" class="btnYg my-2">사진등록</button>
                                        </div>
                                    </form>                        
                                </div>
                                <div class="col-md-9 mx-auto">
                                    <div class="card-body">
                                        <div class="table-responsive text-center">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">음식명</th>
                                                        <th>칼로리</th>
                                                        <th>탄수화물</th>
                                                        <th>단백질</th>
                                                        <th>지방</th>
                                                        <th>당</th>
                                                        <th>나트륨</th>
                                                        <th>섭취량</th>
                                                        <th>
                                                            <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                                                                <input type="hidden" name="time" value="1">
                                                                <button type="submit" class="btnYg">음식추가</button>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            {{-- 식단이 존재할 경우에만 즐겨찾기 등록 버튼 출력 --}}
                                                            <button type="button" class="btnYg" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                            즐겨찾기
                                                            </button>
                                                            <!-- 즐겨찾기 등록 Modal -->
                                                            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">식단 즐겨찾기에 추가하기</h1>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="fw-bold">음식 목록</p>                                     
                                                                            @foreach($data['dietFood']['dietLunch'] as $val)
                                                                                <p>{{$val->food_name}}<p>
                                                                            @endforeach
                                                                            <form action="{{route('fav.insert')}}">
                                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}" >
                                                                                <input type="hidden" name="d_flg" value="1">
                                                                                <input type="text" name="fav_name" required placeholder="식단명을 입력해주세요." autocomplete="off" maxlength="10">
                                                                                <button type="submit" class="btnYg">등록하기</button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btnYg" data-bs-dismiss="modal">닫기</button>
                                                                        </div>
                                                                    </div>
                                                                </div>                    
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                          
                                                    @forelse($data['dietFood']['dietLunch'] as $val)
                                                        <tr id="foodlist{{$val->df_id}}">
                                                            <form action="{{route('home.update', ['df_id' => $val->df_id])}}" method="POST" style="display:inline-block">
                                                                @csrf
                                                                <td colspan="2">{{$val->food_name}}</td>
                                                                <td>{{$val->kcal}}</td>
                                                                <td>{{$val->carbs}}</td>
                                                                <td>{{$val->protein}}</td>
                                                                <td>{{$val->fat}}</td>
                                                                <td>{{$val->sugar}}</td>
                                                                <td>{{$val->sodium}}</td>
                                                                <td><input name="df_intake" value="{{$val->df_intake}}" type="number" min="0.5" max="100" step="0.5" id ="df_intake{{$val->df_id}}" required ></td>
                                                                <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}" class ="intakdate">
                                                                <td>
                                                                    <button type="button" class="editBtn" onclick="updateIntake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 0 512 512" class="greenIcon"><style>svg.greenIcon{fill:#195f1c}</style><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                                                    </button>
                                                                </td>
                                                            </form>
                                                            <td>
                                                                {{-- <form action="{{route('home.intakedel', ['df_id' => $val->df_id])}}" method="post" style="display:inline-block" id="delintakeform"> --}}
                                                                    {{-- @csrf
                                                                    @method('delete') --}}
                                                                    {{-- <input type="text" value="{{$val->df_id}}" name="df_id"> --}}
                                                                    {{-- <input type="hidden" value="{{$data['date'] ?? $data['today']}}" name="date" id="deldate"> --}}
                                                                    <button type="button" class="delBtn" onclick="delintake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"      height="25" fill="#195F1C" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                                        </svg>
                                                                    {{-- </button> --}}
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>정보가없어요</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- 점심 식단이 존재하지 않는 경우 음식 추가버튼만 출력 --}}  
                <div class="flgBox text-center">
                    점심&nbsp;
                    <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                        <input type="hidden" name="time" value="1">
                        <button type="submit" class="plusBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div id="dinnerArea">
        {{-- 저녁 식단 --}}
            {{-- 저녁 식단이 존재하는 경우 --}}
            @if(isset($data['dietFood']['dietDinner'][0]->d_id))
                <div class="flgBox text-center" id="dinnerBtn" data-bs-toggle="collapse" data-bs-target="#collapseExampleThree" aria-expanded="false" aria-controls="collapseExampleThree">
                    저녁
                    <span class="fc-green frBtn">▼</span>
                    <span class="fc-green frBtn off">▲</span>
                </div>
                <div class="diet">
                    <div class="food container pb-3">
                        <div class="row row-cols-2 row-cols-md-4 mx-auto p-3 p-xl-4 text-md-center">
                            <div class="col"><span class="fc-green">■</span>칼로리 {{$data['dinnerSum']['kcalSum']}} Kcal</div>
                            <div class="col"><span class="fc-pink">■</span>탄수화물 {{$data['dinnerSum']['carbSum']}} g</div>
                            <div class="col"><span class="fc-yel">■</span>단백질 {{$data['dinnerSum']['proteinSum']}} g</div>
                            <div class="col"><span class="fc-blue">■</span>지방 {{$data['dinnerSum']['fatSum']}} g</div>
                        </div>
                    </div>
                </div>
                <div class="dietDetail">
                    <div class="collapse" id="collapseExampleThree">
                        <div class="card mb-3">
                            <div class="row g-0">
                                {{-- 식단이 있는지 체크 --}}
                                <div class="col-md-3 mx-auto mx-md-0 p-1 pb-md-5 text-center" style="max-width:350px;">
                                    <form action="{{route('img.edit',['d_id' => $data['dietFood']['dietDinner'][0]->d_id])}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        {{-- 식단은 있지만 사진이 있는지 체크 --}}
                                        @if (isset($data['dietFood']['dietDinner'][0]->d_img_path))
                                            <div class="imgbox dinnerImg">
                                                <img src="{{asset($data['dietFood']['dietDinner'][0]->d_img_path)}}" class="img-fluid rounded-start" alt="저녁식단사진">
                                            </div>
                                        @else
                                            <div class="imgbox dinnerImg ms-2 ms-sm-3 my-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}">
                                        <div class="filebox my-2 text-center">
                                            <input class="upload-name fileDinnerName" value="첨부파일" placeholder="첨부파일" readonly>
                                            <label for="fileDinner">파일찾기</label>
                                            <input type="file" id="fileDinner" name="dietImg" accept="image/*">
                                            <button type="submit" class="btnYg my-2">사진등록</button>
                                        </div>
                                    </form>                        
                                </div>
                                <div class="col-md-9 mx-auto">
                                    <div class="card-body">
                                        <div class="table-responsive text-center">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">음식명</th>
                                                        <th>칼로리</th>
                                                        <th>탄수화물</th>
                                                        <th>단백질</th>
                                                        <th>지방</th>
                                                        <th>당</th>
                                                        <th>나트륨</th>
                                                        <th>섭취량</th>
                                                        <th>
                                                            <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                                                                <input type="hidden" name="time" value="2">
                                                                <button type="submit" class="btnYg">음식추가</button>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            {{-- 식단이 존재할 경우에만 즐겨찾기 등록 버튼 출력 --}}
                                                            <button type="button" class="btnYg" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                                            즐겨찾기
                                                            </button>
                                                            <!-- 즐겨찾기 등록 Modal -->
                                                            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">식단 즐겨찾기에 추가하기</h1>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="fw-bold">음식 목록</p>                                     
                                                                            @foreach($data['dietFood']['dietDinner'] as $val)
                                                                                <p>{{$val->food_name}}<p>
                                                                            @endforeach
                                                                            <form action="{{route('fav.insert')}}">
                                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}" >
                                                                                <input type="hidden" name="d_flg" value="2">
                                                                                <input type="text" name="fav_name" required placeholder="식단명을 입력해주세요." autocomplete="off" maxlength="10">
                                                                                <button type="submit" class="btnYg">등록하기</button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btnYg" data-bs-dismiss="modal">닫기</button>
                                                                        </div>
                                                                    </div>
                                                                </div>                    
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                          
                                                    @forelse($data['dietFood']['dietDinner'] as $val)
                                                        <tr id="foodlist{{$val->df_id}}">
                                                            <form action="{{route('home.update', ['df_id' => $val->df_id])}}" method="POST" style="display:inline-block">
                                                                @csrf
                                                                <td colspan="2">{{$val->food_name}}</td>
                                                                <td>{{$val->kcal}}</td>
                                                                <td>{{$val->carbs}}</td>
                                                                <td>{{$val->protein}}</td>
                                                                <td>{{$val->fat}}</td>
                                                                <td>{{$val->sugar}}</td>
                                                                <td>{{$val->sodium}}</td>
                                                                <td><input name="df_intake" value="{{$val->df_intake}}" type="number" min="0.5" max="100" step="0.5" id ="df_intake{{$val->df_id}}" required ></td>
                                                                <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}" class ="intakdate">
                                                                <td>
                                                                    <button type="button" class="editBtn" onclick="updateIntake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 0 512 512" class="greenIcon"><style>svg.greenIcon{fill:#195f1c}</style><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                                                    </button>
                                                                </td>
                                                            </form>
                                                            <td>
                                                                {{-- <form action="{{route('home.intakedel', ['df_id' => $val->df_id])}}" method="post" style="display:inline-block" id="delintakeform"> --}}
                                                                    {{-- @csrf
                                                                    @method('delete') --}}
                                                                    {{-- <input type="text" value="{{$val->df_id}}" name="df_id"> --}}
                                                                    {{-- <input type="hidden" value="{{$data['date'] ?? $data['today']}}" name="date" id="deldate"> --}}
                                                                    <button type="button" class="delBtn" onclick="delintake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"      height="25" fill="#195F1C" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                                        </svg>
                                                                    {{-- </button> --}}
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>정보가없어요</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- 저녁 식단이 존재하지 않는 경우 음식 추가버튼만 출력 --}}  
                <div class="flgBox text-center">
                    저녁&nbsp;
                    <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                        <input type="hidden" name="time" value="2">
                        <button type="submit" class="plusBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div id="snackArea">
        {{-- 간식 식단 --}}
            {{-- 간식 식단이 존재하는 경우 --}}
            @if(isset($data['dietFood']['dietSnack'][0]->d_id))
                <div class="flgBox text-center" id="snackBtn" data-bs-toggle="collapse" data-bs-target="#collapseExampleFour" aria-expanded="false" aria-controls="collapseExampleFour">
                    간식
                    <span class="fc-green frBtn">▼</span>
                    <span class="fc-green frBtn off">▲</span>
                </div>
                <div class="diet">
                    <div class="food container pb-3">
                        <div class="row row-cols-2 row-cols-md-4 mx-auto p-3 p-xl-4 text-md-center">
                            <div class="col"><span class="fc-green">■</span>칼로리 {{$data['snackSum']['kcalSum']}} Kcal</div>
                            <div class="col"><span class="fc-pink">■</span>탄수화물 {{$data['snackSum']['carbSum']}} g</div>
                            <div class="col"><span class="fc-yel">■</span>단백질 {{$data['snackSum']['proteinSum']}} g</div>
                            <div class="col"><span class="fc-blue">■</span>지방 {{$data['snackSum']['fatSum']}} g</div>
                        </div>
                    </div>
                </div>
                <div class="dietDetail">
                    <div class="collapse" id="collapseExampleFour">
                        <div class="card mb-3">
                            <div class="row g-0">
                                {{-- 식단이 있는지 체크 --}}
                                <div class="col-md-3 mx-auto mx-md-0 p-1 pb-md-5 text-center" style="max-width:350px;">
                                    <form action="{{route('img.edit',['d_id' => $data['dietFood']['dietSnack'][0]->d_id])}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        {{-- 식단은 있지만 사진이 있는지 체크 --}}
                                        @if (isset($data['dietFood']['dietSnack'][0]->d_img_path))
                                            <div class="imgbox snackImg">
                                                <img src="{{asset($data['dietFood']['dietSnack'][0]->d_img_path)}}" class="img-fluid rounded-start" alt="간식식단사진">
                                            </div>
                                        @else
                                            <div class="imgbox snackImg ms-2 ms-sm-3 my-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}">
                                        <div class="filebox my-2 text-center">
                                            <input class="upload-name fileSnackName" value="첨부파일" placeholder="첨부파일" readonly>
                                            <label for="fileSnack">파일찾기</label>
                                            <input type="file" id="fileSnack" name="dietImg" accept="image/*">
                                            <button type="submit" class="btnYg my-2">사진등록</button>
                                        </div>
                                    </form>                        
                                </div>
                                <div class="col-md-9 mx-auto">
                                    <div class="card-body">
                                        <div class="table-responsive text-center">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">음식명</th>
                                                        <th>칼로리</th>
                                                        <th>탄수화물</th>
                                                        <th>단백질</th>
                                                        <th>지방</th>
                                                        <th>당</th>
                                                        <th>나트륨</th>
                                                        <th>섭취량</th>
                                                        <th>
                                                            <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                                                                <input type="hidden" name="time" value="3">
                                                                <button type="submit" class="btnYg">음식추가</button>
                                                            </form>
                                                        </th>
                                                        <th>
                                                            {{-- 식단이 존재할 경우에만 즐겨찾기 등록 버튼 출력 --}}
                                                            <button type="button" class="btnYg" data-bs-toggle="modal" data-bs-target="#exampleModal3">
                                                            즐겨찾기
                                                            </button>
                                                            <!-- 즐겨찾기 등록 Modal -->
                                                            <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">식단 즐겨찾기에 추가하기</h1>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="fw-bold">음식 목록</p>                                     
                                                                            @foreach($data['dietFood']['dietSnack'] as $val)
                                                                                <p>{{$val->food_name}}<p>
                                                                            @endforeach
                                                                            <form action="{{route('fav.insert')}}">
                                                                                <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}" >
                                                                                <input type="hidden" name="d_flg" value="3">
                                                                                <input type="text" name="fav_name" required placeholder="식단명을 입력해주세요." autocomplete="off" maxlength="10">
                                                                                <button type="submit" class="btnYg">등록하기</button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btnYg" data-bs-dismiss="modal">닫기</button>
                                                                        </div>
                                                                    </div>
                                                                </div>                    
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>                                          
                                                    @forelse($data['dietFood']['dietSnack'] as $val)
                                                        <tr id="foodlist{{$val->df_id}}">
                                                            <form action="{{route('home.update', ['df_id' => $val->df_id])}}" method="POST" style="display:inline-block">
                                                                @csrf
                                                                <td colspan="2">{{$val->food_name}}</td>
                                                                <td>{{$val->kcal}}</td>
                                                                <td>{{$val->carbs}}</td>
                                                                <td>{{$val->protein}}</td>
                                                                <td>{{$val->fat}}</td>
                                                                <td>{{$val->sugar}}</td>
                                                                <td>{{$val->sodium}}</td>
                                                                <td><input name="df_intake" value="{{$val->df_intake}}" type="number" min="0.5" max="100" step="0.5" id ="df_intake{{$val->df_id}}" required ></td>
                                                                <input type="hidden" name="d_date" value="{{$data['date'] ?? $data['today']}}" class ="intakdate">
                                                                <td>
                                                                    <button type="button" class="editBtn" onclick="updateIntake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" height="25" viewBox="0 0 512 512" class="greenIcon"><style>svg.greenIcon{fill:#195f1c}</style><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                                                    </button>
                                                                </td>
                                                            </form>
                                                            <td>
                                                                {{-- <form action="{{route('home.intakedel', ['df_id' => $val->df_id])}}" method="post" style="display:inline-block" id="delintakeform"> --}}
                                                                    {{-- @csrf
                                                                    @method('delete') --}}
                                                                    {{-- <input type="text" value="{{$val->df_id}}" name="df_id"> --}}
                                                                    {{-- <input type="hidden" value="{{$data['date'] ?? $data['today']}}" name="date" id="deldate"> --}}
                                                                    <button type="button" class="delBtn" onclick="delintake({{$val->df_id}})">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"      height="25" fill="#195F1C" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                                        </svg>
                                                                    {{-- </button> --}}
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>정보가없어요</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        <tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            {{-- 간식 식단이 존재하지 않는 경우 음식 추가버튼만 출력 --}}  
                <div class="flgBox text-center">
                    간식&nbsp;
                    <form action="{{route('search.list.get',['id' => Auth::user()->user_id])}}" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{$data['date'] ?? $data['today']}}">
                        <input type="hidden" name="time" value="3">
                        <button type="submit" class="plusBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#195F1C" class="bi bi-plus-circle-fill text-center" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>



<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="{{asset('js/home.js')}}"></script>
<script>
    
    if({{$data['total']['tdgTotal']}} === 0)
        {
            new Chart(document.getElementById("doughnut-chart"),
                {
                    type: 'doughnut',
                        data:
                        {
                            // labels: ["탄수화물", "단백질", "지방"],
                            datasets: [
                                {
                                label: "Population (millions)",
                                backgroundColor: ["#F2F2F2"],
                                data: [100]
                                }
                            ]
                        },
                        options:
                        {
                            title: {
                                display: true,
                                text: '오늘 식단'
                            }
                        }
                });
        }
        else
        {
            new Chart(document.getElementById("doughnut-chart"), {
                type: 'doughnut',
                data: {
                labels: ["탄수화물", "단백질", "지방"],
                datasets: [
                    {
                    label: "Population (millions)",
                    backgroundColor: ["#EE6666", "#FFD029","#6799E4"],
                    data: [{{$data['total']['carbTotal']}},{{$data['total']['proteinTotal']}},{{$data['total']['fatTotal']}}]
                    }
                ]
                },
                options: {
                title: {
                    display: true,
                    text: '오늘 식단'
                }
                }
            });
        }

</script>
@endsection