@extends('layouts.app')
@section('title') اضافة عميل جديد @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">اضافة عميل جديد</h3>
               <a href="{{route('customers.index')}}" class="btn btn-primary"> عرض جميع العملاء<i class="fe fe-users" style="margin-right: 10px"></i></a>
            </div>
            <li class="mb-4">رمز <span class="text-danger">*</span> يعني ان هذا الحقل مطلوب ولابد من كتابتة</li>
            <form action="{{route('customers.store')}}" method="post">
             @csrf
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم العميل <span class="text-danger">*</span></label>
               <input type="text" name="name" value="{{old('name')}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="phone">رقم الهاتف</label>
               <input type="text" name="phone" value="{{old('phone')}}" minlength="11" maxlength="11" class="form-control" id="phone" autocomplete="phone">
               @error('phone')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="email">البريد الالكتروني</label>
               <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email" autocomplete="email">
               @error('email')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-12 mb-3 form-group">
                <label for="address">العنوان</label>
                <textarea name="address" id="address" class="form-control" autocomplete="address">{{old('address')}}</textarea>
               @error('address')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="form-group col-md-12">
               <button type="submit" class="btn btn-primary btn-lg">اضافة عميل</button>
              </div>
             </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection