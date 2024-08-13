@extends('layouts.app')
@section('title') الاعدادات@endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
              <h3 class="card-title">اعدادت الشركة</h3>
             </div>
             <form action="{{route('settings.store')}}" method="post" enctype="multipart/form-data">
              @csrf
               <div class="row">
                 @foreach ($siteSettings as $key => $value)
                   <div class="col-lg-6  form-group mb-3">
                    <label for="{{$key}}">{{str_replace('_', ' ', $key)}}</label>
                    @if ($key == 'لوجو_الشركة')
                       <input type="file" accept="image/*" name="{{$key}}" class="form-control">
                    @else
                       <input type="text" name="{{$key}}" value="{{$value}}" class="form-control">
                    @endif
                   </div>
                 @endforeach
               </div>
               <button type="submit" class="btn btn-success btn-lg text-white">تعديل البيانات</button>
             </form>
        </div>
    </div>
@endsection

@section('js')
@endsection