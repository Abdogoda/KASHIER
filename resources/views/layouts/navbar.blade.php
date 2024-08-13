<nav class="topnav navbar navbar-light">
 <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
   <i class="fe fe-menu navbar-toggler-icon"></i>
 </button>
 <h5 class="mr-auto mb-0 d-none d-md-block">
  <a href="{{route('employees.show', auth()->user())}}" class="text-muted">الموظف : {{auth()->user()->name}}</a>
 </h5>
 <div>
  @if ($current_shift)
    @can('end_shift')
      <form action="{{route('shifts.update', $current_shift)}}" method="post" class="w-100 d-flex justify-content-center">
        @csrf
        @method('put')
        <button type="submit" class="btn btn-danger m-1" onclick="return confirm('هل أنت متأكد من أنك تريد انهاء الوردية الجارية؟')">انهاء الوردية</button>
      </form>
    @endcan
  @else
    @can('start_shift')
      <button class="btn btn-success text-white" data-toggle="modal" data-target="#start_shift" >بدء وردية جديدة</button>
    @endcan
  @endif
 </div>
 <ul class="nav">
   <li class="nav-item nav-notif">
     <a class="nav-link text-muted my-2" title="الاشعارات" href="#" data-toggle="modal" data-target=".modal-notif">
       <span class="fe fe-bell fe-16"></span>
       @if ($siteNotifications->count() > 0)
         <span class="dot dot-md bg-danger"></span>
       @endif
     </a>
   </li>
   <li class="nav-item dropdown">
     <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       <span class="avatar avatar-sm mt-2">
         <img src="{{asset('assets/images/icons/profile.png')}}" alt="employee-image" class="avatar-img rounded-circle">
       </span>
     </a>
     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
       <a class="dropdown-item text-center" href="{{route('profile')}}">عرض الحساب</a>
       @can('view_settings')
         <a class="dropdown-item text-center" href="{{route('settings.index')}}">الاعدادات</a>
       @endcan
       <a class="">
        <div class="p-2 pt-0" style="padding-top: 0">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-danger w-100">تسجيل الخروج</a>
           </form>
        </div>
       </a>
     </div>
   </li>
 </ul>
</nav>

    {{-- start shift modal --}}
    <div class="modal fade" id="start_shift" tabindex="-1" role="dialog"
        aria-labelledby="start_shiftLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="start_shiftLabel">بدء وردية جديدة </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('shifts.store')}}" method="post">
                    @csrf
                    <div class="modal-body"> 
                        <div class="col-md-12 mb-3 form-group">
                            <label for="employee_name">اسم الموظف</label>
                            <input type="text" readonly name="employee_name" value="{{auth()->user()->name}}" class="form-control" id="employee_name">
                        </div>
                        <div class="col-md-12 mb-3 form-group">
                            <label for="initial_amount">الرصيد الافتتاحي</label>
                            <input type="number" min="0" readonly name="initial_amount" value="{{App\Models\FundAccount::first()->balance}}" class="form-control" id="initial_amount">
                        </div>
                        <div class="col-md-12 mb-3 form-group">
                            <label for="start_date">وقت البداية</label>
                            <input type="datetime-local" dir="rtl" readonly name="start_date" value="{{date('Y-m-d\TH:i')}}" class="form-control" id="start_date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-primary">بدء الوردية</button>
                    </div>
                </form>
            </div>
        </div>
    </div>