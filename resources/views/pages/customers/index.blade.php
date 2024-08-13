@extends('layouts.app')
@section('title') العملاء @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">جميع العملاء</h3>
                <a href="{{route('customers.create')}}" class="btn btn-primary">اضافة عميل جديد <i class="fe fe-user-plus" style="margin-right: 10px"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover cell-border stripe text-center py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">الاسم</th>
                            <th class="text-center">رقم الهاتف</th>
                            <th class="text-center">العنوان</th>
                            <th class="text-center">الشريحة</th>
                            <th class="text-center">الفواتير</th>
                            <th class="text-center">الحساب</th>
                            <th class="text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{$customer->id}}</td>
                                <td><a href="{{route('customers.show', $customer)}}">{{$customer->name}}</a></td>
                                <td><a href="tel:+2{{$customer->phone}}" class="text-main">{{$customer->phone}}</a></td>
                                <td>{{$customer->address}}</td>
                                <td>{{$customer->segment->name ?? '__'}}</td>
                                <td><a href="{{route('selling_invoices.index', ['customer_id' => $customer->id])}}">{{$customer->invoices->count()}}</a></td>
                                <td class="table-{{$customer->account > 0 ? 'success' : 'danger'}}"><a href="{{$customer->account > 0 ? route('selling_invoices.index', ['customer_id' => $customer->id, 'payment_status' => 'معلق']) : '#'}}">{{$customer->account}}</a></td>
                                <td class="text-center"><span class="badge badge-pill badge-{{$customer->status == 'مفعل' ? 'success' : 'danger'}} text-white">{{$customer->status}}</span></td>
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
    $('#dataTable-1').DataTable(
      {
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
                $(win.document.body).find('table').addClass('compact').css('font-size', '19px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [20, 40, 80, -1],
          [20, 40, 80, "All"]
        ],
        order: [[1, 'asc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
  </script>
@endsection
