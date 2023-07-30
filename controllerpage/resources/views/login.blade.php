<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Monsterlite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Monster admin lite design, Monster admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Monster Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="xe1t9is6-Q1bcuuJ8G5rdTXWCRqzkSat7FUI">
    <title>Login</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/monster-admin-lite/" />
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
    <!-- Custom CSS -->
    <link href="{{asset('temple/css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/login.css')}}" rel="stylesheet">
</head>
<body>
    <h1 class="text-center">RE:BORN 관리자</h1>
    <div id="wrap">
        <form action="{{route('login.post')}}"  method="post">
            @csrf
            <div class="container">
                <div>
                    <label for="text">ID</label>
                    <br>
                    <input type="text" name="email" id="email" value="{{ $errors->has('email') ? '' : old('email', isset($data) ? $data->user_email : '') }}" autocomplete="off">
                </div>
                {{-- @error('email') 
                <div class="err-mgs">{{ $message }}</div>
                @enderror --}}
                <div class="pwBox">
                    <label for="password">Password</label>
                    <br>
                    <input type="password" name="password" id="password" autocomplete="off">
                </div>
                {{-- @error('password')
                <div class="err-mgs">{{ $message }}</div>
                @enderror --}}
                <div class="error_msg">
                    @if(session('error'))
                        {{session('error')}}
                    @endif
                </div>    
                <div class="loginBtn">
                    <button type="submit" id="loginBtn">로그인</button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{asset('temple/assets/plugins/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('temple/assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('temple/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('temple/js/waves.js')}}"></script>
    <script src="{{asset('temple/js/sidebarmenu.js')}}"></script>
    <script src="{{asset('temple/js/custom.js')}}"></script>
    <script src="{{asset('js/food.js')}}"></script>
</body>

</html>


