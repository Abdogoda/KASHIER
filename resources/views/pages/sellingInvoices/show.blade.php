@extends('layouts.app')
@section('title') عرض الفاتورة: {{$invoice->invoice_number}} @endsection
@section('css')
<style>
  .tab-content .row .card-body{
    padding: 1rem
  }
  .tab-content .row > div{
    padding: 0 7.5px
  }
</style>
@endsection
@section('content')
    
  <div class="card shadow border-0">
    <div class="card-body">
      <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
          <h3 >فاتورة بيع رقم: {{$invoice->invoice_number}}</h3>
          <a href="{{route('selling_invoices.index')}}" class="btn btn-primary">عرض فواتير البيع</a>
      </div>
      <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">تفاصيل الفاتورة</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">منتجات الفاتورة</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">حساب الفاتورة</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        {{-- invoice details --}}
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
          <div class="row">
            <div class="col-md-6 col-lg-4">
              <div class="list-group px-0 mb-3">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">رقم الفاتورة</h4>
                      <p class="text-muted mb-0">{{$invoice->invoice_number}}</p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">تاريخ الفاتورة</h4>
                      <p class="text-muted mb-0">{{formatArabicDate($invoice->invoice_date)}} - {{formatTime($invoice->invoice_time)}}</p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">وقت انشاء الفاتورة</h4>
                      <p class="text-muted mb-0">{{$invoice->created_at->diffForHumans()}}</p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">الاسترجاع</h4>
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
                      <p class="text-muted mb-0"><span class="badge px-2 text-white bg-{{$color}}">{{$is_returned}}</span></p>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="list-group px-0 mb-3">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">رقم الوردية</h4>
                      <p class="text-muted mb-0"><a href="{{route('shifts.show', $invoice->shift)}}">{{$invoice->shift_id}}</a></p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">اسم الموظف</h4>
                      <p class="text-muted mb-0"><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">اسم العميل</h4>
                      <p class="text-muted mb-0">
                        @if ($invoice->customer)
                            <a href="{{route('customers.show', $invoice->customer)}}">{{$invoice->customer_name}}</a>
                        @else
                        {{$invoice->customer_name}}
                        @endif
                      </p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">رقم العميل</h4>
                      <p class="text-muted mb-0">{{$invoice->customer_phone}}</p>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="list-group px-0 mb-3">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">طريقة الدفع</h4>
                      <?php 
                        $color = 'warning';
                        if($invoice->payment_method == 'نقدي') $color = 'success';
                        if($invoice->payment_method == 'أجل') $color = 'danger';
                      ?>
                      <p class="text-muted mb-0 text-{{$color}}">{{$invoice->payment_method}}</p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">حالة الدفع</h4>
                      <p class="text-muted mb-0"> <span class="badge badge-pill text-white badge-{{$invoice->payment_status == 'تم الدفع' ? 'success' : 'warning'}}">{{$invoice->payment_status}}</span></p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">حالة استلام المبلغ المدفوع (خاصة بالدرج)</h4>
                      <p class="text-muted mb-0"> <span class="badge badge-pill text-white badge-{{$invoice->payment_fund_status == 'تم الاستلام' ? 'success' : 'warning'}}">{{$invoice->payment_fund_status}}</span></p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">حالة التوصيل</h4>
                      <p class="mb-0"><span class="badge badge-pill text-white badge-{{$invoice->delivery_status == 'تم التوصيل' ? 'success' : 'warning'}}">{{$invoice->delivery_status}}</span></p>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- invoice products --}}
        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab"> 
          <div class="table-responsive mb-3">
            <table class="table table-bordered text-center text-nowrap">
              <thead>
                <tr>
                  <th class="text-center">اسم المنتج</th>
                  <th class="text-center">سعر المنتج</th>
                  <th class="text-center">الكمية</th>
                  <th class="text-center">الاجمالي</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($invoice->products as $invoice_product)
                    @if ($invoice_product->type == 0)
                        <tr>
                          <td>
                            @if ($invoice_product->product)
                                <a href="{{route('products.show', $invoice_product->product)}}" target="__blank">{{$invoice_product->product->name}}</a>
                            @else
                                تم الحذف
                            @endif
                          </td>
                          <td>{{$invoice_product->price}}</td>
                          <td>{{$invoice_product->quantity}}</td>
                          <td>{{$invoice_product->total_price}}</td>
                        </tr>
                    @endif
                @endforeach
              </tbody>
            </table>
          </div>

          @if ($invoice->is_returned != 0)
            <h3>المنتجات المسترجعة</h3>
            <div class="table-responsive mb-3">
              <table class="table table-bordered text-center text-nowrap">
                <thead>
                  <tr>
                    <th class="text-center">اسم المنتج</th>
                    <th class="text-center">سعر الاسترجاع</th>
                    <th class="text-center">الكمية</th>
                    <th class="text-center">الاجمالي</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoice->products as $invoice_product)
                      @if ($invoice_product->type == 1)
                        <tr>
                          <td>
                            @if ($invoice_product->product)
                                <a href="{{route('products.show', $invoice_product->product)}}" target="__blank">{{$invoice_product->product->name}}</a>
                            @else
                                تم الحذف
                            @endif
                          </td>
                          <td>{{$invoice_product->price}}</td>
                          <td>{{$invoice_product->quantity}}</td>
                          <td>{{$invoice_product->total_price}}</td>
                        </tr>
                      @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>

        {{-- invoice account --}}
        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
          <div class="row justify-content-center">
              <div class="col-md-6 col-xl-{{$invoice->is_returned != 0 ? '2' : '3'}} mb-3">
                <div class="card shadow bg-secondary text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">المبلغ قبل الخصم</p>
                        <span class="h3 mb-0 text-white">{{$invoice->cost_before_discount}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-{{$invoice->is_returned != 0 ? '2' : '3'}} mb-3">
                <div class="card shadow bg-secondary text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">قيمة الخصم</p>
                        <span class="h3 mb-0 text-white">{{$invoice->discount_value}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-{{$invoice->is_returned != 0 ? '2' : '3'}} mb-3">
                <div class="card shadow bg-secondary text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">المبلغ بعد الخصم</p>
                        <span class="h3 mb-0 text-white">{{$invoice->cost_after_discount}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-{{$invoice->is_returned != 0 ? '2' : '3'}} mb-3">
                <div class="card shadow bg-secondary text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">قيمة الفاتورة الكلية</p>
                        <span class="h3 mb-0 text-white">{{$invoice->total_cost}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if ($invoice->is_returned != 0)
                <div class="col-md-6 col-xl-2 mb-3">
                  <div class="card shadow bg-primary text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <p class="text-light mb-0">المبلغ المرتجع</p>
                          <span class="h3 mb-0 text-white">{{$invoice->return_value}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-2  mb-3">
                  <div class="card shadow bg-primary text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <p class="text-light mb-0"> الفاتورة بعد الاسترجاع</p>
                          <span class="h3 mb-0 text-white">{{$invoice->total_cost - $invoice->return_value}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 col-xl-3 mb-3">
                <div class="card shadow bg-success text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">المدفوع</p>
                        <span class="h3 mb-0 text-white">{{$invoice->paid + $invoice->over_paid}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3 mb-3">
                <div class="card shadow bg-primary text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0"> المتبقي</p>
                        <span class="h3 mb-0 text-white">{{$invoice->remaining}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if ($invoice->customer)
                <div class="col-md-6 col-xl-2 mb-3">
                  <div class="card shadow bg-warning text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <p class="text-light mb-0">الحساب قبل الفاتورة</p>
                          <span class="h3 mb-0 text-white">{{$invoice->account_before ?? 0}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-2 mb-3">
                  <div class="card shadow bg-warning text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <p class="text-light mb-0">الحساب بعد الفاتورة</p>
                          <span class="h3 mb-0 text-white">{{$invoice->account_after ?? 0}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-2 mb-3">
                  <div class="card shadow bg-primary text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                          <p class="text-light mb-0">الحساب الحالي</p>
                          <span class="h3 mb-0 text-white">{{$invoice->customer->account ?? 0}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
        </div>
      </div>
      <div class="">
        @can('print_selling_invoice')
          @if ($invoice->is_returned == 2)
              <a href="{{route('selling_invoices.print', ['selling_invoice' => $invoice, 'returned' => false])}}" class="btn btn-success mt-2 text-white" target="__blank">طباعة الفاتورة بعد الاسترجاع<i class="fe fe-printer ml-2"></i></a>
          @else
            <a href="{{route('selling_invoices.print', $invoice)}}" class="btn btn-success mt-2 text-white" target="__blank">طباعة الفاتورة <i class="fe fe-printer ml-2"></i></a>
          @endif
        @endcan
        @if (Carbon\Carbon::parse($invoice->invoice_date)->diffInDays(Carbon\Carbon::now()) <= 5 && $invoice->is_returned == 0)
            @can('return_selling_invoice')
                <a href="{{route('selling_invoices.edit', $invoice)}}" class="btn ml-2 mt-2 btn-danger">استرجاع الفاتورة <i class="fe fe-corner-down-left ml-2"></i></a>
            @endcan
        @endif
        @if ($invoice->payment_status != 'تم الدفع' && $invoice->customer->account < 0)
            @can('repayment_selling_invoice')
                <button class="btn btn-info ml-2 mt-2 " data-toggle="modal" data-target="#payment_status">سداد المديونية <i class="fe fe-dollar-sign"></i></button>
            @endcan
        @endif
        @if ($invoice->payment_fund_status != 'تم الاستلام')
            @can('payment_fund_selling_invoice')
                <button class="btn btn-success ml-2 mt-2 text-white" data-toggle="modal" data-target="#payment_fund_status_modal">تم الاستلام <i class="fe fe-dollar-sign"></i></button>
            @endcan
        @endif
        @if ($invoice->delivery_status != 'تم التوصيل')
            @can('delivery_selling_invoice')
              <button class="btn btn-secondary ml-2 mt-2 " data-toggle="modal" data-target="#delivery_status">تم التوصيل <i class="fe fe-truck"></i></button>
            @endcan
        @endif
      </div>
    </div>
  </div>


    {{-- start payment status modal --}}
    @if ($invoice->customer)
      <div class="modal fade" id="payment_status" tabindex="-1" role="dialog"
        aria-labelledby="payment_statusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="start_shiftLabel">سداد مديونية الفاتورة رقم: {{$invoice->invoice_number}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('selling_invoices.repayment', $invoice)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body"> 
                      <div class="row">
                        <div class="col p-3 bg-success text-white d-flex justify-content-center align-items-center gap-2">
                          <p class="mb-0">المدفوع مسبقا : </p>
                          <h3 class="text-white mb-0">{{$invoice->paid}}</h3>
                        </div>
                        <div class="col p-3 bg-warning text-white d-flex justify-content-center align-items-center gap-2">
                          <p class="mb-0">المتبقي : </p>
                          <h3 class="text-white mb-0">{{$invoice->remaining}}</h3>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col p-3 bg-danger text-white d-flex justify-content-center align-items-center gap-2">
                          <p class="mb-0">المديونية الكلية : </p>
                          <h3 class="text-white mb-0">{{-1*$invoice->customer->account ?? 0}}</h3>
                        </div>
                      </div>

                        <div class="mb-3 form-group">
                            <label for="paid">المدفوع</label>
                            <input type="number" value="{{-1*$invoice->customer->account}}" min="0" step="0.01" name="paid" class="form-control" id="paid">
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3 form-group">
                            <label for="remaining">المتبقي</label>
                            <input type="number" value="0" min="0" step="0.01" readonly name="remaining" class="form-control" id="remaining">
                          </div>
                          <div class="col-md-6 mb-3 form-group">
                            <label for="rest">الاضافي</label>
                            <input type="number" value="0" min="0" step="0.01" readonly name="rest" class="form-control" id="rest">
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-primary">سداد المديونية</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    {{-- end payment status modal --}}

    {{-- start delivery_status modal --}}
    <div class="modal fade" id="delivery_status" tabindex="-1" role="dialog"
        aria-labelledby="payment_statusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="start_shiftLabel">توصيل بضاعة الفاتورة رقم: {{$invoice->invoice_number}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('selling_invoices.delivery', $invoice)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body"> 
                     <h2> هل تم توصيل الفاتورة بنجاح؟</h2>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-primary">نعم تم التوصيل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end delivery_status modal --}}

    {{-- start payment_fund_status modal --}}
    <div class="modal fade" id="payment_fund_status_modal" tabindex="-1" role="dialog"
        aria-labelledby="payment_fund_statusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payment_fund_statusLabel">استلام المبلغ للفاتورة رقم: {{$invoice->invoice_number}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('selling_invoices.payment_fund', $invoice)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body"> 
                      <h2> هل تم استلام مبلغ الفاتورة من الموظف بنجاح؟</h2>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-primary">نعم تم الاستلام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end payment_fund_status modal --}}

@endsection

@section('js')

@if ($invoice->customer)
  <script>
    document.addEventListener('DOMContentLoaded', function () {
          const cost = {{$invoice->customer->account*-1}}
          const paidInput = document.getElementById('paid');
          const remainingInput = document.getElementById('remaining');
          const restInput = document.getElementById('rest');
          
          paidInput.addEventListener('input', function () {
            const paidValue = parseFloat(paidInput.value) || 0;
              if(paidValue <= cost){
                const remainingValue = cost - paidValue;
                remainingInput.value = remainingValue.toFixed(2);
                restInput.value = 0;
              }else{
                remainingInput.value = 0;
                const restValue = paidValue - cost;
                restInput.value = restValue.toFixed(2);
              }
          });
    });
  </script>
@endif
@endsection