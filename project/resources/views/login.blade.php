@extends('layout.loginlayout')

@section('title','로그인')

@section('js')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection

@section('contents')
    <form action="{{route('user.loginpost')}}"  method="post" name="loginForm">
        @csrf
        <div class="writein">
            <div class="email_write">
                <label for="email">Email</label>
                <br>
                <input type="text" name="email" id="email" value="{{ $errors->has('email') ? '' : old('email', isset($data) ? $data->user_email : '') }}" autocomplete="off">
            </div>
            @error('email') 
            <div class="err-mgs">{{ $message }}</div>
            @enderror

            <div class="password_write">
                <label for="password">Password</label>
                <br>
                <input type="password" name="password" id="password" autocomplete="off">
            </div>
            @error('password')
            <div class="err-mgs">{{ $message }}</div>
            @enderror

            <div class="error_msg">
                @if(isset($errors))
                    <span class="err-mgs">{{count($errors) > 0 ? $errors->first('idpw', ':message') : ''}}</span>
                @endif
            </div>
            
            <div class="loginBtn">
                <button type="button" id="greenBtn" onclick="loginFormChk()">로그인</button>
            </div>
        </div>
    </form>
    <div class="registBtn">
        <a href="{{route('user.userfindE')}}">이메일찾기</a>
        <a href="{{route('user.regist')}}">회원가입</a>
    </div>
@endsection