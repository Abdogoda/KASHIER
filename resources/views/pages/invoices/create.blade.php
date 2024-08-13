@extends('layouts.app')
@section('title') اضافة فاتورة سند جديدة @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">اضافة فاتورة سند جديدة</h3>
                <a href="{{route('purchasing_invoices.index')}}" class="btn btn-primary"> عرض جميع فواتير السندات</a>
            </div>

            <form action="{{route('invoices.store')}}" method="post">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 form-group  mb-3">
                        <label for="date">تاريخ الفاتورة</label>
                        <input type="date" id="date" value="{{old('invoice_date')}}" name="invoice_date" class="form-control">
                        @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="col-md-6 form-group  mb-3">
                        <label for="time">وقت الفاتورة</label>
                        <input type="time" id="time" value="{{old('invoice_time')}}" name="invoice_time" class="form-control">
                        @error('invoice_time') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="col-md-6 form-group  mb-3">
                        <label for="type">نوع الفاتورة</label>
                        <select id="type" name="type" class="form-control">
                            <option value="">اختر...</option>
                            <option value="صرف" {{ request('type') == 'صرف' ? 'selected' : '' }}>صرف</option>
                            <option value="قبض" {{ request('type') == 'قبض' ? 'selected' : '' }}>قبض</option>
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="col-md-6 form-group  mb-3">
                        <label for="cost">التكلفة</label>
                        <input type="number" min="0" step="0.01" value="{{old('cost')}}" max="100000000" id="cost" name="cost" class="form-control">
                        @error('cost') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 form-group  mb-3">
                        <label for="description">وصف الفاتورة</label>
                        <textarea id="description" name="description" class="form-control">{{old('description')}}</textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success text-white">اضف الفاتورة</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection