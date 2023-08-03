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
                <th>게시글 번호</th>
                <th>유저 번호</th>
                <th>게시글 제목</th>
                <th>신고 접수건수</th>
                <th>게시글 생성일</th>
                <th>게시글 삭제일</th>
                <th>삭제여부</th>
            </tr>

            @foreach ($data as $item)
                <form action="{{ route('board.boarddel', ['id' => $item->board_id])}}" method="post">
                    @csrf
                    @method('delete')
                    <tr>
                        <td>{{ $item->board_id }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->btitle }}</td>
                        <td>{{ $item->Count }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{$item->deleted_at}}</td>
                            @if($item->deleted_at === null)
                                <td><button type="submit">삭제</button></td>
                            @elseif($item->deleted_at !== null)
                                <td>삭제된 게시글입니다.</td>
                            @endif
                    </tr>
                </form>
            @endforeach
        </table>
    </div>
</body>
</html>