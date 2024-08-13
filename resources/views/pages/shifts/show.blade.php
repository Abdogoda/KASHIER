@extends('layouts.app')
@section('title') عرض الوردية رقم {{$shift->id}} - تاريخ:  {{$shift->start_date}} @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    {{-- shift details --}}
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">تفاصيل الوردية رقم {{$shift->id}} - تاريخ:  {{$shift->start_date}}</h3>
                <a href="{{route('shifts.index')}}" class="btn btn-primary"> عرض جميع الورديات<i class="fe fe-users" style="margin-right: 10px"></i></a>
            </div>
            <div class="row">
              <div class="col-md-4 col-xl-3 mb-4">
                <div class="card shadow">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                          <i class="fe fe-16 fe-pocket text-white mb-0"></i>
                        </span>
                      </div>
                      <div class="col pr-0">
                        <p class="text-muted mb-0">المبلغ الافتتاحي</p>
                        <span class="h3 mb-0">{{$shift->initial_amount}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @if ($shift->status == 'منتهية')
                <div class="col-md-4 col-xl-3 mb-4">
                  <div class="card shadow">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-pocket text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="text-muted mb-0">المبلغ النهائي</p>
                          <span class="h3 mb-0">{{$shift->total_amount}}</span>
                          <span class="text-{{$shift->total_amount - $shift->initial_amount > 0 ? 'success' : 'danger'}}">{{$shift->total_amount - $shift->initial_amount > 0 ? '+' : ''}} {{$shift->total_amount - $shift->initial_amount}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xl-3 mb-4">
                  <div class="card shadow bg-primary text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary-light">
                            <i class="fe fe-16 fe-plus-circle text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="text-white mb-0">المبلغ المضاف</p>
                          <span class="h3 mb-0 text-white">{{$shift->added_amount}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xl-3 mb-4">
                  <div class="card shadow bg-warning text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-warning-light">
                            <i class="fe fe-16 fe-minus-circle text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="text-white mb-0">المبلغ المسحوب</p>
                          <span class="h3 mb-0 text-white">{{$shift->withdraw_amount}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xl-3 mb-4">
                  <div class="card shadow bg-{{$shift->difference_amount >= 0 ? 'success' : 'danger'}} text-white">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-{{$shift->difference_amount >= 0 ? 'success' : 'danger'}}-light">
                            <i class="fe fe-16 fe-bar-chart-2 text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="text-white mb-0">العجز / الزيادة</p>
                          <span class="h3 mb-0 text-white">{{$shift->difference_amount}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
            <div class="row">
              {{-- timing --}}
              <div class="col-md-6">
                <p><b>تاريخ البداية: </b>{{$shift->day}} - {{formatArabicDate($shift->start_date)}} - {{formatTime($shift->start_time)}}</p>
                @if ($shift->status == 'منتهية') 
                  <p class="w-100"><b>تاريخ الانتهاء: </b>{{formatArabicDate($shift->end_date)}} - {{formatTime($shift->end_time)}}</p>
                @endif
              </div>
              <div class="col-md-6">
                <p><b>الموظف: </b><a href="{{route('employees.show', $shift->employee)}}">{{$shift->employee->name}}</a></p>
                <p><b>حالة الفاتورة: </b><span class="badge text-white bg-{{$shift->status == 'منتهية' ? 'success' : 'warning'}}">{{$shift->status}}</span></p>
              </div>
            </div>
        </div>
    </div>

    {{-- selling invoices --}}
    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <h3>فواتير البيع للوردية</h3>
          <div class="d-flex align-items-center">
            <a href="{{route('selling_invoices.index')}}" class="btn btn-primary">عرض جميع فواتير البيع</a>
          </div>
        </div>
        <div class="table-responsive mt-3">
          <table class="table table-hover cell-border stripe text-center text-nowrap py-3 dataTable-1" style="width:100%">
              <thead>
                  <tr>
                      <th class="text-center">رقم الفاتورة</th>
                      <th class="text-center">التاريخ</th>
                      <th class="text-center">الوقت</th>
                      <th class="text-center">الموظف</th>
                      <th class="text-center">اسم العميل</th>
                      <th class="text-center">رقم العميل</th>
                      <th class="text-center">الاسترجاع</th>
                      <th class="text-center">طريقة الدفع</th>
                      <th class="text-center">تكلفة الفاتورة</th>
                      <th class="text-center">قيمة المسترجع</th>
                      <th class="text-center">المدفوع</th>
                      <th class="text-center">المتبقي</th>
                      <th class="text-center">المتبقي للعميل</th>
                      <th class="text-center">هامش الربح</th>
                      <th class="text-center">حالة الدفع</th>
                      <th class="text-center">حالة الاستلام</th>
                      <th class="text-center">حالة التوصيل</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($selling_invoices as $invoice)
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
                          <td class="text-center">{{$invoice->payment_method}}</td>
                          <td>{{$invoice->total_cost}}</td>
                          <td>{{$invoice->return_value}}</td>
                          <td>{{$invoice->paid}}</td>
                          <td>{{$invoice->remaining}}</td>
                          <td>{{$invoice->remaining_for_customer}}</td>
                          <td class="table-{{$invoice->profits > 0 ? 'success' : 'danger'}}">{{$invoice->profits}}</td>
                          <td class="text-center"><span class="badge badge-pill badge-{{$invoice->payment_status == 'تم الدفع' ? 'success' : 'warning'}} text-white">{{$invoice->payment_status}}</span></td>
                          <td class="text-center"><span class="badge badge-pill badge-{{$invoice->payment_fund_status == 'تم الاستلام' ? 'success' : 'warning'}} text-white">{{$invoice->payment_fund_status}}</span></td>
                          <td class="text-center"><span class="badge badge-pill badge-{{$invoice->delivery_status == 'تم التوصيل' ? 'success' : 'warning'}} text-white">{{$invoice->delivery_status}}</span></td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- purchasing invoices --}}
    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <h3>فواتير الشراء للوردية</h3>
          <div class="d-flex align-items-center">
            <a href="{{route('purchasing_invoices.index')}}" class="btn btn-primary">عرض جميع فواتير الشراء</a>
          </div>
        </div>
        <div class="table-responsive mt-3">
          <table class="table table-hover cell-border stripe text-center text-nowrap py-3 dataTable-2" style="width:100%">
              <thead>
                  <tr>
                      <th class="text-center">رقم الفاتورة</th>
                      <th class="text-center">التاريخ</th>
                      <th class="text-center">الموظف</th>
                      <th class="text-center">المورد</th>
                      <th class="text-center">طريقة الدفع</th>
                      <th class="text-center">القيمة الكلية</th>
                      <th class="text-center">صافي بعد الخصم</th>
                      <th class="text-center">المدفوع</th>
                      <th class="text-center">المتبقي</th>
                      <th class="text-center">حالة الدفع</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($purchasing_invoices as $invoice)
                      <tr>
                          <td><a href="{{route('purchasing_invoices.show', $invoice)}}">{{$invoice->invoice_number}}</a></td>
                          <td>{{$invoice->invoice_date}}</td>
                          <td><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></td>
                          <td>
                              @if ($invoice->supplier)
                                  <a href="{{route('suppliers.show', $invoice->supplier)}}">{{$invoice->supplier_name}}</a>
                              @else
                                  {{$invoice->supplier_name}}
                              @endif
                          </td>
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

    {{-- invoices --}}
    <div class="card shadow border-0 mt-5">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <h3>فواتير السندات</h3>
          <div class="d-flex align-items-center">
            <a href="{{route('invoices.index')}}" class="btn btn-primary">عرض جميع فواتير السندات</a>
          </div>
        </div>
          <div class="table-responsive mt-3">
            <table class="table table-hover cell-border stripe text-center text-nowrap py-3 dataTable-2" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">رقم الفاتورة</th>
                        <th class="text-center">التاريخ</th>
                        <th class="text-center">الوقت</th>
                        <th class="text-center">الموظف</th>
                        <th class="text-center">نوع الفاتورة</th>
                        <th class="text-center">قيمة الفاتورة</th>
                        <th class="text-center">حالة الاسترجاع</th>
                        <th class="text-center">تاريخ الاسترجاع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td><a href="{{route('invoices.show', $invoice)}}">{{$invoice->invoice_number}}</a></td>
                            <td>{{$invoice->invoice_date}}</td>
                            <td>{{formatTime($invoice->invoice_time)}}</td>
                            <td><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></td>
                            <td class="text-center table-{{$invoice->type == 'قبض' ? 'success' : 'danger'}}">{{$invoice->type}}</td>
                            <td>{{$invoice->cost}}</td>
                            <td class="text-{{$invoice->is_returned == 0 ? 'success' : 'danger'}}">{{$invoice->is_returned == 0 ? 'لا يوجد' : 'تم الاسترجاع'}}</td>
                            <td>{{$invoice->return_date_time}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
      </div>
    </div>

@endsection

@section('js')
<script src="{{asset('assets/dataTablesFolder/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/jszip.min.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/buttons.print.min.js')}}"></script>
<script>
    $('.dataTable-1').DataTable(
      {
        responsive: true,
        autoWidth: true,
        dom: "Bfrtip",
        buttons: ["copy", "excel", {
            extend:"print",
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '12.5px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [20, 40, 80, -1],
          [20, 40, 80, "All"]
        ],
        order: [[0, 'desc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
    $('.dataTable-2').DataTable(
      {
        responsive: true,
        autoWidth: true,
        dom: "Bfrtip",
        buttons: ["copy", "excel", {
            extend:"print",
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '22px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [20, 40, 80, -1],
          [20, 40, 80, "All"]
        ],
        order: [[0, 'desc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
  </script>
@endsection