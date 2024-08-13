@extends('layouts.app')
@section('title') عرض الفاتورة: {{$invoice->invoice_number}} @endsection
@section('css')
@endsection
@section('content')
    
  <div class="card shadow border-0">
    <div class="card-body">
      <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
          <h3 >فاتورة شراء: {{$invoice->invoice_number}}</h3>
          <a href="{{route('purchasing_invoices.index')}}" class="btn btn-primary">عرض فواتير الشراء</a>
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
              <div class="list-group px-0 mb-4">
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
                      <p class="text-muted mb-0">{{formatArabicDate($invoice->invoice_date)}}</p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">وقت الفاتورة</h4>
                      <p class="text-muted mb-0">{{formatTime($invoice->invoice_time)}}</p>
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
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="list-group px-0 mb-4">
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
                      <h4 class="mb-2">اسم المورد</h4>
                      <p class="text-muted mb-0">
                        @if ($invoice->supplier)
                            <a href="{{route('suppliers.show', $invoice->supplier)}}">{{$invoice->supplier_name}}</a>
                        @else
                        {{$invoice->supplier_name}}
                        @endif
                      </p>
                    </div>
                  </div> 
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-2">رقم المورد</h4>
                      <p class="text-muted mb-0">{{$invoice->supplier_phone}}</p>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="list-group px-0 mb-4">
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
                      <h4 class="mb-2">حالة الفاتورة</h4>
                      <p class="mb-0 text-{{$invoice->status == 'مفعل' ? 'success' : 'danger'}}">{{$invoice->status}}</p>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- invoice products --}}
        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab"> 
          <div class="table-responsive mb-4">
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
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- invoice account --}}
        <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
          <div class="row justify-content-center">
            <div class="col-md-6 col-xl-4 mb-4">
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
            <div class="col-md-6 col-xl-4 mb-4">
              <div class="card shadow bg-info text-white">
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
            <div class="col-md-6 col-xl-4 mb-4">
              <div class="card shadow bg-primary text-white">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col">
                      <p class="text-light mb-0">المبلغ النهائي</p>
                      <span class="h3 mb-0 text-white">{{$invoice->total_cost}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-4 mb-4">
              <div class="card shadow bg-success text-white">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col">
                      <p class="text-light mb-0">المدفوع</p>
                      <span class="h3 mb-0 text-white">{{$invoice->paid}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-4 mb-4">
              <div class="card shadow bg-warning text-white">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col">
                      <p class="text-light mb-0">المتبقي</p>
                      <span class="h3 mb-0 text-white">{{$invoice->remaining}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @if ($invoice->supplier)
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card shadow bg-{{$invoice->supplier->account > 0 ? 'success' : 'danger'}} text-white">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col">
                        <p class="text-light mb-0">حساب المورد</p>
                        <span class="h3 mb-0 text-white">{{$invoice->supplier->account}}</span>
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
        <a href="{{route('purchasing_invoices.print', $invoice)}}" class="btn btn-success mt-2 text-white" target="__blank">طباعة الفاتورة <i class="fe fe-printer ml-2"></i></a>
        @can('repayment_purchasing_invoice')
            @if ($invoice->payment_status != 'تم الدفع' && $invoice->remaining > 0)
                <button class="btn btn-info ml-2 mt-2 " data-toggle="modal" data-target="#payment_status">سداد المديونية <i class="fe fe-dollar-sign"></i></button>
            @endif
        @endcan
      </div>
    </div>
  </div>


      {{-- start payment status modal --}}
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
              <form action="{{route('purchasing_invoices.repayment', $invoice)}}" method="post">
                  @csrf
                  @method('put')
                  <div class="modal-body"> 
                    <div class="row mb-3">
                      <div class="col p-3 bg-success text-white d-flex justify-content-center align-items-center gap-2">
                        <p class="mb-0">المدفوع مسبقا</p>
                        <h3 class="text-white mb-0">{{$invoice->paid}}</h3>
                      </div>
                      <div class="col p-3 bg-warning text-white d-flex justify-content-center align-items-center gap-2">
                        <p class="mb-0">المتبقي</p>
                        <h3 class="text-white mb-0">{{$invoice->remaining}}</h3>
                      </div>
                    </div>

                      <div class="mb-3 form-group">
                          <label for="paid">المدفوع</label>
                          <input type="number" value="{{$invoice->remaining}}" min="0" step="0.01" name="paid" max="{{$invoice->customer->account ?? $invoice->remaining}}" class="form-control" id="paid">
                      </div>
                      <div class="mb-3 form-group">
                          <label for="remaining">المتبقي</label>
                          <input type="number" value="0" min="0" step="0.01" readonly name="remaining" class="form-control" id="remaining">
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
@endsection 

@section('js')
<script>
  document.addEventListener('DOMContentLoaded', function () {
        const cost = {{$invoice->customer->account ?? $invoice->remainig}}
        const paidInput = document.getElementById('paid');
        const remainingInput = document.getElementById('remaining');
        
        paidInput.addEventListener('input', function () {
          const paidValue = parseFloat(paidInput.value) || 0;
            if(paidValue <= cost){
              const remainingValue = cost - paidValue;
              remainingInput.value = remainingValue.toFixed(2);
            }else{
              remainingInput.value = 0;
            }
        });
  });
</script>
@endsection