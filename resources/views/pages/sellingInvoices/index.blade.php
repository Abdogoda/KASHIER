@extends('layouts.app')
@section('title') فواتير البيع @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/select-2/select2.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/css/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card mb-3 shadow border-0">
        
    </div>
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">فواتير البيع</h3>
                <a href="{{route('selling_invoices.create')}}" class="btn btn-primary">اضافة فاتورة بيع جديدة <i class="fe fe-plus" style="margin-right: 10px"></i></a>
            </div>
            <form action="{{ route('selling_invoices.index') }}" method="GET" class="border p-3 rounded mb-5">
                <div class="form-row">
                    <div class="form-group col-6 col-md-4 col-lg-2">
                        <label for="invoice_number">رقم الفاتورة</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ request('invoice_number') }}">
                    </div>
                    <div class="form-group col-6 col-md-4 col-lg-1">
                        <label for="is_returned">نوع الاسترجاع </label>
                        <select class="form-control" id="is_returned" name="is_returned">
                            <option value="{{null}}" selected>كل الفواتير</option>
                            <option value="0" {{ request('is_returned') == '0' ? 'selected' : '' }}>لا يوجد</option>
                            <option value="2" {{ request('is_returned') == '2' ? 'selected' : '' }}>استرجاع جزئي</option>
                            <option value="1" {{ request('is_returned') == '1' ? 'selected' : '' }}>استرجاع كلي</option>
                        </select>
                    </div>
                    <div class="form-group col-6 col-md-4 col-lg-1">
                        <label for="payment_status">حالة الدفع</label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">اختر ...</option>
                            <option value="تم الدفع" {{ request('payment_status') == 'تم الدفع' ? 'selected' : '' }}>تم الدفع</option>
                            <option value="معلق" {{ request('payment_status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>
                    <div class="form-group col-6 col-md-4 col-lg-1">
                        <label for="delivery_status">حالة التوصيل</label>
                        <select class="form-control" id="delivery_status" name="delivery_status">
                            <option value="">اختر ...</option>
                            <option value="تم التوصيل" {{ request('delivery_status') == 'تم التوصيل' ? 'selected' : '' }}>تم التوصيل</option>
                            <option value="معلق" {{ request('delivery_status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>
                    <div class="form-group col-6 col-md-3 col-lg-2">
                        <label for="select2">اسم العميل</label>
                        <select name="customer_id" class="form-control" id="select2">
                            <option value="">اختر ...</option>
                            @foreach ($customers as $customer)
                                <option value="{{$customer->id}}" {{request('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 col-md-4 col-lg-2">
                        <label for="invoice_date">تاريخ الفاتورة</label>
                        <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ request('invoice_date') }}">
                    </div>
                    <div class="form-group col-6 col-md-4 col-lg-3">
                        <label for="reportrange">تاريخ محدد</label>
                        <input type="hidden" id="start_hidden_date" name="start_date">
                        <input type="hidden" id="end_hidden_date" name="end_date">
                        <div id="reportrange" class="px-2 py-1" style="border: 1px solid #dee2e6; border-radius:0.25rem;">
                            <i class="fe fe-calendar fe-16 mx-2"></i>
                            <span>{{ request('start_date') ? formatArabicDate(request('start_date')) : '' }} {{ request('end_date') ? formatArabicDate(request('end_date')) : '' }}</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-lg text-white">بحث وفلترة</button>
                <a href="{{route('selling_invoices.index')}}" class="btn btn-danger btn-lg">حذف اعدادات البحث</a>
            </form>

            <div class="table-responsive">
                <table class="table table-hover cell-border stripe text-center text-nowrap py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الفاتورة</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الوقت</th>
                            <th class="text-center">الموظف</th>
                            <th class="text-center">الوردية</th>
                            <th class="text-center">اسم العميل</th>
                            <th class="text-center">رقم العميل</th>
                            <th class="text-center">طريقة الدفع</th>
                            <th class="text-center">حالة الدفع</th>
                            <th class="text-center">الاسترجاع</th>
                            <th class="text-center">تكلفة الفاتورة</th>
                            <th class="text-center">المدفوع</th>
                            <th class="text-center">المتبقي</th>
                            <th class="text-center">المسترجع</th>
                            <th class="text-center">هامش الربح</th>
                            <th class="text-center">حالة الاستلام</th>
                            <th class="text-center">حالة التوصيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td><a href="{{route('selling_invoices.show', $invoice)}}">{{$invoice->invoice_number}}</a></td>
                                <td>{{$invoice->invoice_date}}</td>
                                <td>{{formatTime($invoice->invoice_time)}}</td>
                                <td><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></td>
                                <td><a href="{{route('shifts.show', $invoice->shift)}}">{{$invoice->shift->id}}</a></td>
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
@endsection

@section('js')
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/daterangepicker.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/jszip.min.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/buttons.print.min.js')}}"></script>

{{-- select 2 --}}
<script src="{{asset('assets/select-2/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#select2').select2();
    })
</script>

{{-- daterange --}}
<script>
    function formatArabicDate(date) {
        const formatter = new Intl.DateTimeFormat('ar-EG', { month: 'long' });
        const monthName = formatter.format(date.toDate()); // Convert moment to JavaScript Date
        const day = date.format('D');
        const year = date.format('YYYY');
        return `${monthName} ${day} ${year}`;
    }
    var start = moment().subtract(29, 'days');
    var end = moment();
    function cb(start, end){
        $('#reportrange span').html(formatArabicDate(start) + ' - ' + formatArabicDate(end));
        $('#start_hidden_date').val(start.format('YYYY-M-D'));
        $('#end_hidden_date').val(end.format('YYYY-M-D'));
    }
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges:{
            'اليوم': [moment(), moment()],
            'أمس': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'أخر 7 أيام': [moment().subtract(6, 'days'), moment()],
            'أخر 30 يوم': [moment().subtract(29, 'days'), moment()],
            'هذا الشهر': [moment().startOf('month'), moment().endOf('month')],
            'الشهر الماضي': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    // cb(start, end);
</script>


{{-- datatables --}}
<script>
    $('#dataTable-1').DataTable({
        responsive: true,
        autoWidth: true,
        dom: "Bfrtip",
        buttons: ["copy", "excel", {
            extend:"print",
            title: function() {
                var pageTitle = document.title;
                var currentDate = new Date().toISOString().split('T')[0];
                return pageTitle + ' | ' + currentDate;
            },
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '12.5px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
            [25, 50, -1],
            [25, 50, "All"]
        ],
        order: [[0, 'desc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
    });
</script>
@endsection
