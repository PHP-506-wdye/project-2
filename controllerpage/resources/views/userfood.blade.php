@extends('layout.layout')
@section('title','회원음식관리')
@section('css')
    <link rel="stylesheet" href="{{asset('css/food.css')}}">
@endsection

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">회원 등록 음식 관리
                        <form class="app-search ps-3" action="{{route('food.search')}}" method="get">
                            @csrf
                            <input type="text" class="form-control" name="search" placeholder="음식이름으로 검색해주세요" autocomplete="off">
                            <button type="submit" class="btn srh-btn"><i class="ti-search"></i></button>
                        </form>
                    </h4>
                    <h6 class="card-subtitle">음식정보 <code>.관리자용</code></h6>
                    <div class="table-responsive">
                        <table class="table user-table no-wrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">음식 번호</th>
                                    <th class="border-top-0">회원 번호</th>
                                    <th class="border-top-0">음식 이름</th>
                                    <th class="border-top-0">음식 등록일</th>
                                    <th class="border-top-0">음식 삭제일</th>
                                    <th>                        
                                        <button type="button" onclick="chkDel()" class="btn delBtn">선택삭제</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($data as $item)
                                        <form>
                                            @csrf
                                            <tr>
                                                <td>{{ $item->food_id }}</td>
                                                <td>{{ $item->user_id }}</td>
                                                <td><a data-bs-toggle="modal" data-bs-target="#postModal{{ $item->food_id }}">{{ $item->food_name }}</a></td>
                                                <td>{{ $item->created_at }}</td>
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
                                        </form>                                                    
                                        {{-- 모달 --}}
                                        <div class="modal" tabindex="-1" id="postModal{{ $item->food_id }}" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title">음식번호 {{$item->food_id}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body usermodal">
                                                        <ul>
                                                            <li><span class="w100">음식이름</span> {{$item->food_name}}</li>
                                                            <li><span class="w100">칼로리</span> {{$item->kcal}}KCAL</li>
                                                            <li><span class="w100">탄수화물</span> {{$item->carbs}}g</li>
                                                            <li><span class="w100">단백질</span> {{$item->protein}}g</li>
                                                            <li><span class="w100">지방</span> {{$item->fat}}g</li>
                                                            <li><span class="w100">당</span> {{$item->sugar}}g</li>
                                                            <li><span class="w100">나트륨</span> {{$item->sodium}}g</li>
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
    <script src="{{asset('js/food.js')}}"></script>
@endsection

