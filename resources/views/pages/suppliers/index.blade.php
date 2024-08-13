@extends('layouts.app')
@section('title') الموردين @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">جميع الموردين</h3>
                <a href="{{route('suppliers.create')}}" class="btn btn-primary">اضافة مورد جديد <i class="fe fe-user-plus" style="margin-right: 10px"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover cell-border stripe text-center  py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">الاسم</th>
                            <th class="text-center">رقم الهاتف</th>
                            <th class="text-center">البريد الالكتروني</th>
                            <th class="text-center">العنوان</th>
                            <th class="text-center">الفواتير</th>
                            <th class="text-center">الحساب</th>
                            <th class="text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{$supplier->id}}</td>
                                <td><a href="{{route('suppliers.show', $supplier)}}">{{$supplier->name}}</a></td>
                                <td><a href="tel:+2{{$supplier->phone}}" class="text-main">{{$supplier->phone}}</a></td>
                                <td><a href="mailto:{{$supplier->email}}" class="text-main">{{$supplier->email}}</a></td>
                                <td>{{$supplier->address}}</td>
                                <td><a href="{{route('purchasing_invoices.index', ['supplier_id' => $supplier->id])}}">{{$supplier->invoices->count()}}</a></td>
                                <td class="table-{{$supplier->account > 0 ? "success" : 'danger'}}">{{$supplier->account}}</td>
                                <td class="text-center"><span class="badge badge-pill badge-{{$supplier->status == 'مفعل' ? 'success' : 'danger'}} text-white">{{$supplier->status}}</span></td>
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
                $(win.document.body).find('table').addClass('compact').css('font-size', '20px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [10, 20, 40, -1],
          [10, 20, 40, "All"]
        ],
        order: [[1, 'asc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
  </script>
@endsection
