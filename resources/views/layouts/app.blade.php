<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="كاشير هو تطبيق شامل قائم على الويب مصمم لإدارة مصادقة الموظفين وإصدار الفواتير داخل بيئة تنظيمية. تدور الوظيفة الأساسية لـ كاشير حول توفير سير عمل سلس وفعال للموظفين والعمليات المالية.">
    <meta name="author" content="abdogoda0a@gmail.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>{{$siteSettings['اسم_الشركة'] ?? 'كاشير'}} | @yield('title')</title>
    
      <!-- Favicon -->
      <link rel="shortcut icon" type="image" href="{{$siteSettings['لوجو_الشركة'] ? asset('storage/'.$siteSettings['لوجو_الشركة']) : asset('assets/images/logo.png')}}" />
    
    <!-- Simplebar -->
    <link rel="stylesheet" href="{{asset('assets/css/simplebar.css')}}" />

    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- App CSS -->
    @livewireStyles
    <link rel="stylesheet" href="{{asset('assets/css/feather.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/app-light.css')}}">
    <style>
      body{overflow-x: hidden;}
      body::-webkit-scrollbar, .modal-notif .modal-dialog::-webkit-scrollbar { width: 0.8rem;}
      body::-webkit-scrollbar-thumb, .modal-notif .modal-dialog::-webkit-scrollbar-thumb  { background-color: darkgrey;}
      aside::-webkit-scrollbar{width: 0}
      .vertical .main-content, .vertical.hover .main-content, .narrow.open .main-content {min-height: auto}
      .modal-notif .modal-dialog{
        overflow: scroll;
      }
    </style>
    @yield('css')
  </head>
  <body class="vertical  light rtl collapsed" style="position: relative">
    {{-- loader --}}
    <div id="loader" class="loader"><div class="spinner"></div></div>

    {{-- wrapper --}}
    <div class="wrapper" id="wrapper" style="display: none">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <main role="main" class="main-content">
          <div class="container-fluid">
            @yield('content')
        </div> <!-- .container-fluid -->
        <div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">الاشعارات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="list-group list-group-flush my-n3">
                  @forelse ($siteNotifications as $notification)
                    <div class="list-group-item bg-transparent">
                      <div class="row align-items-center">
                        <div class="col-auto pr-0 ">
                          @if ($notification->type == 'warning')
                            <span class="fe fe-alert-circle text-warning fe-24"></span>
                          @endif
                        </div>
                        <div class="col ">
                          <div class="my-0 text-muted small">{{$notification->message}}</div>
                          <small class="badge badge-pill badge-light text-muted">{{$notification->created_at->diffForHumans()}}</small>
                        </div>
                      </div>
                    </div>
                  @empty
                  <div class="list-group-item text-center text-muted">لا يوجد اشعارات متاحة</div>
                  @endforelse
                </div>
              </div>
              @if ($siteNotifications->count() > 0)
                <div class="modal-footer mb-2">
                  <form action="{{route('notification')}}" method="post" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-block">تعيين كمقروء</button>
                  </form>
                </div>
              @endif
            </div>
          </div>
        </div>
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/simplebar.min.js')}}"></script>
    <script src='{{asset('assets/js/daterangepicker.js')}}'></script>
    <script src='{{asset('assets/js/jquery.stickOnScroll.js')}}'></script>
    <script src="{{asset('assets/js/tinycolor-min.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
          document.getElementById('loader').style.display = 'none';
          document.getElementById('wrapper').style.display = 'block';
        }, 1000);
      });
    </script>
    @stack('scripts')
    <script src="{{asset('assets/js/apps.js')}}"></script>
    @livewireScripts
    @yield('js')
  </body>
</html>