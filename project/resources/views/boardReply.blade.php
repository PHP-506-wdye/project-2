<div class="shadowYellow" id="yellowMargin">
    <div>
        @forelse ($reply as $item)
            <div class="replyDiv">
                <div>{{$item->rcontent}}</div>
                <div>
                    <span>{{$item->nkname}}</span>
                    <span>{{substr($item->created_at, 0, 10)}}</span>
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

        @if ($reply->hasPages())
            <ul class="pagination pagination">
                @if ($reply->currentPage() > 1)
                <li>
                    <a href="{{ $reply->previousPageUrl() }}">
                        <span class="fa fa-chevron-left" aria-hidden="true"><</span>
                    </a>
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
                <li>
                    <a href="{{$reply->nextPageUrl()}}">
                        <span class="fa fa-chevron-right" aria-hidden="true">></span>
                    </a>
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
                <div><textarea textarea name="reply" id="reply" placeholder="댓글을 작성하세요.">{{count($errors) > 0 ? old('reply') : ''}}</textarea></div>
                <div><button type="sumbit" id="greenBtn">작성</button></div>
            </div>
        </form>
    </div>
</div>