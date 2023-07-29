@extends('layout.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/userinfo.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection


@section('contents')
    <section class="container pt-5">
        <div class="row">
            <!-- 프로필 이미지 (왼쪽) 영역 -->
            <aside class="col-lg-3 col-md-4 border-end pb-5 mt-n5">
                <div class="position-sticky top-0">
                <div class="text-center pt-5">
                    {{-- <div class="d-table position-relative mx-auto mt-2 mt-lg-4 pt-5 mb-3">
                    </div> --}}
                    @if(isset($medal))
                        <a href="{{route('quest.questAchieve')}}" id="medal">{{ $medal }} <p id="medalChange"></p></a>
                    @endif
                    {{-- <h2 class="h5 mb-1">{{Auth::user()->user_name}}</h2>
                    <p class="mb-3 pb-3">{{Auth::user()->user_email}}</p> --}}
                    <button type="button" class="btn w-100 d-md-none mt-n2 mb-3 collapsed" data-bs-toggle="collapse" data-bs-target="#account-menu" aria-expanded="false">
                    <i class="bx bxs-user-detail fs-xl me-2"></i>
                    My Page
                    <i class="bx bx-chevron-down fs-lg ms-1"></i>
                    </button>
                    <div id="account-menu" class="list-group list-group-flush d-md-block collapse">
                    <a href="{{route('user.userinfoedit')}}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-user fs-xl opacity-60 me-2"></i>
                        나의 정보
                    </a>
                    <a href="{{route('user.userpsedit')}}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-lock-alt fs-xl opacity-60 me-2"></i>
                        비밀번호변경
                    </a>
                    <a href="{{route('user.prevateinfo')}}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-bowl-rice fs-xl opacity-60 me-2"></i>
                        식단설정
                    </a>
                    <a href="{{route('user.board')}}" class="list-group-item list-group-item-action d-flex align-items-center" id="myboard">
                        <i class="bx bx-clipboard fs-xl opacity-60 me-2"></i>
                        내가 남긴 글 & 댓글
                    </a>
                    <a href="{{route('user.userwithdraw')}}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-user-minus fs-xl opacity-60 me-2"></i>
                        회원탈퇴
                    </a>
                    <a href="{{route('user.logout')}}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-log-out fs-xl opacity-60 me-2"></i>
                        로그아웃
                    </a>
                    </div>
                </div>
                </div>
            </aside>
            <!-- 상세 정보 영역 (오른쪽) 영역 -->
            @yield('userinfocontents')
            @yield('prevateinfocontents')
            @yield('passwordcontents')
            @yield('userdrawcontents')
            @yield('myboard')
        </div>
    </section>

    {{-- <div class="boardCon">
        <div class="dis-left"></div>
        <div class="boardNavDiv">
            <div>회원 정보</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.userinfoedit')}}">나의 정보</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.userpsedit')}}">비밀번호 변경</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.prevateinfo')}}">식단 설정 변경</a>
                </li>
            </ul>
        </div>
        <div class="prevateConDiv">
            @yield('prevateinfocontents')
            @yield('userinfocontents')
            @yield('passwordcontents')
        </div>
        <div class="dis-right"></div>
    </div> --}}
@endsection

@section('js')
    <script src="{{ asset('js/userinfo.js') }}"></script>
    @yield('subjs')
@endsection