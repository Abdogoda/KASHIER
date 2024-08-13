@extends('layouts.guest-app')
@section('title')
    تغيير كلمة المرور
@endsection
@section('content')
    <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="post" action="{{route('password.store')}}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">
        <img src="{{asset('assets/images/logo.png')}}" id="logo" class="navbar-brand-img brand-md" alt="website logo">
        </a>
        <h1 class="h6 mb-3">قم بتغيير كلمة المرور الان</h1>
        <div class="form-group mb-3" style="text-align: right">
            <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{old('email', $request->email)}}" autocomplete="email" autofocus placeholder="البريد الالكتروني">
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
        
        <div class="form-group mb-3" style="text-align: right">
            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="كلمة المرور" >
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>
        <div class="form-group mb-3" style="text-align: right">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="تأكيد كلمة المرور" >
            @if ($errors->has('password_confirmation'))
                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">تغيير الان</button>
    </form>
@endsection