@extends('layout.userinfoNav')

@section('title', '나의 작성글')

@section('myboard')
    <div class="col-md-8 offset-lg-1 pb-5 mt-n3 mb-2 mb-lg-4 mt-n3 mt-md-0">
        <div class="ps-md-3 ps-lg-0 mt-md-2 py-md-4">
                <h1 class="h2 pt-xl-1 pb-3">
                    <a href="{{route('user.board')}}" id="boardBtn">나의 게시글({{$boardCnt}})</a> /
                    <a href="{{route('user.reply')}}" id="replyBtn">나의 댓글({{$replyCnt}})</a>
                </h1>
            <div class="row pb-2">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <div class="table"> --}}
                            <div>
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0" width="10%">no</th>
                                            @if($flg)
                                                <th class="border-top-0" width="60%">게시글 제목</th>
                                            @else
                                                <th class="border-top-0" width="60%">댓글 내용</th>
                                            @endif
                                            <th class="border-top-0" width="30%">생성일</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>
                                                    @if(isset($item->reply_id))
                                                        {{ $item->reply_id }}
                                                    @else
                                                        {{ $item->board_id }}
                                                    @endif
                                                </td>
                                                <td class="btitle">
                                                    <a href="{{route('board.show', ['board' => $item->board_id])}}">
                                                        @if(isset($item->btitle))
                                                            {{mb_strlen($item->btitle) >= 15 ? mb_substr($item->btitle,0,15) . "..." :  $item->btitle}}                                            
                                                        @elseif(isset($item->rcontent))
                                                            {{mb_strlen($item->rcontent) >= 15 ? mb_substr($item->rcontent,0,15) . "..." :  $item->rcontent}}
                                                        @endif
                                                    </a>
                                                </td>
                                                <td>{{mb_substr($item->created_at,0,10)}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td></td>
                                                <td>작성한 글이 없습니다.</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
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
    </div>
@endsection
