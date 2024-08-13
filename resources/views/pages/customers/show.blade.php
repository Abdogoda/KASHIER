@extends('layouts.app')
@section('title') عرض العميل {{$customer->name}} @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">بيانات العميل {{$customer->name}}</h3>
                <a href="{{route('customers.index')}}" class="btn btn-primary"> عرض جميع العملاء<i class="fe fe-users" style="margin-right: 10px"></i></a>
            </div>
            <form action="{{route('customers.update', $customer)}}" method="post">
              @csrf
              @method('patch')
              <div class="row">
                <div class="col-md-12 mb-3 form-group">
                  <label for="name">اسم العميل <span class="text-danger">*</span></label>
                  <input type="text" name="name" value="{{$customer->name}}" class="form-control" id="name" autocomplete="name" >
                  @error('name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-3 form-group">
                  <label for="phone">رقم الهاتف</label>
                  <input type="text" name="phone" value="{{$customer->phone}}" minlength="11" maxlength="11" class="form-control" id="phone" autocomplete="phone">
                  @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-3 form-group">
                  <label for="email">البريد الالكتروني </label>
                  <input type="email" name="email" value="{{$customer->email}}" class="form-control" id="email" autocomplete="email">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-12 mb-3 form-group">
                  <label for="address">العنوان</label>
                  <textarea name="address" class="form-control" id="address" autocomplete="address">{{$customer->address}}</textarea>
                  @error('address')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6 mb-5 form-group">
                  <label for="status">الحالة</label>
                  <select name="status" id="status" class="form-control">
                    <option {{$customer->status == 'مفعل' ? 'selected' : ''}}  value="مفعل">مفعل</option>
                    <option {{$customer->status == 'غير مفعل' ? 'selected' : ''}}  value="غير مفعل">غير مفعل</option>
                  </select>
                  @error('status')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group d-flex align-items-center col-md-12">
                  <button type="submit" class="btn btn-primary btn-lg mr-2">تعديل البيانات</button>
                </div>
              </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <h3 class="card-title">حساب العميل</h3>
        <div class="row">
          <div class="col-md-6 mb-3">
            <input type="number" step="0.01" value="{{$customer->account}}" readonly class="form-control" id="account">
          </div>
        </div>
        <button type="button" data-toggle="modal" data-target="#repayment" class="btn btn-success text-white">{{$customer->account >= 0 ? 'تحت الحساب' : 'سداد المديونية'}}</button>
        @if ($customer->account > 0)
          <button type="button" data-toggle="modal" data-target="#repayment_for_customer" class="btn btn-warning text-white">صرف المستحقات</button>
        @endif
      </div>
    </div>

    {{-- clinet invoices --}}
    @if ($customer->invoices->count() > 0)
      <div class="card shadow border-0 mt-5">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap">
            <h3>اخر فواتير {{$customer->name}}</h3>
            <div class="d-flex align-items-center">
              <a href="{{route('selling_invoices.index', ['customer_id' => $customer->id])}}" class="btn btn-primary">عرض جميع الفواتير</a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover cell-border stripe text-center text-nowrap py-3" id="dataTable-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">رقم الفاتورة</th>
                        <th class="text-center">التاريخ</th>
                        <th class="text-center">الوقت</th>
                        <th class="text-center">الموظف</th>
                        <th class="text-center">اسم العميل</th>
                        <th class="text-center">رقم العميل</th>
                        <th class="text-center">طريقة الدفع</th>
                        <th class="text-center">حالة الدفع</th>
                        <th class="text-center">الاسترجاع</th>
                        <th class="text-center">تكلفة الفاتورة</th>
                        <th class="text-center">المدفوع</th>
                        <th class="text-center">المتبقي</th>
                        <th class="text-center">قيمة المسترجع</th>
                        <th class="text-center">هامش الربح</th>
                        <th class="text-center">حالة الاستلام</th>
                        <th class="text-center">حالة التوصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->invoices()->latest()->take(5)->get() as $invoice)
                        <tr>
                            <td><a href="{{route('selling_invoices.show', $invoice)}}">{{$invoice->invoice_number}}</a></td>
                            <td>{{$invoice->invoice_date}}</td>
                            <td>{{formatTime($invoice->invoice_time)}}</td>
                            <td><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></td>
                            <td>
                                @if ($invoice->customer)
                                    <a href="{{route('customers.show', $invoice->customer)}}">{{$invoice->customer_name}}</a>
                                @else
                                    {{$invoice->customer_name}}
                                @endif
                            </td>
                            <td>
                                @if ($invoice->customer)
                                    <a href="tel:+2{{$invoice->customer_phone}}">{{$invoice->customer_phone}}</a>
                                @else
                                    {{$invoice->customer_phone}}
                                @endif
                            </td>
                            <td class="text-center">{{$invoice->payment_method}}</td>
                            <td class="text-center"><span class="badge badge-pill badge-{{$invoice->payment_status == 'تم الدفع' ? 'success' : 'warning'}} text-white">{{$invoice->payment_status}}</span></td>
                            <?php 
                                $color = 'success';
                                $is_returned = 'لا يوجد';
                                if($invoice->is_returned == 1){
                                    $color = 'danger';
                                    $is_returned = 'استرجاع كلي';
                                }elseif($invoice->is_returned == 2){
                                    $color = 'warning';
                                    $is_returned = 'استرجاع جزئي';
                                }
                            ?>
                            <td class="text-center"><span class="badge bg-{{$color}} text-white">{{$is_returned}}</span></td>
                            <td>{{$invoice->total_cost}}</td>
                            <td>{{$invoice->paid}}</td>
                            <td>{{$invoice->remaining}}</td>
                            <td>{{$invoice->return_value}}</td>
                            <td class="table-{{$invoice->profits > 0 ? 'success' : 'danger'}}">{{$invoice->profits}}</td>
                            <td class="text-center"><span class="badge badge-pill badge-{{$invoice->payment_fund_status == 'تم الاستلام' ? 'success' : 'warning'}} text-white">{{$invoice->payment_fund_status}}</span></td>
                            <td class="text-center"><span class="badge badge-pill badge-{{$invoice->delivery_status == 'تم التوصيل' ? 'success' : 'warning'}} text-white">{{$invoice->delivery_status}}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
      </div>
    @endif


  {{-- start payment status modal --}}
  <div class="modal fade" id="repayment" tabindex="-1" role="dialog" aria-labelledby="repaymentLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="start_shiftLabel">سداد مديونية العميل رقم: {{$customer->id}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form action="{{route('customers.repayment', $customer)}}" method="post">
                  @csrf
                  @method('put')
                  <div class="modal-body"> 
                    <div class="row mb-3">
                      <div class="col p-3 bg-danger text-white d-flex justify-content-center align-items-center gap-2">
                        <p class="mb-0">المديونية الكلية : </p>
                        <h3 class="text-white mb-0">{{$customer->account < 0 ? $customer->account *-1 : 0}}</h3>
                      </div>
                    </div>

                      <div class="mb-3 form-group">
                          <label for="paid">المدفوع</label>
                          <input type="number" value="{{$customer->account < 0 ? $customer->account *-1 : 0}}" min="0" step="0.01" name="paid" class="form-control" id="paid">
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


  @if ($customer->account > 0)
    {{-- start repayment_for_customer modal --}}
    <div class="modal fade" id="repayment_for_customer" tabindex="-1" role="dialog"aria-labelledby="repayment_for_customerLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="start_shiftLabel">صرف مستحقات العميل رقم: {{$customer->id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('customers.repayment_for_customer', $customer)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body"> 
                      <div class="row">
                        <div class="col p-3 bg-{{$customer->account >=0 ? 'success' : 'danger'}} text-white d-flex justify-content-center align-items-center gap-2">
                          <p class="mb-0">{{$customer->account >= 0 ? 'المستحقات' : 'المديونية'}} الكلية : </p>
                          <h3 class="text-white mb-0">{{$customer->account >= 0 ? $customer->account : $customer->account*-1}}</h3>
                        </div>
                      </div>
                      <div class="mb-3 form-group">
                          <label for="paid">المصروف</label>
                          <input type="number" value="{{$customer->account >= 0 ? $customer->account : 0}}" min="0" max="{{$customer->account >= 0 ? $customer->account : 0}}" step="0.01" name="paid" class="form-control" id="paid">
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-warning text-white">صرف المستحقات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end repayment_for_customer modal --}}
  @endif
@endsection

@section('js')
@endsection