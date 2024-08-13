@extends('layouts.app')
@section('title') :عرض المنتج {{$product->name}} @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
      <div class="card-body">
        <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
           <h3 class="card-title">:عرض المنتج {{$product->name}}</h3>
           <a href="{{route('products.index')}}" class="btn btn-primary"> عرض جميع المنتجات</a>
        </div>
        <li class="mb-4">رمز <span class="text-danger">*</span> يعني ان هذا الحقل مطلوب ولابد من كتابتة</li>
        <form action="{{route('products.update', $product)}}" method="post">
         @csrf
         @method('patch')
         <div class="row">
          <div class="col-md-12 mb-3 form-group">
           <label for="name">اسم المنتج <span class="text-danger">*</span></label>
           <input type="text" name="name" value="{{$product->name}}" class="form-control" id="name" autocomplete="name" autofocus>
           @error('name')
            <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="col-md-6 mb-3 form-group">
           <label for="purchasing_price">سعر الشراء</label>
           <input type="number" min="0" step="0.1" name="purchasing_price" value="{{$product->purchasing_price}}" class="form-control" id="purchasing_price" autocomplete="purchasing_price">
           @error('purchasing_price')
            <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="col-md-6 mb-3 form-group">
           <label for="status">الحالة</label>
           <select name="status" id="status" class="form-control">
            <option {{$product->status == 'مفعل' ? 'selected' : ''}}  value="مفعل">مفعل</option>
            <option {{$product->status == 'غير مفعل' ? 'selected' : ''}}  value="غير مفعل">غير مفعل</option>
           </select>
           @error('status')
            <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          @foreach ($product->segments as $segment)
            <div class="col-md-4 mb-3 form-group">
            <label for="segments{{$segment->segment_id}}">سعر بيع {{$segment->segment->name}}</label>
            <input type="number" min="0" step="0.1" name="segments[{{$segment->segment_id}}]" value="{{$segment->segment_price}}" class="form-control" id="segments{{$segment->segment_id}}">
            @error('segments.'.$segment->segment_id)
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
          @endforeach
          <div class="col-md-12 mb-5 form-group">
            <label for="description">وصف المنتج</label>
            <textarea name="description" id="description" class="form-control" autocomplete="description">{{$product->description}}</textarea>
           @error('description')
            <span class="text-danger">{{ $message }}</span>
           @enderror
          </div>
          <div class="form-group col-md-12">
           <button type="submit" class="btn btn-primary btn-lg">تعديل المنتج</button>
          </div>
         </div>
        </form>
    </div>
    </div>

@endsection

@section('js')
@endsection