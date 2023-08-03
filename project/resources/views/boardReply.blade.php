<div class="shadowYellow" id="yellowMargin">
    <div>
        @forelse ($reply as $item)
            <div class="replyDiv">
                <div>{{$item->rcontent}}</div>
                <div>
                    <span>{{$item->quest_style}} {{$item->nkname}}</span>
                    <br id="disNone">
                    <span>{{substr($item->created_at, 0, 10)}}</span>
                    {{-- v002 댓글 신고 버튼 --}}
                    <button type="button" id="reportBtn" data-bs-toggle="modal" data-bs-target="#reportreply">
                        <i class="fa-solid fa-triangle-exclamation"></i> 신고
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="reportreply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">댓글 신고</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('report')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="reporter" value="{{Auth::user()->user_id}}">
                                        <input type="hidden" name="suspect" value="{{$item->user_id}}">
                                        <input type="hidden" name="reply_id" value="{{$item->reply_id}}">
                                        <label for="reportreason">신고 사유</label>
                                        <select name="reportselect" id="reportreason">
                                            <option value="0">불법광고</option>
                                            <option value="1">비방 및 욕설</option>
                                            <option value="2">허위 사실 유포</option></option>
                                            <option value="3">지속적인 도배</option>
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
                    {{-- <button type="button" id="deleteBtn" onclick="reReplyFormInsert(event)">ㄴ</button> --}}
                    {{-- v002 --}}
                    {{-- 댓글 삭제버튼 --}}
                    <span>
                        @if (Auth::user()->user_id === $item->user_id)
                            <button type="button" id="deleteBtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                                X
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">댓글 삭제</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        정말 삭제하시겠습니까?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-bs-dismiss="modal">취소</button>
                                            <form action="{{route('board.replyDelete', ['board' => $data['id'], 'id' => $item->reply_id])}}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" id="greenBtn">삭제</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </span>
                </div>
                <div>
                    <hr>
                </div>
            </div>
        @empty
            <div>댓글이 없습니다</div>
            <hr>
        @endforelse

        {{-- 페이지네이션 --}}
        @if ($reply->hasPages())
            <ul class="pagination pagination">
            @if ($reply->currentPage() > 1)
            <li class="active">
                <a href="{{ $reply->previousPageUrl() }}"><</a>
            </li>
            @else
                <li><</li>
            @endif

            @for($i = 1; $i <= $reply->lastPage(); $i++)
                @if ($i == $reply->currentPage())
                    <li class="active"><span>{{ $i }}</span></li>
                @else
                    <li><a href="{{ $reply->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor
            
            @if ($reply->currentPage() < $reply->lastPage() )
                <li class="active">
                    <a href="{{$reply->nextPageUrl()}}">></a>
                </li>
            @else
                <li>></li>
            @endif
            </ul>
        @endif

        <form action="{{route('board.replyPost')}}" method="post">
            @csrf
            <div id="replyInsertDiv">
                <div class="errorMsg">{{count($errors) > 0 ? $errors->first('reply', ':message') : ''}}</div>
                <div><input type="hidden" name="board_id" value="{{$data['id']}}"></div>
                <div><input type="hidden" name="user_id" value="{{$data['user_id']}}"></div>
                <div><textarea textarea name="reply" id="reply" placeholder="댓글을 작성하세요.">{{count($errors) > 0 ? old('reply') : ''}}</textarea></div>
                <div><button type="sumbit" id="greenBtn">작성</button></div>
            </div>
        </form>
    </div>
</div>