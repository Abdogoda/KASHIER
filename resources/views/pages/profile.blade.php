@extends('layouts.app')
@section('title') عرض حسابي @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">عرض حسابي</h3>
            </div>
            <form action="{{route('profile.update')}}" method="post">
             @csrf
             @method('patch')
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم الموظف <span class="text-danger">*</span></label>
               <input type="text" {{$employee->id == auth()->user()->id ? '' : 'readonly'}} name="name" value="{{$employee->name}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="phone">رقم الهاتف</label>
               <input type="text" {{$employee->id == auth()->user()->id ? '' : 'readonly'}} name="phone" value="{{$employee->phone}}" minlength="11" maxlength="11" class="form-control" id="phone" autocomplete="phone">
               @error('phone')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="email">البريد الالكتروني <span class="text-danger">*</span></label>
               <input type="email" {{$employee->id == auth()->user()->id ? '' : 'readonly'}} name="email" value="{{$employee->email}}" class="form-control" id="email" autocomplete="email">
               @error('email')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="form-group d-flex align-items-center col-md-10">
                @if ($employee->id == auth()->user()->id)
                 <button type="submit" class="btn btn-primary btn-lg mr-2">تعديل البيانات</button>
                </form>
                 <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-danger btn-lg">تسجيل الخروج</a>
                 </form>
                @endif
               </div>
              </div>
        </div>
    </div>

    {{-- change password --}}
    @if ($employee->id == auth()->user()->id)
      <div class="card shadow border-0 mt-5">
       <div class="card-body">
        <form action="{{route('password.update')}}" method="post" class="row">
         @csrf
         @method('put')
         <div class="col-md-12 mb-3 form-group">
          <label for="current_password">كلمة المرور الحالية <span class="text-danger">*</span></label>
          <input type="password" name="current_password" class="form-control" id="current_password" autocomplete="current_password">
          @error('current_password')
           <span class="text-danger">{{ $message }}</span>
          @enderror
         </div>
         <div class="col-md-6 mb-3 form-group">
          <label for="password">كلمة المرور الجديدة <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" id="password" autocomplete="password">
          @error('password')
           <span class="text-danger">{{ $message }}</span>
          @enderror
         </div>
         <div class="col-md-6 mb-5 form-group">
          <label for="password_confirmation">تأكيد كلمة المرور <span class="text-danger">*</span></label>
          <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" autocomplete="password_confirmation">
          @error('password_confirmation')
           <span class="text-danger">{{ $message }}</span>
          @enderror
         </div>
         <div class="col-md-6 form-group">
          <button type="submit" class="btn btn-primary">تغير كلمة المرور</button>
         </div>
        </form>
       </div>
      </div>
    @endif

   <div class="card shadow border-0 mt-5">
    <div class="card-body">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
     <h3>صلاحيات ال{{$employee->role->name}}</h3>
     <div class="d-flex align-items-center">
      <a href="{{route('roles.show', $employee->role)}}" class="btn btn-primary">عرض الوظيفة</a>
     </div>
    </div>
     <div class="row mt-3">
      @forelse ($employee->role->permissions as $permission)
          <div class="py-2 px-3 m-1 rounded border shadow">{{$permission->ar_name}}</div>
      @empty
          <div class="m-auto p-2 shadow border rounded text-center text-muted">لا يوجد صلاحيات</div>
      @endforelse
     </div>
    </div>
   </div>

@endsection

@section('js')
@endsection