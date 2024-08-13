@extends('layouts.guest-app')
@section('title')
    تسجيل الدخول
@endsection
@section('content')
  <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="post" action="{{route('login')}}">
    @csrf
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">
      <img src="{{asset('assets/images/logo.png')}}" id="logo" class="navbar-brand-img brand-md" alt="website logo">
    </a>
    <h1 class="h6 mb-3">سجل الدخول الي حسابك الان</h1>
    <div class="form-group mb-3" style="text-align: right">
      <label for="name" class="sr-only">اسم الموظف</label>
      <input type="name" id="name" name="name" class="form-control form-control-lg" placeholder="اسم الموظف"  autofocus>
      @if ($errors->has('name'))
          <span class="text-danger">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group mb-3" style="text-align: right">
      <label for="password" class="sr-only">كلمة المرور</label>
      <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="كلمة المرور" >
      @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
      @endif
    </div>
    <p>هل نسيت كلمة المرور؟ <a href="{{url('forgot-password')}}">اضغط هنا</a></p>
    <button class="btn btn-lg btn-primary btn-block" type="submit">تسجيل الدخول</button>
  </form>
@endsection