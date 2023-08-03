@extends('layout.userinfoNav')

@section('title')
{{Auth::user()->user_name}}'s Profile
@endsection

@section('userinfocontents')
<!-- 상세 정보 영역 (오른쪽) 영역 -->
@if(isset($medal))
<p>{{ $medal }}</p>
@endif
<div class="col-md-8 offset-lg-1 pb-5 mt-n3 mb-2 mb-lg-4 pt-md-5 mt-n3 mt-md-0">
    <div class="ps-md-3 ps-lg-0 mt-md-2 py-md-4">
        <h1 class="h2 pt-xl-1 pb-3">나의 정보</h1>
        <form class="needs-validation border-bottom pb-3 pb-lg-4" action="{{ route('user.userinfoeditPost') }}" method="post">
            @csrf
            <div class="row pb-2">
                <div class="col-sm-12 mb-4">
                    <label for="user_email" class="form-label fs-base">이메일</label>
                    <input type="email" id="user_email" class="form-control form-control-lg" value="{{ $data->user_email }}" required readonly>
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="user_name" class="form-label fs-base">이름</label>
                    <input type="text" id="user_name" name="user_name" class="form-control form-control-lg" value="{{old('user_name') !== null ? old('user_name') : $data->user_name}}" required>
                    @error('user_name')
                    <div class="fc-red">{{ $message }}</div>
                @enderror
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="nkname" class="form-label fs-base">닉네임</label>
                    <input type="text" id="nkname" name="nkname" class="form-control form-control-lg" value="{{old('nkname') !== null ? old('nkname') : $data->nkname}}" required >
                    @error('nkname')
                        <div class="fc-red">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="user_birth" class="form-label fs-base">생년월일</label>
                    <input type="date" id="user_birth" name="user_birth" class="form-control form-control-lg" required value="{{$userKcal->user_birth}}" readonly max="{{ now()->toDateString() }}">
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="user_phone_num" class="form-label fs-base">휴대전화</label>
                    <input type="tel" id="user_phone_num" name="user_phone_num" class="form-control form-control-lg" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value= "{{old('user_phone_num') !== null ? old('user_phone_num') : $data->user_phone_num}}" >
                    @error('user_phone_num')
                        <div class="fc-red">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="user_tall" class="form-label fs-base">키</label>
                    <input type="number" id="user_tall" class="form-control form-control-lg" value="{{ $errors->has('user_tall') ? old('user_tall') : $userKcal->user_tall}}" name="user_tall">
                    @error('user_tall')
                        <div class="fc-red">{{ $user_tall }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 mb-4">
                    <label for="user_weight" class="form-label fs-base">몸무게</label>
                    <input type="number" id="user_weight" class="form-control form-control-lg" value="{{$userKcal->user_weight}}" name="user_weight">
                    @error('user_weight')
                        <div class="fc-red">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex mb-4">
                <button type="reset" class="btn btn-secondary me-3">취소</button>
                <button type="submit" class="btn greenBtn">정보수정</button>
            </div>
        </form>
        @if(session('changemsg'))
            {{session('changemsg')}}
        @endif
    </div>
</div>

{{-- <div class="shadowYellow">
    <div>
        <h2>나의 정보</h2>
        <div class="listCon">
            <table>
                <form action="{{ route('user.userinfoeditPost') }}" method="post">
                    @csrf
                <tr class="trBasic">    
                    <th class="info-table"><label for="user_email">Email  </label></th>
                    <td class="info-table"><input type="text" name="user_email" id="user_email" value= "{{old('user_email') !== null ? old('user_email') : $data->user_email}}" readonly></td>
                </tr>
                <tr>
                    <th class="info-table"><label for="user_name">이름  </label></th>
                    <td class="info-table"><input type="text" name="user_name" id="user_name" value= "{{old('user_name') !== null ? old('user_name') : $data->user_name}}"></td>
                </tr>
                <tr>
                    <th class="info-table"><label for="nkname">닉네임  </label></th>
                    <td class="info-table"><input type="text" name="nkname" id="nkname" value= "{{old('nkname') !== null ? old('nkname') : $data->nkname}}">
                        <span id="nkRegexm"></span>
                    </td>
                </tr>
                <tr>
                    <th class="info-table"><label for="user_phone_num">전화번호  </label></th>
                    <td class="info-table"><input type="text" name="user_phone_num" id="user_phone_num" value= "{{old('user_phone_num') !== null ? old('user_phone_num') : $data->user_phone_num}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                </tr>
                    <caption><button type = "submit" id="greenBtn">정보수정</button></caption>
                    @if(session('changemsg'))
                        {{session('changemsg')}}
                    @endif
                </form>
            </table>
        </div>    
    </div>
</div> --}}

@endsection

{{-- @section('js')
    <script src="{{ asset('js/userinfo.js') }}"></script>
@endsection --}}


























{{------------------ 제외 코드 최초수정 : 230627------------------------------------------}}
{{-- //----------------------------------------------------------------------------------- --}}
 {{-- <div class="Physical-table">
        <div class="info-boxt">
            <table class="info-t">
                <form action="{{ route('user.userKcalup') }}" method="post">
                    @csrf
                <tr>    
                    <th class="info-table"><label for="user_birth">나이(생년월일) : </label><span id="userage"></span></th>
                    <td  class="info-table" colspan="3"><input type="date" name="user_birth" id="user_birth" value= "{{old('user_birth') !== null ? old('user_birth') : $data->user_birth}}"></td>
                </tr>
                <tr>
                    <th class="info-table"><label for="user_tall">키 : </label></th>
                    <td class="info-table" colspan="3"><input type="text" name="user_tall" id="user_tall" value= "{{old('user_tall') !== null ? old('user_tall') : $data->user_tall}}"></td>
            </tr>
            <tr>
                <th class="info-table"><label for="user_weight">몸무게 : </label></th>
                <td class="info-table" colspan="3"><input type="text" name="user_weight" id="user_weight" value= "{{old('user_weight') !== null ? old  ('user_weight') : $data->user_weight}}"></td>
            </tr>
            <tr>
                <th class="info-table"><label for="user_activity">활동량 : </label></th>
                <td class="info-table"><input type="radio" name="user_activity" id="user_activity" value="0">적음(1.2)</td>
                <td class="info-table"><input type="radio" name="user_activity" id="user_activity" value="1">보통(1.55)</td>
                <td class="info-table"><input type="radio" name="user_activity" id="user_activity" value="2">많음(1.9)</td>
            </tr>
                <br>
                <caption><button type = "submit" id="userinfosubmit">기본자료수정</button></caption>
            </table>
        </div>    
    </div>
</div> --}}