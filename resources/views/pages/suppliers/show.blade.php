@extends('layouts.app')
@section('title') عرض المورد {{$supplier->name}} @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">عرض المورد {{$supplier->name}}</h3>
               <a href="{{route('suppliers.index')}}" class="btn btn-primary"> عرض جميع الموردين<i class="fe fe-users" style="margin-right: 10px"></i></a>
            </div>
            <form action="{{route('suppliers.update', $supplier)}}" method="post">
              @csrf
              @method('patch')
              <div class="row">
                <div class="col-md-12 mb-3 form-group">
                  <label for="name">اسم المورد <span class="text-danger">*</span></label>
                  <input type="text" name="name" value="{{$supplier->name}}" class="form-control" id="name" autocomplete="name" >
                  @error('name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-3 form-group">
                  <label for="phone">رقم الهاتف</label>
                  <input type="text" name="phone" value="{{$supplier->phone}}" minlength="11" maxlength="11" class="form-control" id="phone" autocomplete="phone">
                  @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-3 form-group">
                  <label for="email">البريد الالكتروني </label>
                  <input type="email" name="email" value="{{$supplier->email}}" class="form-control" id="email" autocomplete="email">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-12 mb-3 form-group">
                  <label for="address">العنوان</label>
                  <textarea name="address" class="form-control" id="address" autocomplete="address">{{$supplier->address}}</textarea>
                  @error('address')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-5 form-group">
                  <label for="status">الحالة</label>
                  <select name="status" id="status" class="form-control">
                    <option {{$supplier->status == 'مفعل' ? 'selected' : ''}}  value="مفعل">مفعل</option>
                    <option {{$supplier->status == 'غير مفعل' ? 'selected' : ''}}  value="غير مفعل">غير مفعل</option>
                  </select>
                  @error('status')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group d-flex align-items-center col-md-10">
                  <button type="submit" class="btn btn-primary btn-lg mr-2">تعديل البيانات</button>
                </div>
              </div>
            </form>
        </div>
    </div>

    {{-- supplier account --}}
    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <h3 class="card-title">حساب المورد</h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <input type="number" step="0.01" value="{{$supplier->account}}" readonly class="form-control" id="account">
          </div>
        </div>
        @if ($supplier->account < 0)
          <button type="button" data-toggle="modal" data-target="#repayment" class="btn btn-success text-white">سداد المديونية</button>
        @endif
      </div>
    </div>

    {{-- clinet invoices --}}
    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <h3>اخر فواتير {{$supplier->name}}</h3>
          <div class="d-flex align-items-center">
            <a href="{{route('suppliers.show', $supplier)}}" class="btn btn-primary">عرض جميع الفواتير</a>
          </div>
        </div>
        <div class="table-responsive mt-5">
          <table class="table table-stripe">
            <thead>
              <th class="text-center">رقم الفاتورة</th>
              <th class="text-center">التاريخ</th>
              <th class="text-center">الموظف</th>
              <th class="text-center">الوردية</th>
              <th class="text-center">طريقة الدفع</th>
              <th class="text-center">القيمة الكلية</th>
              <th class="text-center">صافي بعد الخصم</th>
              <th class="text-center">المدفوع</th>
              <th class="text-center">المتبقي</th>
              <th class="text-center">حالة الدفع</th>
            </thead>
            <tbody>
              @foreach ($supplier->invoices()->latest()->take(5)->get() as $invoice)
                <tr class="text-center">
                  <td><a href="{{route('purchasing_invoices.show', $invoice)}}">{{$invoice->invoice_number}}</a></td>
                  <td>{{$invoice->invoice_date}}</td>
                  <td><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></td>
                  <td><a href="{{route('shifts.show', $invoice->shift)}}">{{$invoice->shift->id}}</a></td>
                  <td class="text-center">{{$invoice->payment_method}}</td>
                  <td>{{$invoice->cost_before_discount}}</td>
                  <td>{{$invoice->total_cost}}</td>
                  <td>{{$invoice->paid}}</td>
                  <td>{{$invoice->remaining}}</td>
                  <td class="text-center"><span class="badge badge-pill badge-{{$invoice->payment_status == 'تم الدفع' ? 'success' : 'warning'}} text-white">{{$invoice->payment_status}}</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

      {{-- start payment status modal --}}
  <div class="modal fade" id="repayment" tabindex="-1" role="dialog" aria-labelledby="repaymentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="start_shiftLabel">سداد مديونية المورد رقم: {{$supplier->id}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('suppliers.repayment', $supplier)}}" method="post">
                @csrf
                @method('put')
                <div class="modal-body"> 
                  <div class="row mb-3">
                    <div class="col p-3 bg-danger text-white d-flex justify-content-center align-items-center gap-2">
                      <p class="mb-0">المديونية الكلية : </p>
                      <h3 class="text-white mb-0">{{$supplier->account}}</h3>
                    </div>
                  </div>

                    <div class="mb-3 form-group">
                        <label for="paid">المدفوع</label>
                        <input type="number" value="{{$supplier->account < 0 ? $supplier->account *-1 : 0}}" max="{{$supplier->account < 0 ? $supplier->account *-1 : 0}}" min="0" step="0.01" name="paid" class="form-control" id="paid">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn mb-2 btn-primary text-white">سداد المديونية</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end payment status modal --}}
@endsection

@section('js')
@endsection