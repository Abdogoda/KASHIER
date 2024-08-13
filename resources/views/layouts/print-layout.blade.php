<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('assets/css/print.css')}}">
    </head>
    <body>

        <div class="invoice-wrapper" id="print-area" dir="rtl">
            <div class="invoice">
                <div class="invoice-container">
                    <div class="invoice-head">
                        <div class=" text-center border-bottom">
                            <h3>{{$siteSettings['اسم_الشركة'] ?? 'كاشير'}}</h3>
                            <p>ادارة: {{$siteSettings['اسم_المدير'] ?? $siteSettings['اسم_الشركة']}} </p>
                        </div>
                        <p class="invoice-title">@yield('title')</p>
                        @yield('details')
                    </div>
                    <div class="overflow-view">
                        <div class="invoice-body">
                            @yield('products')
                            {{--  --}}
                            @yield('bottom')
                        </div>
                    </div>
                    <div class="invoice-foot text-center">
                     <div class="invoice-foot-datails">
                      <h5 class="text-center">{{$siteSettings['اسم_الشركة']}} | 
                        {{$siteSettings['رقم_الشركة_1']}}</h5>
                     </div>
                    </div>
                </div>
            </div>
        </div>

        <script>window.print();</script>
    </body>
</html>