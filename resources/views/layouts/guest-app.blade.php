<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="كاشير هي عبارة عن منصة إدارة مالية مبتكرة وشاملة مصممة لتبسيط وتعزيز الطريقة التي تتعامل بها الشركات مع عملياتها المالية. تم تصميم كاشير باستخدام إطار عمل Laravel القوي، ويوفر واجهة آمنة وسهلة الاستخدام لإدارة الجوانب المالية المختلفة، بما في ذلك مصادقة الموظفين والفواتير والسجلات المالية.">
    <meta name="author" content="abdogoda0a@gmail.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>كاشير | @yield('title')</title>

      <!-- Favicon -->
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo.png')}}" />
    
    <!-- Simplebar -->
    <link rel="stylesheet" href="{{asset('assets/css/simplebar.css')}}" />

    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/feather.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/app-light.css')}}">
    <style>
    body{
      overflow: hidden;
    }
    aside::-webkit-scrollbar{width: 0}
    .vertical .main-content, .vertical.hover .main-content, .narrow.open .main-content {min-height: auto}
    </style>

  </head>
  <body class="light rtl">
    <div class="wrapper vh-100">
      <div class="row align-items-center h-100">
        @yield('content')
      </div>
    </div>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/simplebar.min.js')}}"></script>
 
    <script src="{{asset('assets/js/jquery.stickOnScroll.js')}}"></script>
    <script src="{{asset('assets/js/tinycolor-min.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>
 
    <script src="{{asset('assets/js/apps.js')}}"></script>
  </body>
</html>
</body>
</html>