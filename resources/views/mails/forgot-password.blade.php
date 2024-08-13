<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$siteSettings['اسم_الشركة'] ?? 'كاشير'}} | @yield('title')</title>
    
      <!-- Favicon -->
      <link rel="shortcut icon" type="image" href="{{$siteSettings['لوجو_الشركة'] ? asset('storage/'.$siteSettings['لوجو_الشركة']) : asset('assets/images/logo.png')}}" />
</head>
<body>
    <center>
     <h1>مرحبا</h1>
     <p>لقد قمت بطلب تغيير كلمة المرور الخاصة بك</p>
     <p>يمكنك تغيير كلمة المرور من خلال الضغط علي هذا الرابط وسيتم تحويلك الي صفحة تغيير كلمة المرور</p>
     <a href="{{route('password.reset', $token)}}">تغيير كلمة المرور</a>
    </center>
</body>
</html>