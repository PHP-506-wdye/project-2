@extends('layout.layout')
@section('title','관리자음식관리')
@section('css')
    <link rel="stylesheet" href="{{asset('css/food.css')}}">
@endsection

@section('contents')
<div class="container-fluid" id="edit">
    <div class="row">
        <!-- column -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h4 class="card-title">관리자 등록 음식 관리
                            <form class="app-search ps-3" action="{{route('manager.search')}}" method="get">
                                @csrf
                                <input type="text" class="form-control" name="search" placeholder="음식이름으로 검색해주세요" autocomplete="off">
                                <button type="submit" class="btn srh-btn"><i class="ti-search"></i></button>
                            </form>
                            <span style="float: right">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertModal">음식추가</button>
                            </span>
                        </h4>
                        <h6 class="card-subtitle">음식정보 <code>.관리자용</code></h6>
                    </div>

                    {{-- 음식추가 모달 --}}
                    <div class="modal" tabindex="-1" id="insertModal" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form class="form-horizontal form-material mx-2" action="{{route('food.insert')}}" method="post" id="insertForm">
                                    @csrf
                                    <input type="hidden" value="0" id="user_id" name="user_id">
                                    <input type="hidden" value="0" id="userfood_flg" name="userfood_flg">
                                    <div class="modal-header">
                                    <h5 class="modal-title">관리자음식 등록</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="food_name" class="col-md-12 mb-0"><span class="fc-red">⁕</span> 음식이름</label>
                                                    <div class="col-md-12">
                                                        <input type="text"
                                                            id="food_name" name="food_name" class="form-control ps-0 form-control-line" autocomplete="off">
                                                    </div>
                                                    <div id="errMsg"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kcal" class="col-md-12"><span class="fc-red">⁕</span> 칼로리(kcal)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="kcal" name="kcal"
                                                            class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="carbs" class="col-md-12 mb-0"><span class="fc-red">⁕</span> 탄수화물(g)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="carbs" name="carbs"
                                                            class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="protein" class="col-md-12 mb-0"><span class="fc-red">⁕</span> 단백질(g)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="protein" name="protein"
                                                            class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fat" class="col-md-12 mb-0"><span class="fc-red">⁕</span> 지방(g)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="fat" name="fat"
                                                        class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sugar" class="col-md-12 mb-0">당(g)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="sugar" name="sugar"
                                                        class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sodium" class="col-md-12 mb-0">나트륨(g)</label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="sodium" name="sodium"
                                                        class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="serving" class="col-md-12 mb-0"><span class="fc-red">⁕</span> 1회제공량
                                                        <input class="form-check-input" type="radio" name="ser_unit" id="unit0" value="0" checked>
                                                        <label class="form-check-label" for="unit2">
                                                        g
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="ser_unit" id="unit1" value="1">
                                                        <label class="form-check-label" for="unit1">
                                                            ml
                                                        </label>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <input type="number" id="serving" name="serving"
                                                        class="form-control ps-0 form-control-line">
                                                    </div>
                                                </div>
                                                <span class="fc-red">⁕ 필수입력사항입니다.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="foodinsert();" class="btn btn-primary">등록하기</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table user-table no-wrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">음식 번호</th>
                                    <th class="border-top-0">음식 이름</th>
                                    <th class="border-top-0">음식 등록일</th>
                                    <th class="border-top-0">음식 삭제일</th>
                                    <th>
                                        <button type="button" onclick="chkDel()" class="btn delBtn ">선택삭제</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td><a data-bs-toggle="modal" data-bs-target="#editModal{{ $item->food_id }}">{{ $item->food_id }}</a></td>
                                        <td><a data-bs-toggle="modal" data-bs-target="#editModal{{ $item->food_id }}">{{ $item->food_name }}</a></td>
                                        <td><a data-bs-toggle="modal" data-bs-target="#editModal{{ $item->food_id }}">{{ $item->created_at }}</a></td>
                                        @if(isset($item->deleted_at))
                                            <td>{{$item->deleted_at}}</td>
                                        @else
                                            <td class="delDate"><button type="button" onclick="foodDel({{$item->food_id}})" class="delBtn">삭제</button></td>
                                        @endif
                                        @if(isset($item->deleted_at))
                                            <td></td>
                                        @else
                                            <td><input type="checkbox" class="delChk ms-4" value="{{ $item->food_id }}"></td>
                                        @endif
                                    </tr>
                                    {{-- 수정 모달 --}}
                                    <div class="modal" tabindex="-1" id="editModal{{ $item->food_id }}" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form class="form-horizontal form-material mx-2" id="editModal">
                                                    @csrf
                                                    <input type="hidden" value="{{ $item->food_id }}" id="food_id" name="food_id">
                                                    <input type="hidden" value="0" id="user_id" name="user_id">
                                                    <input type="hidden" value="0" id="userfood_flg" name="userfood_flg">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">음식번호 {{ $item->food_id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <ul class="card-body">
                                                                <li class="form-group">
                                                                    <label for="food_name" class="w100">음식이름</label>
                                                                    <input type="text" id="food_name" name="food_name" value="{{ $item->food_name }}" class="editInput">
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="kcal" class="w100">칼로리(kcal)</label>
                                                                    <input type="number" id="kcal" name="kcal"
                                                                    value="{{ $item->kcal }}" class="editInput">
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="carbs" class="w100">탄수화물(g)</label>
                                                                    <input type="number" id="carbs" name="carbs"
                                                                    value="{{ $item->carbs }}" class="editInput">           
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="protein" class="w100">단백질(g)</label>
                                                                    <input type="number" id="protein" name="protein"
                                                                    value="{{ $item->protein }}" class="editInput">
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="fat" class="w100">지방(g)</label>
                                                                    <input type="number" id="fat" name="fat"
                                                                    value="{{ $item->fat }}" class="editInput"> 
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="sugar" class="w100">당(g)</label>
                                                                    <input type="number" id="sugar" name="sugar"
                                                                    value="{{ $item->sugar }}" class="editInput">
                                                                </li>
                                                                <li class="form-group">
                                                                    <label for="sodium" class="w100">나트륨(g)</label>
                                                                    <input type="number" id="sodium" name="sodium"
                                                                    value="{{ $item->sodium }}" class="editInput">
                                                                </li>
                                                                <li>
                                                                    <label for="serving" class="w150">1회제공량
                                                                        @if($item->ser_unit === '0')
                                                                            <input class="form-check-input" type="radio" name="ser_unit" id="unit0" value="0" checked>
                                                                            <label class="form-check-label" for="unit2">g</label>
                                                                            <input class="form-check-input" type="radio" name="ser_unit" id="unit1" value="1">
                                                                            <label class="form-check-label" for="unit1">ml</label>
                                                                        @else
                                                                            <input class="form-check-input" type="radio" name="ser_unit" id="unit0" value="0">
                                                                            <label class="form-check-label" for="unit2">g</label>
                                                                            <input class="form-check-input" type="radio" name="ser_unit" id="unit1" value="1" checked>
                                                                            <label class="form-check-label" for="unit1">ml</label>
                                                                        @endif
                                                                    </label>
                                                                    <input type="number" id="serving" name="serving"
                                                                    value="{{ $item->serving }}" class="editInput serving">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" onclick="foodedit({{$item->food_id}});" class="btn btn-warning">수정하기</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    데이터가없습니다
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- 페이지네이션 --}}
                    @if ($data->hasPages())
                        <ul class="pagination pagination">                    
                            @php
                                $block = 5;
                                $startPage = max(1, $data->currentPage() - floor($block / 2));
                                $endPage = min($startPage + $block - 1, $data->lastPage());
                            @endphp
                            {{-- 첫 페이지 버튼 --}}
                            @if ($data->onFirstPage())
                                <li><<</li>
                            @else
                                <li class="active">
                                    <a href="{{ $data->appends(request()->except('page'))->url(1) }}" rel="prev"><<</a>
                                </li>
                            @endif                  
                            {{-- 이전 페이지 버튼 --}}
                            @if ($data->onFirstPage())
                                <li><</li>
                            @else
                                <li class="active">
                                    <a href="{{ $data->appends(request()->except('page'))->previousPageUrl() }}" rel="prev"><</a>
                                </li>
                            @endif                    
                            {{-- 페이징 --}}
                            {{-- range() : 지정된 범위의 숫자를 생성하여 배열로 반환 --}}
                            @foreach(range($startPage, $endPage) as $i)
                                @if ($i == $data->currentPage())
                                    <li class="active"><span>{{ $i }}</span></li>
                                @else
                                    <li class="active">
                                        <a href="{{$data->appends(request()->except('page'))->url($i)}}">{{$i}}</a>
                                    </li>
                                @endif
                            @endforeach
                            {{-- 다음 페이지 버튼 --}}
                            @if ($data->hasMorePages())
                                <li class="active">
                                    <a href="{{$data->appends(request()->except('page'))->nextPageUrl()}}">></a>
                                </li>
                            @else
                                <li>></li> 
                            @endif
                            {{-- 마지막 페이지 --}}
                            @if ($data->hasMorePages())
                                <li class="active">
                                    <a href="{{ $data->appends(request()->except('page'))->url($data->lastPage()) }}" rel="next">>></a>
                                </li>
                            @else
                                <li>>></li> 
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="/js/food.js"></script>
@endsection