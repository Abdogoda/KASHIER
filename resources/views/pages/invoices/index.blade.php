@extends('layouts.app')
@section('title') فواتير السندات @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">فواتير السندات</h3>
                <a href="{{route('invoices.create')}}" class="btn btn-primary">اضافة فاتورة سند جديدة <i class="fe fe-plus" style="margin-right: 10px"></i></a>
            </div>
            <form action="{{ route('invoices.index') }}" method="GET" class="border p-3 rounded mb-5">
                <div class="form-row">
                    <div class="form-group col-md-6 col-lg-4">
                        <label for="invoice_number">رقم الفاتورة</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ request('invoice_number') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-4">
                        <label for="invoice_date">تاريخ الفاتورة</label>
                        <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ request('invoice_date') }}">
                    </div>
                    <div class="form-group col-md-6 col-lg-4">
                        <label for="reportrange">تاريخ محدد</label>
                        <input type="hidden" id="start_hidden_date" name="start_date">
                        <input type="hidden" id="end_hidden_date" name="end_date">
                        <div id="reportrange" class="px-2 py-1" style="border: 1px solid #dee2e6; border-radius:0.25rem;">
                            <i class="fe fe-calendar fe-16 mx-2"></i>
                            <span></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-lg text-white">بحث وفلترة</button>
                <a href="{{route('invoices.index')}}" class="btn btn-danger btn-lg">مسح</a>
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
                                <td><a href="{{route('shifts.show', $invoice->shift)}}">{{$invoice->shift->id}}</a></td>
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
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/daterangepicker.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/jszip.min.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/buttons.print.min.js')}}"></script>

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
                $(win.document.body).find('table').addClass('compact').css('font-size', '21px').css('text-align', 'right');
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
