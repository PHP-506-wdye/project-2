@extends('layout.loginlayout')

@section('title', '회원가입')


@section('contents')
<div class="regist-body">
    <div class = "registpage">
        <form action="{{route('user.registpost')}}" method="post">
            @csrf
            <div class="registinfo">
                <div>
                    <h1>회원가입</h1>
                </div>
                <div class="email">
                    <label for="user_email">이메일</label>
                    <br>
                    @if (isset($userInfo['email']))
                        <input type="text" name="user_email" id="user_email" value="{{ $errors->has('user_email') ? old('user_email') : $userInfo['email'] }}" required readonly>
                    @else
                        <input type="text" name="user_email" id="user_email" value="{{ $errors->has('user_email') ? '' : old('user_email', isset($data) ? $data->user_email : '') }}" autocomplete="off" required>
                        {{-- <button type="button" id="checkEmail" onclick="duplicationEmail();">중복체크</button> --}}
                        <div id="mailMsg"></div>
                        @error('user_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div>
                    <label for="user_name">이름</label>
                    <br>
                    @if (isset($userInfo['name']))
                        <input type="text" name="user_name" id="user_name" value="{{ $errors->has('user_name') ? old('user_name') : $userInfo['name'] }}" required
                        @if ($userInfo['name'] !== '')
                            readonly
                        @endif
                        >
                    @else
                        <input type="text" name="user_name" id="user_name" value="{{ $errors->has('user_name') ? '' : old('user_name', isset($data) ? $data->user_name : '') }}" required>
                        @error('user_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                @if (isset($userInfo['social']))
                    <input type="hidden" name="social" value="{{ $userInfo['social'] }}">
                @endif
                <div>
                    <label for="password">비밀번호</label>
                    <br>
                    <input type="password" name="password" id="password"  value="{{ $errors->has('password') ? '' : old('password', isset($data) ? $data->password : '') }}" required oninput="pwCheck();">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="passwordchk">비밀번호 확인</label>
                    <br>
                    <input type="password" name="passwordchk" id="passwordchk" value="{{ $errors->has('passwordchk') ? '' : old('passwordchk', isset($data) ? $data->passwordchk : '') }}" required oninput="pwCheck();">
                    <div id="pwMsg"></div>
                    @error('passwordchk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="nkname">닉네임</label>
                    <br>
                    <input type="text" name="nkname" id="nkname"  value="{{ $errors->has('nkname') ? '' : old('nkname', isset($data) ? $data->nkname : '') }}" required>
                    @error('nkname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="user_phone_num">전화번호</label>
                    <br>
                    @if (isset($userInfo['phonenum']))
                        <input type="text" name="user_phone_num" id="user_phone_num" value="{{ $errors->has('user_phone_num') ? old('user_name') : $userInfo['phonenum'] }}" required
                        @if ($userInfo['phonenum'] !== '')
                            readonly
                        @endif
                        >
                    @else
                        <input type="text" name="user_phone_num" id="user_phone_num"  value="{{ $errors->has('user_phone_num') ? '' : old('user_phone_num', isset($data) ? $data->user_phone_num : '') }}" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        @error('user_phone_num')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
                <div>
                    <label for="user_birth">생년월일</label>
                    <input type="date" name="user_birth" id="user_birth" max="{{ now()->toDateString() }}" required>
                </div>
                <div>
                    <label for="gender">성별</label>
                    <input type="radio" name="gender" id="gender0" value="0" checked> 남자
                    <input type="radio" name="gender" id="gender1" value="1"> 여자
                    @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="regisBtn">
                    <button type="button" onclick="location.href='{{route('user.login')}}'" id="exit">취소</button>
                    <button type ="submit" id="signupButton" class="greenBtn">회원가입</button>
                </div>
            </div>
        </form>
        @if(session('error'))
            {{session('error')}}
        @endif
        @if(session('success'))
        {{session('success')}}
    @endif
    </div>
</div>

@endsection

@section('js')
    <script src="{{ asset('js/regist.js') }}"></script>
@endsection