<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <table>
            <tr>
                <th>댓글번호</th>
                <th>유저번호</th>
                <th>게시글번호</th>
                <th>댓글 내용</th>
                <th>신고 접수건수</th>
                <th>댓글 생성일자</th>
                <th>댓글 삭제일자</th>
                <th>삭제여부</th>
            </tr>
        @foreach ($data as $item)
            <form action="{{ route('comment.commentdel', ['id' => $item->reply_id])}}" method="post">
                @csrf
                @method('delete')
                    <tr>
                        <td>{{ $item->reply_id }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->board_id }}</td>
                        <td>{{ $item->rcontent }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{$item->deleted_at}}</td>
                            @if($item->deleted_at === null)
                                <td><button type="submit">삭제</button></td>
                            @elseif($item->deleted_at !== null)
                                <td>삭제된 댓글입니다.</td>
                            @endif
                    </tr>
                </form>
            @endforeach
        </table>
    </div>
</body>
</html>