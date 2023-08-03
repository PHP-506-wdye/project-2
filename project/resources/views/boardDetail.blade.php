@extends('layout.boardNav')

@section('title', '게시글 상세')

@section('boardContent')
    <div class="shadowWhite">
        <div class="divUnderShadow">
            <div class="boardDetailTitle">
                <div>{{$data['title']}}</div>
                <div>{{$data['cate']}}</div>
                <div>
                    @if (isset($data['style']))
                        {{$data['style']}}
                    @endif
                    {{$data['nkname']}}</div>
                <div>{{substr($data['created_at'], 0, 16)}}</div>
                <div><i class="bi bi-bar-chart-fill"></i> {{number_format($data['hits'])}}</div>
                {{-- 신고 --}}
                {{-- v002 add start --}}
                <div>
                    <button type="button" id="reportBtn" data-bs-toggle="modal" data-bs-target="#reportpost">
                        <i class="fa-solid fa-triangle-exclamation"></i> 신고
                    </button>
                </div>
                {{-- v002 add end --}}
            </div>
            {{-- v002 add start --}}
            <div class="modal fade" id="reportpost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">게시글 신고</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('report')}}" method="post">
                                @csrf
                                <input type="hidden" name="reporter" value="{{Auth::user()->user_id}}">
                                <input type="hidden" name="suspect" value="{{$data['user_id']}}">
                                <input type="hidden" name="board_id" value="{{$data['id']}}">
                                <label for="reportreason">신고 사유</label>
                                <select name="reportselect" id="reportreason">
                                    <option value="0">불법광고</option>
                                    <option value="1">비방 및 욕설</option>
                                    <option value="2">허위 사실 유포</option></option>
                                    <option value="3">지속적인 도배</option>
                                    <option value="4">기타</option>
                                </select>
                                <textarea name="reporttext" id="reportreason" cols="30" rows="10"></textarea>
                                <div class="modal-footer">
                                    <button type="button" data-bs-dismiss="modal">취소</button>
                                    <button type="submit" id="greenBtn">신고</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- v002 add end --}}
            <div class="boardDetailContet">
                @if (isset($data['img']))
                <div class="imgDiv">
                    <img src="{{asset('storage/images/board/'.$data['img'])}}" class="img-fluid" alt="1">
                </div>
                @endif
                <span>{!! $data['content']!!}</span>
                {{-- v002 add start --}}
                @if ($diet)
                    <div class="dietshere"> {{-- 게시글에 입력한 식단 출력 div --}}
                        @foreach ($diet as $foods)
                            <span>{{$foods->food_name}}</span>
                            <span> | </span>
                            <span>{{$foods->fav_f_intake}} 인분</span>
                            <br>
                        @endforeach
                        @if ($data['fav_id'] != 0)
                            <button type="button" class="shereBtn" id="greenBtn" data-bs-toggle="modal" data-bs-target="#exampleModal0">식단 내려받기</button>
                            {{-- 식단 명 입력 모달 --}}
                            <div class="modal fade" id="exampleModal0" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">식단 즐겨찾기에 추가하기</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="fw-bold">식단명을 입력해주세요</p>                                     
                                            <form action="{{route('board.dietdownload', ['favid' => $data['fav_id']])}}" method="post">
                                                @csrf
                                                <input type="text" name="fav_name" required placeholder="식단명을 입력해주세요." autocomplete="off" maxlength="10">
                                                <button type="submit" id="greenBtn" class="dietshare">등록하기</button>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>         
                        @endif
                    </div>
                @endif
                {{-- v002 add end --}}
                <div class="likeDiv">
                    <input type="hidden" id="value1" value="{{session('user_id')}}">
                    <input type="hidden" id="value2" value="{{$data['id']}}">
                    <button
                        class="likeUpDown"
                    @if ($data['like_flg'] === 1)
                        id="greenBtn"
                    @endif
                        type="button"
                        onclick="likeUpDown()"
                        >좋아요 <span id="likes">{{$data['like']}}</span>
                    </button>
                </div>
    
                {{-- todo @can 방법 : https://laracasts.com/series/laravel-6-from-scratch/episodes/50 --}}
                @if (Auth::user()->user_id === $data['user_id'] || Auth::user()->user_id === 0)
                <div class="rigtTwoBtnDiv">
                    @if (Auth::user()->user_id !== 0)
                        <button
                            type="button"
                            onclick="location.href='{{route('board.edit', ['board' => $data['id']])}}'"
                            id="greenBtn"
                            >글 수정
                        </button>
                    @endif
                    <button type="button" id="greenBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                        글 삭제
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">게시글 삭제</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                정말 삭제하시겠습니까?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-bs-dismiss="modal">취소</button>
                                    <form action="{{route('board.destroy', ['board' => $data['id']])}}" method='post'>
                                        @csrf
                                        @method('delete')
                                        <button type="submit" id="greenBtn">삭제</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @include('boardReply')
    </div>
@endsection