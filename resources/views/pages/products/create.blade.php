@extends('layouts.app')
@section('title') اضافة منتج جديد @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">اضافة منتج جديد</h3>
               <a href="{{route('products.index')}}" class="btn btn-primary"> عرض جميع المنتجات</a>
            </div>
            <li class="mb-4">رمز <span class="text-danger">*</span> يعني ان هذا الحقل مطلوب ولابد من كتابتة</li>
            <form action="{{route('products.store')}}" method="post">
             @csrf
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم المنتج <span class="text-danger">*</span></label>
               <input type="text" name="name" value="{{old('name')}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="purchasing_price">سعر الشراء</label>
               <input type="number" min="0" step="0.1" name="purchasing_price" value="{{old('purchasing_price')}}" class="form-control" id="purchasing_price" autocomplete="purchasing_price">
               @error('purchasing_price')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-6 mb-3 form-group">
               <label for="opening_balance">الرصيد الافتتاحي في المخزن</label>
               <input type="number" min="0" name="opening_balance" value="{{old('opening_balance', 0)}}" class="form-control" id="opening_balance" autocomplete="opening_balance">
               @error('opening_balance')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              @foreach ($segments as $segment)
                <div class="col-md-4 mb-3 form-group">
                <label for="segments{{$segment->id}}">سعر بيع {{$segment->name}}</label>
                <input type="number" min="0" step="0.1" name="segments[{{$segment->id}}]" value="{{old('segments.'.$segment->id)}}" class="form-control" id="segments{{$segment->id}}">
                @error('segments.'.$segment->id)
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
              @endforeach
              <div class="col-md-12 mb-5 form-group">
                <label for="description">وصف المنتج</label>
                <textarea name="description" id="description" class="form-control" autocomplete="description">{{old('description')}}</textarea>
               @error('description')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="form-group col-md-12">
               <button type="submit" class="btn btn-primary btn-lg">اضافة منتج</button>
              </div>
             </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection