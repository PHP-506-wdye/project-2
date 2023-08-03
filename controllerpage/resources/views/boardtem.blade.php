@extends('layout.layout')
@section('title', '게시글관리')
@section('css')
    <link href="{{asset('css/food.css')}}" rel="stylesheet">
@endsection
@section('contents')
<div class="container-fluid">

                <div class="row">
   
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">게시글 관리</h4>
                                <h6 class="card-subtitle">게시글 <code>.관리자용</code></h6>
                                <div class="table-responsive">
                                    <table class="table user-table no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">게시글 번호</th>
                                                <th class="border-top-0">유저 번호</th>
                                                <th class="border-top-0">게시글 제목</th>
                                                {{-- <th class="border-top-0">신고 접수건수</th> --}}
                                                <th class="border-top-0">게시글 생성일</th>
                                                <th class="border-top-0">게시글 삭제일</th>
                                                <th class="border-top-0">삭제여부</th>
                                                <th class="border-top-0">
                                                    <input type="checkbox" name='delchk' value='selectall' onclick='selectAll(this)'>
                                                    <button id="selectdel" onclick="event.preventDefault(); massDelete('{{ route('comment.bordmassDelete') }}')">선택삭제</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach ($data as $item)
                                                    <form action="{{ route('board.boarddel', ['id' => $item->board_id])}}"  id="deleteForm{{ $item->board_id }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <tr>
                                                            <td>{{ $item->board_id }}</td>
                                                            <td>{{ $item->user_id }}</td>
                                                            <td class="btitle">{{ $item->btitle }}</td>
                                                            {{-- <td>{{ $item->Count }}</td> --}}
                                                            <td>{{ $item->created_at }}</td>
                                                            <td>{{$item->deleted_at}}</td>
                                                                @if($item->deleted_at === null)
                                                                    <td><button type="submit" class="delBtn">삭제</button></td>
                                                                <td class="masschk">    
                                                                    <input type="checkbox" class="delchk" name="delchk[]" value="{{ $item->board_id }}">
                                                                </td>
                                                                @elseif($item->deleted_at !== null)
                                                                    <td>삭제된 게시글입니다.</td>
                                                                @endif
                                                        </tr>
                                                    </form>   
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
                                            <a href="{{ $data->url(1) }}" rel="prev"><<</a>
                                        </li>
                                    @endif
                                    {{-- 이전 페이지 버튼 --}}
                                    @if ($data->onFirstPage())
                                        <li><</li>
                                    @else
                                        <li class="active">
                                            <a href="{{ $data->previousPageUrl() }}" rel="prev"><</a>
                                        </li>
                                    @endif
                                    {{-- 페이징 --}}
                                    {{-- range() : 지정된 범위의 숫자를 생성하여 배열로 반환 --}}
                                    @foreach(range($startPage, $endPage) as $i)
                                        @if ($i == $data->currentPage())
                                            <li class="active"><span>{{ $i }}</span></li>
                                        @else
                                            <li class="active">
                                                <a href="{{$data->url($i)}}">{{$i}}</a>
                                            </li>
                                        @endif
                                    @endforeach
                
                                    {{-- 다음 페이지 버튼 --}}
                                    @if ($data->hasMorePages())
                                        <li class="active">
                                            <a href="{{$data->nextPageUrl()}}">></a>
                                        </li>
                                    @else
                                        <li>></li> 
                                    @endif
                
                                    {{-- 마지막 페이지 --}}
                                    @if ($data->hasMorePages())
                                        <li class="active">
                                            <a href="{{ $data->url($data->lastPage()) }}" rel="next">>></a>
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
    <script src="/js/member.js"></script>
@endsection