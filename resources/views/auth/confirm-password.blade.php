@extends('layouts.guest-app')
@section('title')
    تأكيد كلمة المرور
@endsection
@section('content')
  <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="post" action="{{route('password.confirm')}}">
    @csrf
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">
      <img src="{{$siteSettings['لوجو_الشركة'] ? asset('storage/'.$siteSettings['لوجو_الشركة']) : asset('assets/images/logo.png')}}" id="logo" class="navbar-brand-img brand-md" alt="website logo">
    </a>
    <h1 class="h6 mb-3">قم بتأكيد كلمة المرور للاستمرار</h1>
    <div class="form-group mb-3" style="text-align: right">
      <label for="password" class="sr-only">كلمة المرور</label>
      <input type="password" id="password" name="password" class="form-control form-control-lg" autocomplete="current-password" autofocus placeholder="كلمة المرور" >
      @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
      @endif
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">تأكيد</button>
  </form>
@endsection