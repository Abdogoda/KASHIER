@extends('layouts.app')
@section('title') عرض حساب الموظف @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">حساب الموظف ({{$employee->name}})</h3>
               <a href="{{route('employees.index')}}" class="btn btn-primary"> عرض جميع الموظفين<i class="fe fe-users" style="margin-right: 10px"></i></a>
            </div>
            <form action="{{route('employees.update', $employee)}}" method="post">
             @csrf
             @method('patch')
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم الموظف <span class="text-danger">*</span></label>
               <input type="text" readonly name="name" value="{{$employee->name}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="phone">رقم الهاتف</label>
               <input type="text" readonly name="phone" value="{{$employee->phone}}" minlength="11" maxlength="11" class="form-control" id="phone" autocomplete="phone">
               @error('phone')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="email">البريد الالكتروني <span class="text-danger">*</span></label>
               <input type="email" readonly name="email" value="{{$employee->email}}" class="form-control" id="email" autocomplete="email">
               @error('email')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="form-group d-flex align-items-center col-md-10">
                @can('change_employee_status')
                    <a href="{{route('employees.change-status', $employee)}}" class="btn text-white btn-lg btn-{{$employee->status == 'مفعل' ? 'danger' : 'success' }}">{{$employee->status == 'مفعل' ? 'الغاء التفعيل' : 'تفعيل' }}</a>
                @endcan
               </div>
              </div>
        </div>
    </div>

  <div class="card shadow border-0 mt-5">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <h3>صلاحيات ال{{$employee->role->name}}</h3>
        <div class="d-flex align-items-center">
          <a href="{{route('roles.show', $employee->role)}}" class="btn btn-primary">عرض الوظيفة</a>
          @can('change_employee_role')
            <button type="button" data-toggle="modal" data-target="#changerole" class="btn btn-success text-white ml-2">تغيير الوظيفة</لا>
          @endcan
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

  <div class="modal fade" id="changerole" tabindex="-1" role="dialog" aria-labelledby="changeroleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changeroleLabel">تغيير وظيفة الموظف</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('employees.change-role', $employee)}}" method="post">
          @csrf
        <div class="modal-body">
            <div class="form-group">
              <label for="role_id" class="col-form-label">الوظيفة</label>
              <select name="role_id" id="role_id" class="form-control">
                <option disabled selected>اختر الوظيفة</option>
                @foreach ($roles as $role)
                  <option {{$role->id == $employee->role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}} ({{$role->permissions->count()}} صلاحية)</option>
                @endforeach
              </select>
              @error('role_id')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn mb-2 btn-primary">حفظ البيانات</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('js')
@endsection