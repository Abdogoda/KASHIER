<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="كاشير هو تطبيق شامل قائم على الويب مصمم لإدارة مصادقة الموظفين وإصدار الفواتير داخل بيئة تنظيمية. تدور الوظيفة الأساسية لـ كاشير حول توفير سير عمل سلس وفعال للموظفين والعمليات المالية.">
    <meta name="author" content="abdogoda0a@gmail.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>{{$siteSettings['name']->value ?? 'كاشير'}} | 401 - غير مصرح</title>
    
      <!-- Favicon -->
      <link rel="shortcut icon" type="image/x-icon" href="{{$siteSettings['لوجو_الشركة'] ? asset('storage/'.$siteSettings['لوجو_الشركة']) : asset('assets/images/logo.png')}}" />
      <link rel="stylesheet" href="{{asset('assets/css/app-light.css')}}" id="lightTheme">
  </head>
  <body class="light rtl">
    <div class="wrapper vh-100">
      <div class="align-items-center h-100 d-flex w-50 mx-auto">
        <div class="mx-auto text-center">
          <img src="{{asset('assets/images/errors/401.svg')}}" class="mx-auto my-2" alt="401-error-image" style="width:200px; max-width: 100%">
          <h1 class="display-1 m-0 font-weight-bolder text-muted" style="font-size:80px;">401</h1>
          <h1 class="mb-1 text-muted font-weight-bold">401 - غير مصرح</h1>
          <h4 class="mb-3 text-muted">عذرًا، ليس لديك الصلاحية للوصول إلى هذه الصفحة.</h4>
          <a href="{{route('dashboard')}}" class="btn btn-lg btn-primary px-5">الرجوع الي لوحة التحكم</a>
        </div>
      </div>
    </div>
  </body>
</html>
</body>
</html>