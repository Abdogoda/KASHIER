@extends('layouts.guest-app')
@section('title')
    استرجاع كلمة المرور
@endsection
@section('content')
  <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="post" action="{{route('password.email')}}">
    @csrf
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">
      <img src="{{asset('assets/images/logo.png')}}" id="logo" class="navbar-brand-img brand-md" alt="website logo">
    </a>
    <h1 class="h6 mb-3">قم باسترجاع كلمة المرور الان</h1>
    <div class="form-group mb-3" style="text-align: right">
      <label for="email" class="sr-only">البريد الالكتروني</label>
      <input type="email" id="email" name="email" class="form-control form-control-lg" autocomplete="email" autofocus placeholder="البريد الالكتروني">
      @if ($errors->has('email'))
          <span class="text-danger">{{ $errors->first('email') }}</span>
      @endif
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">استرجاع</button>
  </form>
@endsection